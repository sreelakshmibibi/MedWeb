<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\Country;
use App\Models\PatientProfile;
use App\Models\StaffProfile;
use App\Models\State;
use App\Services\DoctorAvaialbilityService;
use Illuminate\Support\Carbon;
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
        $clinicsData = DB::table('clinic_basic_details')->first();
        $hasBranches = DB::table('clinic_branches')->exists();
        $role = '';
        $totalPatients = 0;
        $totalStaffs = 0;
        $totalDoctors = 0;
        $totalOthers = 0;
        if ($hasBranches && $hasClinics) {
            if ($user->is_admin) {
                $role = 'Admin';
                $doctorAvailabilityService = new DoctorAvaialbilityService();
                $currentDayName = Carbon::now()->englishDayOfWeek;
                $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors(null, $currentDayName);
                $totalPatients = PatientProfile::where('status', 'Y')->count(); // Replace with your actual logic to get the total
                $totalStaffs = StaffProfile::where('status', 'Y')->count();
                $totalDoctors = StaffProfile::where('status', 'Y')->whereNot('license_number', NULL )->count();
                $totalOthers = $totalStaffs - $totalDoctors;
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
            session(['currency' => $clinicsData->currency]);
            session(['treatmentTax' => $clinicsData->treatment_tax_included]);

            if ($staffDetails) {
                session(['staffPhoto' => $staffDetails->photo]);
            }
            // echo "<pre>"; print_r($workingDoctors); echo "</pre>";exit;
            return view($dashboardView, compact('workingDoctors', 'totalPatients', 'totalStaffs', 'totalDoctors', 'totalOthers'));

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
