<?php

namespace App\Http\Controllers;

use App\Services\CommonService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $commonService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function __construct(CommonService $commonService)
    {
        $this->middleware('auth');
        $this->commonService = $commonService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // if (Auth::user()->is_admin) {
        //     return view('dashboard.admin');
        // } else if (Auth::user()->is_doctor) {
        //     return view('dashboard.admin');
        // } else if (Auth::user()->is_nurse) {
        //     return view('dashboard.admin');
        // } else {
        //     return view('dashboard.admin');
        // }
        if (Auth::user()->is_admin) {
            $dashboardView = 'dashboard.admin';
        } elseif (Auth::user()->is_doctor) {
            $dashboardView = 'dashboard.admin';
        } elseif (Auth::user()->is_nurse) {
            $dashboardView = 'dashboard.nurse';
        } else {
            $dashboardView = 'dashboard.user';
        }

        // Fetch menu items using CommonService
        $menuItems = $this->commonService->getMenuItems();

        // Return the view with menu items
        return view($dashboardView, ['menuItems' => $menuItems]);

    }
}
