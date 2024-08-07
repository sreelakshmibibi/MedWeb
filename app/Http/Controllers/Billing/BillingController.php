<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\AppointmentType;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\Insurance;
use App\Models\PatientDetailBilling;
use App\Models\PatientTreatmentBilling;
use App\Models\Prescription;
use App\Models\StaffProfile;
use App\Models\ToothExamination;
use App\Models\TreatmentComboOffer;
use App\Services\BillingService;
use App\Services\CommonService;
use App\Services\DoctorAvaialbilityService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BillingController extends Controller
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
            $selectedDate = $request->input('selectedDate');
            $appointments = Appointment::whereDate('app_date', $selectedDate)
                ->with(['patient', 'doctor', 'branch'])
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
                    $statusMap = [
                        AppointmentStatus::SCHEDULED => 'badge-success',
                        AppointmentStatus::WAITING => 'badge-warning',
                        AppointmentStatus::UNAVAILABLE => 'badge-warning-light',
                        AppointmentStatus::CANCELLED => 'badge-danger',
                        AppointmentStatus::COMPLETED => 'badge-success-light',
                        AppointmentStatus::BILLING => 'badge-primary',
                        AppointmentStatus::PROCEDURE => 'badge-secondary',
                        AppointmentStatus::MISSED => 'badge-danger-light',
                        AppointmentStatus::RESCHEDULED => 'badge-info',
                    ];
                    $btnClass = isset($statusMap[$row->app_status]) ? $statusMap[$row->app_status] : '';

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
                    $buttons[] = "<a href='" . route('billing.create', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-primary btn-xs me-1' title='generate bill' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' ><i class='fa fa-plus'></i></a>";

                    // Check if the appointment date is less than the selected date
                    if ($row->app_date < date('Y-m-d') && $row->app_status == AppointmentStatus::COMPLETED) {
                        // If appointment date is less than the selected date, show view icon
                        $buttons[] = "<a href='" . route('treatment', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-info btn-xs me-1' title='view' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' ><i class='fa-solid fa-eye'></i></a>";
                    } elseif ($row->app_date == date('Y-m-d')) {
                        // Otherwise, show treatment icon
                    } else {
                        $buttons[] = '';
                    }
                    if ($row->app_status != AppointmentStatus::COMPLETED) {

                        $buttons[] = "<button type='button' class='waves-effect waves-light btn btn-circle btn-danger btn-xs' id='btn-cancel' data-bs-toggle='modal' data-bs-target='#modal-cancel' data-id='{$row->id}' title='cancel'><i class='fa fa-times'></i></button>";
                    }
                    if ($row->app_status == AppointmentStatus::COMPLETED) {
                        $buttons[] = "<button type='button' class='waves-effect waves-light btn btn-circle btn-secondary btn-pdf-generate btn-xs me-1' title='Download' data-bs-toggle='modal' data-app-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}'  data-bs-target='#modal-download'><i class='fa fa-download'></i></button>";
                        $buttons[] = "<button type='button' class='waves-effect waves-light btn btn-circle btn-warning btn-pdf-generate btn-xs' title='Print' data-bs-toggle='modal' data-app-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}'  data-bs-target='#modal-download'><i class='fa fa-print'></i></button>";

                    }

                    return implode('', $buttons);
                })

                ->rawColumns(['patient_id', 'name', 'status', 'action'])
                ->make(true);
        }
        
        return view('billing.index');
    }

    public function comboOffer(Request $request, $appointmentId)
    {
       
        // Decode and decrypt appointment ID
        $id = base64_decode(Crypt::decrypt($appointmentId));
        $appointment = Appointment::find($id);

        // Check if the appointment exists
        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'Appointment not found.'], 404);
        }

        // Process incoming data
        $appointment->combo_offer_id = $request->input('combos');
        $appointment->save();

        // Redirect back to the page where the appointment is displayed
        return response()->json(['success' => true, 'message' => ''], 200);
    }

    public function create($appointmentId)
    {
        $id = base64_decode(Crypt::decrypt($appointmentId));
        $appointment = Appointment::with(['patient', 'doctor', 'branch'])
                        ->find($id);
        $billExists = PatientTreatmentBilling::where('status', 'Y')
                        ->where('appointment_id', $id)
                        ->where('patient_id', $appointment->patient_id)
                        ->first();
        if (!empty($billExists)) {
            $detailBill = PatientDetailBilling::where('billing_id', $billExists->id)->get();
            return view('billing.generateBill', compact('appointment', 'billExists', 'detailBill'));
        }
        $billingService = new BillingService();
        $treatmentAmounts = $billingService->individualTreatmentAmounts($id, $appointment->patient_id);
        $individualTreatmentAmounts = $treatmentAmounts['individualTreatmentAmounts'];
        $selectedTreatments = $treatmentAmounts['selectedTreatmentIds'];
        $totalCost = $treatmentAmounts['totalCost'];
        // Fetch the doctor discount from the appointment
        $insuranceApproved = 0;
        $doctorDiscount = $appointment->doctor_discount;
        $clinicBasicDetails = ClinicBasicDetail::first();
        $feesFrequency = $clinicBasicDetails->consultation_fees_frequency;
        $checkAppointmentCount = $billingService->getAppointmentCount($appointment->patient_id, $appointment->id);
        $consultationFees = 1;
        $fees = $appointment->doctor->staffProfile->consultation_fees;
        if ($checkAppointmentCount > 1) {
            $consultationFees = $billingService->getConsultationFees($appointment->patient_id, $feesFrequency);
            
        }
        $insuranceDetails = Insurance::where('patient_id', $appointment->patient_id)
        ->where('status', 'Y')
        ->where('policy_end_date', '>=', $appointment->app_date)
        ->first();
        $combOffers = $billingService->getOffers($selectedTreatments, $appointment->combo_offer_id);
        $isMedicineProvided = (ClinicBranch::find($appointment->app_branch))->is_medicine_provided;
        $prescriptions = Prescription::where('app_id', $appointment->id)
                            ->where('patient_id', $appointment->patient_id)
                            ->where('status', 'Y')
                            ->get();
        $comboOfferId = $appointment->combo_offer_id ? $appointment->combo_offer_id : 0;
        $comboOfferApplied = 0;
        $comboOfferDeduction = 0;
        if (!empty($insuranceDetails)) {
            foreach ($individualTreatmentAmounts as &$individualTreatmentAmount) {
                $individualTreatmentAmount['discount_percentage'] = 0;
                $individualTreatmentAmount['treat_cost'] = $individualTreatmentAmount['cost'];
                $individualTreatmentAmount['subtotal'] = $individualTreatmentAmount['quantity'] * $individualTreatmentAmount['treat_cost'];                    // $actualCost += $individualTreatmentAmount->cost; 
            }
        }
        if($comboOfferId) {
            $comboOfferTreatments = TreatmentComboOffer::with('treatments')->find($comboOfferId);
            if ($comboOfferTreatments) {
                $comboOfferApplied = $comboOfferTreatments->offer_amount;
                $comboOfferActualAmount = $comboOfferTreatments->treatments->sum('treat_cost');
                $actualCost = 0;
                foreach ($individualTreatmentAmounts as &$individualTreatmentAmount) {
                    $individualTreatmentAmount['discount_percentage'] = 0;
                    $individualTreatmentAmount['treat_cost'] = $individualTreatmentAmount['cost'];
                    $individualTreatmentAmount['subtotal'] = $individualTreatmentAmount['quantity'] * $individualTreatmentAmount['treat_cost']; 
                }
                $comboOfferDeduction= $comboOfferActualAmount - $comboOfferApplied;
            } 
        }
        
        $medicineTotal = 0;
        $insurance = 0;
        // Pass variables to the view
       
        // if (!empty($insuranceDetails)) {
        return view('billing.add', compact('appointment', 'individualTreatmentAmounts', 'doctorDiscount', 'totalCost', 'insuranceApproved', 'checkAppointmentCount', 'clinicBasicDetails', 'consultationFees', 'fees', 'combOffers', 'isMedicineProvided', 'prescriptions', 'comboOfferApplied', 'medicineTotal', 'insurance', 'comboOfferDeduction', 'insuranceDetails'));
        // }
        //  else {
        //     return view('billing.generateBill', compact('appointment', 'individualTreatmentAmounts', 'doctorDiscount', 'totalCost', 'insuranceApproved', 'checkAppointmentCount', 'clinicBasicDetails', 'consultationFees', 'fees', 'combOffers', 'isMedicineProvided', 'prescriptions', 'comboOfferApplied', 'medicineTotal', 'insurance', 'comboOfferDeduction'));
        // }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
    
            // Collect inputs
            $inputs = $request->only([
                'appointment_id', 'patient_id', 'treatment_total_amount',
                'combo_offer_deduction', 'doctor_discount', 'insurance_paid',
                'amount_to_be_paid', 'treatmentCount', 'treatmentReg', 'regCost',
                'regAmount', 'consultationFees', 'consultationFeesCost', 'consultationFeesAmount'
            ]);
            // Create and save treatment bill
            $treatmentBill = new PatientTreatmentBilling($inputs);
            $commonService = new CommonService();
            $biilingId = $commonService->generateUniqueBillingId();
            $treatmentBill->bill_id = $biilingId;
            $treatmentBill->treatment_total_amount = (float) str_replace(',', '',$inputs['treatment_total_amount']);
            $treatmentBill->combo_offer_deduction = (float) str_replace(',', '',$inputs['combo_offer_deduction'] ?? 0.000);
            $treatmentBill->doctor_discount = (float) str_replace(',', '',$inputs['doctor_discount'] ?? 0.000);
            $treatmentBill->insurance_paid = (float) str_replace(',', '',$inputs['insurance_paid'] ?? 0.000);
            $treatmentBill->amount_to_be_paid = (float)  str_replace(',', '',$inputs['amount_to_be_paid'] ?? 0.000);
            $treatmentSave = $treatmentBill->save();

            if ($treatmentSave) {
                $billingService = new BillingService();
                // Process treatment details
                $treatmentCount = $inputs['treatmentCount'] ?? 0;
                for ($i = 1; $i <= $treatmentCount; $i++) {
                    $billingService->savePatientDetailBilling($treatmentBill->id, $request, $i);
                }
        
                // Process additional charges if present
                $billingService->saveAdditionalCharges($treatmentBill->id, $inputs);
            
                DB::commit();
                
                return redirect()->back()->with('success', 'Bill created successfully.');
            } else {
                DB::rollBack();
                exit;
                return redirect()->back()->with('error', 'Failed to create bill ');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            echo "<pre>"; print_r($e->getMessage());echo "</pre>";exit;
            return redirect()->back()->with('error', 'Failed to create bill: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    
}
