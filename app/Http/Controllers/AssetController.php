<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\TOPdeskAsset;
use App\Models\TOPdeskAssetView;
use App\Models\TOPdeskAssetValue;
use App\Models\TOPdeskCustomer;

class AssetController extends Controller {
    public function __construct() {
        $this->middleware('authnodb');
    }

    public function index(Request $request) {
        $user = session()->get('user');

        $data = [
            'kunder' => $user->customers_r,
            'valdkund' => $request->kund,
            'kommuner' => $user->see_all?json_decode(env('MUNICIPALITIES')):null,
        ];

        return view('asset.index')->with($data);
    }

    public function listajax(Request $request) {
        $user = session()->get('user');

        if (strlen($request->kund) == 4) {
            $assets = TOPdeskAssetView::where('kundnummer', 'like', $request->kund.'%')->get();
        } else {
            $assets = TOPdeskAssetView::where('kundnummer', $request->kund)->get();
            $kund = TOPdeskCustomer::where('debiteurennummer', $request->kund)->first();
            $order_access = $user->customers->contains($kund);
        }

        if($user->see_all) {
            $order_access = true;
        }

        $data = [
            'assets' => $assets,
            'kund' => $request->kund,
            'order_access' => $order_access,
        ];

        return view('asset.listajax')->with($data);
    }

    public function ordermodal(Request $request) {
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

        $relatedLeasingAssetValues = TOPdeskAssetValue::select('entityid')->where('textvalue', 'like', '%'.$leasingservice->tillgangliga_typer.'%');

        $relatedLeasingAssets = TOPdeskAsset::whereIn('unid', $relatedLeasingAssetValues)
                                    ->withoutGlobalScope('status_aktiv')
                                    ->where('name', 'like', 'Leasing%')
                                    ->orderBy('name')
                                    ->get();

        $replacements = array();
        $locale = env('LOCALE');
        foreach($relatedLeasingAssets as $relatedLeasingAsset) {
            if(isset($relatedLeasingAsset->zervicepoint_tjanste_id)) {
                $zpinfo = $this->getzporderinfo($relatedLeasingAsset->zervicepoint_tjanste_id);
                $replacement = new \stdClass();
                $replacement->DisplayName = $zpinfo->DisplayName->$locale;
                $replacement->ShortDescription = $zpinfo->ShortDescription->$locale;
                $replacement->Description = $zpinfo->Description->$locale;
                $replacement->zervicepoint_tjanste_id = $relatedLeasingAsset->zervicepoint_tjanste_id;
                $replacement->shortname = $relatedLeasingAsset->shortname;
                $replacement->pretty_shortname = $relatedLeasingAsset->pretty_shortname;
                foreach($zpinfo->ServiceImages as $image) {
                    if($image->UniqueId == $zpinfo->ImageUniqueId) {
                        $replacement->imageContent = $image->Content;
                        $replacement->imageContentType = $image->ContentType;
                        break;
                    }
                }
                if(mb_strcasecmp($artikelnummer, $replacement->shortname)==0) {
                    $replacement->DisplayName .= " (samma som innan)";
                    $replacement->pretty_shortname = "Byt ut mot en ny ".$replacement->pretty_shortname;
                    array_unshift($replacements, $replacement);
                } elseif(mb_stripos($replacement->DisplayName, "Individuellt") !== false) {
                    $replacement->pretty_shortname = "Ersätt med ".$replacement->pretty_shortname;
                    $lastchoice = $replacement;
                } else {
                    $replacement->pretty_shortname = "Ersätt med ".$replacement->pretty_shortname;
                    $replacements[] = $replacement;
                }
            }
        }
        if(isset($lastchoice)) {
            $replacements[] = $lastchoice;
        }

        $data = [
            'replacements' => $replacements,
            'kund' => $request->kund,
            'user' => $request->user,
            'oldasset' => $asset,
            'tillval_bildskarmsTyp' => $tillval_bildskarmsTyp,
            'tillval_bildskarmsAntal' => $tillval_bildskarmsAntal,
            'tillval_4Gmodem' => $tillval_4Gmodem,
            ];

        return view('asset.ordermodal')->with($data);
    }

    private function getzporderinfo(String $uniqueId) {
        $response = Http::withoutVerifying()
                        ->withToken(env("ZP_TOKEN"))
                        ->acceptJson()
                        ->get(env("ZP_BASEURL").':30000/Store/api/Service', ['uniqueId' => $uniqueId]);

        return json_decode($response);
    }

    public function orderstatusmodal(Request $request) {
        $response = Http::withoutVerifying()
                        ->withToken(env("ZP_TOKEN"))
                        ->acceptJson()
                        ->get(env("ZP_BASEURL").':30000/Store/api/Order', ['orderId' => $request->orderid]);

        $hiddenparameters = ['Quantity', 'kundnummerDisplayName', 'produkt', 'utbyte', 'bildskarmstypDisplayname', 'extraBildskarmDisplayname', 'modemDisplayName'];
        
        $data = [
            'order' => $response->object()[0],
            'kund' => $request->kund,
            'assetname' => $request->assetname,
            'hiddenparameters' => $hiddenparameters,
        ];

        return view('asset.orderstatusmodal')->with($data);
    }

    public function cancelorder(Request $request) {
        $user = session()->get('user');
        $asset = TOPdeskAsset::where('name', $request->assetname)->first();

        $ordernummerutbytevalue = $asset->assetValues->where('fieldid', 49)->first();
        $ordernummerutbytevalue->textvalue = null;
        $ordernummerutbytevalue->save();

        $valtutbytevalue = $asset->assetValues->where('fieldid', 64)->first();
        $valtutbytevalue->textvalue = null;
        $valtutbytevalue->save();

        $response = Http::withoutVerifying()
                        ->contentType("application/json")
                        ->withToken(env("ZP_TOKEN"))
                        ->put(env("ZP_BASEURL").':30000/Store/api/Order', ['orderId' => $request->orderid, 'Action' => 'Terminate']);

        logger("Order ".$request->orderid." was terminated by ".$user->username);
    }

    public function subassets(Request $request) {
        $asset = TOPdeskAsset::where('name', $request->name)->first();

        return $asset->subassets;
    }

    public function dontreplace(Request $request) {
        $user = session()->get('user');

        //Kolla om användaren har en profil i Zervicepoint, använd den i så fall
        $response = Http::withoutVerifying()
                        ->withToken(env("ZP_TOKEN"))
                        ->get(env("ZP_BASEURL").':30000/Store/api/Profile', ['email' => $user->email]);
        if($response->getStatusCode() == 200) {
            $requesterId = $response->object()->ProfileId;
        } else {
            $requesterId = env('ZP_USER');
        }

        $user = session()->get('user');
        $kund = TOPdeskCustomer::where('debiteurennummer', $request->kund)->first();
        $user = session()->get('user');
        $response = Http::withoutVerifying()
                        ->contentType("application/json")
                        ->withToken(env("ZP_TOKEN"))
                        ->post(env("ZP_BASEURL").':30000/Store/api/Order', [
                            'ServiceUniqueId' => env('ZP_DONTREPLACE_SERVICE'), 
                            'Requester' => $requesterId, 
                            'Receiver' => $requesterId, 
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
