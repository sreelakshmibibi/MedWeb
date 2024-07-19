<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\ClinicBranch;
use App\Models\Country;
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
use App\Services\CommonService;
use App\Services\DoctorAvaialbilityService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
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
        abort_if(!$appointment, 404);
        // Format clinic address
       
        $patientProfile = PatientProfile::with(['lastAppointment'])->find($appointment->patient->id);
        $appointmentService = new AppointmentService();
        $latestAppointment = $appointmentService->getLatestAppointment($id,$appointment->app_date, $appointment->patient->id);
        //$patient = PatientProfile::find($id);
        abort_if(!$patientProfile, 404);
        $appointment = $patientProfile->lastAppointment;
        
        $tooth = Teeth::all();
        $toothScores = ToothScore::all();
        $surfaceConditions = SurfaceCondition::all();
        $treatmentStatus = TreatmentStatus::all();
        $treatments = TreatmentType::where('status', 'Y')->get();
        $diseases = Disease::where('status', 'Y')->get();
        Session::put('appId', $id);
        Session::put('patientId', $appointment->patient->id);
        if ($request->ajax()) {

            return DataTables::of($appointment)
                ->addIndexColumn()
                ->addColumn('doctor', function ($row) {
                    return str_replace("<br>", " ", $row->doctor->name);
                })
                ->addColumn('branch', function ($row) {
                    if (!$row->branch) {
                        return '';
                    }
                    $address = implode(", ", explode("<br>", $row->branch->clinic_address));
                    return implode(", ", [$address, $row->branch->city->city, $row->branch->state->state]);
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
                ->rawColumns(['status'])
                ->make(true);
        }

        // return view('appointment.treatment');
        return view('appointment.treatment', compact('patientProfile', 'appointment', 'tooth', 'latestAppointment', 'toothScores', 'surfaceConditions', 'treatmentStatus', 'treatments', 'diseases'));
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
