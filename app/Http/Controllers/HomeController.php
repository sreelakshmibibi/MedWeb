<?php

namespace App\Http\Controllers;
use App\Models\Appointment;

use Illuminate\Support\Facades\Crypt;
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
    public function index($userType = null)
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

        $newlyRegisteredPatients = DB::table('patient_profiles')
            ->select('patient_id')
            ->whereMonth('created_at', now()->month)
            ->pluck('patient_id')
            ->toArray();

        $firstVisits = DB::table('appointments')
            ->select('patient_id', DB::raw('MIN(created_at) as first_visit_date'))
            ->whereIn('patient_id', $newlyRegisteredPatients)
            ->groupBy('patient_id')
            ->pluck('first_visit_date', 'patient_id')
            ->toArray();

        $firstVisitDates = array_values($firstVisits);

        $revisitedPatients = DB::table('appointments')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereIn('patient_id', function ($query) use ($newlyRegisteredPatients) {
                $query->select('patient_id')
                    ->from('appointments')
                    ->groupBy('patient_id')
                    ->havingRaw('COUNT(*) > 1')
                    ->whereNotIn('patient_id', $newlyRegisteredPatients);
            })
            ->orWhere(function ($query) use ($newlyRegisteredPatients, $firstVisitDates) {
                $query->whereIn('patient_id', $newlyRegisteredPatients)
                    ->whereNotIn('created_at', $firstVisitDates); // Exclude first visit
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
            // ->orderBy(DB::raw('DATE(created_at)'), 'DESC')
            ->orderBy(DB::raw('DATE(created_at)'), 'ASC')
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

        $totalUniquePatients = 0;
        $malePatientsCount = 0;
        $femalePatientsCount = 0;
        $childrenCount = 0;
        $otherCount = 0;
        $newPatientsCount = 0;
        $followupPatientsCount = 0;
        if ($hasBranches && $hasClinics) {
            $doctorAvailabilityService = new DoctorAvaialbilityService();
            $currentDayName = Carbon::now()->englishDayOfWeek;
            // $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors(null, $currentDayName, date('Y-m-d'));
            $branchid = null;
            if (!Auth::user()->is_admin) {
                $branchid = StaffProfile::where('user_id', Auth::user()->id)
                    ->pluck('clinic_branch_id')
                    ->first();
            }
            $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors($branchid, $currentDayName, date('Y-m-d'), null);
            $totalPatients = PatientProfile::where('status', 'Y')->count(); // Replace with your actual logic to get the total
            $totalStaffs = StaffProfile::where('status', 'Y')->count();
            // $totalDoctors = StaffProfile::where('status', 'Y')->whereNot('license_number', null)->count();
            $totalDoctors = StaffProfile::where('status', 'Y')->whereNot('specialization', null)->count();

            $totalOthers = $totalStaffs - $totalDoctors;
            $totalTreatments = ToothExamination::distinct('treatment_id')->count('treatment_id');

            $staffProfile = StaffProfile::with('user')->where('user_id', $user->id)->first();
            $username = str_replace('<br>', ' ', $user->name);
            $appointments = null;
            if ($user->is_doctor) {
                $appointments = Appointment::where('doctor_id', $user->id)
                    ->with(['patient', 'doctor', 'branch'])
                    ->get();
            } else {
                $appointments = Appointment::with(['patient', 'doctor', 'branch'])
                    ->get();
            }

            // Extract the patients from the appointments
            $patients = $appointments->pluck('patient')->unique('id');
            // Extract the patients from the appointments
            $appointmentstype = $appointments->unique('patient_id');
            if ($user->is_nurse || $user->is_reception) {
                $clinicBranchId = StaffProfile::where('user_id', Auth::user()->id)
                    ->pluck('clinic_branch_id')
                    ->first();

                $patients = $appointments->where('app_branch', $clinicBranchId)
                    ->pluck('patient')->unique('id');

                $appointmentstype = $appointments->where('app_branch', $clinicBranchId)->unique('patient_id');
            }


            // Count the total number of unique patients
            $totalUniquePatients = $patients->count();

            // Count the number of male and female patients
            $malePatientsCount = $patients->where('gender', 'M')->count();
            $femalePatientsCount = $patients->where('gender', 'F')->count();

            // Count the number of children and other patients
            // Get the current date
            $today = Carbon::now()->toDateString();

            $childrenCount = $patients->filter(function ($patient) use ($today) {
                $age = Carbon::parse($patient->date_of_birth)->diffInYears($today);
                return $age <= 18;
            })->count();
            $otherCount = $patients->filter(function ($patient) use ($today) {
                $age = Carbon::parse($patient->date_of_birth)->diffInYears($today);
                return $age > 18;
            })->count();


            $newPatientsCount = $appointmentstype->where('app_type', '2')->count();
            $followupPatientsCount = $appointmentstype->where('app_type', '1')->count();
            $currentappointments = null;
            if ($user->is_doctor) {
                $currentappointments = Appointment::where('doctor_id', $user->id)
                    ->where('app_status', 1)
                    ->whereDate('app_date', today())
                    ->orderBy('token_no') // Order by token_no to get the first three
                    ->limit(3) // Limit the results to the first three
                    ->with(['patient', 'doctor', 'branch']) // Eager load relationships
                    ->get();

            } else {
                $currentappointments = Appointment::where('app_status', 1)
                    ->whereDate('app_date', today())
                    ->orderBy('token_no') // Order by token_no to get the first three
                    ->limit(3) // Limit the results to the first three
                    ->with(['patient', 'doctor', 'branch']) // Eager load relationships
                    ->get();
            }
            $userType = $userType ?? ($user->is_admin ? 'admin' : ($user->is_doctor ? 'doctor' : ($user->is_nurse ? 'nurse' : 'user')));

            // Map user type to role and dashboard view
            $roleMapping = [
                'admin' => ['Admin', 'dashboard.admin'],
                'doctor' => ['Doctor', 'dashboard.doctor'],
                'nurse' => ['Nurse', 'dashboard.reception'],
                'user' => ['User', 'dashboard.reception'],
            ];

            // Retrieve role and dashboard view based on user type
            [$role, $dashboardView] = $roleMapping[$userType] ?? ['User', 'dashboard.reception'];

            session(['username' => $username]);
            session(['role' => $role]);
            session(['currency' => $clinicsData->currency]);
            session(['treatmentTax' => $clinicsData->treatment_tax_included]);

            // Initialize $idEncrypted
            $pstaffidEncrypted = '';
            $staffId = '';
            if ($staffProfile) {
                session(['staffPhoto' => $staffProfile->photo]);
                $staffId = $staffProfile->user->id;
                $base64Id = base64_encode($staffId);
                $pstaffidEncrypted = Crypt::encrypt($base64Id);
                session(['pstaffidEncrypted' => $pstaffidEncrypted]);
            }

            $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
                ->where('clinic_status', 'Y')
                ->get();

            return view($dashboardView, compact('workingDoctors', 'totalPatients', 'totalStaffs', 'totalDoctors', 'totalOthers', 'totalTreatments', 'newlyRegisteredData', 'revisitedPatientsData', 'months', 'dates', 'chartTotalPatients', 'chartfollowupPatients', 'totalUniquePatients', 'malePatientsCount', 'femalePatientsCount', 'newPatientsCount', 'followupPatientsCount', 'currentappointments', 'pstaffidEncrypted', 'childrenCount', 'otherCount', 'clinicBranches'));

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

    // controller method to fetch appointment counts per hour
    public function getAppointmentsByHour()
    {
        $appointments = DB::table('appointments')
            ->select(DB::raw('HOUR(app_time) as hour'), DB::raw('COUNT(*) as count'))
            ->whereDate('app_date', '=', now()->toDateString()) // Filter by the current date or your desired date
            ->where('doctor_id', Auth::user()->id)
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return response()->json($appointments);
    }

    // public function getAppointmentsByMonth()
    // {
    //     // Get the current month and year
    //     $currentMonth = now()->month;
    //     $currentYear = now()->year;

    //     // Calculate the start and end months for the range
    //     $startMonth = $currentMonth - 3;
    //     $endMonth = $currentMonth + 3;

    //     // Adjust the year if crossing year boundaries
    //     if ($startMonth <= 0) {
    //         $startMonth += 12;
    //         $startYear = $currentYear - 1;
    //     } else {
    //         $startYear = $currentYear;
    //     }

    //     if ($endMonth > 12) {
    //         $endMonth -= 12;
    //         $endYear = $currentYear + 1;
    //     } else {
    //         $endYear = $currentYear;
    //     }

    //     // Fetch appointment counts within the range
    //     $appointments = DB::table('appointments')
    //         ->select(DB::raw('MONTH(app_date) as month'), DB::raw('COUNT(*) as count'))
    //         ->whereBetween(DB::raw('MONTH(app_date)'), [$startMonth, $endMonth])
    //         ->whereBetween(DB::raw('YEAR(app_date)'), [$startYear, $endYear])
    //         ->groupBy('month')
    //         ->orderBy('month')
    //         ->get();

    //     return response()->json($appointments);
    // }

    public function getAppointmentsByMonth()
    {
        // Get the current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Calculate the start month and year (6 months before the current month)
        $startDate = now()->subMonths(6)->startOfMonth();
        $endDate = now()->endOfMonth(); // This is the current month

        // Fetch appointment counts within the range
        $appointments = DB::table('appointments')
            ->select(DB::raw('MONTH(app_date) as month'), DB::raw('COUNT(*) as count'))
            ->where('doctor_id', Auth::user()->id)
            ->where('app_status', '5')
            ->whereBetween('app_date', [$startDate, $endDate])
            ->groupBy(DB::raw('MONTH(app_date)'))
            ->orderBy(DB::raw('MONTH(app_date)'))
            ->get();

        return response()->json($appointments);
    }

}
