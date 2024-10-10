<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\AppointmentType;
use App\Models\ClinicBranch;
use App\Models\DoctorWorkingHour;
use App\Models\PatientTreatmentBilling;
use App\Models\StaffProfile;
use App\Services\CommonService;
use App\Services\DoctorAvaialbilityService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:appointments', ['only' => ['index']]);
        $this->middleware('permission:appointment create', ['only' => ['store']]);
        $this->middleware('permission:appointment reschedule', ['only' => ['edit', 'update']]);
        $this->middleware('permission:appointment cancel', ['only' => ['destroy']]);

    }
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
            $selectedDate = $request->input('selectedDate');

            // $query = Appointment::whereDate('app_date', $selectedDate)
            //     ->with(['patient', 'doctor', 'branch']);

            // // Filter by doctor if the authenticated user is a doctor
            // // if (Auth::user()->is_doctor) {
            // //     $query->where('doctor_id', Auth::user()->id);
            // // }

            // // Get appointments
            // $appointments = $query->get();

            $appointments = Appointment::whereDate('app_date', $selectedDate);
            // if (Auth::user()->is_doctor) {
            //     if (!Auth::user()->is_admin) {
            //         $appointments = $appointments->where('doctor_id', Auth::user()->id);
            //     }
            //     $appointments = $appointments->with(['patient', 'doctor', 'branch'])
            //         ->orderBy('token_no', 'ASC')
            //         ->get();
            // } else {
            //     $clinicBranchId = StaffProfile::where('user_id', Auth::user()->id)
            //         ->pluck('clinic_branch_id')
            //         ->first();

            //     $appointments = $appointments->where('app_branch', $clinicBranchId)
            //         ->with(['patient', 'doctor', 'branch'])
            //         ->orderBy('token_no', 'ASC')
            //         ->get();
            // }

            if (Auth::user()->is_admin) {
                // $appointments = $appointments->with(['patient', 'doctor', 'branch'])
                //     ->orderBy('token_no', 'ASC')
                //     ->get();
            } else if (Auth::user()->is_doctor) {
                $appointments = $appointments->where('doctor_id', Auth::user()->id);
            } else {
                $clinicBranchId = StaffProfile::where('user_id', Auth::user()->id)
                    ->pluck('clinic_branch_id')
                    ->first();

                // $appointments = $appointments->where('app_branch', $clinicBranchId);
                

                // Check if the clinicBranchId is not null or empty
                if (!empty($clinicBranchId)) {
                    // Convert the string to an array
                    $clinicBranchIdsArray = explode(',', $clinicBranchId);
      
                    // Filter appointments based on the clinic branch IDs
                    $appointments = $appointments->whereIn('app_branch', $clinicBranchIdsArray);
                }
            }

            $appointments = $appointments->with(['patient', 'doctor', 'branch'])
                ->orderBy('token_no', 'ASC')
                ->get();

            return DataTables::of($appointments)
                ->addIndexColumn()
                ->addColumn('patient_id', function ($row) {
                    $parent_id = $row->app_parent_id ? $row->app_parent_id : $row->id;
                    $base64Id = base64_encode($parent_id);
                    $idEncrypted = Crypt::encrypt($base64Id);
                    $name1 = "<a href='" . route('treatment', $idEncrypted) . "' class='waves-effect waves-light' title='open treatment' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' >" . $row->patient->patient_id . '</i></a>';

                    return $name1;
                })
                ->addColumn('name', function ($row) {
                    $parent_id = $row->app_parent_id ? $row->app_parent_id : $row->id;
                    $base64Id = base64_encode($parent_id);
                    $idEncrypted = Crypt::encrypt($base64Id);
                    $name = str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name);
                    $name1 = "<a href='" . route('treatment', $idEncrypted) . "' class='waves-effect waves-light' title='open treatment' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' >" . $name . '</i></a>';

                    return $name1;
                })
                ->addColumn('doctor', function ($row) {
                    return str_replace('<br>', ' ', $row->doctor->name);
                })
                ->addColumn('app_type', function ($row) {
                    return $row->app_type == AppointmentType::NEW ? AppointmentType::NEW_WORDS :
                        ($row->app_type == AppointmentType::FOLLOWUP ? AppointmentType::FOLLOWUP_WORDS : '');
                })
                ->addColumn('branch', function ($row) {
                    if (!$row->branch) {
                        return '';
                    }
                    $address = implode(', ', explode('<br>', $row->branch->clinic_address));

                    return implode(', ', [$address, $row->branch->city->city, $row->branch->state->state]);
                })
                ->addColumn('phone', function ($row) {
                    return $row->patient->phone;
                })
                ->addColumn('status', function ($row) {
                    // $statusMap = [
                    //     AppointmentStatus::SCHEDULED => 'text-success',
                    //     AppointmentStatus::WAITING => 'text-warning',
                    //     AppointmentStatus::UNAVAILABLE => 'text-dark',
                    //     AppointmentStatus::CANCELLED => 'text-danger',
                    //     AppointmentStatus::COMPLETED => 'text-muted',
                    //     AppointmentStatus::BILLING => 'text-primary',
                    //     AppointmentStatus::PROCEDURE => 'text-secondary',
                    //     AppointmentStatus::MISSED => 'text-white',
                    //     AppointmentStatus::RESCHEDULED => 'text-info',
                    // ];
                    $statusMap = [
                        AppointmentStatus::SCHEDULED => 'badge-success',
                        AppointmentStatus::WAITING => 'badge-warning',
                        AppointmentStatus::UNAVAILABLE => 'badge-gray',
                        AppointmentStatus::CANCELLED => 'badge-danger',
                        AppointmentStatus::COMPLETED => 'badge-info',
                        AppointmentStatus::BILLING => 'badge-primary',
                        AppointmentStatus::PROCEDURE => 'badge-secondary',
                        AppointmentStatus::MISSED => 'badge-dark',
                        AppointmentStatus::RESCHEDULED => 'badge-gray',
                    ];
                    $btnClass = isset($statusMap[$row->app_status]) ? $statusMap[$row->app_status] : '';

                    // return "<span class=' {$btnClass}'>" . AppointmentStatus::statusToWords($row->app_status) . '</span>';
    
                    return "<span class='btn d-block btn-xs badge {$btnClass}'>" . AppointmentStatus::statusToWords($row->app_status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    if ($row->app_status == AppointmentStatus::CANCELLED || $row->app_status == AppointmentStatus::RESCHEDULED) {
                        return '';
                    }
                    $parent_id = $row->app_parent_id ? $row->app_parent_id : $row->id;
                    $buttons = [];
                    $base64Id = base64_encode($row->id);
                    $idEncrypted = Crypt::encrypt($base64Id);
                    // Check if the appointment date is less than the selected date
                    if (Auth::user()->can('treatments') && (Auth::user()->id == $row->doctor_id || Auth::user()->is_admin)) {
                        if ($row->app_date < date('Y-m-d') && $row->app_status == AppointmentStatus::COMPLETED) {
                            // If appointment date is less than the selected date, show view icon
                            $buttons[] = "<a href='" . route('treatment', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-info btn-xs me-1' title='view' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' ><i class='fa-solid fa-eye'></i></a>";
                        } elseif ($row->app_date == date('Y-m-d') && $row->app_status != AppointmentStatus::MISSED) {
                            $bills = PatientTreatmentBilling::where('appointment_id', $row->id)->where('status', 'Y')->get();

                            if ($bills->isEmpty()) {
                                $buttons[] = "<a href='" . route('treatment', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-primary btn-xs me-1' title='treatment' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' ><i class='fa-solid fa-stethoscope'></i></a>";
                            } else {
                                $buttons[] = "<a href='" . route('treatment', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-info btn-xs me-1' title='view' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' ><i class='fa-solid fa-eye'></i></a>";
                            }
                        } else {
                            $buttons[] = '';
                        }
                    }
                    if (Auth::user()->can('appointment create') && $row->app_status != AppointmentStatus::MISSED) {
                        $buttons[] = "<button type='button' class='waves-effect waves-light btn btn-circle btn-success btn-add btn-xs me-1' title='follow up' data-bs-toggle='modal' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' data-bs-target='#modal-booking'><i class='fa fa-plus'></i></button>";
                    }
                    if ($row->app_status != AppointmentStatus::COMPLETED) {
                        if (Auth::user()->can('appointment reschedule')) {
                            $buttons[] = "<button type='button' class='waves-effect waves-light btn btn-circle btn-warning btn-reschedule btn-xs me-1' title='reschedule' data-bs-toggle='modal' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' data-bs-target='#modal-reschedule'><i class='fa-solid fa-calendar-days'></i></button>";
                        }
                        if (Auth::user()->can('appointment cancel')) {
                            $buttons[] = "<button type='button' class='waves-effect waves-light btn btn-circle btn-danger btn-xs me-1' id='btn-cancel' data-bs-toggle='modal' data-bs-target='#modal-cancel' data-id='{$row->id}' title='cancel'><i class='fa fa-times'></i></button>";
                        }
                    }
                    if ($row->app_status == AppointmentStatus::SCHEDULED) {

                        $buttons[] = "<button type='button' class='waves-effect waves-light btn btn-circle btn-info btn-xs me-1' id='btn-appStatus' data-id='$row->id' class='waves-effect waves-light btn btn-circle btn-info btn-xs me-1' title='change status to waiting'><i class='fa-solid fa-sliders'></i></a>";

                    }
                    if ($row->app_status == AppointmentStatus::WAITING) {
                        $buttons[] = "<button type='button' class='waves-effect waves-light btn btn-circle btn-info btn-xs me-1' id='btn-appStatus' data-id='$row->id' class='waves-effect waves-light btn btn-circle btn-info btn-xs me-1' title='change status to scheduled'><i class='fa-solid fa-sliders'></i></a>";
                    }
                    if ($row->app_status == AppointmentStatus::COMPLETED) {
                        $buttons[] = "<button type='button' class='waves-effect waves-light btn btn-circle btn-secondary btn-treatment-pdf-generate btn-xs me-1' title='Download Treatment Summary' data-bs-toggle='modal' data-app-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}'  data-bs-target='#modal-download'><i class='fa fa-download'></i></button>";
                        $buttons[] = "<a href='#' class='waves-effect waves-light btn btn-circle btn-prescription-pdf-generate btn-warning btn-xs me-1' title='Download Prescription' data-app-id='{$row->id}' data-patient-id='{$row->patient->patient_id}' ><i class='fa fa-prescription'></i></a>";
                    }

                    return implode('', $buttons);
                })

                ->rawColumns(['patient_id', 'name', 'status', 'action'])
                ->make(true);
        }

        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->get();
        $firstBranchId = $clinicBranches->first()?->id;
        $currentDayName = Carbon::now()->englishDayOfWeek;
        $doctorAvailabilityService = new DoctorAvaialbilityService();
        $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors($firstBranchId, $currentDayName, date('Y-m-d'), date('H:i'));

        $appointmentTypes = AppointmentType::all();

        return view('appointment.index', compact('clinicBranches', 'firstBranchId', 'currentDayName', 'workingDoctors', 'appointmentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $appDateTime = Carbon::parse($request->input('appdate'));
            $appDate = $appDateTime->toDateString(); // 'Y-m-d'
            $appTime = $appDateTime->toTimeString(); // 'H:i:s'
            $doctorId = $request->input('doctor_id');
            $patientId = $request->input('patient_id');
            $commonService = new CommonService();
            $doctorAvailabilityService = new DoctorAvaialbilityService();
            $tokenNo = $commonService->generateTokenNo($doctorId, $appDate);
            $clinicBranchId = $request->input('clinic_branch_id');
            // Check if an appointment with the same date, time, and doctor exists
            $existingAppointment = $commonService->checkexisting($doctorId, $appDate, $appTime, $clinicBranchId, $patientId);
            $existingAppointmentPatient = $doctorAvailabilityService->checkAppointmentDate($clinicBranchId, $appDate, $doctorId, $patientId);
            if ($existingAppointmentPatient) {
                return response()->json(['errorPatient' => 'An appointment already exists for the given date and doctor.'], 422);
            }
            if ($existingAppointment) {
                return response()->json(['error' => 'An appointment already exists for the given date, time, and doctor.'], 422);
            }

            // Store the appointment data
            $appointment = new Appointment();
            $appointment->app_id = $commonService->generateUniqueAppointmentId($appDate);
            $appointment->patient_id = $patientId;
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
        $clinicAddress = implode(', ', [
            str_replace('<br>', ', ', $appointment->branch->clinic_address),
            $appointment->branch->city->city,
            $appointment->branch->state->state,
        ]);
        $appointment->clinic_branch = $clinicAddress;
        $appointment->app_date = date('d-m-Y', strtotime($appointment->app_date));
        $appointment->app_time = date('H:i', strtotime($appointment->app_time));
        // $currentDayName = Carbon::createFromFormat('d-m-Y', $appointment->app_date)->englishDayOfWeek;
        // $doctorAvailabilityService = new DoctorAvaialbilityService();
        // $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors($appointment->app_branch, $currentDayName, $appointment->app_date);
        // $appointment->workingDoctors =$workingDoctors;
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
            $doctorId = $request->edit_doctor;
            $commonService = new CommonService();
            $tokenNo = $commonService->generateTokenNo($doctorId, $appDate);
            // Check if an appointment with the same date, time, and doctor exists
            $doctorAvailabilityService = new DoctorAvaialbilityService();
            $carbonDate = Carbon::parse($appDate);
            $weekDay = $carbonDate->format('l');
            $appointmentExists = $commonService->checkexisting($doctorId, $appDate, $appTime, $existingAppointment->app_branch, $existingAppointment->patient_id);
            if ($appointmentExists) {
                return response()->json(['error' => 'An appointment already exists for the given date, time, and doctor.'], 422);
            }
            // Store the appointment data
            $appointment = new Appointment();
            $appointment->app_id = $commonService->generateUniqueAppointmentId($appDate);
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
    public function showForm($appBranch)
    {
        $availableDoctorIds = DoctorWorkingHour::
            where('status', 'Y') // Assuming 'Y' indicates availability
            ->where('clinic_branch_id', $appBranch)
            ->pluck('user_id'); // Get user IDs of available doctors

        // Step 2: Fetch doctors from StaffProfile and ensure they are actual doctors
        $allDoctors = StaffProfile::with('user')
            ->whereIn('user_id', $availableDoctorIds)
            ->whereHas('user', function ($query) {
                $query->where('is_doctor', 1); // Ensure the user is a doctor
            })
            ->get();

        return response()->json($allDoctors);

    }
    public function changeStatus($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment)
            abort(404);
        $message = null;
        if ($appointment->app_status == AppointmentStatus::SCHEDULED) {
            $appointment->app_status = AppointmentStatus::WAITING;
            $message = 'Appointment Status changed to Waiting';
        } else if ($appointment->app_status == AppointmentStatus::WAITING) {
            $appointment->app_status = AppointmentStatus::SCHEDULED;
            $message = 'Appointment Status changed back to Scheduled';
        }
        if ($appointment->save()) {
            return response()->json(['success', $message]);
        } else {
            return response()->json(['error', 'Error occured while updating status']);
        }
    }
}
