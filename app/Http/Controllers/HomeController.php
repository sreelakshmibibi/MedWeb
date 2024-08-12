<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\Country;
use App\Models\PatientProfile;
use App\Models\StaffProfile;
use App\Models\State;
use App\Models\ToothExamination;
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
        $newlyRegistered = DB::table('patient_profiles')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month')
            ->toArray();
        // $revisitedPatients = DB::table('appointments')
        //     ->join('patient_profiles', 'appointments.patient_id', '=', 'patient_profiles.patient_id')
        //     ->select(DB::raw('MONTH(appointments.created_at) as month'), DB::raw('COUNT(DISTINCT patient_profiles.id) as count'))
        //     ->groupBy(DB::raw('MONTH(appointments.created_at)'))
        //     ->pluck('count', 'month')
        //     ->toArray();
        $revisitedPatients = DB::table('appointments')
            ->join('patient_profiles', 'appointments.patient_id', '=', 'patient_profiles.patient_id')
            ->select(DB::raw('MONTH(appointments.created_at) as month'), DB::raw('COUNT(DISTINCT appointments.patient_id) as count'))
            ->whereIn('appointments.patient_id', function ($query) {
                $query->select('patient_id')
                    ->from('appointments')
                    ->groupBy('patient_id')
                    ->havingRaw('COUNT(*) > 1');
            })
            ->groupBy(DB::raw('MONTH(appointments.created_at)'))
            ->pluck('count', 'month')
            ->toArray();
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // Ensure all months are represented
        $newlyRegisteredData = array_replace(array_fill_keys(array_keys($months), 0), $newlyRegistered);
        $revisitedPatientsData = array_replace(array_fill_keys(array_keys($months), 0), $revisitedPatients);

        // Convert the associative arrays to indexed arrays
        $newlyRegisteredData = array_values($newlyRegisteredData);
        $revisitedPatientsData = array_values($revisitedPatientsData);
        $role = '';
        $totalPatients = 0;
        $totalStaffs = 0;
        $totalDoctors = 0;
        $totalOthers = 0;
        $totalTreatments = 0;
        if ($hasBranches && $hasClinics) {
            if ($user->is_admin) {
                $role = 'Admin';
                $doctorAvailabilityService = new DoctorAvaialbilityService();
                $currentDayName = Carbon::now()->englishDayOfWeek;
                $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors(null, $currentDayName);
                $totalPatients = PatientProfile::where('status', 'Y')->count(); // Replace with your actual logic to get the total
                $totalStaffs = StaffProfile::where('status', 'Y')->count();
                $totalDoctors = StaffProfile::where('status', 'Y')->whereNot('license_number', null)->count();
                $totalOthers = $totalStaffs - $totalDoctors;
                $dashboardView = 'dashboard.admin';
                $totalTreatments = ToothExamination::distinct('treatment_id')->count('treatment_id');

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
            $username = str_replace('<br>', ' ', $user->name);

            session(['username' => $username]);
            session(['role' => $role]);
            session(['currency' => $clinicsData->currency]);
            session(['treatmentTax' => $clinicsData->treatment_tax_included]);

            if ($staffDetails) {
                session(['staffPhoto' => $staffDetails->photo]);
            }

            // echo "<pre>"; print_r($workingDoctors); echo "</pre>";exit;
            return view($dashboardView, compact('workingDoctors', 'totalPatients', 'totalStaffs', 'totalDoctors', 'totalOthers', 'totalTreatments', 'newlyRegisteredData', 'revisitedPatientsData', 'months'));

        } else {
            $countries = Country::all();
            $states = State::all();
            $cities = City::all();
            $clinicDetails = ClinicBasicDetail::first();
            $data = ClinicBranch::all();
            $total = count($data);

            // Set the flash message
            session()->flash('error', 'Please enter clinics and branch details before proceeding.');

            return view('settings.clinics.index', compact('countries', 'states', 'cities', 'clinicDetails', 'data', 'total', 'newlyRegisteredData', 'revisitedPatientsData', 'months'));

        }
    }
}
