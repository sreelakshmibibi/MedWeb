<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\City;
use App\Models\ClinicBasicDetail;
use App\Models\PatientProfile;
use App\Models\Prescription;
use App\Models\State;
use App\Models\TeethRow;
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

    public function generateTreatmentPdf(Request $request)
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
            if (!$appointment) {
                return response()->json(['message' => 'Appointment not found'], 404);
            }

            $view = 'pdf.treatment_pdf';
            $data = [
                'appointment' => $appointment,
                'toothExamDetails' => $toothExamDetails,
                'clinicDetails' => $clinicDetails,
            ];

            $fileName = 'appointment_' . $appointmentId . '.pdf';

        } elseif ($pdfType === 'tooth') {
            // Extract tooth IDs and row IDs from the $toothIds
            $toothIds = collect($toothIds); // Convert to a collection for easier manipulation

            // Separate Tooth IDs and Row IDs
            $toothIdValues = $toothIds->filter(function ($id) {
                return strpos($id, 'Teeth:') === 0; // Check if it starts with 'Teeth:'
            })->map(function ($id) {
                return str_replace('Teeth:', '', $id); // Extract the ID value
            });

            $rowIdValues = $toothIds->filter(function ($id) {
                return strpos($id, 'Row:') === 0; // Check if it starts with 'Row:'
            })->map(function ($id) {
                return str_replace('Row:', '', $id); // Extract the ID value
            });
            // Fetch specific tooth details
            $tooth = Appointment::with([
                'doctor:id,name',
                'branch:id,clinic_address,city_id,state_id,country_id,pincode,clinic_phone,clinic_email', // Load branch details
                'branch.state:id,state',
                'branch.city:id,city',
                'branch.country:id,country',
                'toothExamination' => function ($query) use ($toothIdValues, $rowIdValues, $appointmentId) {
                    $query->where('status', 'Y')
                        ->where('app_id', $appointmentId)
                        ->where(function ($query) use ($toothIdValues, $rowIdValues) {
                            if (!empty($toothIdValues)) {
                                $query->whereIn('tooth_id', $toothIdValues);
                            }

                            if (!empty($rowIdValues)) {
                                $query->orWhereIn('row_id', $rowIdValues);
                            }
                        })
                        ->whereNull('deleted_at')
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
            ])
                ->where('id', $appointmentId)
                ->first();

            if (!$tooth) {
                return response()->json(['message' => 'Tooth examination not found'], 404);
            }

            $view = 'pdf.treatment_pdf';
            $toothExamDetails = $tooth->toothExamination;
            $data = [
                'appointment' => $tooth,
                'toothExamDetails' => $toothExamDetails,
                'clinicDetails' => $clinicDetails,
            ];

            $fileName = 'tooth_' . $toothIds->map(function ($id) {
                return str_replace(['Teeth:', 'Row:'], '', $id); // Clean IDs for file name
            })->implode('_') . '.pdf';
            $fileName = 'tooth_' . $toothIds->map(function ($id) {
                return str_replace(['Teeth:', 'Row:'], '', $id); // Clean IDs for file name
            })->implode('_') . '.pdf';
        } else {
            return response()->json(['message' => 'Invalid PDF type'], 400);
        }

        // Generate the PDF
        $pdf = Pdf::loadView($view, $data);

        // Download the PDF
        return $pdf->download($fileName);
    }

    public function fetchTeethDetails($patientId, $appId)
    {

        $teethDetails = ToothExamination::where('patient_id', $patientId)
            ->where('app_id', $appId)
            ->where('status', 'Y')
            ->with('teeth') // Ensure `teeth` relationship is loaded
            ->get()
            ->map(function ($examination) {
                if ($examination->tooth_id) {
                    // If `tooth_id` is present, use the related teeth name
                    return [
                        'teeth_id' => 'Teeth:' . $examination->tooth_id,
                        'teeth_name' => $examination->teeth ? $examination->teeth->teeth_name : 'Unknown Tooth',
                    ];
                } elseif ($examination->row_id) {
                    // If `row_id` is present, use it to get the description
                    switch ($examination->row_id) {
                        case TeethRow::Row1:
                            $teethName = TeethRow::Row_1_Desc;
                            break;
                        case TeethRow::Row2:
                            $teethName = TeethRow::Row_2_Desc;
                            break;
                        case TeethRow::Row3:
                            $teethName = TeethRow::Row_3_Desc;
                            break;
                        case TeethRow::Row4:
                            $teethName = TeethRow::Row_4_Desc;
                            break;
                        default:
                            $teethName = 'Unknown Row';
                            break;
                    }

                    return [
                        'teeth_id' => 'Row:' . $examination->row_id,
                        'teeth_name' => 'Row : ' . $teethName,
                    ];
                }

                if ($examination->tooth_id) {
                    // If `tooth_id` is present, use the related teeth name
                    return [
                        'teeth_id' => 'Teeth:' . $examination->tooth_id,
                        'teeth_name' => $examination->teeth ? $examination->teeth->teeth_name : 'Unknown Tooth',
                    ];
                } elseif ($examination->row_id) {
                    // If `row_id` is present, use it to get the description
                    switch ($examination->row_id) {
                        case TeethRow::Row1:
                            $teethName = TeethRow::Row_1_Desc;
                            break;
                        case TeethRow::Row2:
                            $teethName = TeethRow::Row_2_Desc;
                            break;
                        case TeethRow::Row3:
                            $teethName = TeethRow::Row_3_Desc;
                            break;
                        case TeethRow::Row4:
                            $teethName = TeethRow::Row_4_Desc;
                            break;
                        default:
                            $teethName = 'Unknown Row';
                            break;
                    }

                    return [
                        'teeth_id' => 'Row:' . $examination->row_id,
                        'teeth_name' => 'Row : ' . $teethName,
                    ];
                }

            });

        return response()->json($teethDetails);

    }

    public function generatePrescriptionPdf(Request $request)
    {
        $appId = $request->input('app_id');
        $patientId = $request->input('patient_id');

        $clinicDetails = ClinicBasicDetail::first();
        $prescriptions = Prescription::with([
            'medicine',
            'dosage',
            'prescribedBy:id,name',
            'route:id,route_name',
        ])
            ->where('patient_id', $patientId)
            ->where('app_id', $appId)
            ->where('status', 'Y')
            ->get();

        // Fetch appointment and patient details
        $appointment = Appointment::with([
            'doctor:id,name',
            'branch:id,clinic_address,city_id,state_id,country_id,pincode,clinic_phone,clinic_email',
            'branch.state:id,state',
            'branch.city:id,city',
            'branch.country:id,country',
        ])
            ->where('id', $appId)
            ->first();

        $patient = PatientProfile::with([
            'country:id,country',
            'state:id,state',
            'city:id,city',
        ])
            ->where('patient_id', $patientId)
            ->first();

        // Pass data to the PDF view
        $pdf = Pdf::loadView('pdf.prescription_pdf', [
            'prescriptions' => $prescriptions,
            'appointment' => $appointment,
            'patient' => $patient,
            'clinicDetails' => $clinicDetails,
        ])->setPaper('A5', 'portrait');

        // Download the generated PDF
        return $pdf->download('prescription.pdf');
    }

    public function generatePatientIDCardPdf(Request $request)
    {
        $appId = $request->input('app_id');
        $patientId = $request->input('patient_id');

        $clinicDetails = ClinicBasicDetail::first();
        // $prescriptions = Prescription::with([
        //     'medicine',
        //     'dosage',
        //     'prescribedBy:id,name',
        //     'route:id,route_name',
        // ])
        //     ->where('patient_id', $patientId)
        //     ->where('app_id', $appId)
        //     ->where('status', 'Y')
        //     ->get();
        if ($clinicDetails->clinic_logo == '') {
            $clinicLogo = 'public/images/logo-It.png';
        } else {
            $clinicLogo = 'storage/' . $clinicDetails->clinic_logo;
        }

        // Fetch appointment and patient details
        $appointment = Appointment::with([
            'doctor:id,name',
            'branch:id,clinic_address,city_id,state_id,country_id,pincode,clinic_phone,clinic_email',
            'branch.state:id,state',
            'branch.city:id,city',
            'branch.country:id,country',
        ])
            ->where('id', $appId)
            ->first();

        $patient = PatientProfile::with([
            'country:id,country',
            'state:id,state',
            'city:id,city',
        ])
            ->where('patient_id', $patientId)
            ->first();

        // Pass data to the PDF view
        $pdf = Pdf::loadView('pdf.patientidcard_pdf', [
            // 'prescriptions' => $prescriptions,
            'appointment' => $appointment,
            'patient' => $patient,
            'clinicDetails' => $clinicDetails,
            'clinicLogo' => $clinicLogo,
        ])->setPaper('A6', 'landscape');

        // Download the generated PDF
        return $pdf->download('patientidcard.pdf');
    }

    // public function printPrescription(Request $request)
    // {
    //     $appId = $request->input('app_id');
    //     $patientId = $request->input('patient_id');

    //     $clinicDetails = ClinicBasicDetail::first();
    //     $prescriptions = Prescription::with([
    //         'medicine',
    //         'dosage',
    //         'prescribedBy:id,name',
    //         'route:id,route_name',
    //     ])
    //         ->where('patient_id', $patientId)
    //         ->where('app_id', $appId)
    //         ->where('status', 'Y')
    //         ->get();

    //     // Fetch appointment and patient details
    //     $appointment = Appointment::with([
    //         'doctor:id,name',
    //         'branch:id,clinic_address,city_id,state_id,country_id,pincode,clinic_phone,clinic_email',
    //         'branch.state:id,state',
    //         'branch.city:id,city',
    //         'branch.country:id,country',
    //     ])
    //         ->where('id', $appId)
    //         ->first();

    //     $patient = PatientProfile::with([
    //         'country:id,country',
    //         'state:id,state',
    //         'city:id,city',
    //     ])
    //         ->where('patient_id', $patientId)
    //         ->first();

    //     // Pass data to the print view
    //     return view('pdf.prescription_print', [
    //         'prescriptions' => $prescriptions,
    //         'appointment' => $appointment,
    //         'patient' => $patient,
    //         'clinicDetails' => $clinicDetails,
    //     ]);
    // }
}
