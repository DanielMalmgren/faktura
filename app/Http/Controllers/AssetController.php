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
        if(strpos($request->artikelnummer, "+")) {
            $aarray = explode("+", $request->artikelnummer);
            $artikelnummer = $aarray[0];
            $tillval = $aarray[1];
            $antaltillval = count($aarray)-1;
        } else {
            $artikelnummer = $request->artikelnummer;
            $tillval = null;
            $antaltillval = 0;
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
            'tillval' => $tillval,
            'antaltillval' => $antaltillval,
        ];

        return view('asset.ordermodal')->with($data);
    }
}
