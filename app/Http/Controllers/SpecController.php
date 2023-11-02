<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoicedAsset;

class SpecController extends Controller
{
    public function __construct()
    {
        $this->middleware('authnodb');
    }

    public function index(Request $request)
    {
        $kunder = ['0512-7330', '0512-7340', '0512-6131', '0512-7200', '0512-4200', '0512-7320', '0512-6130', '0512-6120-2', '0512-6121'];
        $perioder = ['2310', '2311'];

        $data = [
            'kunder' => $kunder,
            'perioder' => $perioder,
            'valdkund' => $request->kund,
            'valdperiod' => $request->period,
        ];

        return view('spec.index')->with($data);
    }

    public function listajax(Request $request)
    {
        $year = 2000 + substr($request->period, 0, 2);
        $month = substr($request->period, 2, 2);

        $assets = InvoicedAsset::where('customer_number', $request->kund)->where('year', $year)->where('month', $month)->get();

        $data = [
            'assets' => $assets,
        ];

        return view('spec.listajax')->with($data);
    }
}
