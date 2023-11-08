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
        $articlename = 'Leasing '.$request->artikelnummer;

        $relatedArticles = TOPdeskArticle::where('naam', $articlename)->with('relatedArticles')->first();
        if($relatedArticles) {
            $articles = $relatedArticles->relatedArticles;
        } else {
            $articles = null;
        }

        $data = [
            'currentarticle' => $request->artikelnummer,
            'articles' => $articles,
            'kund' => $request->kund,
            'user' => $request->user,
        ];

        return view('asset.ordermodal')->with($data);
    }
}
