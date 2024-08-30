<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\DueBillRequest;
use App\Models\Appointment;
use App\Models\CardPay;
use App\Models\ClinicBasicDetail;
use App\Models\PatientDueBill;
use App\Models\PatientProfile;
use App\Models\PatientTreatmentBilling;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\BillingService;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $clinicDetails = ClinicBasicDetail::first();
        $cardPay = CardPay::where('status', 'Y')->get();

        return view('billing.duePayment', compact('clinicDetails', 'cardPay'));
    }

    public function searchPatient(Request $request)
    {
        $name = $request->get('name');
        $phone = $request->get('phone');
        $id = $request->get('id');
        if ($name != '' || $phone != '' || $id != '') {
            $query = PatientProfile::query();
            $clinicDetails = ClinicBasicDetail::first();

            if ($name) {
                $query->where('first_name', 'like', "%$name%");
            }

            if ($phone) {
                $query->where('phone', 'like', "%$phone%");
            }

            if ($id) {
                $query->where('patient_id', $id);
            }

            $patient = $query->first();

            if ($patient) {

                $previousOutStanding = 0;
                $previousBill = PatientProfile::with([
                    'latestAppointment.billingDetails',  // Load the billing details
                ])->find($patient->id);
                if ($previousBill && $previousBill->latestAppointment && $previousBill->latestAppointment->billingDetails->isNotEmpty()) {
                    $treatmentBillDetails = $previousBill->latestAppointment->billingDetails->last();
                    $billAppId = $treatmentBillDetails->appointment_id;
                    $base64Id = base64_encode($billAppId);
                    $billAppIdEncrypted = Crypt::encrypt($base64Id);
                    $treatmentBillId = $treatmentBillDetails->id;
                    $base64Id = base64_encode($treatmentBillId);
                    $treatmentBillIdEncrypted = Crypt::encrypt($base64Id);
                    $dueBillId = $treatmentBillDetails->due_covered_bill_no;
                    $base64Id = base64_encode($dueBillId);
                    $dueBillIdEncrypted = Crypt::encrypt($base64Id);

                    if ($treatmentBillDetails->bill_status == PatientTreatmentBilling::PAYMENT_DONE) {
                        $previousOutStanding += $treatmentBillDetails->balance_due;
                    }
                    if ($treatmentBillDetails->bill_status == PatientTreatmentBilling::BILL_GENERATED) {
                        $previousOutStanding += $treatmentBillDetails->amount_to_be_paid;
                    }
                    if ($treatmentBillDetails->due_covered_bill_no != null) {

                        $dueBillDetails = PatientDueBill::where('id', $treatmentBillDetails->due_covered_bill_no)->first();
                    } else {
                        $dueBillDetails = [];
                    }
                } else {
                    $treatmentBillDetails = $previousBill->latestAppointment->billingDetails;
                    $billAppIdEncrypted = '';
                    $treatmentBillIdEncrypted = '';
                    $dueBillIdEncrypted = '';
                    $dueBillDetails = [];
                    $billAppId = $previousBill->latestAppointment->id;
                    $base64Id = base64_encode($billAppId);
                    $billAppIdEncrypted = Crypt::encrypt($base64Id);

                }

                // $patientProfile = PatientProfile::with('latestBilling')->find($patient->id);

                // if ($patientProfile && $patientProfile->latestBilling) {

                //     $billAppId = $patientProfile->latestBilling->appointment_id;
                //     $base64Id = base64_encode($billAppId);
                //     $billAppIdEncrypted = Crypt::encrypt($base64Id);
                //     $treatmentBillId = $patientProfile->latestBilling->id;
                //     $base64Id = base64_encode($treatmentBillId);
                //     $treatmentBillIdEncrypted = Crypt::encrypt($base64Id);
                //     $dueBillId = $patientProfile->latestBilling->due_covered_bill_no;
                //     $base64Id = base64_encode($dueBillId);
                //     $dueBillIdEncrypted = Crypt::encrypt($base64Id);
                //     if ($patientProfile->latestBilling->balance_due > 0) {
                //         $dueAmount = $patientProfile->latestBilling->balance_due;

                //     } else {
                //         $dueAmount = 0;
                //     }

                // } else {
                //     $dueAmount = 0;
                //     $billAppIdEncrypted = '';
                //     $treatmentBillIdEncrypted = '';
                //     $dueBillIdEncrypted = '';
                // }
                // if ($patientProfile && $patientProfile->latestBilling && $patientProfile->latestBilling->due_covered_bill_no != null) {

                //     $dueBillDetails = PatientDueBill::where('id', $patientProfile->latestBilling->due_covered_bill_no)->first();
                // } else {
                //     $dueBillDetails = [];
                // }
                //Log::info('$dueBillDetails: '.$dueBillDetails);
                $name = str_replace('<br>', ' ', $previousBill->first_name).' '.$previousBill->last_name;

                return response()->json([
                    'success' => true,
                    'patient' => [
                        'currency' => $clinicDetails->currency,
                        'name' => $name,
                        'phone' => $previousBill->phone,
                        'id' => $previousBill->patient_id,
                        'due_amount' => $previousOutStanding,
                        'billAppId' => $billAppIdEncrypted,
                        'treatmentBillId' => $treatmentBillIdEncrypted,
                        'dueBillId' => $dueBillIdEncrypted,
                        'dueBillDetails' => $dueBillDetails,
                        'treatmentBillDetails' => $treatmentBillDetails,
                        // 'name' => $patientProfile->first_name,
                        // 'phone' => $patientProfile->phone,
                        // 'id' => $patientProfile->patient_id,
                        // 'due_amount' => $dueAmount,
                        // 'billAppId' => $billAppIdEncrypted,
                        // 'treatmentBillId' => $treatmentBillIdEncrypted,
                        // 'dueBillId' => $dueBillIdEncrypted,
                        // 'dueBillDetails' => $dueBillDetails,
                    ],
                ]);
            }
        }

        return response()->json(['success' => false]);
    }

    public function payDue(DueBillRequest $request)
    {
        DB::beginTransaction();

        try {
            $totalPaid = 0;
            if ($request->filled('duegpay')) {
                $totalPaid += $request->duegpay;
            }
            if ($request->filled('duecash')) {
                $totalPaid += $request->duecash;
            }
            if ($request->filled('duecard')) {
                $totalPaid += $request->duecard;
            }

            // Verify that the total paid amount matches the due amount paid
            if ($totalPaid != $request->dueAmountPaid) {
                return redirect()->back()->withErrors(['dueAmountPaid' => 'The total paid amount does not match the calculated amount.']);
            }

            $appId = base64_decode(Crypt::decrypt($request->appInput));
            $treatBillId = base64_decode(Crypt::decrypt($request->treatmentBillInput));
            $bill_id = $this->generateBillId();
            $billPaidDate = Carbon::now();

            // Create a new PatientDueBill record
            $dueBill = PatientDueBill::create([
                'bill_id' => $bill_id, // Generate a unique bill ID
                'patient_id' => $request->patientIdInput,
                'appointment_id' => $appId,
                'treatment_bill_id' => $treatBillId,
                'total_amount' => $request->dueTotal,
                'gpay' => $request->duegpay,
                'cash' => $request->duecash,
                'card' => $request->duecard,
                'card_pay_id' => $request->duemachine,
                'paid_amount' => $totalPaid,
                'balance_given' => $request->dueBalanceToGiveBack ? $request->dueBalanceToGiveBack : null,
                'bill_paid_date' => $billPaidDate,
                'status' => 'Y',
                'created_by' => auth()->user()->id,
            ]);

            $patientTreatmentBilling = PatientTreatmentBilling::findOrFail($treatBillId);
            $patientTreatmentBilling->due_covered_bill_no = $dueBill->id;
            $patientTreatmentBilling->due_covered_date = now();
            if ($patientTreatmentBilling->save()) {

                $billingService = new BillingService();
                
                $incomeData = [
                    'bill_type' => 'due_bill',
                    'bill_no' => $bill_id,
                    'bill_date' => $billPaidDate,
                    'gpay' => $request->duegpay ? $request->duegpay : 0,
                    'cash' => $request->duecash ? $request->duecash : 0,
                    'card' => $request->duecard ? $request->duecard : 0,
                    'card_pay_id' => $request->duemachine ?$request->duemachine:null,
                    'balance_given' => $request->dueBalanceToGiveBack ? $request->dueBalanceToGiveBack : 0,
                    'created_by' => auth()->user()->id, 
                ];
                $incomeReport = $billingService->saveIncomeReport($incomeData);
                // Commit the transaction since everything was successful
                DB::commit();

                // Generate the PDF
                $bill = PatientDueBill::with(['patientProfile', 'creator'])->findOrFail($dueBill->id);
                $appointmentDetails = Appointment::with(['patient', 'doctor', 'branch'])->find($bill->appointment_id);
                $clinicDetails = ClinicBasicDetail::first();

                $clinicLogo = $clinicDetails->clinic_logo == '' ? 'public/images/logo-It.png' : 'storage/'.$clinicDetails->clinic_logo;

                $pdf = PDF::loadView('pdf.dueBill_pdf', [
                    'billDetails' => $bill,
                    'patient' => $bill->patientProfile,
                    'appointment' => $appointmentDetails,
                    'clinicDetails' => $clinicDetails,
                    'clinicLogo' => $clinicLogo,
                    'currency' => $clinicDetails->currency,
                ])->setPaper('A5', 'portrait');

                return response()->json([
                    'success' => true,
                    'message' => 'Due payment successfully recorded.',
                    'pdf' => base64_encode($pdf->output()),
                ]);
            } else {
                DB::rollBack();

                return response()->json(['success' => false, 'message' => 'An error occurred while processing the due payment.', 'error' => 'An error occurred while processing the due payment. '], 500);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'An error occurred while processing the due payment.', 'error' => 'Failed to update treatment bill: '.$e->getMessage()], 500);
        }

    }

    public function generateBillId()
    {
        $yearMonth = date('Ym'); // Year and Month
        $latestBill = PatientDueBill::where('bill_id', 'like', $yearMonth.'%')
            ->orderBy('bill_id', 'desc')
            ->first();
        $lastBillId = $latestBill ? intval(substr($latestBill->bill_id, -4)) : 0;
        $newBillId = $yearMonth.str_pad($lastBillId + 1, 4, '0', STR_PAD_LEFT);

        return $newBillId;
    }

    public function paymentReceipt(Request $request)
    {

        // Validate that the bill_id exists
        $validatedData = $request->validate([
            'bill_id' => 'required',
        ]);
        $billId = base64_decode(Crypt::decrypt($request->bill_id));
        // Fetch the bill details
        $bill = PatientDueBill::with(['patientProfile', 'creator'])->findOrFail($billId);

        $appointmentDetails = Appointment::with(['patient', 'doctor', 'branch'])
            ->find($bill->appointment_id);
        $clinicDetails = ClinicBasicDetail::first();
        if ($clinicDetails->clinic_logo == '') {
            $clinicLogo = 'public/images/logo-It.png';
        } else {
            $clinicLogo = 'storage/'.$clinicDetails->clinic_logo;
        }
        $pdf = PDF::loadView('pdf.dueBill_pdf', ['billDetails' => $bill,
            'patient' => $bill->patientProfile,
            'appointment' => $appointmentDetails,
            'clinicDetails' => $clinicDetails,
            'clinicLogo' => $clinicLogo,
            'currency' => $clinicDetails->currency, ])->setPaper('A5', 'portrait');

        return $pdf->download('outstanding_payment_receipt_'.$bill->patientProfile->patient_id.'_'.$bill->bill_id.'.pdf');

    }
}
