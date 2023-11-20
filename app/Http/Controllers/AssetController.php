<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\TOPdeskAsset;
use App\Models\TOPdeskAssetValue;
use App\Models\TOPdeskCustomer;
use App\Models\TOPdeskArticle;

class AssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('authnodb');
    }

    public function index(Request $request)
    {
        $user = session()->get('user');

        $data = [
            'kunder' => $user->customers,
            'valdkund' => $request->kund,
        ];

        return view('asset.index')->with($data);
    }

    public function listajax(Request $request)
    {
        $kund = TOPdeskCustomer::where('unid', $request->kund)->first();

        $assets = $kund->assets;

        $data = [
            'assets' => $assets,
            'kund' => $request->kund,
        ];

        return view('asset.listajax')->with($data);
    }

    public function ordermodal(Request $request)
    {
        $tillval_bildskarmsTyp='';
        $tillval_bildskarmsAntal=0;
        $tillval_4Gmodem='false';

        if(strpos($request->artikelnummer, "+")) {
            $allatillval = explode("+", $request->artikelnummer);
            $artikelnummer = $allatillval[0];

            //För assets enligt ny standard kan jag här istället loopa igenom alla sub-assets
            foreach ($allatillval as $tillval) {
                if($tillval == 'WWAN') {
                    $tillval_4Gmodem='true';
                } elseif(strpos(strtolower($tillval), "bildskärm") === 0) {
                    $tillval_bildskarmsAntal++;
                    $tillval_bildskarmsTyp=$tillval;
                }
            }
        } else {
            $artikelnummer = $request->artikelnummer;
        }
        $articlename = 'Leasing '.$artikelnummer;

        $relatedArticles = TOPdeskArticle::where('naam', $articlename)->with('relatedArticles')->first();
        if($relatedArticles) {
            $articles = $relatedArticles->relatedArticles;
        } else {
            $articles = null;
        }

        $data = [
            'currentarticle' => $artikelnummer,
            'articles' => $articles,
            'kund' => $request->kund,
            'user' => $request->user,
            'oldasset' => $request->oldasset,
            'tillval_bildskarmsTyp' => $tillval_bildskarmsTyp,
            'tillval_bildskarmsAntal' => $tillval_bildskarmsAntal,
            'tillval_4Gmodem' => $tillval_4Gmodem,
            ];

        return view('asset.ordermodal')->with($data);
    }

    public function orderstatusmodal(Request $request)
    {
        $response = Http::withoutVerifying()
                        ->withToken(env("ZP_TOKEN"))
                        ->acceptJson()
                        ->get(env("ZP_BASEURL").':30000/Store/api/Order', ['orderId' => $request->orderid]);

        $data = [
            'order' => $response->object()[0],
            'kund' => $request->kund,
            'assetname' => $request->assetname,
        ];

        return view('asset.orderstatusmodal')->with($data);
    }

    public function cancelorder(Request $request)
    {
        $asset = TOPdeskAsset::where('name', $request->assetname)->first();

        $ordernummerutbytevalue = $asset->assetValues->where('fieldname', 'ordernummer-utbyte')->first();
        $ordernummerutbytevalue->textvalue = null;
        $ordernummerutbytevalue->save();

        $valtutbytevalue = $asset->assetValues->where('fieldname', 'valt-utbyte')->first();
        $valtutbytevalue->textvalue = null;
        $valtutbytevalue->save();

        $response = Http::withoutVerifying()
                        ->bodyFormat('query')
                        ->withToken(env("ZP_TOKEN"))
                        ->delete(env("ZP_BASEURL").':30000/Store/api/Order', ['orderId' => $request->orderid, 'removeParameters' => 'true']);
    }
}
