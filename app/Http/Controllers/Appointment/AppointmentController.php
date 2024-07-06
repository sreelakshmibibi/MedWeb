<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\AppointmentRequest;
use App\Models\City;
use App\Models\ClinicBranch;
use App\Models\Country;
use App\Models\Department;
use App\Models\DoctorWorkingHour;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\StaffProfile;
use App\Models\State;
use App\Models\User;
use App\Models\UserType;
use App\Models\WeekDay;
use App\Services\StaffService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Services\CommonService;
use App\Services\DoctorAvaialbilityService;

use App\Notifications\WelcomeVerifyNotification;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $successMessage = $request->query('success_message');

        if ($successMessage) {
            session()->flash('success', $successMessage);
        }

        if ($request->ajax()) {
            // Retrieve selected date from the request
            $selectedDate = $request->input('selectedDate');

            // Example: Fetch data from your model based on selected date
            $appointments = Appointment::whereDate('app_date', $selectedDate)
                            ->with(['patient', 'doctor', 'branch'])
                            ->get();
            // $appointments = Appointment::orderBy('token', 'asc')->get();

            return DataTables::of($appointments)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return str_replace("<br>", " ", $row->patient->first_name." ".$row->patient->last_name);
                })
                ->addColumn('doctor', function ($row) {
                    return str_replace("<br>", " ", $row->doctor->name);
                })
                ->addColumn('branch', function ($row) {
                    if ($row->branch) {
                        $address = explode("<br>",$row->branch->clinic_address);
                        $address = implode(", ", $address) ;
                        $clinicAddress = implode(", ", [
                            $address,
                            $row->branch->city->city,
                            $row->branch->state->state
                            // $row->branch->country->country,
                            // "Pincode - " . $row->branch->pincode
                        ]);
                
                        return $clinicAddress;
                    }
                
                    return '';
                })
                ->addColumn('phone', function ($row) {
                    return $row->patient->phone;
                })
                ->addColumn('status', function ($row) {
                    if ($row->patient->latestAppointment) {
                        $btn = '';

                        //return $row->latestAppointment->app_status;
                        if ($row->patient->latestAppointment->app_status == 1) {
                            $btn = "<span class='btn-sm badge badge-success-light'>Scheduled</span>";
                        } elseif ($row->patient->latestAppointment->app_status == 2) {
                            $btn = '<span class="btn-sm badge badge-success-light">Waiting</span>';
                        } elseif ($row->patient->latestAppointment->app_status == 3) {
                            $btn = '<span class="btn-sm badge badge-danger-light">Unavailable</span>';
                        } elseif ($row->patient->latestAppointment->app_status == 4) {
                            $btn = '<span class="btn-sm badge badge-danger-light">Cancelled</span>';
                        } elseif ($row->patient->latestAppointment->app_status == 5) {
                            $btn = '<span class="btn-sm badge badge-success-light">Completed</span>';
                        } elseif ($row->patient->latestAppointment->app_status == 6) {
                            $btn = '<span class="btn-sm badge badge-success-light">Billing</span>';
                        } elseif ($row->patient->latestAppointment->app_status == 7) {
                            $btn = '<span class="btn-sm badge badge-success-light">Procedure</span>';
                        } elseif ($row->patient->latestAppointment->app_status == 8) {
                            $btn = '<span class="btn-sm badge badge-danger-light">Missed</span>';
                        } elseif ($row->patient->latestAppointment->app_status == 9) {
                            $btn = '<span class="btn-sm badge badge-success-light">Re-Scheduled</span>';
                        }

                        return $btn;
                    }

                    return 'N/A';
                })
                ->addColumn('action', function ($row) {

                    $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-add btn-xs me-1" title="new booking" data-bs-toggle="modal" data-id="' . $row->id . '" data-patient-id="' . $row->patient->patient_id . '" data-patient-name="' . str_replace("<br>", " ", $row->patient->first_name." ".$row->patient->last_name) . '"
                        data-bs-target="#modal-booking" ><i class="fa fa-plus"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-edit btn-xs me-1" title="reschedule" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-reschedule" ><i class="fa-solid fa-calendar-days"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-cancel" data-id="' . $row->id . '" title="cancel">
                        <i class="fa fa-times"></i></button>';

                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        $firstBranchId = optional($clinicBranches->first())->id;
        $currentDayName = Carbon::now()->englishDayOfWeek;
        $doctorAvailabilityService = new DoctorAvaialbilityService();
        $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors($firstBranchId, $currentDayName);
        $appointmentStatuses = AppointmentStatus::all();
        return view('appointment.index', compact('clinicBranches', 'firstBranchId', 'currentDayName', 'workingDoctors', 'appointmentStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        echo "hi";
        exit;
        try {
            DB::beginTransaction();
        
        } catch (\Exception $e) {
            // echo "<pre>";
            // print_r($e->getMessage());

            DB::rollback();
            // exit;
            return redirect()->back()->with('error', 'Failed to create staff: ' . $e->getMessage());
        }
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $appointment = Appointment::with(['patient', 'doctor', 'branch'])
                        ->find($id);
        abort_if(!$appointment, 404);
        return $appointment;

        // $userDetails = $Appointment->user;
        // $departments = Department::where('status', 'Y')->get();
        // $userTypes = UserType::where('status', 'Y')->get();
        // $commonService = new CommonService();
        // $name = $commonService->splitNames($userDetails->name);
        // $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        // $availability = DoctorWorkingHour::where('user_id', $Appointment->user_id)->get();
        // $availabilityCount = DoctorWorkingHour::where('user_id', $Appointment->user_id)->groupBy('clinic_branch_id')->count();
        // $doctorAvailability = new DoctorAvaialbilityService();
        // $availableBranches = $doctorAvailability->availableBranchAndTimings($Appointment->user_id);
        // $countries = Country::all();
        // return view('appointment.edit', compact('name', 'countries', 'userTypes', 'departments', 'Appointment', 'userDetails', 'availability', 'clinicBranches', 'availabilityCount', 'availability', 'availableBranches'));
    }



    public function destroy($id)
    {
        // $Appointment = Appointment::findOrFail($id);
        // $Appointment->delete();

        // return response()->json(['success', 'Staff deleted successfully.'], 201);
    }

    public function view(string $id)
    {
        // $Appointment = Appointment::with('user')->find($id);
        // abort_if(!$Appointment, 404);

        // $userDetails = $Appointment->user;
        // $departments = Department::where('status', 'Y')->get();
        // $userTypes = UserType::where('status', 'Y')->get();
        // $commonService = new CommonService();
        // $name = $commonService->splitNames($userDetails->name);
        // $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        // $availability = DoctorWorkingHour::where('user_id', $Appointment->user_id)->get();
        // $availabilityCount = DoctorWorkingHour::where('user_id', $Appointment->user_id)->groupBy('clinic_branch_id')->count();
        // $doctorAvailability = new DoctorAvaialbilityService();
        // $availableBranches = $doctorAvailability->availableBranchAndTimings($Appointment->user_id);
        // $countries = Country::all();
        return view('appointment.view', compact('name', 'countries', 'userTypes', 'departments', 'Appointment', 'userDetails', 'availability', 'clinicBranches', 'availabilityCount', 'availability', 'availableBranches'));
    }


}

