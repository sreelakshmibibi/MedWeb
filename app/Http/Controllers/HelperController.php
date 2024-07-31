<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\City;
use App\Models\ClinicBasicDetail;
use App\Models\State;
use App\Models\ToothExamination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class HelperController extends Controller
{
    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)
            ->orderBy('state', 'ASC')
            ->pluck('state', 'id')
            ->toArray();

        return $states;

    }

    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)
            ->orderBy('city', 'ASC')
            ->pluck('city', 'id')
            ->toArray();

        return $cities;
    }

    public function getSessionData()
    {
        // Retrieve session data
        $response = [
            'appId' => Session::get('appId'),
            'patientId' => Session::get('patientId'),
            'patientName' => Session::get('patientName'),
            'loginedUserAdmin' => Auth::user()->is_admin,
        ];

        return response()->json($response);
    }

    public function generatePdf(Request $request)
    {
        $appointmentId = $request->input('pdf_appointment_id');
        $patientId = $request->input('pdf_patient_id');
        $pdfType = $request->input('pdf_type');
        $toothIds = $request->input('tooth_id');

        // Initialize variables
        $view = '';
        $data = [];
        $clinicDetails = ClinicBasicDetail::first();
        // Fetch data based on the PDF type
        if ($pdfType === 'appointment') {
            // Fetch appointment details
            $appointment = Appointment::with([
                'doctor:id,name',
                'branch:id,clinic_address,city_id,state_id,country_id,pincode,clinic_phone,clinic_email', // Load branch details
                'branch.state:id,state',
                'branch.city:id,city',
                'branch.country:id,country',
                'toothExamination' => function ($query) {
                    $query->where('status', 'Y')
                        ->with([
                            'teeth:id,teeth_name,teeth_image',
                            'treatment:id,treat_name',
                            'treatmentStatus:id,status',
                            'disease:id,name',
                            'lingualCondition:id,condition',
                            'labialCondition:id,condition',
                            'occulusalCondition:id,condition',
                            'distalCondition:id,condition',
                            'mesialCondition:id,condition',
                            'palatalCondition:id,condition',
                            'buccalCondition:id,condition',
                        ]);
                },
                'patient:id,first_name,last_name,gender,aadhaar_no,patient_id,date_of_birth,phone,address1,address2,pincode,city_id,state_id,country_id', // Include patient details
                'patient.country:id,country',
                'patient.state:id,state',
                'patient.city:id,city',
            ])
                ->where('id', $appointmentId)
                ->first();
            $toothExamDetails = $appointment->toothExamination;
            //Log::info('Appointments: '.$toothExamDetails);
            if (! $appointment) {
                return response()->json(['message' => 'Appointment not found'], 404);
            }

            $view = 'pdf.treatment_pdf';
            $data = [
                'appointment' => $appointment,
                'toothExamDetails' => $toothExamDetails,
                'clinicDetails' => $clinicDetails,
            ];

            $fileName = 'appointment_'.$appointmentId.'.pdf';

        } elseif ($pdfType === 'tooth') {
            // Fetch specific tooth details
            $tooth = Appointment::with([
                'doctor:id,name',
                'branch:id,clinic_address,city_id,state_id,country_id,pincode,clinic_phone,clinic_email', // Load branch details
                'branch.state:id,state',
                'branch.city:id,city',
                'branch.country:id,country',
                'toothExamination' => function ($query) use ($toothIds) {
                    $query->where('status', 'Y') // Filter ToothExamination records by status
                        ->whereIn('tooth_id', $toothIds) // Filter by specific tooth IDs
                        ->with([
                            'teeth:id,teeth_name,teeth_image',
                            'treatment:id,treat_name',
                            'treatmentStatus:id,status',
                            'disease:id,name',
                            'lingualCondition:id,condition',
                            'labialCondition:id,condition',
                            'occulusalCondition:id,condition',
                            'distalCondition:id,condition',
                            'mesialCondition:id,condition',
                            'palatalCondition:id,condition',
                            'buccalCondition:id,condition',
                        ]);
                },
                'patient:id,first_name,last_name,gender,aadhaar_no,patient_id,date_of_birth,phone,address1,address2,pincode,city_id,state_id,country_id', // Include patient details
                'patient.country:id,country',
                'patient.state:id,state',
                'patient.city:id,city',
            ])
                ->where('id', $appointmentId)
                ->first();
            if (! $tooth) {
                return response()->json(['message' => 'Tooth examination not found'], 404);
            }

            $view = 'pdf.treatment_pdf';
            $toothExamDetails = $tooth->toothExamination;
            $data = [
                'appointment' => $tooth,
                'toothExamDetails' => $toothExamDetails,
                'clinicDetails' => $clinicDetails,
            ];

            $fileName = 'tooth_'.implode('_', $toothIds).'.pdf';
        } else {
            return response()->json(['message' => 'Invalid PDF type'], 400);
        }

        // Generate the PDF
        $pdf = Pdf::loadView($view, $data);

        // Download the PDF
        return $pdf->download($fileName);
    }

    public function fetchTeethDetails($id)
    {
        $teethDetails = ToothExamination::where('patient_id', $id)
            ->with('teeth') // Ensure `teeth` relationship is loaded
            ->get()
            ->map(function ($examination) {
                return [
                    'teeth_id' => $examination->teeth->id, // Adjust if necessary
                    'teeth_name' => $examination->teeth->teeth_name,
                ];
            });

        // Return the results as JSON
        return response()->json($teethDetails);

    }
}
