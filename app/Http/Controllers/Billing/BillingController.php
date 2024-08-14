<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\AppointmentType;
use App\Models\CardPay;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\Insurance;
use App\Models\Log;
use App\Models\PatientDetailBilling;
use App\Models\PatientProfile;
use App\Models\PatientTreatmentBilling;
use App\Models\Prescription;
use App\Models\PatientPrescriptionBilling;
use App\Models\PrescriptionDetailBilling;
use App\Models\TreatmentComboOffer;
use App\Services\BillingService;
use App\Services\CommonService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
                // ->where('app_status', AppointmentStatus::COMPLETED)
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
                // ->addColumn('amount', function ($row) {
                //     $billing = PatientTreatmentBilling::where('appointment_id', $row->id)
                //     ->where('status', 'Y')
                //     ->first();
                //     $amount = number_format(0, 3);
                //     if (!empty($billing) && $billing->insurance_paid != null)
                //     {
                //         $amount =  $billing->amount_to_be_paid + $billing->insurance_paid;
                //     } else if (!empty($billing))
                //     {
                //         $amount = $billing->amount_to_be_paid;

                //     }
                //     return number_format($amount, 3);

                // })
                ->addColumn('status', function ($row) {
                    $billing = PatientTreatmentBilling::where('appointment_id', $row->id)
                        // ->whereNot('bill_status', PatientTreatmentBilling::BILL_CANCELLED)
                        ->where('status', 'Y')
                        // ->whereNot('bill_status', PatientTreatmentBilling::BILL_CANCELLED)
                        ->first();

                    // $statusMap = [
                    //     AppointmentStatus::SCHEDULED => 'badge-success',
                    //     AppointmentStatus::WAITING => 'badge-warning',
                    //     AppointmentStatus::UNAVAILABLE => 'badge-warning-light',
                    //     AppointmentStatus::CANCELLED => 'badge-danger',
                    //     AppointmentStatus::COMPLETED => 'badge-success-light',
                    //     AppointmentStatus::BILLING => 'badge-primary',
                    //     AppointmentStatus::PROCEDURE => 'badge-secondary',
                    //     AppointmentStatus::MISSED => 'badge-danger-light',
                    //     AppointmentStatus::RESCHEDULED => 'badge-info',
                    // ];
                    $statusMap = [
                        AppointmentStatus::SCHEDULED => 'text-success',
                        AppointmentStatus::WAITING => 'text-warning',
                        AppointmentStatus::UNAVAILABLE => 'text-dark',
                        AppointmentStatus::CANCELLED => 'text-danger',
                        AppointmentStatus::COMPLETED => 'text-muted',
                        AppointmentStatus::BILLING => 'text-primary',
                        AppointmentStatus::PROCEDURE => 'text-secondary',
                        AppointmentStatus::MISSED => 'text-white',
                        AppointmentStatus::RESCHEDULED => 'text-info',
                    ];
                    $status = $row->app_status;
                    $bill_status = null;
                    $btnClass = isset($statusMap[$row->app_status]) ? $statusMap[$row->app_status] : '';
                    if ($status == AppointmentStatus::COMPLETED) {
                        if (!empty($billing) && $billing->bill_status == PatientTreatmentBilling::BILL_GENERATED) {
                            // $btnClass = 'badge-warning';
                            $btnClass = 'text-warning';
                            $bill_status = PatientTreatmentBilling::BILL_GENERATED_WORDS;
                        } elseif (!empty($billing) && $billing->bill_status == PatientTreatmentBilling::PAYMENT_DONE) {
                            // $btnClass = 'badge-success';
                            $btnClass = 'text-success';
                            $bill_status = PatientTreatmentBilling::PAYMENT_DONE_WORDS;
                        } elseif (!empty($billing) && $billing->bill_status == PatientTreatmentBilling::BILL_CANCELLED) {
                            // $btnClass = 'badge-danger';
                            $btnClass = 'text-danger';
                            $bill_status = PatientTreatmentBilling::BILL_CANCELLED_WORDS;
                        }
                    }
                    return "<span class='{$btnClass}'>" . ($bill_status != null ? $bill_status : AppointmentStatus::statusToWords($row->app_status)) . '</span>';

                    // return "<span class='btn d-block btn-xs badge {$btnClass}'>" . ($bill_status != null ? $bill_status : AppointmentStatus::statusToWords($row->app_status)) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    if ($row->app_status == AppointmentStatus::CANCELLED || $row->app_status == AppointmentStatus::RESCHEDULED) {
                        return '';
                    }
                    $parent_id = $row->app_parent_id ? $row->app_parent_id : $row->id;
                    $buttons = [];
                    $base64Id = base64_encode($row->id);
                    $idEncrypted = Crypt::encrypt($base64Id);
                    $billing = PatientTreatmentBilling::where('appointment_id', $row->id)
                        // ->whereNot('bill_status', PatientTreatmentBilling::BILL_CANCELLED)
                        ->where('status', 'Y')
                        ->first();
                    $hasPrescriptionBill = PatientPrescriptionBilling::where('appointment_id', $row->id)->first();
                    // if ($row->app_status == AppointmentStatus::COMPLETED && !empty($billing) && $billing->bill_status == PatientTreatmentBilling::BILL_CANCELLED) {
                    //     $buttons[] = "<a href='" . route('billing.create', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-primary btn-xs me-1' title='generate bill' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' ><i class='fa fa-plus'></i></a>";
                    if ($row->app_status == AppointmentStatus::COMPLETED && !empty($billing) && ($billing->bill_status == PatientTreatmentBilling::BILL_CANCELLED || $billing->bill_status == PatientTreatmentBilling::BILL_GENERATED)) {
                        $buttons[] = "<a href='" . route('billing.create', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-primary btn-xs me-1' title='generate bill' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' ><i class='fa fa-plus'></i></a>";

                    } elseif ($row->app_status == AppointmentStatus::COMPLETED && empty($billing)) {
                        $buttons[] = "<a href='" . route('billing.create', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-primary btn-xs me-1' title='generate bill' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' ><i class='fa fa-plus'></i></a>";
                    }

                    // if (!empty($billing) && $billing->amount_paid != null && $billing->bill_status != PatientTreatmentBilling::BILL_CANCELLED) {
                    //     $buttons[] = "<a href='" . route('billing.create', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-success btn-xs me-1' title='Print bill' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' ><i class='fa fa-money-bill-alt'></i></a>";
                    if (!empty($billing) &&  $billing->bill_status == PatientTreatmentBilling::PAYMENT_DONE) {
                        $buttons[] = "<a href='" . route('billing.create', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-info btn-xs me-1' title='View bill' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' ><i class='fa fa-eye'></i></a>";
                        $buttons[] = '<button type="button" id="printPayment1"
                            class="waves-effect waves-light btn btn-circle btn-secondary btn-xs me-1"
                            title="Download & Print Treatment Bill"><i class="fa fa-download"></i></button>';
                        if ($hasPrescriptionBill) {
                            $buttons[] = '<button type="button" id="prescPrintPayment1"
                            class="waves-effect waves-light btn btn-circle btn-warning btn-xs me-1"
                            title="Print Medicine Bill"><i class="fa fa-print"></i></button>';
                        }
                        
                    }
                    if (auth()->user()->hasRole('Admin') && !empty($billing) &&  ($billing->bill_status == PatientTreatmentBilling::PAYMENT_DONE || $billing->bill_status == PatientTreatmentBilling::BILL_GENERATED)) {
                        // $buttons[] = "<a href='" . route('billing.edit', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-warning btn-xs me-1' title='Edit bill' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace('<br>', ' ', $row->patient->first_name . ' ' . $row->patient->last_name) . "' ><i class='fa fa-edit'></i></a>";
                        $buttons[] = "<button type='button' class='waves-effect waves-light btn btn-circle btn-danger btn-xs' id='btn-cancel-bill' data-bs-toggle='modal' data-bs-target='#modal-cancel-bill' data-id='{$billing->id}' title='cancel'><i class='fa fa-times'></i></button>
                        ";
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
        // if (!empty($billExists)) {
        //     $detailBills = PatientDetailBilling::with('treatment')->where('billing_id', $billExists->id)->get();

        //     return view('billing.generateBill', compact('appointment', 'billExists', 'detailBills'));
        // }
        // if (!empty($billExists)) {
        //     $detailBills = PatientDetailBilling::with('treatment')->where('billing_id', $billExists->id)->get();
        //     $previousOutStanding = 0;
        //     $previousBill = PatientTreatmentBilling::where('patient_id', $appointment->patient_id)
        //         ->where('appointment_id', '<', $appointment->id)
        //         ->where('status', 'Y')
        //         ->orderBy('appointment_id', 'desc') // Order by descending to get the most recent previous appointment
        //         ->first(); // Get the first result which will be the closest previous appointment

        //     // Check if a previous appointment was found
        //     if ($previousBill) {
        //         if ($previousBill->bill_status == PatientTreatmentBilling::PAYMENT_DONE) {
        //             $previousOutStanding += $previousBill->balance_due;
        //         }
        //         if ($previousBill->bill_status == PatientTreatmentBilling::BILL_GENERATED) {
        //             $previousOutStanding += $previousBill->amount_to_be_paid;
        //         }

        //     }

        //     return view('billing.generateBill', compact('appointment', 'billExists', 'detailBills', 'previousOutStanding'));
        // }
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
        // $prescriptions = Prescription::where('app_id', $appointment->id)
        //     ->where('patient_id', $appointment->patient_id)
        //     ->where('status', 'Y')
        //     ->get();
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
        if ($comboOfferId) {
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
                $comboOfferDeduction = $comboOfferActualAmount - $comboOfferApplied;
            }
        }
        $prescriptions = Prescription::with(['medicine', 'dosage'])
            ->where('app_id', $appointment->id)
            ->where('patient_id', $appointment->patient_id)
            ->where('status', 'Y')
            ->get();
        $medicineTotal = 0;
        $hasPrescriptionBill = PatientPrescriptionBilling::where('appointment_id', $appointment->id)->first();

        // Initialize prescription bill details
        if ($hasPrescriptionBill) {

            $prescriptionBillDetails = PrescriptionDetailBilling::where('bill_id', $hasPrescriptionBill->id)->get();
        } else {
            $prescriptionBillDetails = collect(); // Use an empty collection
        }

        $insurance = 0;
        // Pass variables to the view
        // if (!empty($billExists)) {
        //     $detailBills = PatientDetailBilling::with('treatment')->where('billing_id', $billExists->id)->get();

        //     return view('billing.generateBill', compact('appointment', 'billExists', 'detailBills', 'isMedicineProvided', 'clinicBasicDetails', 'medicineTotal', 'prescriptions', 'hasPrescriptionBill', 'prescriptionBillDetails'));
        // }
        if (!empty($billExists)) {
            $detailBills = PatientDetailBilling::with('treatment')->where('billing_id', $billExists->id)->get();
            $previousOutStanding = 0;
            $previousBill = PatientTreatmentBilling::where('patient_id', $appointment->patient_id)
                ->where('appointment_id', '<', $appointment->id)
                ->where('status', 'Y')
                ->orderBy('appointment_id', 'desc') // Order by descending to get the most recent previous appointment
                ->first(); // Get the first result which will be the closest previous appointment

            // Check if a previous appointment was found
            if ($previousBill) {
                if ($previousBill->bill_status == PatientTreatmentBilling::PAYMENT_DONE) {
                    $previousOutStanding += $previousBill->balance_due;
                }
                if ($previousBill->bill_status == PatientTreatmentBilling::BILL_GENERATED) {
                    $previousOutStanding += $previousBill->amount_to_be_paid;
                }

            }
            $cardPay = CardPay::where('status', 'Y')->get();
            if ($billExists->bill_status = PatientTreatmentBilling::BILL_GENERATED) {
                return view('billing.generateBill', compact('appointment', 'billExists', 'detailBills', 'previousOutStanding', 'clinicBasicDetails', 'isMedicineProvided', 'medicineTotal', 'prescriptions', 'hasPrescriptionBill', 'prescriptionBillDetails', 'cardPay'));
            } else if ($billExists->bill_status = PatientTreatmentBilling::PAYMENT_DONE){
                return redirect()->route('billing.paymentReceipt')->with([
                    'billId' => $billExists->id,
                    'appointmentId' => $appointment->id,
                    
                ]);
            }
            
        }
        $cardPay = CardPay::where('status', "Y")->get();
        // if (!empty($insuranceDetails)) {
        return view('billing.add', compact('appointment', 'individualTreatmentAmounts', 'doctorDiscount', 'totalCost', 'insuranceApproved', 'checkAppointmentCount', 'clinicBasicDetails', 'consultationFees', 'fees', 'combOffers', 'isMedicineProvided', 'prescriptions', 'comboOfferApplied', 'medicineTotal', 'insurance', 'comboOfferDeduction', 'insuranceDetails', 'hasPrescriptionBill', 'prescriptionBillDetails', 'cardPay'));
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
                'appointment_id',
                'patient_id',
                'treatment_total_amount',
                'combo_offer_deduction',
                'doctor_discount',
                'insurance_paid',
                'amount_to_be_paid_insurance',
                'treatmentCount',
                'treatmentReg',
                'regCost',
                'tax',
                'totaltoPay',
                'consultationFees',
                'consultationFeesCost',
                'consultationFeesAmount',
            ]);

            // Fix input key case
            $totalToPay = (float) str_replace(',', '', $request->input('totaltoPay')) ?? 0.00;
            $insurancePaid = $inputs['insurance_paid'] ?? 0.000;

            // Convert inputs to float
            $inputs['treatment_total_amount'] = (float) str_replace(',', '', $inputs['treatment_total_amount']);
            $inputs['combo_offer_deduction'] = (float) str_replace(',', '', $inputs['combo_offer_deduction'] ?? 0.000);
            $inputs['doctor_discount'] = (float) str_replace(',', '', $inputs['doctor_discount'] ?? 0.000);
            $inputs['insurance_paid'] = (float) str_replace(',', '', $insurancePaid);
            $inputs['tax'] = (float) str_replace(',', '', $inputs['tax'] ?? 0.000);

            // Determine amount to be paid
            $amountToBePaid = isset($inputs['insurance_paid']) && $inputs['insurance_paid'] > 0
                ? (float) str_replace(',', '', $inputs['amount_to_be_paid_insurance'] ?? 0.000)
                : (float) str_replace(',', '', $totalToPay);

            // Create and save treatment bill
            $clinicBasicDetails = ClinicBasicDetail::first();
            $treatmentBill = new PatientTreatmentBilling($inputs);
            $commonService = new CommonService();
            $biilingId = $commonService->generateUniqueBillingId();
            $treatmentBill->bill_id = $biilingId;
            $treatmentBill->amount_to_be_paid = $amountToBePaid;
            $treatmentBill->tax_percentile = $clinicBasicDetails->tax;
            $treatmentBill->bill_status = $amountToBePaid > 0 ?  PatientTreatmentBilling::BILL_GENERATED : PatientTreatmentBilling::PAYMENT_DONE;
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
               if ($treatmentBill->bill_status = PatientTreatmentBilling::PAYMENT_DONE){
                    // return redirect()->route('billing.paymentReceipt')->with([
                    //     'billId' =>  Crypt::encrypt(base64_encode($treatmentBill->id)),
                    //     'appointmentId' => Crypt::encrypt(base64_encode($treatmentBill->appointment_id)),

                    // ]);
                    return redirect()->route('billing')->with('success', 'Bill created successfully.');
                }
                return redirect()->back()->with('success', 'Bill created successfully.');
            } else {
                DB::rollBack();

                return redirect()->back()->with('error', 'Failed to create bill.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create bill: ' . $e->getMessage());
        }
    }

    public function payment(Request $request)
    { 
            DB::beginTransaction();

        try {
            $billId = base64_decode(Crypt::decrypt($request['billId']));
            $appointmentId = base64_decode(Crypt::decrypt($request['appointmentId']));
            $appointment = Appointment::with(['patient', 'doctor', 'branch'])
                ->find($appointmentId);
            $patientTreatmentBilling = PatientTreatmentBilling::findOrFail($billId);
            
            if (!$patientTreatmentBilling) {
                throw new \Exception('Bill not found.');
            }

            // Update billing information
            $patientTreatmentBilling->previous_outstanding = isset($request['previousOutStanding']) ? $request['previousOutStanding'] : 0;
            $patientTreatmentBilling->amount_to_be_paid = $request['totaltoPay'];
            $patientTreatmentBilling->amount_paid = $request['amountPaid'];
            $patientTreatmentBilling->card_pay_id = $request['machine'];
            $patientTreatmentBilling->gpay = $request['gpaycash'];
            $patientTreatmentBilling->card = $request['cardcash'];
            $patientTreatmentBilling->cash = $request['cash'];
            $patientTreatmentBilling->consider_for_next_payment = isset($request['balance_given']) ? 0 : 1;
            $patientTreatmentBilling->balance_due = $request['balance'];
            $patientTreatmentBilling->balance_to_give_back = $request['balanceToGiveBack'];
            $patientTreatmentBilling->balance_given = isset($request['balance_given']) ? 1 : 0;
            $patientTreatmentBilling->bill_status = PatientTreatmentBilling::PAYMENT_DONE;
            $patientTreatmentBilling->bill_paid_date = Carbon::now();
            $patientTreatmentBilling->billed_by = Auth::user()->id;
            
            // Log the update attempt
            Log::create([
                'log_type' => 'INFO',
                'message' => 'Attempting to update PatientTreatmentBilling',
                'context' => [
                    'billId' => $billId,
                    'amountPaid' => $request['amountPaid']
                ]
            ]);

            $i = $patientTreatmentBilling->save();

            if ($i && $patientTreatmentBilling->previous_outstanding != 0) {
                $previousBill = PatientTreatmentBilling::where('patient_id', $appointment->patient_id)
                    ->where('appointment_id', '<', $appointment->id)
                    ->where('status', 'Y')
                    ->orderBy('appointment_id', 'desc')
                    ->first();
                if (!empty($previousBill)) {
                    $previousBill->due_covered_bill_no = $patientTreatmentBilling->bill_id;
                    $previousBill->due_covered_date = Carbon::now();
                    
                    if ($previousBill->save()) {
                        Log::create([
                            'log_type' => 'INFO',
                            'message' => 'Previous bill updated successfully',
                            'context' => ['previousBillId' => $previousBill->id]
                        ]);
                        DB::commit();
                    } else {
                        throw new \Exception('Failed to update outstanding bill.');
                    }
                }
            } else if ($i) {
                Log::create([
                    'log_type' => 'INFO',
                    'message' => 'Bill updated successfully',
                    'context' => ['billId' => $billId]
                ]);
                DB::commit();
            } else {
                throw new \Exception('Failed to pay bill.');
            }

            $appointment = Appointment::with(['patient', 'doctor', 'branch'])->find($appointmentId);
            $billDetails = PatientDetailBilling::with('treatment')->where('billing_id', $billId)->get();
            $clinicDetails = ClinicBasicDetail::first();
            $clinicLogo = $clinicDetails->clinic_logo ? 'storage/' . $clinicDetails->clinic_logo : 'public/images/logo-It.png';

            $pdf = Pdf::loadView('pdf.treatmentBill_pdf', [
                'billDetails' => $billDetails,
                'patientTreatmentBilling' => $patientTreatmentBilling,
                'appointment' => $appointment,
                'patient' => $appointment->patient,
                'clinicDetails' => $clinicDetails,
                'clinicLogo' => $clinicLogo,
                'currency' => $clinicDetails->currency,
            ])->setPaper('A5', 'portrait');
            
            $fileName = 'bill_' . $appointment->patient_id . '_' . date('Y-m-d') . '.pdf';
            $filePath = 'public/pdfs/' . $fileName;
            Storage::put($filePath, $pdf->output());

            // Log PDF generation
            Log::create([
                'log_type' => 'INFO',
                'message' => 'PDF generated',
                'context' => ['filePath' => $filePath]
            ]);

            return response()->json(['pdfUrl' => Storage::url($filePath)]);

        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error
            Log::create([
                'log_type' => 'ERROR',
                'message' => 'Payment process failed',
                'context' => ['error' => $e->getMessage()]
            ]);
            return redirect()->back()->with('error', 'Failed to pay bill: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function paymentReceipt(Request $request)
    {
        $billId = base64_decode(Crypt::decrypt($request->input('billId')));
        $appointmentId = base64_decode(Crypt::decrypt($request['appointmentId']));
        $patientTreatmentBilling = PatientTreatmentBilling::with('billedBy')->findOrFail($billId);

        // Generate PDF
        $appointment = Appointment::with(['patient', 'doctor', 'branch'])
            ->find($appointmentId);
        $billDetails = PatientDetailBilling::with('treatment')->where('billing_id', $billId)->get();
        $clinicDetails = ClinicBasicDetail::first();
        if ($clinicDetails->clinic_logo == '') {
            $clinicLogo = 'public/images/logo-It.png';
        } else {
            $clinicLogo = 'storage/' . $clinicDetails->clinic_logo;
        }
        // $commonService = new CommonService();

        // $patientTreatmentBillingWords = $commonService->numberToWords($patientTreatmentBilling->amount_paid);
        $pdf = Pdf::loadView('pdf.treatmentBill_pdf', [
            'billDetails' => $billDetails,
            'patientTreatmentBilling' => $patientTreatmentBilling,
            // 'patientTreatmentBillingWords' => $patientTreatmentBillingWords,
            'appointment' => $appointment,
            'patient' => $appointment->patient,
            'clinicDetails' => $clinicDetails,
            'clinicLogo' => $clinicLogo,
            'currency' => $clinicDetails->currency,
        ])->setPaper('A5', 'portrait');
        $bill_patientId = 'bill_' . $appointment->patient_id . '_' . date('Y-m-d') . '.pdf';
        // Save PDF to storage
        $fileName = 'billing_report_' . $bill_patientId;
        $filePath = 'public/pdfs/' . $fileName;
        Storage::put($filePath, $pdf->output());

        // Return PDF file URL
        return response()->json(['pdfUrl' => Storage::url($filePath)]);
    }

    public function destroy($id, Request $request)
    {
        try {
            $bill = PatientTreatmentBilling::findOrFail($id);
            $bill->bill_delete_reason = $request->input('reason');
            $bill->status = 'N';
            $bill->bill_status = PatientTreatmentBilling::BILL_CANCELLED;

            if ($bill->save()) {
                return response()->json(['success', 'Bill cancelled successfully.'], 200);
            } else {
                return response()->json(['error', 'Bill cancellation unsuccessfull.'], 422);
            }

        } catch (Exception $e) {
            return response()->json(['error', 'Bill not cancelled.'], 200);
        }
    }
}
