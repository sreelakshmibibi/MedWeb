<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->is_admin) {
            return view('dashboard.admin');
        } else if (Auth::user()->is_doctor) {
            return view('dashboard.admin');
        } else if (Auth::user()->is_nurse) {
            return view('dashboard.admin');
        } else {
            return view('dashboard.admin');
        }
        
    }
}
