<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\TOPdeskAsset;
use App\Models\TOPdeskAssetValue;
use App\Models\TOPdeskCustomer;

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
            'kunder' => $user->customers_r,
            'valdkund' => $request->kund,
            'kommuner' => $user->see_all?json_decode(env('MUNICIPALITIES')):null,
        ];

        return view('asset.index')->with($data);
    }

    public function listajax(Request $request)
    {
        $user = session()->get('user');

        if (strlen($request->kund) == 4) {
            $kunder = TOPdeskCustomer::where('debiteurennummer', 'like', $request->kund.'%')->with('assets')->get();
            $assets = $kunder->flatMap(function ($kund) {
                return $kund->assets;
            });
            $order_access = true;
        } else {
            $kund = TOPdeskCustomer::where('unid', $request->kund)->first();
            $assets = $kund->assets;
            $order_access = $user->customers->contains($kund);
        }

        $data = [
            'assets' => $assets,
            'kund' => $request->kund,
            'order_access' => $order_access,
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

        $asset = TOPdeskAsset::where('name', $request->oldasset)->first();
        $leasingservice = $asset->leasingservice();

        $relatedLeasingAssetValues = TOPdeskAssetValue::select('entityid')->where('textvalue', $leasingservice->typ);

        $relatedLeasingAssets = TOPdeskAsset::whereIn('unid', $relatedLeasingAssetValues)
                                    ->withoutGlobalScope('status_aktiv')
                                    ->where('name', 'like', 'Leasing%')
                                    ->orderBy('name')
                                    ->get();

        $data = [
            'currentarticle' => $artikelnummer,
            'replacements' => $relatedLeasingAssets,
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
        $user = session()->get('user');
        $asset = TOPdeskAsset::where('name', $request->assetname)->first();

        $ordernummerutbytevalue = $asset->assetValues->where('fieldname', 'ordernummer-utbyte')->first();
        $ordernummerutbytevalue->textvalue = null;
        $ordernummerutbytevalue->save();

        $valtutbytevalue = $asset->assetValues->where('fieldname', 'valt-utbyte')->first();
        $valtutbytevalue->textvalue = null;
        $valtutbytevalue->save();

        $response = Http::withoutVerifying()
                        ->contentType("application/json")
                        ->withToken(env("ZP_TOKEN"))
                        ->put(env("ZP_BASEURL").':30000/Store/api/Order', ['orderId' => $request->orderid, 'Action' => 'Terminate']);

        logger("Order ".$request->orderid." was terminated by ".$user->username);
    }

    public function subassets(Request $request)
    {
        $asset = TOPdeskAsset::where('name', $request->name)->first();

        return $asset->subassets;
    }

    public function dontreplace(Request $request)
    {
        $user = session()->get('user');
        $kund = TOPdeskCustomer::where('unid', $request->kund)->first();
        $user = session()->get('user');
        $response = Http::withoutVerifying()
                        ->contentType("application/json")
                        ->withToken(env("ZP_TOKEN"))
                        ->post(env("ZP_BASEURL").':30000/Store/api/Order', [
                            'ServiceUniqueId' => env('ZP_DONTREPLACE_SERVICE'), 
                            'Requester' => env('ZP_USER'), 
                            'Receiver' => env('ZP_USER'), 
                            'FieldValues' => [
                                [
                                    "Name" => "enhetSomInteSkaErsattas",
                                    "Value" => $request->assetname
                                ],
                                [
                                    "Name" => "utbyte",
                                    "Value" => "true"
                                ],
                                [
                                    "Name" => "kundnummerDisplayName",
                                    "Value" => $kund->naam
                                ],
                                [
                                    "Name" => "bestallningsansvarig",
                                    "Value" => $user->username
                                ],
                                [
                                    "Name" => "kundnummer",
                                    "Value" => json_encode([
                                        "id" => $request->kund,
                                        "text" => $kund->naam
                                    ])
                                ]
                            ]
                        ]);

        logger($user->username." choose not to replace ".$request->assetname);
    }
}
