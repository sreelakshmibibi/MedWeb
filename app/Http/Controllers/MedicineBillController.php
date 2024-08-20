<?php

namespace App\Http\Controllers;

use App\Http\Requests\Billing\MedicineBillRequest;
use App\Models\Appointment;
use App\Models\CardPay;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\PatientPrescriptionBilling;
use App\Models\Prescription;
use App\Models\PrescriptionDetailBilling;
use App\Services\BillingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MedicineBillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($appointmentId)
    {
        $cardPay = CardPay::where('status', 'Y')->get();
        // print_r($request->all());exit;
        $id = base64_decode(Crypt::decrypt($appointmentId));
        $appointment = Appointment::with(['patient', 'doctor', 'branch'])
            ->find($id);
        $billingService = new BillingService();
        $clinicBasicDetails = ClinicBasicDetail::first();
        $isMedicineProvided = (ClinicBranch::find($appointment->app_branch))->is_medicine_provided;
        $prescriptions = Prescription::with(['medicine', 'dosage'])
            ->where('app_id', $appointment->id)
            ->where('patient_id', $appointment->patient_id)
            ->where('status', 'Y')
            ->get();
        $medicineTotal = 0;
        $hasPrescriptionBill = PatientPrescriptionBilling::where('appointment_id', $id)->first();

        // Initialize prescription bill details
        if ($hasPrescriptionBill) {

            $prescriptionBillDetails = PrescriptionDetailBilling::where('bill_id', $hasPrescriptionBill->id)->get();
        } else {
            $prescriptionBillDetails = collect(); // Use an empty collection
        }

        // Pass variables to the view
        return view('billing.medicine', compact('appointment', 'clinicBasicDetails', 'isMedicineProvided', 'prescriptions', 'medicineTotal', 'hasPrescriptionBill', 'prescriptionBillDetails', 'cardPay'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MedicineBillRequest $request)
    {
        // Generate a unique bill_id
        $bill_id = $this->generateBillId();
        // Decode and decrypt the appointmentId
        $appId = base64_decode(Crypt::decrypt($request->appointmentId));
        $gpayAmount = $request->medgpay ?? 0;
        $cashAmount = $request->medcash ?? 0;
        $cardAmount = $request->medcard ?? 0;
        $cardPayId = $request->medmachine ?? null;

        DB::beginTransaction();

        try {
            // Create the billing record
            $billing = PatientPrescriptionBilling::create([
                'bill_id' => $bill_id,
                'appointment_id' => $appId,
                'patient_id' => $request->patientId,
                'prescription_total_amount' => $request->total,
                'tax_percentile' => $request->medtax,
                'tax' => $request->grandTotal - $request->total,
                'amount_to_be_paid' => $request->grandTotal,
                'gpay' => $gpayAmount,
                'cash' => $cashAmount,
                'card' => $cardAmount,
                'card_pay_id' => $cardPayId,
                'amount_paid' => $request->medamountPaid,
                'balance_given' => $request->medbalanceToGiveBack,
                'status' => 'Y',
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);

            // Store prescription details
            foreach ($request->quantity as $index => $quantity) {
                if ($quantity > 0 && $request->rate[$index] > 0 && ! empty($request->medicine_checkbox[$index])) {
                    PrescriptionDetailBilling::create([
                        'bill_id' => $billing->id,
                        'medicine_id' => $request->medicine_checkbox[$index],
                        'quantity' => $quantity,
                        'cost' => $request->unitcost[$index],
                        'amount' => $request->rate[$index] ?? 0,
                        'created_by' => auth()->user()->id,
                        'updated_by' => auth()->user()->id,
                        'status' => 'Y',
                    ]);
                }
            }
            DB::commit();

            return redirect()->route('billing')->with('success', 'Billing recorded successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to create bill: '.$e->getMessage());
        }
    }

    public function generateBillId()
    {
        $yearMonth = date('Ym'); // Year and Month
        $latestBill = PatientPrescriptionBilling::where('bill_id', 'like', $yearMonth.'%')
            ->orderBy('bill_id', 'desc')
            ->first();
        $lastBillId = $latestBill ? intval(substr($latestBill->bill_id, -4)) : 0;
        $newBillId = $yearMonth.str_pad($lastBillId + 1, 4, '0', STR_PAD_LEFT);

        return $newBillId;
    }

    public function paymentReceipt(Request $request)
    {

        $billId = base64_decode(Crypt::decrypt($request->input('medbillId')));
        $appointmentId = base64_decode(Crypt::decrypt($request['appointmentId']));
        $patientPrescriptionBilling = PatientPrescriptionBilling::with('createdBy')->findOrFail($billId);
        // Generate PDF
        $appointment = Appointment::with(['patient', 'doctor', 'branch'])
            ->find($appointmentId);
        $billDetails = PrescriptionDetailBilling::with('medicine')->where('bill_id', $billId)->get();
        $clinicDetails = ClinicBasicDetail::first();
        if ($clinicDetails->clinic_logo == '') {
            $clinicLogo = 'public/images/logo-It.png';
        } else {
            $clinicLogo = 'storage/'.$clinicDetails->clinic_logo;
        }
        $pdf = Pdf::loadView('pdf.prescriptionBill_pdf', [
            'billDetails' => $billDetails,
            'patientPrescriptionBilling' => $patientPrescriptionBilling,
            'appointment' => $appointment,
            'patient' => $appointment->patient,
            'clinicDetails' => $clinicDetails,
            'clinicLogo' => $clinicLogo,
            'currency' => $clinicDetails->currency,
        ])->setPaper('A5', 'portrait');
        $bill_patientId = 'prescriptionbill_'.$appointment->patient_id.'_'.date('Y-m-d').'.pdf';
        // Save PDF to storage
        $fileName = 'prescriptionBilling_report_'.$bill_patientId;
        $filePath = 'public/pdfs/'.$fileName;
        // Storage::put($filePath, $pdf->output());

        // // Return PDF file URL
        // return response()->json(['pdfUrl' => Storage::url($filePath)]);
        return $pdf->download('prescriptionBill.pdf');
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
