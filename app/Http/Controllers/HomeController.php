<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\Country;
use App\Models\StaffProfile;
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
        $role = '';
        if ($hasBranches && $hasClinics) {
            if ($user->is_admin) {
                $role = 'Admin';
                $dashboardView = 'dashboard.admin';
            } elseif ($user->is_doctor) {
                $role = 'Doctor';
                $dashboardView = 'dashboard.admin';
            } elseif ($user->is_nurse) {
                $role = 'Nurse';
                $dashboardView = 'dashboard.nurse';
            } else {
                $role = 'User';
                $dashboardView = 'dashboard.user';
            }

            //for logo and name as per user entry
            // $clinicDetails = ClinicBasicDetail::first();
            // // Set session variable
            // session(['logoPath' => $clinicDetails->clinic_logo]);
            // session(['clinicName' => $clinicDetails->clinic_name]);

            // return view($dashboardView);

            $staffDetails = StaffProfile::where('user_id', $user->id)->first();
            $username = str_replace("<br>", " ", $user->name);

            session(['username' => $username]);
            session(['role' => $role]);

            if ($staffDetails) {
                session(['staffPhoto' => $staffDetails->photo]);
            }
            return view($dashboardView);

        } else {
            $countries = Country::all();
            $states = State::all();
            $cities = City::all();
            $clinicDetails = ClinicBasicDetail::first();
            $data = ClinicBranch::all();
            $total = count($data);

            // Set the flash message
            session()->flash('error', 'Please enter clinics and branch details before proceeding.');

            return view('settings.clinics.index', compact('countries', 'states', 'cities', 'clinicDetails', 'data', 'total'));

        }
    }
}
