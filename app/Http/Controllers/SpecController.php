<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoicedAsset;
use App\Models\TOPdeskCustomer;
use DateTime;

class SpecController extends Controller
{
    public function __construct()
    {
        $this->middleware('authnodb');
    }

    public function index(Request $request)
    {
        $user = session()->get('user');

        $perioder = array();
        
        $startdatum = new DateTime('2023-12-01'); //TODO! Ändra till rätt månad sedan!
        $slutdatum = new DateTime(date('Y-m-01'));

        while ($startdatum <= $slutdatum) {
            $perioder[] = $slutdatum->format('ym');
            $slutdatum->modify('-1 month');
        }

        $data = [
            'kunder' => $user->customers_r,
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
