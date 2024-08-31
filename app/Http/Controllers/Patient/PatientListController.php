<?php

namespace App\Http\Controllers\Patient;
use App\Models\CardPay;
use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\AppointmentRequest;
use App\Http\Requests\Patient\PatientEditRequest;
use App\Http\Requests\Patient\PatientListRequest;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\AppointmentType;
use App\Models\StaffProfile;
use App\Models\City;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\Country;
use App\Services\AppointmentService;
use App\Models\TreatmentStatus;
use App\Models\TeethRow;
use App\Models\DoctorWorkingHour;
use App\Models\History;
use App\Models\Insurance;
use App\Models\InsuranceCompany;
use App\Models\LeaveApplication;
use App\Models\PatientProfile;
use App\Models\PatientRegistrationFee;
use App\Models\State;
use App\Services\BillingService;
use App\Services\CommonService;
use App\Services\DoctorAvaialbilityService;
use App\Models\PatientTreatmentBilling;
use App\Models\PatientPrescriptionBilling;
use App\Models\PatientDueBill;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables as DataTables;

class PatientListController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:patients', ['only' => ['index']]);
        $this->middleware('permission:patient create', ['only' => ['create', 'store']]);
        $this->middleware('permission:patient update', ['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:patient delete', ['only' => ['destroy']]);
        $this->middleware('permission:patient view', ['only' => ['show']]);
        $this->middleware('permission:appointment create', ['only' => ['appointmentBooking']]);
        $this->middleware('permission:appointments', ['only' => ['appointmentDetails']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get the doctor ID for filtering if the user is a doctor
            $doctorId = Auth::user()->is_doctor ? Auth::id() : null;

            // Query to fetch patients
            $query = PatientProfile::with([
                'latestAppointment',
                'nextAppointment',
                'country',
                'state',
                'city'
            ]);

            if ($doctorId) {
                if (!Auth::user()->is_admin) {
                    // If the user is a doctor, filter patients who have appointments with them
                    $query->whereHas('appointments', function ($query) use ($doctorId) {
                        $query->where('doctor_id', $doctorId);
                    });
                }
                // $patients = $query->get();
            } else {
                if (!Auth::user()->is_admin) {
                    $clinicBranchId = StaffProfile::where('user_id', Auth::user()->id)
                        ->pluck('clinic_branch_id')
                        ->first();

                    $query = $query->where('app_branch', $clinicBranchId);
                }
            }

            $patients = $query->get();

            return DataTables::of($patients)
                ->addIndexColumn()
                ->addColumn('patient_id', function ($row) {
                    $latestAppointment = $row->latestAppointment ?? $row->nextAppointment;
                    if ($latestAppointment) {
                        return "<a href='#' class='waves-effect waves-light btn-patientidcard-pdf-generate' title='download patient ID' data-app-id='{$latestAppointment->id}' data-patient-id='{$row->patient_id}'>{$row->patient_id}</a>";
                    }
                    return $row->patient_id;
                })
                ->addColumn('name', function ($row) {
                    return str_replace('<br>', ' ', $row->first_name) . ' ' . $row->last_name;
                })
                ->addColumn('gender', function ($row) {
                    return match ($row->gender) {
                        'M' => 'Male',
                        'F' => 'Female',
                        'O' => 'Other',
                        default => 'Unknown',
                    };
                })
                ->addColumn('record_status', function ($row) {
                    return $row->status === 'Y'
                        ? '<span class="text-success" title="active"><i class="fa-solid fa-circle-check"></i></span>'
                        : '<span class="text-danger" title="inactive"><i class="fa-solid fa-circle-xmark"></i></span>';
                })
                ->addColumn('appointment', function ($row) {
                    return $row->latestAppointment ? $row->latestAppointment->app_date . ' ' . $row->latestAppointment->app_time : 'N/A';
                })
                ->addColumn('next_appointment', function ($row) {
                    return $row->nextAppointment ? $row->nextAppointment->app_date . ' ' . $row->nextAppointment->app_time : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $base64Id = base64_encode($row->id);
                    $idEncrypted = Crypt::encrypt($base64Id);
                    $btn = '';

                    if (Auth::user()->can('appointment create')) {
                        $btn .= "<button type='button' class='waves-effect waves-light btn btn-circle btn-primary btn-add btn-xs me-1' title='New Booking' data-bs-toggle='modal' data-id='{$row->id}' data-parent-id='' data-patient-id='{$row->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->first_name . ' ' . $row->last_name) . "' data-bs-target='#modal-booking'><i class='fa fa-plus'></i></button>";
                    }

                    if (Auth::user()->can('patient view')) {
                        $btn .= '<a href="' . route('patient.patient_list.view', $idEncrypted) . '" class="waves-effect waves-light btn btn-circle btn-info btn-xs me-1" title="view"><i class="fa fa-eye"></i></a>';
                    }

                    if (Auth::user()->can('patient update')) {
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-xs me-1" data-bs-toggle="modal" data-bs-target="#modal-status" data-id="' . $row->id . '" title="change status"><i class="fa-solid fa-sliders"></i></button>';
                        $btn .= '<a href="' . route('patient.patient_list.edit', $idEncrypted) . '" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit"><i class="fa fa-pencil"></i></a>';
                    }

                    if (Auth::user()->can('patient delete')) {
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="Delete"><i class="fa-solid fa-trash"></i></button>';
                    }

                    return $btn;
                })
                ->rawColumns(['patient_id', 'record_status', 'appointment', 'next_appointment', 'action'])
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
        $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors($firstBranchId, $currentDayName, date('Y-m-d'), date('H:i'));

        // Fetch all appointment statuses
        $appointmentStatuses = AppointmentStatus::all();

        return view('patient.patient_list.index', compact('clinicBranches', 'firstBranchId', 'currentDayName', 'workingDoctors', 'appointmentStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        // Get the first branch ID
        $firstBranchId = optional($clinicBranches->first())->id;
        $date = date('Y-m-d');
        $currentDayName = Carbon::now()->englishDayOfWeek;
        $doctorAvailabilityService = new DoctorAvaialbilityService();
        $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors($firstBranchId, $currentDayName, $date, date('H:i'));
        $appointmentStatuses = AppointmentStatus::all(); // Get all appointment statuses
        $clinic = ClinicBasicDetail::first();
        $registrationFees = $clinic->patient_registration_fees;
        $cardPay = CardPay::where('status', 'Y')->get();

        return view('patient.patient_list.add', compact('countries', 'states', 'cities', 'clinicBranches', 'workingDoctors', 'appointmentStatuses', 'registrationFees', 'cardPay'));

    }

    public function fetchDoctors($branchId, Request $request)
    {
        // Extract the date part from appdate
        $date = Carbon::parse($request->input('appdate'))->toDateString(); // 'Y-m-d'
        $time = Carbon::parse($request->input('appdate'))->toTimeString(); // 'Y-m-d'

        $carbonDate = Carbon::parse($date);
        $weekday = $carbonDate->format('l');
        $doctorAvailabilityService = new DoctorAvaialbilityService();
        $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors($branchId, $weekday, $date, $time);

        return response()->json($workingDoctors);
    }

    public function fetchExistingAppointments($branchId, Request $request)
    {
        $appDateTime = Carbon::parse($request->input('appdate'));
        $appDate = $appDateTime->toDateString(); // Extract date
        $appTime = $appDateTime->toTimeString(); // Extract time
        $doctor_id = $request->input('doctorId');
        $patient_id = $request->input('patientId');
        $doctorAvailabilityService = new DoctorAvaialbilityService();
        $checkAppointmentDate = 0;
        $checkAppointmentDate = $doctorAvailabilityService->checkAppointmentDate($branchId, $appDate, $doctor_id, $patient_id);
        $existingAppointments = $doctorAvailabilityService->getExistingAppointments($branchId, $appDate, $doctor_id);
        $checkAllocated = $doctorAvailabilityService->checkAllocatedAppointments($branchId, $appDate, $doctor_id, $appTime);

        $carbonDate = Carbon::parse($appDate);
        $weekDay = $carbonDate->format('l');
        $doctorAvailable = true;
        if ($branchId != '' && $doctor_id != '' && $weekDay != '' && $appTime != '') {
            $doctorAvailable = $doctorAvailabilityService->isDoctorAvailable($branchId, $doctor_id, $weekDay, $appTime);
        }

        $response = [
            'existingAppointments' => $existingAppointments,
            'checkAllocated' => $checkAllocated,
            'checkAppointmentDate' => $checkAppointmentDate,
            'doctorAvailable' => $doctorAvailable,
            // 'nextAppointment' => $nextAppointment,
        ];

        return response()->json($response);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientListRequest $request)
    {
        //
        try {
            //Log::info('$appId: '.$request);
            DB::beginTransaction();
            // Generate a unique patient ID using the current date
            $date = now()->format('Ymd'); // Get the current date in YYYYMMDD format
            $today = Carbon::today();

            // Get the maximum patient_id for today and increment it
            $latestPatient = DB::table('patient_profiles')
                ->whereDate('created_at', $today)
                ->orderByDesc('patient_id')
                ->first();

            if ($latestPatient) {
                // Extract the count from the latest patient_id (last 3 digits)
                $latestCount = (int) substr($latestPatient->patient_id, -3);
                $dailyCount = $latestCount + 1;
            } else {
                // No patient IDs for today, start with 001
                $dailyCount = 1;
            }

            $uniquePatientId = $date . sprintf('%03d', $dailyCount);

            // Store the patient data
            $patient = new PatientProfile();
            $patient->patient_id = $uniquePatientId; // Generate a unique patient_id
            $patient->first_name = $request->input('title') . '<br> ' . $request->input('firstname');
            $patient->last_name = $request->input('lastname');
            $patient->gender = $request->input('gender');
            $patient->date_of_birth = $request->input('date_of_birth');
            $patient->aadhaar_no = $request->input('aadhaar_no');
            $patient->email = $request->input('email');
            $patient->phone = $request->input('phone');
            $patient->alternate_phone = $request->input('alter_phone');
            $patient->blood_group = $request->input('blood_group');
            $patient->address1 = $request->input('address1');
            $patient->address2 = $request->input('address2');
            $patient->country_id = $request->input('country_id');
            $patient->state_id = $request->input('state_id');
            $patient->city_id = $request->input('city_id');
            $patient->pincode = $request->input('pincode');
            $patient->marital_status = $request->input('marital_status');
            $patient->created_by = auth()->user()->id;
            // $patient->updated_by = auth()->user()->id;
            if ($patient->save()) {

                // Parse and format the appointment date and time
                $appDateTime = Carbon::parse($request->input('appdate'));
                $appDate = $appDateTime->toDateString(); // 'Y-m-d'
                $appTime = $appDateTime->toTimeString(); // 'H:i:s'

                // Generate unique token number for the appointment
                $doctorId = $request->input('doctor2');
                $commonService = new CommonService();

                $tokenNo = $commonService->generateTokenNo($doctorId, $appDate);
                $clinicBranchId = $request->input('clinic_branch_id0');
                // Check if an appointment with the same date, time, and doctor exists
                $existingAppointment = $commonService->checkexisting($doctorId, $appDate, $appTime, $clinicBranchId, $patient->id);
                if ($existingAppointment) {
                    DB::rollBack();

                    return response()->json(['error' => 'An appointment already exists for the given date, time, and doctor.'], 422);
                }

                // Store the appointment data
                $appointment = new Appointment();
                $appointment->app_id = $commonService->generateUniqueAppointmentId($appDate);
                //$appointment->app_id = $this->generateUniqueAppointmentId();
                $appointment->patient_id = $patient->patient_id;
                $appointment->app_date = $appDate;
                $appointment->app_time = $appTime;
                $appointment->token_no = $tokenNo;
                $appointment->doctor_id = $request->input('doctor2');
                $appointment->app_branch = $request->input('clinic_branch_id0');
                $appointment->app_type = AppointmentType::NEW;
                $appointment->temperature = $request->input('temperature');
                $appointment->height_cm = $request->input('height');
                $appointment->weight_kg = $request->input('weight');
                $appointment->blood_pressure = $request->input('bp');
                $appointment->smoking_status = $request->input('smoking_status');
                $appointment->alcoholic_status = $request->input('alcoholic_status');
                $appointment->diet = $request->input('diet');
                $appointment->allergies = $request->input('allergies');
                $appointment->pregnant = $request->input('pregnant');
                $appointment->referred_doctor = $request->input('rdoctor');
                $appointment->app_status = $request->input('appstatus');
                $appointment->created_by = auth()->user()->id;
                // $appointment->updated_by = auth()->user()->id;
                if ($appointment->save()) {
                    // Save patient medical condition history if provided
                    $medicalConditions = $request->input('medical_conditions');
                    foreach ($medicalConditions as $condition) {

                        if ($condition != '') {
                            $history = new History();
                            $history->patient_id = $patient->patient_id;
                            $history->app_id = $appointment->id;
                            $history->history = $condition;
                            $history->doctor_id = $request->input('doctor2');
                            $history->created_by = auth()->user()->id;
                            $history->save();
                        }

                    }
                    $billingService = new BillingService();
                    $bill_id = $billingService->generateBillId();
                    $billPaidDate = Carbon::now();
                    $registrationFee = PatientRegistrationFee::create([
                        'bill_id' => $bill_id,
                        'patient_id' => $patient->patient_id,
                        'appointment_id' => $appointment->id,
                        'amount' => $request->input('regfee'),
                        'amount_to_be_paid' => $request->input('regfee'),
                        'payment_method' => $request->input('paymode'),
                        'gpay' => $request->input('paymode') == 'GPay' ? $request->input('regfee') : 0,
                        'cash' => $request->input('paymode') == 'Cash' ? $request->input('regfee') : 0,
                        'card' => $request->input('paymode') == 'Card' ? $request->input('regfee') : 0,
                        'card_pay_id' => $request->input('paymode') == 'Card' ? $request->input('cardmachine') : null,
                        'amount_paid' => $request->input('regfee'),
                        'balance_given' => null,
                        'bill_paid_date' => $billPaidDate,
                        'status' => 'Y',
                        'created_by' => auth()->user()->id,
                        'updated_by' => auth()->user()->id,
                    ]);
                    $incomeData = [
                        'bill_type' => 'registration_fee',
                        'bill_no' => $bill_id,
                        'bill_date' => $billPaidDate,
                        'gpay' => $request->input('paymode') == 'GPay' ? $request->input('regfee') : 0,
                        'cash' => $request->input('paymode') == 'Cash' ? $request->input('regfee') : 0,
                        'card' => $request->input('paymode') == 'Card' ? $request->input('regfee') : 0,
                        'card_pay_id' => $request->input('paymode') == 'Card' ? $request->input('cardmachine') : null,
                        'balance_given' => 0,
                        'created_by' => auth()->user()->id,
                    ];
                    $incomeReport = $billingService->saveIncomeReport($incomeData);
                    DB::commit();
                    if ($registrationFee) {
                        $registrationFees = PatientRegistrationFee::with('createdBy')->where('patient_id', $patient->patient_id)->get();

                        $patient1 = PatientProfile::with([
                            'country:id,country',
                            'state:id,state',
                            'city:id,city',
                        ])
                            ->where('patient_id', $patient->patient_id)
                            ->first();
                        $appointmentDetails = Appointment::with(['patient', 'doctor', 'branch'])
                            ->find($appointment->id);
                        $clinicDetails = ClinicBasicDetail::first();
                        if ($clinicDetails->clinic_logo == '') {
                            $clinicLogo = 'public/images/logo-It.png';
                        } else {
                            $clinicLogo = 'storage/' . $clinicDetails->clinic_logo;
                        }
                        // Generate the bill
                        $pdf = PDF::loadView('pdf.registrationBill_pdf', [
                            'billDetails' => $registrationFees,
                            'patient' => $patient1,
                            'appointment' => $appointmentDetails,
                            'clinicDetails' => $clinicDetails,
                            'clinicLogo' => $clinicLogo,
                            'currency' => $clinicDetails->currency,
                        ])->setPaper('A5', 'portrait');


                        // Return the PDF as a download
                        return response()->json([
                            'success' => true,
                            'pdf' => base64_encode($pdf->output()), // Encode the PDF as base64
                        ]);
                    }

                    return redirect()->route('patient.patient_list')->with('success', 'Patient and appointment added successfully');

                } else {
                    DB::rollBack();

                    return redirect()->back()->with('error', 'Failed to create appointment');
                }
            } else {
                DB::rollBack();

                return redirect()->back()->with('error', 'Failed to create patient');
            }

            // Redirect back with a success message
            //return redirect()->route('patient.patient_list')->with('success', 'Patient added successfully');
        } catch (\Exception $e) {

            DB::rollback();

            // exit;
            return response()->json(['error' => 'Failed to create patient: ' . $e->getMessage()], 422);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $id = base64_decode(Crypt::decrypt($id));
        // Find the PatientProfile by its ID
        $patientProfile = PatientProfile::with(['lastAppointment.doctor', 'lastAppointment.branch', 'history'])->find($id);

        // Check if the PatientProfile was found
        if (!$patientProfile) {
            abort(404, 'Patient Profile not found');
        }
        $appointment = $patientProfile->lastAppointment;
        $history = $patientProfile->history;
        Session::put('patientId', $patientProfile->patient_id);
        Session::put('appId', $patientProfile->lastAppointment->id);

        $appointid = $appointment->id;
        $appointmentService = new AppointmentService();
        $previousAppointments = $appointmentService->getPreviousAppointments($appointid, $appointment->app_date, $patientProfile->patient_id);
        $patientName = str_replace('<br>', ' ', $patientProfile->first_name) . ' ' . $patientProfile->last_name;

        // if ($request->ajax()) {
        //     return DataTables::of($previousAppointments)
        //         ->addIndexColumn()
        //         ->addColumn('doctor', function ($row) {
        //             return str_replace('<br>', ' ', $row->doctor->name);
        //         })
        //         ->addColumn('branch', function ($row) {
        //             if (!$row->branch) {
        //                 return '';
        //             }
        //             $address = implode(', ', explode('<br>', $row->branch->clinic_address));

        //             return implode(', ', [$address, $row->branch->city->city, $row->branch->state->state]);
        //         })
        //         ->addColumn('status', function ($row) {


        //             $statusMap = [
        //                 TreatmentStatus::COMPLETED => 'fa-circle-check text-success',
        //                 TreatmentStatus::FOLLOWUP => 'fa-circle-exclamation text-warning',
        //             ];

        //             // Ensure $row->toothExamination is not null and properly loaded
        //             $treatmentStatusId = $row->toothExamination->isNotEmpty()
        //                 ? $row->toothExamination->first()->treatment_status
        //                 : null;

        //             $btnClass = isset($statusMap[$treatmentStatusId]) ? $statusMap[$treatmentStatusId] : '';
        //             //$btnClass = isset($statusMap[$treatmentStatusId]) ? $statusMap[$treatmentStatusId] : 'badge-secondary';
        //             $statusWords = TreatmentStatus::statusToWords($treatmentStatusId);

        //             // return "<span class='btn-sm d-block badge {$btnClass}'>{$statusWords}</span>";
        //             return "<i class='fa-solid {$btnClass} fs-16' title='{$statusWords}'></i>";
        //         })
        //         ->addColumn('treat_date', function ($row) {
        //             return $row->app_date;
        //         })

        //         ->addColumn('teeth', function ($row) {
        //             $teethName = '';
        //             if ($row->toothExamination->isEmpty()) {
        //                 return '';
        //             }
        //             $teethData = $row->toothExamination->map(function ($examination) {
        //                 if ($examination->teeth) {
        //                     $teethName = $examination->teeth->teeth_name;
        //                     $teethImage = $examination->teeth->teeth_image;

        //                     return $teethName;
        //                     //return '<div>'.$teethName.'<br><img src="'.asset($teethImage).'" alt="'.$teethName.'" width="50" height="50"></div>';
        //                 } elseif ($examination->tooth_id == null && $examination->row_id != null) {
        //                     // Use TeethRow constants for descriptions
        //                     switch ($examination->row_id) {
        //                         case TeethRow::Row1:
        //                             $teethName = 'Row : ' . TeethRow::Row_1_Desc;
        //                             break;
        //                         case TeethRow::Row2:
        //                             $teethName = 'Row : ' . TeethRow::Row_2_Desc;
        //                             break;
        //                         case TeethRow::Row3:
        //                             $teethName = 'Row : ' . TeethRow::Row_3_Desc;
        //                             break;
        //                         case TeethRow::Row4:
        //                             $teethName = 'Row : ' . TeethRow::Row_4_Desc;
        //                             break;
        //                         case TeethRow::RowAll:
        //                             $teethName = TeethRow::Row_All_Desc;
        //                             break;
        //                         default:
        //                             $teethName = '';
        //                             break;
        //                     }

        //                     return $teethName;
        //                 }

        //                 return '';
        //             })->implode(',<br>');

        //             return $teethData;
        //         })
        //         ->addColumn('problem', function ($row) {
        //             return $row->toothExamination ? $row->toothExamination->pluck('chief_complaint')->implode(',') : '';
        //         })
        //         ->addColumn('disease', function ($row) {

        //             return $row->toothExamination ? $row->toothExamination->map(function ($examination) {
        //                 return $examination->disease ? $examination->disease->name : 'No Disease';
        //             })->implode(', ') : '';
        //         })
        //         ->addColumn('remarks', function ($row) {
        //             return $row->toothExamination ? $row->toothExamination->pluck('remarks')->implode(', ') : '';
        //         })
        //         ->addColumn('treatment', function ($row) {
        //             return $row->toothExamination ? $row->toothExamination->map(function ($examination) {
        //                 return $examination->treatment ? $examination->treatment->treat_name : '';
        //             })->filter()->implode(', ') // Use comma and <br> to separate treatments
        //                 : '';
        //         })
        //         ->addColumn('action', function ($row) use ($patientName) {

        //             $parent_id = $row->app_parent_id ? $row->app_parent_id : $row->id;
        //             $buttons = [];
        //             // Check if the appointment date is less than the selected date
        //             if ($row->app_status == AppointmentStatus::COMPLETED) {
        //                 $base64Id = base64_encode($row->id);
        //                 $idEncrypted = Crypt::encrypt($base64Id);
        //                 $buttons[] = "<a href='" . route('treatment', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-info btn-xs me-1' title='view' data-id='" . e($row->id) . "' data-parent-id='" . e($parent_id) . "' data-patient-id='" . e($row->patient_id) . "' data-patient-name='" . e($patientName) . "' target='_blank'><i class='fa-solid fa-eye'></i></a>";
        //                 $buttons[] = "<button type='button' class='waves-effect waves-light btn btn-circle btn-secondary btn-treatment-pdf-generate btn-xs' title='Download Treatment Summary' data-bs-toggle='modal' data-app-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient_id}'  data-bs-target='#modal-download'><i class='fa fa-download'></i></button>";
        //             }

        //             return implode('', $buttons);
        //         })

        //         ->rawColumns(['status', 'teeth', 'action'])
        //         ->make(true);
        // }
        if ($request->ajax()) {
            return DataTables::of($previousAppointments)
                ->addIndexColumn()
                ->addColumn('doctor', function ($row) {
                    return str_replace('<br>', ' ', $row->doctor->name);
                })
                ->addColumn('branch', function ($row) {
                    if (!$row->branch) {
                        return '';
                    }
                    $address = implode(', ', explode('<br>', $row->branch->clinic_address));

                    return implode(', ', [$address, $row->branch->city->city, $row->branch->state->state]);
                })
                ->addColumn('status', function ($row) {
                    // Map treatment statuses to FontAwesome classes
                    $statusMap = [
                        TreatmentStatus::COMPLETED => 'fa-circle-check text-success',
                        TreatmentStatus::FOLLOWUP => 'fa-circle-exclamation text-warning',
                    ];
                
                    // Check if toothExamination is not null and properly loaded
                    if (!$row->toothExamination) {
                        return '';
                    }
                
                    // Generate list items for each tooth's status
                    $statusListItems = $row->toothExamination->map(function ($examination) use ($statusMap) {
                        $treatmentStatusId = $examination->treatment_status;
                        $btnClass = isset($statusMap[$treatmentStatusId]) ? $statusMap[$treatmentStatusId] : 'fa-circle text-secondary';
                        $statusWords = TreatmentStatus::statusToWords($treatmentStatusId);
                
                        return "<li><i class='fa-solid {$btnClass} fs-16' title='{$statusWords}'></i></li>";
                    })->implode('');
                
                    // Wrap list items in a <ul> element
                    return $statusListItems ? "<ul>{$statusListItems}</ul>" : '';
                })
                
                
                ->addColumn('treat_date', function ($row) {
                    return $row->app_date;
                })

                ->addColumn('teeth', function ($row) {
                    if ($row->toothExamination->isEmpty()) {
                        return '';
                    }
                    $teethData = $row->toothExamination->map(function ($examination) {
                        if ($examination->teeth) {
                            $teethName = $examination->teeth->teeth_name;
                            return "<li>{$teethName}</li>";
                        } elseif ($examination->tooth_id == null && $examination->row_id != null) {
                            $teethName = match ($examination->row_id) {
                                TeethRow::Row1 => 'Row : ' . TeethRow::Row_1_Desc,
                                TeethRow::Row2 => 'Row : ' . TeethRow::Row_2_Desc,
                                TeethRow::Row3 => 'Row : ' . TeethRow::Row_3_Desc,
                                TeethRow::Row4 => 'Row : ' . TeethRow::Row_4_Desc,
                                TeethRow::RowAll => TeethRow::Row_All_Desc,
                                default => '',
                            };
                            return "<li>{$teethName}</li>";
                        }
                        return '';
                    })->implode('');
                    
                    return $teethData ? "<ul>{$teethData}</ul>" : '';
                })
                
                ->addColumn('problem', function ($row) {
                    if (!$row->toothExamination) {
                        return '';
                    }
                    $problems = $row->toothExamination->pluck('chief_complaint')->filter()->map(function ($problem) {
                        return "<li>{$problem}</li>";
                    })->implode('');
                    
                    return $problems ? "<ul>{$problems}</ul>" : '';
                })
                
                ->addColumn('disease', function ($row) {
                    if (!$row->toothExamination) {
                        return '';
                    }
                    $diseases = $row->toothExamination->map(function ($examination) {
                        return $examination->disease ? "<li>{$examination->disease->name}</li>" : "<li>No Disease</li>";
                    })->implode('');
                    
                    return $diseases ? "<ul>{$diseases}</ul>" : '';
                })
                
                ->addColumn('remarks', function ($row) {
                    if (!$row->toothExamination) {
                        return '';
                    }
                    $remarks = $row->toothExamination->pluck('remarks')->filter()->map(function ($remark) {
                        return "<li>{$remark}</li>";
                    })->implode('');
                    
                    return $remarks ? "<ul>{$remarks}</ul>" : '';
                })
                
                ->addColumn('treatment', function ($row) {
                    if (!$row->toothExamination) {
                        return '';
                    }
                    $treatments = $row->toothExamination->map(function ($examination) {
                        return $examination->treatment ? "<li>{$examination->treatment->treat_name}</li>" : '';
                    })->filter()->implode('');
                    
                    return $treatments ? "<ul>{$treatments}</ul>" : '';
                })
                
                    
                ->addColumn('action', function ($row) use ($patientName) {

                    $parent_id = $row->app_parent_id ? $row->app_parent_id : $row->id;
                    $buttons = [];
                    // Check if the appointment date is less than the selected date
                    if ($row->app_status == AppointmentStatus::COMPLETED) {
                        $base64Id = base64_encode($row->id);
                        $idEncrypted = Crypt::encrypt($base64Id);
                        $buttons[] = "<a href='" . route('treatment', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-info btn-xs me-1' title='view' data-id='" . e($row->id) . "' data-parent-id='" . e($parent_id) . "' data-patient-id='" . e($row->patient_id) . "' data-patient-name='" . e($patientName) . "' target='_blank'><i class='fa-solid fa-eye'></i></a>";
                        $buttons[] = "<button type='button' class='waves-effect waves-light btn btn-circle btn-secondary btn-treatment-pdf-generate btn-xs' title='Download Treatment Summary' data-bs-toggle='modal' data-app-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient_id}'  data-bs-target='#modal-download'><i class='fa fa-download'></i></button>";
                    }

                    return implode('', $buttons);
                })

                ->rawColumns(['status', 'teeth', 'problem', 'disease', 'remarks', 'treatment', 'action'])
                ->make(true);
        }

        // Return a view with the PatientProfile data
        return view('patient.patient_list.view_patient', compact('patientProfile', 'appointment', 'history'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = base64_decode(Crypt::decrypt($id));
        //$patientProfile = PatientProfile::with(['lastAppointment'])->find($id);
        $patientProfile = PatientProfile::with(['lastAppointment', 'history'])->find($id);
        abort_if(!$patientProfile, 404);
        $appointment = $patientProfile->lastAppointment;
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        $countries = Country::all();
        $commonService = new CommonService();
        $appointmentStatuses = AppointmentStatus::all();
        $name = $commonService->splitNames($patientProfile->first_name);
        $date = Carbon::parse($patientProfile->lastAppointment->app_date)->toDateString(); // 'Y-m-d'
        $time = Carbon::parse($patientProfile->lastAppointment->app_date)->toTimeString(); // 'Y-m-d'
        $carbonDate = Carbon::parse($date);
        $weekday = $carbonDate->format('l');
        $doctorAvailabilityService = new DoctorAvaialbilityService();
        $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors($patientProfile->lastAppointment->app_branch, $weekday, $date, $time);
        $appDate = $appointment->app_date;
        $appTime = $appointment->app_time;
        // Combine date and time into a single datetime string
        $dateTimeString = "{$appDate} {$appTime}";
        // Parse the combined datetime string as it is already in IST
        $dateTime = Carbon::parse($dateTimeString)
            ->format('Y-m-d\TH:i');
        $medicalConditions = $patientProfile->history->pluck('history')->toArray();
        $insuranceCompanies = InsuranceCompany::where('status', 'Y')->get();
        $patientInsurance = Insurance::where('patient_id', $patientProfile->patient_id)->where('status', 'Y')->get();
        $clinicDetails = ClinicBasicDetail::first();

        return view(
            'patient.patient_list.edit',
            compact(
                'name',
                'patientProfile',
                'countries',
                'appointment',
                'clinicBranches',
                'appointmentStatuses',
                'workingDoctors',
                'dateTime',
                'medicalConditions',
                'insuranceCompanies',
                'patientInsurance',
                'clinicDetails'
            )
        );
    }

    public function update(PatientEditRequest $request)
    {
        try {
            DB::beginTransaction();

            // Update the patient data
            $patient = PatientProfile::findOrFail($request->edit_patient_id);
            $patient->fill([
                'first_name' => $request->title . '<br> ' . $request->firstname,
                'last_name' => $request->input('lastname'),
                'gender' => $request->input('gender'),
                'date_of_birth' => $request->input('date_of_birth'),
                'aadhaar_no' => $request->input('aadhaar_no'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'alternate_phone' => $request->input('alter_phone'),
                'blood_group' => $request->input('blood_group'),
                'address1' => $request->input('address1'),
                'address2' => $request->input('address2'),
                'country_id' => $request->input('country_id'),
                'state_id' => $request->input('state_id'),
                'city_id' => $request->input('city_id'),
                'pincode' => $request->input('pincode'),
                'marital_status' => $request->input('marital_status'),
                'updated_by' => auth()->user()->id,
            ]);

            if (!$patient->save()) {
                //throw new \Exception('Failed to update patient');
                return redirect()->back()->with('error', 'Failed to update patient');
            }

            $clinicDetails = ClinicBasicDetail::first();
            if ($clinicDetails->clinic_insurance_available == 'Y') {

                if (($request->input('policy_number') != '')) {
                    // Check if an active insurance record already exists for this patient
                    $existingInsurance = Insurance::where('patient_id', $patient->patient_id)
                        ->where('status', 'Y')
                        ->first();

                    // If an existing insurance record is found, update its status to 'N'
                    if ($existingInsurance) {
                        $existingInsurance->update(['status' => 'N']);
                        $existingInsurance->update(['updated_by' => auth()->user()->id]);
                    }

                    // Create a new insurance record
                    Insurance::create([
                        'patient_id' => $patient->patient_id,
                        'policy_number' => $request->input('policy_number'),
                        'insurance_company_id' => $request->input('insurance_company_id'),
                        'policy_end_date' => $request->input('policy_end_date'),
                        'status' => $request->input('status'),
                        'created_by' => auth()->user()->id,
                        'updated_by' => auth()->user()->id,
                        // 'insured_name' => $request->input('insured_name'),
                        // 'insured_dob' => $request->input('insured_dob'),
                    ]);

                }

            }
            DB::commit();

            return redirect()->route('patient.patient_list')->with('success', 'Patient updated successfully');
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Failed to update patient: ' . $e->getMessage()], 422);
        }
    }

    public function changeStatus(string $id)
    {
        $patientProfile = PatientProfile::find($id);
        abort_if(!$patientProfile, 404);
        if ($patientProfile) {
            $active = 'N';
            $inActive = 'Y';
            if ($patientProfile->status == $active) {
                $patientProfile->status = $inActive;
            } else {
                $patientProfile->status = $active;
            }
            $patientProfile->save();

            return redirect()->route('patient.patient_list')->with('success', 'Status updated successfully');
        }

    }

    public function appointmentDetails($id)
    {
        // Prepare data to return
        $patientProfile = PatientProfile::where('patient_id', $id)
            ->with('lastAppointment')
            ->first();

        abort_if(!$patientProfile, 404);

        // Get the last appointment ID
        $lastAppointmentId = $patientProfile->lastAppointment ? $patientProfile->lastAppointment->id : null;

        // Now fetch the history with the last appointment ID
        $history = $patientProfile->history()
            ->when($lastAppointmentId, function ($query) use ($lastAppointmentId) {
                $query->where('app_id', $lastAppointmentId);
            })
            ->get();
        $lastAppointment = $patientProfile->lastAppointment;
        $response = [
            'id' => $patientProfile->id,
            'patient_name' => $patientProfile->name,
            'height_cm' => optional($lastAppointment)->height_cm,
            'weight_kg' => optional($lastAppointment)->weight_kg,
            'blood_pressure' => optional($lastAppointment)->blood_pressure,
            'temperature' => optional($lastAppointment)->temperature,
            'smoking_status' => optional($lastAppointment)->smoking_status,
            'alcoholic_status' => optional($lastAppointment)->alcoholic_status,
            'diet' => optional($lastAppointment)->diet,
            'allergies' => optional($lastAppointment)->allergies,
            'pregnant' => optional($lastAppointment)->pregnant,
            'referred_doctor' => optional($lastAppointment)->referred_doctor,
            'doctor_id' => optional($lastAppointment)->doctor_id,
            'last_appointment_date' => optional($lastAppointment)->app_date,
            'history' => $history,
            'gender' => $patientProfile->gender,
        ];

        return response()->json($response);
    }

    public function appointmentBooking(AppointmentRequest $request)
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
            $doctorAvailabilityService = new DoctorAvaialbilityService();

            $tokenNo = $commonService->generateTokenNo($doctorId, $appDate);
            $clinicBranchId = $request->input('clinic_branch_id');
            $patientId = $request->input('patient_id');
            // Check if an appointment with the same date, time, and doctor exists
            $existingAppointment = $commonService->checkexisting($doctorId, $appDate, $appTime, $clinicBranchId, $patientId);
            $existingAppointmentPatient = $doctorAvailabilityService->checkAppointmentDate($clinicBranchId, $appDate, $doctorId, $patientId);
            if ($existingAppointmentPatient) {
                return response()->json(['errorPatient' => 'An appointment already exists for the given date and doctor.'], 422);
            }

            if ($existingAppointment) {

                DB::rollBack();

                return response()->json(['error' => 'An appointment already exists for the given date, time, and doctor.'], 422);
            }

            // Store the appointment data
            $appointment = new Appointment();
            $appointment->app_id = $commonService->generateUniqueAppointmentId($appDate);
            $appointment->patient_id = $request->input('patient_id');
            $appointment->app_date = $appDate;
            $appointment->app_time = $appTime;
            $appointment->token_no = $tokenNo;
            $appointment->doctor_id = $doctorId;
            $appointment->app_branch = $clinicBranchId;
            $appointment->app_type = AppointmentType::NEW;
            $appointment->height_cm = $request->input('height');
            $appointment->weight_kg = $request->input('weight');
            $appointment->blood_pressure = $request->input('bp');
            $appointment->temperature = $request->input('temperature');
            $appointment->referred_doctor = $request->input('rdoctor');
            $appointment->smoking_status = $request->input('smoking_status');
            $appointment->alcoholic_status = $request->input('alcoholic_status');
            $appointment->diet = $request->input('diet');
            $appointment->allergies = $request->input('allergies');
            $appointment->pregnant = $request->input('pregnant');
            $appointment->app_status = AppointmentStatus::SCHEDULED;
            $appointment->app_parent_id = $request->input('app_parent_id');
            $appointment->created_by = auth()->user()->id;
            $appointment->updated_by = auth()->user()->id;

            if ($appointment->save()) {
                $medicalConditions = $request->input('medical_conditions', []);
                //Add medical conditions to the history table
                foreach ($medicalConditions as $condition) {
                    if (!empty($condition)) {
                        $history = new History();
                        $history->patient_id = $request->input('patient_id');
                        $history->app_id = $appointment->id; // Assuming you have this in your request
                        $history->history = $condition;
                        $history->doctor_id = $doctorId; // Assuming the logged-in user is the doctor
                        $history->created_by = auth()->user()->id;
                        $history->updated_by = auth()->user()->id;
                        $history->save();
                    }
                }
                DB::commit();

                return redirect()->route('patient.patient_list')->with('success', 'Appointment added successfully');
            } else {
                DB::rollBack();

                return redirect()->back()->with('error', 'Failed to create appointment');
            }
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Failed to create appointment: ' . $e->getMessage()], 422);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $patient = PatientProfile::findOrFail($id);
        $patient->delete();

        return response()->json(['success', 'Patient deleted successfully.'], 201);
    }

    public function bill($patientId)
    {
        $treatmentQuery = PatientTreatmentBilling::with([
            'appointment.branch',
            'billedBy:id,name',
        ])
        ->where('status', 'Y')
        ->where('patient_id', $patientId);
        
        $treatmentBillings = $treatmentQuery->get();
        $prescriptionQuery = PatientPrescriptionBilling::with([
            'appointment.branch',
        ])
        ->where('status', 'Y')
        ->where('patient_id', $patientId);
        $prescriptionBillings = $prescriptionQuery->get();

        $registrationFeeQuery = PatientRegistrationFee::with([
            'appointment.branch',
        ])
        ->where('status', 'Y')
        ->where('patient_id', $patientId);
        $registrationBillings = $registrationFeeQuery->get();

        $dueBillQuery = PatientDueBill::with([
            'appointment.branch',
        ])
        ->where('status', 'Y')
        ->where('patient_id', $patientId);
        $dueBillngs = $dueBillQuery->get();



        $allBillings = collect(); // Initialize an empty collection
        $allBillings = $treatmentBillings
                ->concat($prescriptionBillings)
                ->concat($registrationBillings)
                ->concat($dueBillngs);
        
        return DataTables::of($allBillings)
            ->addIndexColumn()
            ->addColumn('billDate', function ($row) {

                if (isset($row->bill_paid_date)) {
                    return $row->bill_paid_date;
                } else {
                    return '';
                }
            })
            ->addColumn('branch', function ($row) {
                if (isset($row->appointment->branch)) {
                    return $row->appointment->branch ? str_replace('<br>', ', ', $row->appointment->branch->clinic_address) : '';
                } else {
                    return '';
                }
            })
            ->addColumn('billType', function ($row) {
                if (isset($row->treatment_total_amount)) {
                    return 'Treatment Bill';
                } elseif (isset($row->prescription_total_amount)) {
                    return 'Medicine Bill';
                } elseif (isset($row->payment_method)) {
                    return 'Registration Bill';
                } elseif (isset($row->treatment_bill_id)) {
                    return 'Outstanding Bill';
                } else {
                    return '';
                }
            })
            ->addColumn('total', function ($row) {
                //return number_format($row->treatment_total_amount, 2);
                if (isset($row->treatment_total_amount)) {
                    // return number_format($row->treatment_total_amount, 2);
                    return $row->treatment_total_amount;
                } elseif (isset($row->prescription_total_amount)) {
                    // return number_format($row->prescription_total_amount, 2);
                    return $row->prescription_total_amount;
                } elseif (isset($row->amount)) {
                    // return number_format($row->amount, 2);
                    return $row->amount;
                } elseif (isset($row->total_amount)) {
                    // return number_format($row->total_amount, 2);
                    return $row->total_amount;
                } else {
                    return 0;
                }
            })
            ->addColumn('discount', function ($row) {
                //return number_format($row->doctor_discount, 2);
                if (isset($row->doctor_discount) || isset($row->combo_offer_deduction)) {
                    //return number_format($row->doctor_discount, 2);
                    return $row->doctor_discount + $row->combo_offer_deduction;
                } elseif (isset($row->discount)) {
                    // return number_format($row->discount, 2);
                    return $row->discount;
                } else {
                    return 0;
                }
            })
            ->addColumn('tax', function ($row) {

                if (isset($row->tax)) {
                    // return number_format($row->tax, 2);
                    return $row->tax;
                } else {
                    return 0;
                }
            })
            ->addColumn('netAmount', function ($row) {
                if (isset($row->amount_to_be_paid)) {
                    // return number_format($row->amount_to_be_paid, 2);
                    return $row->amount_to_be_paid;
                } elseif (isset($row->total_amount)) {
                    // return number_format($row->total_amount, 2);
                    return $row->total_amount;
                } else {
                    return 0;
                }
            })
            ->addColumn('cash', function ($row) {
                if (isset($row->cash)) {
                    //return number_format($row->cash, 2);
                    return $row->cash;
                } else {
                    return 0;
                }
            })
            ->addColumn('gpay', function ($row) {
                if (isset($row->gpay)) {
                    //return number_format($row->gpay, 2);
                    return $row->gpay;
                } else {
                    return 0;
                }
            })
            ->addColumn('card', function ($row) {
                if (isset($row->card)) {
                    //return number_format($row->card, 2);
                    return $row->card;
                } else {
                    return 0;
                }
            })
            ->addColumn('totalPaid', function ($row) {
                //return number_format($row->amount_paid, 2);
                if (isset($row->amount_paid)) {
                    //return number_format($row->amount_paid, 2);
                    return $row->amount_paid;
                } elseif (isset($row->paid_amount)) {
                    //return number_format($row->paid_amount, 2);
                    return $row->paid_amount;
                } else {
                    return 0;
                }
            })
            ->addColumn('balanceGiven', function ($row) {
                // return number_format($row->balance_to_give_back, 2);
                if (isset($row->balance_to_give_back)) {
                    // return number_format($row->balance_to_give_back, 2);
                    return $row->balance_to_give_back;
                } elseif (isset($row->balance_given)) {
                    //return number_format($row->balance_given, 2);
                    return $row->balance_given;
                } else {
                    return 0;
                }
            })
            ->addColumn('outstanding', function ($row) {
                if (isset($row->balance_due)) {
                    // return number_format($row->balance_due, 2);
                    return $row->balance_due;
                } else {
                    return 0;
                }
            })
            ->make(true);
    }
}
