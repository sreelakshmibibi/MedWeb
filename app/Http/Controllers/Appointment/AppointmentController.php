<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\AppointmentRequest;
use App\Models\City;
use App\Models\Teeth;
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
use App\Models\PatientProfile;


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
        // Flash success message if provided in query parameter
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

            return DataTables::of($appointments)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return str_replace("<br>", " ", $row->patient->first_name . " " . $row->patient->last_name);
                })
                ->addColumn('doctor', function ($row) {
                    return str_replace("<br>", " ", $row->doctor->name);
                })
                ->addColumn('app_type', function ($row) {
                    return $row->app_type == AppointmentType::NEW ? AppointmentType::NEW_WORDS :
                        ($row->app_type == AppointmentType::FOLLOWUP ? AppointmentType::FOLLOWUP_WORDS : '');
                })
                ->addColumn('branch', function ($row) {
                    if (!$row->branch) {
                        return '';
                    }
                    $address = implode(", ", explode("<br>", $row->branch->clinic_address));
                    return implode(", ", [$address, $row->branch->city->city, $row->branch->state->state]);
                })
                ->addColumn('phone', function ($row) {
                    return $row->patient->phone;
                })
                ->addColumn('status', function ($row) {
                    $statusMap = [
                        AppointmentStatus::SCHEDULED => 'badge-success-light',
                        AppointmentStatus::WAITING => 'badge-success-light',
                        AppointmentStatus::UNAVAILABLE => 'badge-danger-light',
                        AppointmentStatus::CANCELLED => 'badge-danger-light',
                        AppointmentStatus::COMPLETED => 'badge-success-light',
                        AppointmentStatus::BILLING => 'badge-success-light',
                        AppointmentStatus::PROCEDURE => 'badge-success-light',
                        AppointmentStatus::MISSED => 'badge-danger-light',
                        AppointmentStatus::RESCHEDULED => 'badge-success-light',
                    ];
                    $btnClass = isset($statusMap[$row->app_status]) ? $statusMap[$row->app_status] : '';
                    return "<span class='btn-sm badge {$btnClass}'>" . AppointmentStatus::statusToWords($row->app_status) . "</span>";
                })
                ->addColumn('action', function ($row) {
                    if ($row->app_status == AppointmentStatus::CANCELLED || $row->app_status == AppointmentStatus::RESCHEDULED) {
                        return '';
                    }
                    $parent_id = $row->app_parent_id ? $row->app_parent_id : $row->id;
                    $buttons = [
                        "<a href='" . route('treatment', $row->id) . "' class='waves-effect waves-light btn btn-circle btn-primary btn-add btn-xs me-1' title='treatment' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace("<br>", " ", $row->patient->first_name . " " . $row->patient->last_name) . "' ><i class='fa-solid fa-stethoscope'></i></a>",
                        "<button type='button' class='waves-effect waves-light btn btn-circle btn-success btn-add btn-xs me-1' title='follow up' data-bs-toggle='modal' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace("<br>", " ", $row->patient->first_name . " " . $row->patient->last_name) . "' data-bs-target='#modal-booking'><i class='fa fa-plus'></i></button>",
                        "<button type='button' class='waves-effect waves-light btn btn-circle btn-warning btn-reschedule btn-xs me-1' title='reschedule' data-bs-toggle='modal' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace("<br>", " ", $row->patient->first_name . " " . $row->patient->last_name) . "' data-bs-target='#modal-reschedule'><i class='fa-solid fa-calendar-days'></i></button>",
                        "<button type='button' class='waves-effect waves-light btn btn-circle btn-danger btn-xs' id='btn-cancel' data-bs-toggle='modal' data-bs-target='#modal-cancel' data-id='{$row->id}' title='cancel'><i class='fa fa-times'></i></button>",
                    ];
                    return implode('', $buttons);
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        // Fetch clinic branches for the view
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->get();

        // Fetch the first clinic branch ID
        $firstBranchId = $clinicBranches->first()?->id;

        // Get the current day name
        $currentDayName = Carbon::now()->englishDayOfWeek;

        // Initialize DoctorAvaialbilityService and fetch working doctors
        $doctorAvailabilityService = new DoctorAvaialbilityService();
        $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors($firstBranchId, $currentDayName);

        // Fetch all appointment statuses
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

            // Parse and format the appointment date and time
            $appDateTime = Carbon::parse($request->input('appdate'));
            $appDate = $appDateTime->toDateString(); // 'Y-m-d'
            $appTime = $appDateTime->toTimeString(); // 'H:i:s'

            // Generate unique token number for the appointment
            $doctorId = $request->input('doctor_id');
            $commonService = new CommonService();

            $tokenNo = $commonService->generateTokenNo($doctorId, $appDate);
            $clinicBranchId = $request->input('clinic_branch_id');
            // Check if an appointment with the same date, time, and doctor exists
            $existingAppointment = $commonService->checkexisting($doctorId, $appDate, $appTime, $clinicBranchId);

            if ($existingAppointment) {
                return response()->json(['error' => 'An appointment already exists for the given date, time, and doctor.'], 422);
            }

            // Store the appointment data
            $appointment = new Appointment();
            $appointment->app_id = $commonService->generateUniqueAppointmentId();
            $appointment->patient_id = $request->input('patient_id');
            $appointment->app_date = $appDate;
            $appointment->app_time = $appTime;
            $appointment->token_no = $tokenNo;
            $appointment->doctor_id = $doctorId;
            $appointment->app_branch = $clinicBranchId;
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
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to create appointment: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $appointment = Appointment::with(['patient', 'doctor', 'branch'])->find($id);
        abort_if(!$appointment, 404);
        // Format clinic address
        $clinicAddress = implode(", ", [
            str_replace("<br>", ", ", $appointment->branch->clinic_address),
            $appointment->branch->city->city,
            $appointment->branch->state->state
        ]);

        // Update appointment object with formatted clinic address
        $appointment->clinic_branch = $clinicAddress;

        // Format date and time
        $appointment->app_date = date('d-m-Y', strtotime($appointment->app_date));
        $appointment->app_time = date('H:i', strtotime($appointment->app_time));

        return $appointment;
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $existingAppointment = Appointment::with(['patient', 'doctor', 'branch'])->find($request->edit_app_id);
            $existingAppointment->app_status = AppointmentStatus::RESCHEDULED;
            $existingAppointment->app_status_change_reason = $request->reschedule_reason;
            $existingAppointment->status = 'N';
            $existingAppointment->save();

            $appDateTime = Carbon::parse($request->input('rescheduledAppdate'));
            $appDate = $appDateTime->toDateString(); // 'Y-m-d'
            $appTime = $appDateTime->toTimeString(); // 'H:i:s'

            // Generate unique token number for the appointment
            $doctorId = $existingAppointment->doctor_id;
            $commonService = new CommonService();

            $tokenNo = $commonService->generateTokenNo($doctorId, $appDate);
            // Check if an appointment with the same date, time, and doctor exists
            $appointmentExists = $commonService->checkexisting($doctorId, $appDate, $appTime, $existingAppointment->app_branch);

            if ($appointmentExists) {
                return response()->json(['error' => 'An appointment already exists for the given date, time, and doctor.'], 422);
            }

            // Store the appointment data
            $appointment = new Appointment();
            $appointment->app_id = $commonService->generateUniqueAppointmentId();
            $appointment->patient_id = $existingAppointment->patient_id;
            $appointment->app_date = $appDate;
            $appointment->app_time = $appTime;
            $appointment->token_no = $tokenNo;
            $appointment->doctor_id = $doctorId;
            $appointment->app_branch = $existingAppointment->app_branch;
            $appointment->app_type = $existingAppointment->app_type;
            $appointment->height_cm = $existingAppointment->height_cm;
            $appointment->weight_kg = $existingAppointment->weight_kg;
            $appointment->blood_pressure = $existingAppointment->blood_pressure;
            $appointment->referred_doctor = $existingAppointment->referred_doctor;
            $appointment->app_status = AppointmentStatus::SCHEDULED;
            $appointment->app_parent_id = $existingAppointment->app_parent_id;
            if ($appointment->save()) {
                DB::commit();
                return redirect()->back()->with('success', 'Appointment rescheduled successfully');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Failed to reschedule appointment: ');
            }

        } catch (\Exception $e) {
            print_r($e->getMessage());
            return redirect()->back()->with('error', 'Failed to reschedule appointment: ' . $e->getMessage());
        }

    }

    public function destroy($id, Request $request)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->app_status_change_reason = $request->input('app_status_change_reason');
            $appointment->app_status = AppointmentStatus::CANCELLED;
            $appointment->status = 'N';
            if ($appointment->save()) {
                return response()->json(['success', 'Appointment cancelled successfully.'], 200);
            } else {
                return response()->json(['error', 'Appointment cancellation unsuccessfull.'], 422);
            }

        } catch (Exception $e) {

            return response()->json(['error', 'Appointment not cancelled.'], 200);
        }
    }

}

