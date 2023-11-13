<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TOPdeskAsset;
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
}
