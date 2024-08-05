<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\TreatmentDetailsRequest;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\AppointmentType;
use App\Models\ClinicBranch;
use App\Models\ComboOfferTreatment;
use App\Models\Disease;
use App\Models\Dosage;
use App\Models\Medicine;
use App\Models\MedicineRoute;
use App\Models\PatientProfile;
use App\Models\Prescription;
use App\Models\SurfaceCondition;
use App\Models\Teeth;
use App\Models\TeethRow;
use App\Models\ToothExamination;
use App\Models\ToothScore;
use App\Models\TreatmentComboOffer;
use App\Models\TreatmentPlan;
use App\Models\TreatmentStatus;
use App\Models\TreatmentType;
use App\Models\XRayImage;
use App\Services\AnatomyService;
use App\Services\AppointmentService;
use App\Services\CommonService;
use App\Services\DoctorAvaialbilityService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id, Request $request)
    {

        $id = base64_decode(Crypt::decrypt($id));

        $appointment = Appointment::with(['patient', 'doctor', 'branch'])->find($id);
        abort_if(! $appointment, 404);

        if ($appointment->app_status != AppointmentStatus::COMPLETED) {

            $appointment->consult_start_time = Carbon::now('Asia/Kolkata'); // Get the current time
            $appointment->save();

        }
        $appAction = 'Treatment';
        if ($appointment->app_date < date('Y-m-d') && $appointment->app_status == AppointmentStatus::COMPLETED) {
            $appAction = 'Show';
        }
        $doctorDiscount = $appointment->doctor_discount;
        //$patientProfile = PatientProfile::with(['lastAppointment'])->find($appointment->patient->id);
        $patientProfile = PatientProfile::with(['lastAppointment.doctor', 'lastAppointment.branch'])->find($appointment->patient->id);
        $appointmentService = new AppointmentService();
        $latestAppointment = $appointmentService->getLatestAppointment($id, $appointment->app_date, $appointment->patient->patient_id);
        $previousAppointments = $appointmentService->getPreviousAppointments($id, $appointment->app_date, $appointment->patient->patient_id);

        //$patient = PatientProfile::find($id);
        abort_if(! $patientProfile, 404);
        $appointment = $patientProfile->lastAppointment;
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->get();
        $appointmentBranchId = $appointment->app_branch;

        // Get the current day name
        $currentDayName = Carbon::now()->englishDayOfWeek;

        // Initialize DoctorAvaialbilityService and fetch working doctors
        $doctorAvailabilityService = new DoctorAvaialbilityService();
        $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors($appointmentBranchId, $currentDayName);

        // Fetch all appointment types
        $appointmentTypes = AppointmentType::all();
        $medicines = Medicine::all();
        $dosages = Dosage::all();
        $medicineRoutes = MedicineRoute::all();
        $tooth = Teeth::all();
        $toothScores = ToothScore::all();
        $surfaceConditions = SurfaceCondition::all();
        $treatmentStatus = TreatmentStatus::all();
        $treatments = TreatmentType::where('status', 'Y')->get();
        $diseases = Disease::where('status', 'Y')->get();
        $patientName = str_replace('<br>', ' ', $appointment->patient->first_name).' '.$appointment->patient->last_name;
        $doctorName = str_replace('<br>', ' ', $appointment->doctor->name);
        $patientPrescriptions = Prescription::with([
            'medicine' => function ($query) {
                $query->select('id', 'med_name', 'med_company');
            },
            'dosage' => function ($query) {
                $query->select('id', 'dos_name');
            },
        ])
            ->where('app_id', $id)
            ->where('status', 'Y')
            ->get(['id', 'patient_id', 'app_id', 'medicine_id', 'dosage_id', 'duration', 'advice', 'remark', 'prescribed_by', 'dose', 'dose_unit', 'route_id']);
        $latestFollowup = Appointment::where('app_parent_id', $id)
            ->with('doctor', 'branch')
            ->orderBy('app_date', 'desc')
            ->orderBy('app_time', 'desc')
            ->first();

        Session::put('appId', $id);
        Session::put('patientName', $patientName);
        Session::put('patientId', $appointment->patient->patient_id);
        Session::put('doctorName', $doctorName);
        Session::put('doctorId', $appointment->doctor->id);

        $plans = TreatmentPlan::orderBy('plan', 'asc')->get();
        $toothIds = ToothExamination::where('patient_id', $appointment->patient->patient_id)
            ->where('app_id', '<=', $id)
            ->where('status', 'Y')
            ->select('app_id', 'tooth_id', 'row_id', 'anatomy_image', 'treatment_status', 'lingual_condn', 'labial_condn', 'occulusal_condn', 'distal_condn', 'mesial_condn', 'palatal_condn', 'buccal_condn')
            ->get();
        if ($request->ajax()) {
            return DataTables::of($previousAppointments)
                ->addIndexColumn()
                ->addColumn('doctor', function ($row) {
                    return str_replace('<br>', ' ', $row->doctor->name);
                })
                ->addColumn('branch', function ($row) {
                    if (! $row->branch) {
                        return '';
                    }
                    $address = implode(', ', explode('<br>', $row->branch->clinic_address));

                    return implode(', ', [$address, $row->branch->city->city, $row->branch->state->state]);
                })
                ->addColumn('status', function ($row) {
                    $statusMap = [
                        TreatmentStatus::COMPLETED => 'badge-success-light',
                        TreatmentStatus::FOLLOWUP => 'badge-warning-light',
                    ];

                    // Ensure $row->toothExamination is not null and properly loaded
                    $treatmentStatusId = $row->toothExamination->isNotEmpty()
                        ? $row->toothExamination->first()->treatment_status
                        : null;

                    $btnClass = isset($statusMap[$treatmentStatusId]) ? $statusMap[$treatmentStatusId] : '';
                    //$btnClass = isset($statusMap[$treatmentStatusId]) ? $statusMap[$treatmentStatusId] : 'badge-secondary';
                    $statusWords = TreatmentStatus::statusToWords($treatmentStatusId);

                    return "<span class='btn-sm badge {$btnClass}'>{$statusWords}</span>";
                })
                ->addColumn('treat_date', function ($row) {
                    return $row->app_date;
                })

                ->addColumn('teeth', function ($row) {
                    $teethName = '';
                    if ($row->toothExamination->isEmpty()) {
                        return '';
                    }
                    $teethData = $row->toothExamination->map(function ($examination) {
                        if ($examination->teeth) {
                            $teethName = $examination->teeth->teeth_name;
                            $teethImage = $examination->teeth->teeth_image;

                            return $teethName;
                            //return '<div>'.$teethName.'<br><img src="'.asset($teethImage).'" alt="'.$teethName.'" width="50" height="50"></div>';
                        } elseif ($examination->tooth_id == null && $examination->row_id != null) {
                            // Use TeethRow constants for descriptions
                            switch ($examination->row_id) {
                                case TeethRow::Row1:
                                    $teethName = 'Row : '.TeethRow::Row_1_Desc;
                                    break;
                                case TeethRow::Row2:
                                    $teethName = 'Row : '.TeethRow::Row_2_Desc;
                                    break;
                                case TeethRow::Row3:
                                    $teethName = 'Row : '.TeethRow::Row_3_Desc;
                                    break;
                                case TeethRow::Row4:
                                    $teethName = 'Row : '.TeethRow::Row_4_Desc;
                                    break;
                                default:
                                    $teethName = '';
                                    break;
                            }

                            return $teethName;
                        }

                        return '';
                    })->implode(',<br>');

                    return $teethData;
                })
                ->addColumn('problem', function ($row) {
                    return $row->toothExamination ? $row->toothExamination->pluck('chief_complaint')->implode(',') : '';
                })
                ->addColumn('disease', function ($row) {

                    return $row->toothExamination ? $row->toothExamination->map(function ($examination) {
                        return $examination->disease ? $examination->disease->name : 'No Disease';
                    })->implode(', ') : '';
                })
                ->addColumn('remarks', function ($row) {
                    return $row->toothExamination ? $row->toothExamination->pluck('remarks')->implode(', ') : '';
                })
                ->addColumn('treatment', function ($row) {
                    return $row->toothExamination ? $row->toothExamination->map(function ($examination) {
                        return $examination->treatment ? $examination->treatment->treat_name : '';
                    })->filter()->implode(', ') // Use comma and <br> to separate treatments
                        : '';
                })
                ->addColumn('action', function ($row) use ($patientName) {

                    $parent_id = $row->app_parent_id ? $row->app_parent_id : $row->id;
                    $buttons = [];
                    // Check if the appointment date is less than the selected date
                    if ($row->app_status == AppointmentStatus::COMPLETED) {
                        $base64Id = base64_encode($row->id);
                        $idEncrypted = Crypt::encrypt($base64Id);
                        $buttons[] = "<a href='".route('treatment', $idEncrypted)."' class='waves-effect waves-light btn btn-circle btn-info btn-xs me-1' title='view' data-id='".e($row->id)."' data-parent-id='".e($parent_id)."' data-patient-id='".e($row->patient_id)."' data-patient-name='".e($patientName)."' target='_blank'><i class='fa-solid fa-eye'></i></a>";
                        $buttons[] = "<button type='button' class='waves-effect waves-light btn btn-circle btn-info btn-treatment-pdf-generate btn-xs me-1' title='Download Treatment Summary' data-bs-toggle='modal' data-app-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient_id}'  data-bs-target='#modal-download'><i class='fa fa-download'></i></button>";
                    }

                    return implode('', $buttons);
                })

                ->rawColumns(['status', 'teeth', 'action'])
                ->make(true);
        }

        return view('appointment.treatment', compact('patientProfile', 'appointment', 'tooth', 'latestAppointment', 'toothScores', 'surfaceConditions', 'treatmentStatus', 'treatments', 'diseases', 'previousAppointments', 'clinicBranches', 'appointmentTypes', 'workingDoctors', 'medicines', 'dosages', 'patientPrescriptions', 'appAction', 'doctorDiscount', 'latestFollowup', 'plans', 'toothIds', 'medicineRoutes'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function fetchExistingExamination($toothId, $appId, $patientId)
    {
        $toothExamination = [];
        if (in_array($toothId, [1, 2, 3, 4])) {
            $toothExamination = ToothExamination::where('row_id', $toothId)
                ->where('patient_id', $patientId)
                ->where('app_id', $appId)
                ->where('status', 'Y')
                ->first();
        } else {
            $toothExamination = ToothExamination::where('tooth_id', $toothId)
                ->where('patient_id', $patientId)
                ->where('app_id', $appId)
                ->where('status', 'Y')
                ->first();
        }
        $xrays = null;
        if ($toothExamination) {
            if ($toothExamination->xray == 1) {
                $xrays = XRayImage::where('tooth_examination_id', $toothExamination->id)->get();
            }
        }

        return response()->json(['examination' => $toothExamination, 'xrays' => $xrays]);
    }

    // public function getImages($patientId, $toothId)
    // {

    //     $directory = 'public/x-rays/' . $patientId . '/' . $toothId;
    //     $files = Storage::files($directory);

    //     // Extract only the filenames from the full file paths
    //     $fileNames = [];
    //     foreach ($files as $file) {
    //         $fileName = pathinfo($file, PATHINFO_BASENAME); // Get just the filename
    //         $fileNames[] = $fileName;
    //     }

    //     return response()->json(['images' => $fileNames]);
    // }

    public function getImages($toothExaminationId)
    {
        $xrays = XRayImage::where('tooth_examination_id', $toothExaminationId)->where('status', 'Y')->get();

        return response()->json(['images' => $xrays]);

    }

    public function deleteImage(Request $request)
    {
        $imageName = $request->get('image');
        $patientId = $request->get('patientId');
        $toothId = $request->get('toothId');

        // Storage::delete('storage/x-rays/'.$patientId.'/'.$toothId.'/'.$imageName);
        $XRayImage = XRayImage::findOrFail($imageName);
        $XRayImage->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }

    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $checkExists = [];
            if (in_array($request->row_id, [1, 2, 3, 4])) {
                $checkExists = ToothExamination::where('row_id', $request->row_id)
                    ->where('patient_id', $request->patient_id)
                    ->where('app_id', $request->app_id)
                    ->where('status', 'Y')
                    ->get();
            } else {
                $checkExists = ToothExamination::where('tooth_id', $request->tooth_id)
                    ->where('patient_id', $request->patient_id)
                    ->where('app_id', $request->app_id)
                    ->where('status', 'Y')
                    ->get();
            }

            if (! empty($checkExists)) {
                foreach ($checkExists as $check) {
                    $check->status = 'N';
                    $check->save();
                    // $xraysExists = XRayImage::where('tooth_examination_id', $check->id)->get();
                    // if (!empty($xraysExists)) {
                    //     XRayImage::where('tooth_examination_id', $check->id) // Condition to match
                    //     ->update(['status' => 'N']);
                    // }
                }
            }
            // $toothExamination = new ToothExamination();
            $toothId = $request->tooth_id;
            $row_id = $request->selected_row;
            $occulusal_condn = $request->occulusal_condn != null ? 1 : 0;
            $palatal_condn = $request->palatal_condn != null ? 1 : 0;
            $mesial_condn = $request->mesial_condn != null ? 1 : 0;
            $buccal_condn = $request->buccal_condn != null ? 1 : 0;
            $distal_condn = $request->distal_condn != null ? 1 : 0;

            $toothExamination = ToothExamination::create($request->only([
                'app_id',
                'patient_id',
                'tooth_id',
                'row_id',
                'tooth_score_id',
                'chief_complaint',
                'disease_id',
                'hpi',
                'dental_examination',
                'diagnosis',
                'treatment_id',
                'treatment_plan_id',
                'remarks',
                'palatal_condn',
                'mesial_condn',
                'distal_condn',
                'buccal_condn',
                'occulusal_condn',
                'labial_condn',
                'lingual_condn',
                'treatment_status',
            ]));
            $anatomyService = new AnatomyService();
            $anatomyImage = $anatomyService->getAnatomyImage($toothId, $occulusal_condn, $palatal_condn, $mesial_condn, $distal_condn, $buccal_condn);
            $toothExaminationEdit = ToothExamination::find($toothExamination->id);
            if ($request->hasFile('xray')) {
                $toothExaminationEdit->xray = 1;
                foreach ($request->file('xray') as $file) {
                    $xrayPath = $file->store('x-rays/'.$request->patient_id.'/'.$request->tooth_id, 'public');
                    $xrays = new XRayImage();
                    $xrays->tooth_examination_id = $toothExamination->id;
                    $xrays->xray = $xrayPath;
                    $xrays->save();
                }

            }
            if ($checkExists) {
                foreach ($checkExists as $check) {
                    $xraysExists = XRayImage::where('tooth_examination_id', $check->id)->get();
                    $toothExaminationEdit->xray = 1;
                    if (! $xraysExists->isEmpty()) {
                        // Update XRayImage records associated with this $check
                        XRayImage::where('tooth_examination_id', $check->id)
                            ->update([
                                'tooth_examination_id' => $toothExamination->id,
                            ]);
                    }
                }
            }

            $toothExaminationEdit->anatomy_image = $anatomyImage;
            if ($toothExaminationEdit->save()) {
                DB::commit();

                return response()->json(['success' => 'Tooth examination for teeth no '.$toothId.' added']);
            } else {
                DB::rollback();

                return response()->json(['error' => 'Failed adding Tooth examination for teeth no '.$toothId]);
            }

        } catch (Exception $ex) {
            DB::rollBack();
            echo '<pre>';
            print_r($ex->getMessage());
            echo '</pre>';

            return response()->json(['error' => 'Failed adding Tooth examination for teeth no '.$toothId]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($appointment, Request $request)
    {
        // Retrieve patient_id from the query parameters
        $patientId = $request->query('patient_id');
        echo $patientId;
        // Fetch ToothExamination data with related teeth and treatment details
        $toothExaminations = ToothExamination::with([
            'teeth:id,teeth_name,teeth_image',
            'treatment:id,treat_name,treat_cost',
            'toothScore:id,score',
            'disease:id,name',
            'xRayImages:id,tooth_examination_id,xray,status',
        ])
            ->where('app_id', $appointment)
            ->where('patient_id', $patientId)
            ->where('status', 'Y')
            ->get();

        // Return the data as a JSON response
        return response()->json([
            'toothExaminations' => $toothExaminations,
        ]);
    }

    // public function showCharge($appointment, Request $request)
    // {
    //     // Retrieve patient_id from the query parameters
    //     $patientId = $request->query('patient_id');
    //     // Fetch ToothExamination data with related teeth and treatment details
    //     $toothExaminations = ToothExamination::with([
    //         'teeth:id,teeth_name,teeth_image',
    //         'treatment',
    //     ])
    //         ->where('app_id', $appointment)
    //         ->where('patient_id', $patientId)
    //         ->where('status', 'Y')
    //         ->get();
    //         $treatments = null;
    //     foreach ($toothExaminations as $toothExamination)
    //     {
    //         $treatments[] = $toothExamination->treatment->id;
    //         $treatmentCost = $toothExamination->treatment->treat_cost;
    //         // $toothExamination->treatment->discount_percentage = 0;
    //         $currentDate = date('Y-m-d');
    //         $discount_from = $toothExamination->treatment->discount_from;
    //         $discount_to = $toothExamination->treatment->discount_to;
    //         $discount_percentage = $toothExamination->treatment->discount_percentage;
    //         $discountCost = 0;
    //         if ($discount_from !== null && $discount_to !== null) {
    //             if ($currentDate >= $discount_from && $currentDate <= $discount_to) {
    //                 if ($discount_percentage != null) {
    //                     $discountCost = $treatmentCost *  (1 - $discount_percentage / 100);

    //                 }
    //             }
    //         }
    //         $toothExamination->treatment->discount_cost = $discountCost != 0 ? $discountCost : $treatmentCost;
    //     }
    //    // Fetch ComboOfferTreatment records where treatment_id is in $treatments
    //     $comboOffers = ComboOfferTreatment::whereIn('treatment_id', $treatments)->get();

    //     // Initialize variables to store and validate combo_offer_id
    //     $commonComboOfferId = null;
    //     $valid = true;

    //     // Check if all treatments have the same combo_offer_id
    //     foreach ($comboOffers as $comboOffer) {
    //         if ($commonComboOfferId === null) {
    //             // Initialize with the first combo_offer_id
    //             $commonComboOfferId = $comboOffer->combo_offer_id;
    //         } elseif ($commonComboOfferId !== $comboOffer->combo_offer_id) {
    //             // If combo_offer_id differs, set $valid to false and break the loop
    //             $valid = false;
    //             break;
    //         }
    //     }

    //     // After the loop, if $valid is true, all treatments have the same combo_offer_id
    //     $comboOffersResult = [];
    //     if ($valid && $commonComboOfferId !== null) {
    //         $comboOffersResult = TreatmentComboOffer::where('id', $commonComboOfferId)->get();
    //     }
    //     // Return the data as a JSON response
    //     return response()->json([
    //         'toothExaminations' => $toothExaminations,
    //         'comboOffer' => $comboOffersResult
    //     ]);
    // }

    public function showCharge($appointment, Request $request)
    {
        // Retrieve patient_id from the query parameters
        $patientId = $request->query('patient_id');

        // Fetch ToothExamination data with related teeth and treatment details
        $toothExaminations = ToothExamination::with([
            'teeth:id,teeth_name,teeth_image',
            'treatment',
            'treatmentPlan',
            'toothScore:id,score',
            'disease:id,name',
            'xRayImages:id,tooth_examination_id,xray,status',

        ])
            ->where('app_id', $appointment)
            ->where('patient_id', $patientId)
            ->where('status', 'Y')
            ->get();

        $treatments = [];
        $individualTreatmentAmounts = [];
        $comboOffersResult = [];

        // Process each tooth examination
        foreach ($toothExaminations as $toothExamination) {
            // Collect treatment IDs
            $treatments[] = $toothExamination->treatment->id;

            // Fetch treatment cost and discount details
            $treatmentCost = $toothExamination->treatment->treat_cost;
            $discount_from = $toothExamination->treatment->discount_from;
            $discount_to = $toothExamination->treatment->discount_to;
            $discount_percentage = $toothExamination->treatment->discount_percentage;

            // Calculate discount cost if applicable
            $discountCost = $treatmentCost;
            $currentDate = date('Y-m-d');

            if (
                $discount_from !== null && $discount_to !== null &&
                $currentDate >= $discount_from && $currentDate <= $discount_to &&
                $discount_percentage !== null
            ) {
                $discountCost = $treatmentCost * (1 - $discount_percentage / 100);
            }

            // Store calculated cost back to treatment object
            $toothExamination->treatment->discount_cost = $discountCost;
            // Store calculated cost back to treatment object
            $toothExamination->treatment->discount_cost = $discountCost;

            // Check if there is a combo offer for this treatment
            if ($toothExamination->treatment->comboOffer) {
                $comboOfferId = $toothExamination->treatment->comboOffer->id;
                // Fetch combo offer details if not already fetched
                if (! isset($comboOffersResult[$comboOfferId])) {
                    $comboOffersResult[$comboOfferId] = TreatmentComboOffer::find($comboOfferId);
                }
            } else {
                // If no combo offer, set comboOffersResult to empty array for this treatment
                $comboOffersResult[$toothExamination->treatment->id] = [];
            }

            // Store individual treatment amount
            $individualTreatmentAmounts[$toothExamination->treatment->id] = [
                'treat_name' => $toothExamination->treatment->treat_name,
                'treat_cost' => $discountCost, // Use discounted cost
            ];
        }

        $doctorDiscountApp = Appointment::findOrFail($appointment);
        $doctorDiscount = $doctorDiscountApp->doctor_discount;

        // Return the data as a JSON response
        return response()->json([
            'toothExaminations' => $toothExaminations,
            'individualTreatmentAmounts' => $individualTreatmentAmounts,
            'comboOffers' => $comboOffersResult,
            'doctorDiscount' => $doctorDiscount,
        ]);
    }

    public function storeDetails(TreatmentDetailsRequest $request)
    {
        $appId = Session::get('appId');
        $patientId = Session::get('patientId');
        $consultDoctorName = Session::get('doctorName');
        $consultDoctorId = Session::get('doctorId');
        try {
            DB::beginTransaction();
            $appointmentCreated = false;
            $doctorDiscount = false;
            $prescriptionCreated = false;
            if ($request->input('follow_checkbox')) {

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

                $patientAppointment = Appointment::where('doctor_id', $doctorId)
                    ->where('app_date', $appDate)
                    ->where('patient_id', $patientId)
                    ->where('app_branch', $clinicBranchId)
                    ->exists();
                if ($patientAppointment) {
                    // $existingAppointment = $commonService->checkexisting($doctorId, $appDate, $appTime, $clinicBranchId);
                    // if ($existingAppointment) {
                    //     return response()->json(['error' => 'An appointment already exists for the given date, time, and doctor.'], 422);
                    // }
                    // Additional check to ensure no time conflict
                    $appointmentWithSameTime = Appointment::where('doctor_id', $doctorId)
                        ->where('app_date', $appDate)
                        ->where('app_branch', $clinicBranchId)
                        ->where('patient_id', $patientId)
                        ->first();

                    if ($appointmentWithSameTime && $appointmentWithSameTime->app_time != $appTime) {
                        return response()->json([
                            'error' => 'An appointment already exists for you at '.$appointmentWithSameTime->app_time.' on this date with the same doctor.',
                        ], 422);
                    }

                } else {

                    $appParentId = $appId;
                    $referDoctor = ($consultDoctorId != $doctorId) ? $consultDoctorName : null;

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
                    $appointment->referred_doctor = $referDoctor;
                    $appointment->remarks = $request->input('remarks_followup');
                    $appointment->app_status = AppointmentStatus::SCHEDULED;
                    $appointment->app_parent_id = $appParentId;
                    $appointment->created_by = auth()->user()->id;
                    $appointment->updated_by = auth()->user()->id;
                    $existingFollowupAppId = $request->input('followupAppId');
                    if ($existingFollowupAppId != '') {
                        $previousFollowup = Appointment::findOrFail($existingFollowupAppId);
                        if ($previousFollowup->patient_id == $patientId && $previousFollowup->app_parent_id == $appId) {
                            $previousFollowup->delete();
                        }
                    }
                    if ($appointment->save()) {
                        //DB::commit();

                        //return redirect()->back()->with('success', 'Appointment added successfully');
                        $appointmentCreated = true;
                    } else {
                        DB::rollBack();

                        return redirect()->back()->with('error', 'Failed to create appointment');
                    }
                }

            }
            // Handle prescription data
            if ($request->input('presc_checkbox')) {

                $prescriptions = $request->input('prescriptions', []);
                // Find and update the existing Prescription
                $oldPrescriptionData = Prescription::where('app_id', $appId)
                    ->where('patient_id', $patientId)
                    ->get();
                foreach ($oldPrescriptionData as $prs) {
                    $prs->status = 'N'; // Set the new status
                    $prs->save(); // Save the updated record
                }
                foreach ($prescriptions as $prescription) {
                    // Handle new medicine
                    $medicineId = $prescription['medicine_id'];

                    // Check if the medicine_id is numeric
                    if (is_numeric($medicineId)) {
                        $existingMedicine = Medicine::find($medicineId);
                        if ($existingMedicine) {
                            // Medicine exists, use its ID
                            $medicineId = $existingMedicine->id;
                        } else {
                            // Create new medicine record
                            $medicine = Medicine::create([
                                'med_name' => $prescription['medicine_id'],
                                'stock_status' => 'Out of Stock',
                                'status' => 'Y',
                                'created_by' => auth()->user()->id,
                                'updated_by' => auth()->user()->id,
                            ]);
                            $medicineId = $medicine->id;

                        }
                    } else {
                        // Create new medicine record
                        $medicine = Medicine::create([
                            'med_name' => $prescription['medicine_id'],
                            'stock_status' => 'Out of Stock',
                            'status' => 'Y',
                            'created_by' => auth()->user()->id,
                            'updated_by' => auth()->user()->id,
                        ]);
                        $medicineId = $medicine->id;
                    }

                    $prescriptionData = new Prescription();
                    $prescriptionData->patient_id = $patientId;
                    $prescriptionData->app_id = $appId;
                    $prescriptionData->medicine_id = $medicineId;
                    $prescriptionData->dose = $prescription['dose'];
                    $prescriptionData->dose_unit = $prescription['dose_unit'];
                    $prescriptionData->dosage_id = $prescription['dosage_id'];
                    $prescriptionData->duration = $prescription['duration'];
                    $prescriptionData->advice = $prescription['advice'];
                    $prescriptionData->route_id = $prescription['route_id'];
                    $prescriptionData->remark = $prescription['remark'];
                    $prescriptionData->prescribed_by = auth()->user()->id;
                    $prescriptionData->created_by = auth()->user()->id;
                    $prescriptionData->updated_by = auth()->user()->id;
                    if ($prescriptionData->save()) {

                        $prescriptionCreated = true;
                    } else {
                        DB::rollBack();

                        return redirect()->back()->with('error', 'Failed to create Prescription');
                    }

                }

            }

            if (auth()->user()->hasRole('Admin')) {
                $chargeAppointment = Appointment::findOrFail($appId);
                $chargeAppointment->doctor_discount = $request->input('discount1');
                if ($chargeAppointment->save()) {
                    $doctorDiscount = true;
                } else {
                    DB::rollBack();

                    return redirect()->back()->with('error', 'Failed to add doctor discount');
                }
            }

            $oldAppointment = Appointment::findOrFail($appId);
            $oldAppStatus = $oldAppointment->app_status;
            $oldAppointment->app_status = AppointmentStatus::COMPLETED; // Update to 'Completed'
            $oldAppointment->consult_end_time = Carbon::now('Asia/Kolkata');
            $oldAppointment->save();

            if ($oldAppStatus != AppointmentStatus::COMPLETED) {
                // Increment the visit count of the patient
                $patientProfile = PatientProfile::where('patient_id', $patientId)->firstOrFail();
                $patientProfile->visit_count = $patientProfile->visit_count + 1; // Increment visit count
                $patientProfile->save();
                $oldAppointment->consult_end_time = Carbon::now('Asia/Kolkata');
                $oldAppointment->save();
            }

            DB::commit();
            //Session::flush();
            Session::forget(['appId', 'patientId', 'doctorName', 'doctorId']);

            return redirect()->back()->with('success', 'Appointment and/or prescription data added successfully');

        } catch (\Exception $e) {
            DB::rollback();

            //Log::info('$error: '.$e->getMessage());
            //return redirect()->back()->with('error', 'Failed to create appointment: '.$e->getMessage());
            return response()->json(['error' => 'Failed to add treatment details: '.$e->getMessage()], 422);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $toothExam = ToothExamination::findOrFail($id);
        $toothExam->delete();

        return response()->json(['success', 'Teeth exam details deleted successfully.'], 201);
    }
}
