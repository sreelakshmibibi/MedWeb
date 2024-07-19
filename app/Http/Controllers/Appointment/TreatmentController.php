<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\Disease;
use App\Models\PatientProfile;
use App\Models\SurfaceCondition;
use App\Models\Teeth;
use App\Models\ToothExamination;
use App\Models\ToothScore;
use App\Models\TreatmentStatus;
use App\Models\TreatmentType;
use App\Services\AnatomyService;
use App\Services\AppointmentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id, Request $request)
    {
        $appointment = Appointment::with(['patient', 'doctor', 'branch'])->find($id);
        abort_if(! $appointment, 404);
        // Format clinic address

        //$patientProfile = PatientProfile::with(['lastAppointment'])->find($appointment->patient->id);
        $patientProfile = PatientProfile::with(['lastAppointment.doctor', 'lastAppointment.branch'])->find($appointment->patient->id);
        $appointmentService = new AppointmentService();
        $latestAppointment = $appointmentService->getLatestAppointment($id, $appointment->app_date, $appointment->patient->patient_id);
        $previousAppointments = $appointmentService->getPreviousAppointments($id, $appointment->app_date, $appointment->patient->patient_id);

        //$patient = PatientProfile::find($id);
        abort_if(! $patientProfile, 404);
        $appointment = $patientProfile->lastAppointment;

        $tooth = Teeth::all();
        $toothScores = ToothScore::all();
        $surfaceConditions = SurfaceCondition::all();
        $treatmentStatus = TreatmentStatus::all();
        $treatments = TreatmentType::where('status', 'Y')->get();
        $diseases = Disease::where('status', 'Y')->get();
        Session::put('appId', $id);
        Session::put('patientId', $appointment->patient->id);
        // Log::info('$appointment: '.$previousAppointments);
        // if ($request->ajax()) {

        //     return DataTables::of($appointment)
        //         ->addIndexColumn()
        //         ->addColumn('doctor', function ($row) {
        //             return str_replace('<br>', ' ', $row->doctor->name);
        //         })
        //         ->addColumn('branch', function ($row) {
        //             if (! $row->branch) {
        //                 return '';
        //             }
        //             $address = implode(', ', explode('<br>', $row->branch->clinic_address));

        //             return implode(', ', [$address, $row->branch->city->city, $row->branch->state->state]);
        //         })
        //         ->addColumn('status', function ($row) {
        //             $statusMap = [
        //                 AppointmentStatus::SCHEDULED => 'badge-success-light',
        //                 AppointmentStatus::WAITING => 'badge-success-light',
        //                 AppointmentStatus::UNAVAILABLE => 'badge-danger-light',
        //                 AppointmentStatus::CANCELLED => 'badge-danger-light',
        //                 AppointmentStatus::COMPLETED => 'badge-success-light',
        //                 AppointmentStatus::BILLING => 'badge-success-light',
        //                 AppointmentStatus::PROCEDURE => 'badge-success-light',
        //                 AppointmentStatus::MISSED => 'badge-danger-light',
        //                 AppointmentStatus::RESCHEDULED => 'badge-success-light',
        //             ];
        //             $btnClass = isset($statusMap[$row->app_status]) ? $statusMap[$row->app_status] : '';

        //             return "<span class='btn-sm badge {$btnClass}'>".AppointmentStatus::statusToWords($row->app_status).'</span>';
        //         })
        //         ->rawColumns(['status'])
        //         ->make(true);
        // }

        // return view('appointment.treatment');
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

                    return "<span class='btn-sm badge {$btnClass}'>".AppointmentStatus::statusToWords($row->app_status).'</span>';
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
                            $teethImage = $examination->teeth->teeth_image;

                            return '<div>'.$teethName.'<br><img src="'.asset($teethImage).'" alt="'.$teethName.'" width="50" height="50"></div>';
                        }

                        return '';
                    })->implode('<br>');

                    return $teethData;
                })
                ->addColumn('problem', function ($row) {
                    return $row->toothExamination ? $row->toothExamination->pluck('chief_complaint')->implode(', ') : '';
                })
                ->addColumn('treatment', function ($row) {
                    return $row->toothExamination ? $row->toothExamination->pluck('treatment')->implode(', ') : '';
                })

                ->rawColumns(['status', 'teeth'])
                ->make(true);
        }

        return view('appointment.treatment', compact('patientProfile', 'appointment', 'tooth', 'latestAppointment', 'toothScores', 'surfaceConditions', 'treatmentStatus', 'treatments', 'diseases', 'previousAppointments'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // $toothExamination = new ToothExamination();
            $toothId = $request->tooth_id;
            $occulusal_condn = $request->occulusal_condn != null ? 1 : 0;
            $palatal_condn = $request->palatal_condn != null ? 1 : 0;
            $mesial_condn = $request->mesial_condn != null ? 1 : 0;
            $buccal_condn = $request->buccal_condn != null ? 1 : 0;
            $distal_condn = $request->distal_condn != null ? 1 : 0;
           
            $toothExamination = ToothExamination::create($request->only([
                'app_id', 'patient_id', 'tooth_id', 'tooth_score_id', 'chief_complaint', 'disease_id', 'hpi', 'dental_examination', 'diagnosis', 'xray', 'treatment_id', 'remarks', 'palatal_condn', 'mesial_condn', 'distal_condn', 'buccal_condn', 'occulusal_condn', 'labial_condn', 'lingual_condn', 'treatment_status',
            ]));
            $anatomyService = new AnatomyService();
            $anatomyImage = $anatomyService->getAnatomyImage($toothId, $occulusal_condn, $palatal_condn, $mesial_condn, $distal_condn, $buccal_condn);
            $toothExaminationEdit = ToothExamination::find($toothExamination->id);
            $toothExaminationEdit->anatomy_image = $anatomyImage;
            $toothExaminationEdit->save();
            return response()->json(['success' => 'Tooth examination for teeth no '. $toothId .' added']);

        } catch (Exception $ex) {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            exit;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
