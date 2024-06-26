<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\ClinicBasicDetail;
use App\Models\Country;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $user = Auth::user();

        // Check if there are entries in clinic_branches and clinic_basic_details tables
        $hasClinics = DB::table('clinic_basic_details')->exists();
        $hasBranches = DB::table('clinic_branches')->exists();

        if ($hasBranches && $hasClinics) {
            if ($user->is_admin) {
                $dashboardView = 'dashboard.admin';
            } elseif ($user->is_doctor) {
                $dashboardView = 'dashboard.admin';
            } elseif ($user->is_nurse) {
                $dashboardView = 'dashboard.nurse';
            } else {
                $dashboardView = 'dashboard.user';
            }

            return view($dashboardView);
        } else {
            $countries = Country::all();
            $states = State::all();
            $cities = City::all();
            $clinicDetails = ClinicBasicDetail::first();
            // Set the flash message
            session()->flash('error', 'Please enter clinics and branch details before proceeding.');

            return view('settings.clinics.index', compact('countries', 'states', 'cities', 'clinicDetails'));

        }
    }
}
