<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\FrejaAPI;

class HomeController extends Controller
{
    use FrejaAPI;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('authnodb');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = session()->get('user');

        $data = [
            'user' => $user,
        ];

        return view('home')->with($data);
    }

    public function logout()
    {
        session()->flush();
        return view('logout');
    }
}
