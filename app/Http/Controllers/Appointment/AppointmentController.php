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
use App\Models\AppointmentType;
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
use Exception;

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
                ->addColumn('app_type', function ($row) {
                    if ($row->app_type == AppointmentType::NEW) {
                        return AppointmentType::NEW_WORDS;
                    } else if ($row->app_type == AppointmentType::FOLLOWUP) {
                        return AppointmentType::FOLLOWUP_WORDS;
                    }
                    
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
                    if ($row->app_status) {
                        $btn = '';

                        //return $row->latestAppointment->app_status;
                        if ($row->app_status == AppointmentStatus::SCHEDULED) {
                            $btn = "<span class='btn-sm badge badge-success-light'>".AppointmentStatus::SCHEDULED_WORDS."</span>";
                        } elseif ($row->app_status == AppointmentStatus::WAITING) {
                            $btn = '<span class="btn-sm badge badge-success-light">'.AppointmentStatus::WAITING_WORDS.'</span>';
                        } elseif ($row->app_status == AppointmentStatus::UNAVAILABLE) {
                            $btn = '<span class="btn-sm badge badge-danger-light">'.AppointmentStatus::UNAVAILABLE_WORDS.'</span>';
                        } elseif ($row->app_status == AppointmentStatus::CANCELLED) {
                            $btn = '<span class="btn-sm badge badge-danger-light">'.AppointmentStatus::CANCELLED_WORDS.'</span>';
                        } elseif ($row->app_status == AppointmentStatus::COMPLETED) {
                            $btn = '<span class="btn-sm badge badge-success-light">'.AppointmentStatus::COMPLETED_WORDS.'</span>';
                        } elseif ($row->app_status == AppointmentStatus::BILLING) {
                            $btn = '<span class="btn-sm badge badge-success-light">'.AppointmentStatus::BILLING_WORDS.'</span>';
                        } elseif ($row->app_status == AppointmentStatus::PROCEDURE) {
                            $btn = '<span class="btn-sm badge badge-success-light">'.AppointmentStatus::PROCEDURE_WORDS.'</span>';
                        } elseif ($row->app_status == AppointmentStatus::MISSED) {
                            $btn = '<span class="btn-sm badge badge-danger-light">'.AppointmentStatus::MISSED_WORDS.'</span>';
                        } elseif ($row->app_status == AppointmentStatus::RESCHEDULED) {
                            $btn = '<span class="btn-sm badge badge-success-light">'.AppointmentStatus::RESCHEDULED_WORDS.'</span>';
                        }

                        return $btn;
                    }

                    return 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->app_status != AppointmentStatus::CANCELLED) {
                        $parent_id = $row->app_parent_id ? $row->app_parent_id : $row->id;
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-add btn-xs me-1" title="follow up" data-bs-toggle="modal" data-id="' . $row->id . '" data-parent-id="' . $parent_id . '" data-patient-id="' . $row->patient->patient_id . '" data-patient-name="' . str_replace("<br>", " ", $row->patient->first_name." ".$row->patient->last_name) . '"
                            data-bs-target="#modal-booking" ><i class="fa fa-plus"></i></button>';
                        
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-edit btn-xs me-1" title="reschedule" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-reschedule" ><i class="fa-solid fa-calendar-days"></i></button>';
                        
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-cancel" data-id="' . $row->id . '" title="cancel">
                        <i class="fa fa-times"></i></button>';
                    }

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
        try {
            DB::beginTransaction();
            $date = Carbon::parse($request->input('appdate'))->toDateString(); // 'Y-m-d'
            $appDate = Carbon::parse($date);
           
            $doctorId = $request->input('doctor_id');
            $maxToken = Appointment::where('doctor_id', $doctorId)
                ->whereDate('app_date', $appDate)
                ->max('token_no');
            $tokenNo = $maxToken ? $maxToken + 1 : 1;

            $appDateTime = Carbon::parse($request->input('appdate'));
            $appDate = $appDateTime->toDateString(); // Extract date
            $appTime = $appDateTime->toTimeString(); // Extract time

            // Check if an appointment with the same date and time already exists for the given doctor
            $existingAppointment = Appointment::where('doctor_id', $doctorId)
                ->where('app_date', $appDate)
                ->where('app_time', $appTime)
                ->where('app_branch', $request->input('clinic_branch_id'))
                ->first();

            if ($existingAppointment) {
                DB::rollBack();

                return response()->json(['error' => 'An appointment already exists for the given date, time, and doctor.'], 422);
            }

            $commonService = new CommonService();
            // Store the appointment data
            $appointment = new Appointment();
            //$appointment->app_id = Appointment::max('app_id') + 1; // Generate a unique app_id
            $appointment->app_id = $commonService->generateUniqueAppointmentId();
            $appointment->patient_id = $request->input('patient_id');
            $appointment->app_date = $appDate;
            $appointment->app_time = $appTime;
            $appointment->token_no = $tokenNo;
            $appointment->doctor_id = $doctorId;
            $appointment->app_branch = $request->input('clinic_branch_id');
            $appointment->app_type = AppointmentType::FOLLOWUP;
            $appointment->height_cm = $request->input('height');
            $appointment->weight_kg = $request->input('weight');
            $appointment->blood_pressure = $request->input('bp');
            $appointment->referred_doctor = $request->input('rdoctor');
            $appointment->app_status = AppointmentStatus::SCHEDULED;
            $appointment->app_parent_id = $request->input('app_parent_id');
            $appointment->created_by = auth()->user()->id;
            $appointment->updated_by = auth()->user()->id;
            if ($appointment->save()) {
                DB::commit();
                
                return redirect()->back()->with('success', 'Appointment added successfully');
            } else {
                DB::rollBack();

                return redirect()->back()->with('error', 'Failed to create appointment');
            }

        } catch (\Exception $e) {
            // echo "<pre>";
            // print_r($e->getMessage());
            DB::rollback();
            // exit;
            return redirect()->back()->with('error', 'Failed to create appointment: ' . $e->getMessage());
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



    public function destroy($id, Request $request)
    {
        try {
        $appointment = Appointment::findOrFail($id);
        $appointment->app_status_change_reason = $request->input('app_status_change_reason');
        $appointment->app_status = AppointmentStatus::CANCELLED;
        $appointment->status = 'N';
        $appointment->save();
        return response()->json(['success', 'Appointment cancelled successfully.'], 200);
        
        } catch (Exception $e) {
            
        return response()->json(['error', 'Appointment not cancelled.'], 200);
        }
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

