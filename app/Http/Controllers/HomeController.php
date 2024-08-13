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

        $revisitedPatients = DB::table('appointments')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereIn('patient_id', function ($query) {
                $query->select('patient_id')
                    ->from('appointments')
                    ->groupBy('patient_id')
                    ->havingRaw('COUNT(*) > 1');
            })
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month')
            ->toArray();
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $defaultArray = array_fill_keys($months, 0);
        function mapMonthData($data, $months)
        {
            $mappedData = array_fill_keys($months, 0); // Initialize with zeros
            foreach ($data as $monthIndex => $count) {
                // Adjust for 1-based index in SQL month and 0-based index in PHP array
                if (isset($months[$monthIndex - 1])) {
                    $mappedData[$months[$monthIndex - 1]] = $count;
                }
            }

            return $mappedData;
        }

        $newlyRegisteredData = mapMonthData($newlyRegistered, $months);
        $revisitedPatientsData = mapMonthData($revisitedPatients, $months);
        $today = Carbon::today();
        $tenDaysAgo = $today->copy()->subDays(10);

        $appointmentData = DB::table('appointments')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total_patients'), DB::raw('SUM(CASE WHEN app_type = 1 THEN 1 ELSE 0 END) as followup_patients'))
            ->where('created_at', '>=', $tenDaysAgo)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'), 'DESC')
            ->get();

        $dates = $appointmentData->pluck('date')->toArray();
        $chartTotalPatients = $appointmentData->pluck('total_patients')->toArray();
        $chartfollowupPatients = $appointmentData->pluck('followup_patients')->toArray();
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
            return view($dashboardView, compact('workingDoctors', 'totalPatients', 'totalStaffs', 'totalDoctors', 'totalOthers', 'totalTreatments', 'newlyRegisteredData', 'revisitedPatientsData', 'months', 'dates', 'chartTotalPatients', 'chartfollowupPatients'));

        } else {
            $countries = Country::all();
            $states = State::all();
            $cities = City::all();
            $clinicDetails = ClinicBasicDetail::first();
            $data = ClinicBranch::all();
            $total = count($data);

            // Set the flash message
            session()->flash('error', 'Please enter clinics and branch details before proceeding.');

            return view('settings.clinics.index', compact('countries', 'states', 'cities', 'clinicDetails', 'data', 'total', 'newlyRegisteredData', 'revisitedPatientsData', 'months', 'dates', 'chartTotalPatients', 'chartfollowupPatients'));

        }
    }
}
