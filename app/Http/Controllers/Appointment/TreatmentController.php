<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\TreatmentDetailsRequest;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\AppointmentType;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\ComboOfferTreatment;
use App\Models\Disease;
use App\Models\Dosage;
use App\Models\Medicine;
use App\Models\MedicineRoute;
use App\Models\PatientProfile;
use App\Models\PatientTreatmentBilling;
use App\Models\Prescription;
use App\Models\Shade;
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
use App\Models\FacePart;
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
        abort_if(!$appointment, 404);

        if ($appointment->app_status != AppointmentStatus::COMPLETED) {

            $appointment->consult_start_time = Carbon::now('Asia/Kolkata'); // Get the current time
            $appointment->save();

        }
        $appAction = 'Treatment';
        $bills = PatientTreatmentBilling::where('appointment_id', $appointment->id)->where('status', 'Y')->get();
        if (($appointment->app_date < date('Y-m-d') || $bills->isNotEmpty()) && ($appointment->app_status == AppointmentStatus::COMPLETED && $appointment->app_status != AppointmentStatus::MISSED)) {
            $appAction = 'Show';
        }
        $doctorDiscount = $appointment->doctor_discount;
        //$patientProfile = PatientProfile::with(['lastAppointment'])->find($appointment->patient->id);
        $patientProfile = PatientProfile::with(['lastAppointment.doctor', 'lastAppointment.branch'])->find($appointment->patient->id);
        $appointmentService = new AppointmentService();
        $latestAppointment = $appointmentService->getLatestAppointment($id, $appointment->app_date, $appointment->patient->patient_id);
        $previousAppointments = $appointmentService->getPreviousAppointments($id, $appointment->app_date, $appointment->patient->patient_id);

        //$patient = PatientProfile::find($id);
        abort_if(!$patientProfile, 404);
        $appointment = $patientProfile->lastAppointment;
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->get();
        $appointmentBranchId = $appointment->app_branch;

        // Get the current day name
        $currentDayName = Carbon::now()->englishDayOfWeek;

        // Initialize DoctorAvaialbilityService and fetch working doctors
        $doctorAvailabilityService = new DoctorAvaialbilityService();
        $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors($appointmentBranchId, $currentDayName, date('Y-m-d'), date('H:i'));

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
        $faceparts = FacePart::all();
        $diseases = Disease::where('status', 'Y')->get();
        $patientName = str_replace('<br>', ' ', $appointment->patient->first_name) . ' ' . $appointment->patient->last_name;
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
        $shades = Shade::all();
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
                        } else if($examination->face_part != null) {
                            return $examination->face_part;
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

        return view('appointment.treatment', compact('patientProfile', 'appointment', 'tooth', 'latestAppointment', 'toothScores', 'surfaceConditions', 'treatmentStatus', 'treatments', 'diseases', 'previousAppointments', 'clinicBranches', 'appointmentTypes', 'workingDoctors', 'medicines', 'dosages', 'patientPrescriptions', 'appAction', 'doctorDiscount', 'latestFollowup', 'plans', 'toothIds', 'medicineRoutes', 'shades', 'faceparts'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function fetchExistingExamination($toothId, $appId, $patientId)
    {
        $toothExamination = [];
        $appointment = Appointment::find($appId);
        $parentAppId = $appointment->app_parent_id;
        if (in_array($toothId, [1, 2, 3, 4, 5])) {
            $toothExamination = ToothExamination::where('row_id', $toothId)
                ->where('patient_id', $patientId)
                ->where('app_id', $appId)
                ->where('status', 'Y')
                ->first();
            if ($parentAppId != null && $toothExamination == null) {
                $toothExamination = ToothExamination::where('row_id', $toothId)
                    ->where('patient_id', $patientId)
                    ->where('app_id', $parentAppId)
                    ->where('status', 'Y')
                    ->first();
            }
        } else {
            $toothExamination = ToothExamination::where('tooth_id', $toothId)
                ->where('patient_id', $patientId)
                ->where('app_id', $appId)
                ->where('status', 'Y')
                ->first();
            if ($parentAppId != null && $toothExamination == null) {
                $toothExamination = ToothExamination::where('tooth_id', $toothId)
                    ->where('patient_id', $patientId)
                    ->where('app_id', $parentAppId)
                    ->where('status', 'Y')
                    ->first();
            }

        }
        $xrays = null;
        $diseaseName = null;
        if ($toothExamination) {
            if ($toothExamination->xray == 1) {
                $xrays = XRayImage::where('tooth_examination_id', $toothExamination->id)->get();
            }
            $diseaseName = Disease::where('id', $toothExamination->disease_id)
                ->first();
        }

        return response()->json(['examination' => $toothExamination, 'xrays' => $xrays, 'diseaseName' => $diseaseName]);
    }

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $checkExists = [];
            if (in_array($request->row_id, [1, 2, 3, 4, 5])) {

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

            if (!$checkExists->isEmpty()) {
                foreach ($checkExists as $check) {
                    $check->status = 'N';
                    $check->save();
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
            $diseaseId = $request->disease_id;

            if (is_numeric($diseaseId)) {
                $existingDisease = Disease::find($diseaseId);
                if ($existingDisease) {
                    $diseaseId = $existingDisease->id;
                } else {
                    // Create new Disease record
                    $disease = Disease::create([
                        'name' => ucwords(strtolower($request->disease_id)),
                        'status' => 'Y',
                        'created_by' => auth()->user()->id,
                        'updated_by' => auth()->user()->id,
                    ]);
                    $diseaseId = $disease->id;

                }
            } else {
                // Create new Disease record
                $disease = Disease::create([
                    'name' => ucwords(strtolower($request->disease_id)),
                    'status' => 'Y',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ]);
                $diseaseId = $disease->id;
            }
            $request->merge(['disease_id' => $diseaseId]);
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
                'is_xray_billable',
                'treatment_plan_id',
                'shade_id',
                'upper_shade',
                'middle_shade',
                'lower_shade',
                'metal_trial',
                'bisq_trial',
                'finish',
                'instructions',
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
            if ((TreatmentType::find($request->treatment_id))->treat_name == "Tooth Extraction") {
                $anatomyImage = "images/tooth/noteeth.svg";
            }
            $toothExaminationEdit = ToothExamination::find($toothExamination->id);
            if ($request->hasFile('xray')) {
                $toothExaminationEdit->xray = 1;
                foreach ($request->file('xray') as $file) {
                    $xrayPath = $file->store('x-rays/' . $request->patient_id . '/' . $request->tooth_id, 'public');
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
                    if (!$xraysExists->isEmpty()) {
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
                $successMessage = $request->tooth_id != null ? 'Tooth examination for teeth no ' . $toothId . ' added' : 'Tooth examination added';
                return response()->json(['success' => $successMessage]);
            } else {
                DB::rollback();

                return response()->json(['error' => 'Failed adding Tooth examination for teeth']);
            }

        } catch (Exception $ex) {
            DB::rollBack();
            echo '<pre>';
            print_r($ex->getMessage());
            echo '</pre>';

            return response()->json(['error' => 'Failed adding Tooth examination for teeth no ' . $toothId]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($appointment, Request $request)
    {
        // Retrieve patient_id from the query parameters
        $patientId = $request->query('patient_id');
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

    public function showCharge($appointment, Request $request)
    {
        // Retrieve patient_id from the query parameters
        $patientId = $request->query('patient_id');
        $appointmentPrevious = Appointment::find($appointment);

        // Fetch previous tooth examinations if a parent appointment exists
        $toothExaminationsPrevious = null;
        if ($appointmentPrevious->app_parent_id != null) {
            $toothExaminationsPrevious = ToothExamination::with([
                'teeth:id,teeth_name,teeth_image',
                'treatment',
                'treatmentPlan',
                'toothScore:id,score',
                'disease:id,name',
                'xRayImages:id,tooth_examination_id,xray,status',
            ])
                ->where('app_id', $appointmentPrevious->app_parent_id)
                ->where('patient_id', $patientId)
                ->where('status', 'Y')
                ->get();
        }

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
        $individualTreatmentPlanAmounts = [];
        $comboOffersResult = [];

        $previousTreatments = [];
        if ($toothExaminationsPrevious) {
            foreach ($toothExaminationsPrevious as $prevExam) {
                $treatmentId = $prevExam->treatment_id;
                $teethId = $prevExam->teeth_id;

                // Mark treatment as follow-up
                if ($prevExam->treatment_status === TreatmentStatus::FOLLOWUP) {
                    $previousTreatments[$teethId][$treatmentId] = true;
                }

            }
        }
        $xrays = 0;
        $i = 0;
        // Process each tooth examination
        foreach ($toothExaminations as $toothExamination) {

            // Collect treatment IDs
            $treatments[] = $toothExamination->treatment->id;
            $treatmentPlans[] = $toothExamination->treatment_plan_id;
            // Fetch treatment cost and discount details
            $treatmentCost = $toothExamination->treatment->treat_cost;
            $discount_from = $toothExamination->treatment->discount_from;
            $discount_to = $toothExamination->treatment->discount_to;
            $discount_percentage = $toothExamination->treatment->discount_percentage;

            // Calculate discount cost if applicable
            $discountCost = $treatmentCost;
            $currentDate = date('Y-m-d');
            if ($toothExamination->is_xray_billable == 'Y') {
                $xrays++;
            }

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
                if (!isset($comboOffersResult[$comboOfferId])) {
                    $comboOffersResult[$comboOfferId] = TreatmentComboOffer::find($comboOfferId);
                }
            } else {
                // If no combo offer, set comboOffersResult to empty array for this treatment
                $comboOffersResult[$toothExamination->treatment->id] = [];
            }
            // Skip if treatment is marked as follow-up in previous examinations
            if (
                isset($previousTreatments[$toothExamination->teeth_id]) &&
                isset($previousTreatments[$toothExamination->teeth_id][$toothExamination->treatment_id])
            ) {
                continue;
            }

            // Store individual treatment amount
            $individualTreatmentAmounts[$toothExamination->treatment->id] = [
                'treat_name' => $toothExamination->treatment->treat_name,
                'treat_cost' => $discountCost, // Use discounted cost
                'treat_id' => $toothExamination->treatment_id,
                'cost' => $toothExamination->treatment->treat_cost,
                'discount_percentage' => $toothExamination->treatment->discount_percentage,


            ];

            if ($toothExamination->treatment_plan_id) {
                $i++;
                $individualTreatmentPlanAmounts[$toothExamination->treatmentPlan->plan . $i] = [
                    'treat_name' => $toothExamination->treatmentPlan->plan,
                    'treat_cost' => $toothExamination->treatmentPlan->cost, // Use discounted cost
                    'treat_id' => $toothExamination->treatment_plan_id,
                    'cost' => $toothExamination->treatmentPlan->cost,
                    'discount_percentage' => 0,

                ];

            }
        }
        // if (!empty($treatmentPlans)) {
        //     foreach($treatmentPlans as $key=> $treatment_plan) {
        //         $treatmentPlan = TreatmentPlan::find($treatment_plan);
        //         $individualTreatmentPlanAmounts[$treatmentPlan->plan.$key] = [
        //             'treat_name' => $treatmentPlan->plan,
        //             'treat_cost' => $treatmentPlan->cost, // Use discounted cost
        //             'treat_id' => $treatment_plan,
        //             'cost' => $treatmentPlan->cost,
        //             'discount_percentage' => 0,   
        //         ];

        //     }
        // }


        // Filter toothExaminations to only include those with treatments in individualTreatmentAmounts
        $toothExaminations = $toothExaminations->filter(function ($toothExamination) use ($individualTreatmentAmounts) {
            return isset($individualTreatmentAmounts[$toothExamination->treatment_id]);
        });
        $doctorDiscountApp = Appointment::findOrFail($appointment);
        $doctorDiscount = $doctorDiscountApp->doctor_discount;
        $xraysArray = [];
        if ($xrays > 0) {
            $xrayAmount = (ClinicBasicDetail::first())->xray_amount;
            $xraysCharge = [
                'treat_name' => "X-ray ( " . $xrays . " * " . $xrayAmount . " ).",
                'treat_cost' => $xrayAmount * $xrays,
                'treat_id' => "X-ray",
                'cost' => $xrayAmount * $xrays,
                'discount_percentage' => 0,
            ];
            $xraysArray[] = $xraysCharge; // Add this to your existing xrays array
        }

        // Assuming you have an array for xrays in your response

        // Return the data as a JSON response
        return response()->json([
            'toothExaminations' => $toothExaminations,
            'individualTreatmentAmounts' => $individualTreatmentAmounts,
            'individualTreatmentPlanAmounts' => $individualTreatmentPlanAmounts,
            'comboOffers' => $comboOffersResult,
            'doctorDiscount' => $doctorDiscount,
            'xrays' => $xraysArray,
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
                    // Additional check to ensure no time conflict
                    $appointmentWithSameTime = Appointment::where('doctor_id', $doctorId)
                        ->where('app_date', $appDate)
                        ->where('app_branch', $clinicBranchId)
                        ->where('patient_id', $patientId)
                        ->first();

                    if ($appointmentWithSameTime && $appointmentWithSameTime->app_time != $appTime) {
                        return response()->json([
                            'error' => 'An appointment already exists for you at ' . $appointmentWithSameTime->app_time . ' on this date with the same doctor.',
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
            return response()->json(['error' => 'Failed to add treatment details: ' . $e->getMessage()], 422);
        }

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
