<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TOPdeskAsset;
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
            'kunder' => $user->customers,
            'valdkund' => $request->kund,
        ];

        return view('asset.index')->with($data);
    }

    public function listajax(Request $request)
    {
        $kund = TOPdeskCustomer::where('unid', $request->kund)->first();

        $assets = $kund->assets;//->where('raw_status', '4737db93-c702-4ea6-a9b4-5e28f917144d');
        
        $data = [
            'assets' => $assets,
        ];

        return view('asset.listajax')->with($data);
    }
}
