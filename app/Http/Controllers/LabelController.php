<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TOPdeskAsset;
use App\Models\TOPdeskAssetValue;

class LabelController extends Controller
{
    public function __construct()
    {
        $this->middleware('authnodb');
    }

    public function single(Request $request)
    {
        $asset = TOPdeskAsset::serial($request->serial)->first();

        if(isset($asset)) {
            $name = $asset->name;
        } else {
            $name = null;
        }

        $data = [
            'name' => $name,
            'serial' => $request->serial,
            'link' => env("QR_BASEURL").$request->serial,
        ];

        return view('label.single')->with($data);
    }
}
