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
use App\Models\Medicine;
use App\Services\BillingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\MedicinePurchaseItem;


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
        $prescriptions = Prescription::with(['medicine', 'dosage', 'medicine.latestMedicinePurchaseItem'])
            ->where('app_id', $appointment->id)
            ->where('patient_id', $appointment->patient_id)
            ->where('status', 'Y')
            ->get();
        //Log::info('$prescription: '.$prescriptions);
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
        \Log::info('Request Data:', $request->all());
        // Decode the JSON string to an array
        // $medicineCheckbox = json_decode($request->medicine_checkbox, true);
        // Decode the JSON string to an array
        // $medicineCheckbox = json_decode($request->medicine_checkbox, true);

        // Check if decoding was successful
        // if (json_last_error() !== JSON_ERROR_NONE) {
        //     return response()->json(['success' => false, 'message' => 'Invalid medicine checkbox data.'], 400);
        // }

        \Log::info('Lengths:', [
            // 'medicine_checkbox' => is_array($medicineCheckbox) ? count($medicineCheckbox) : 0,
            'medicine_checkbox' => count($request->medicine_checkbox),
            'quantity' => count($request->quantity),
            'rate' => count($request->rate),
        ]);

        // Generate a unique bill_id
        $bill_id = $this->generateBillId();
        // Decode and decrypt the appointmentId
        $appId = base64_decode(Crypt::decrypt($request->appointmentId));
        $gpayAmount = $request->medgpay ?? 0;
        $cashAmount = $request->medcash ?? 0;
        $cardAmount = $request->medcard ?? 0;
        $cardPayId = $request->medmachine ?? null;
        $billPaidDate = Carbon::now();

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
                'bill_paid_date' => $billPaidDate,
                'status' => 'Y',
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);

            // Store prescription details
            // foreach ($request->quantity as $index => $quantity) {
            // Check if the index exists in other arrays before proceeding
            // if (!isset($request->medicine_checkbox[$index], $request->unitcost[$index], $request->rate[$index])) {
            //     continue; // Skip to the next iteration if any index is missing
            // }
            // if (!isset($medicineCheckbox[$index], $request->unitcost[$index], $request->rate[$index])) {
            //     continue; // Skip to the next iteration if any index is missing
            // }
            foreach ($request->isChecked as $index => $checkedvalue) {
                if ($checkedvalue == 'N') {
                    continue;
                }
                if (!isset($request->unitcost[$index], $request->rate[$index])) {
                    continue; // Skip to the next iteration if any index is missing
                }

                $quantity = $request->quantity[$index];
                $medicineId = $request->medicine_id[$index];
                $med_price = $request->unitcost[$index];
                if ($quantity > 0 && $request->rate[$index] > 0 && !empty($request->medicine_id[$index])) {
                    PrescriptionDetailBilling::create([
                        'bill_id' => $billing->id,
                        'medicine_id' => $request->medicine_id[$index],
                        'quantity' => $quantity,
                        'cost' => $request->unitcost[$index],
                        'amount' => $request->rate[$index] ?? 0,
                        'created_by' => auth()->user()->id,
                        'updated_by' => auth()->user()->id,
                        'status' => 'Y',
                    ]);
                    $medicine = Medicine::findOrFail($request->medicine_id[$index]);
                    $medStock = $medicine->stock - $quantity;
                    $medicine->stock = $medStock;
                    if ($medStock <= 0) {
                        $medicine->stock_status = 'Out of Stock';
                    }
                    $medicine->save();

                    // Query stock for the medicines in prescriptions
                    $medicineStocks = MedicinePurchaseItem::where('medicine_id', $medicineId)
                        ->where('balance', '>', 0)
                        ->where('med_price', '=', $med_price)
                        ->where('status', 'Y')
                        ->orderBy('expiry_date', 'asc')
                        ->get();

                    $totalUsed = $quantity; // Total quantity to be used
                    foreach ($medicineStocks as $medicineStock) {
                        if ($totalUsed <= 0) {
                            break; // Exit if no more quantity needs to be used
                        }

                        // Determine how much can be used from this stock
                        $usableQuantity = min($totalUsed, $medicineStock->balance);

                        // Update the stock
                        $medicineStock->balance -= $usableQuantity;
                        $medicineStock->used_stock += $usableQuantity;
                        $medicineStock->save(); // Save the updated stock

                        // Decrement the total used quantity
                        $totalUsed -= $usableQuantity;
                    }

                    // If there's any remaining quantity that couldn't be fulfilled
                    if ($totalUsed > 0) {
                        return response()->json(['error' => 'Insufficient stock for medicine ID ' . $medicineId], 400);
                    }
                    // Check if the prescription already exists for the given patient and app ID
                    $existingPrescription = Prescription::where('patient_id', $request->patientId)
                        ->where('app_id', $appId)
                        ->where('medicine_id', $medicineId)
                        ->first();
                    // \Log::info('Request data:', $request->all());
                    if (!$existingPrescription) {
                        $prescriptionData = new Prescription();
                        $prescriptionData->patient_id = $request->patientId;
                        $prescriptionData->app_id = $appId;
                        $prescriptionData->medicine_id = $medicineId;
                        $prescriptionData->dose = $request->dose[$index] ?? null;
                        $prescriptionData->dose_unit = $request->dose_unit[$index] ?? null;
                        $prescriptionData->dosage_id = $request->dosage[$index] ?? 8;
                        $prescriptionData->duration = $request->duration[$index] ?? 0;
                        $prescriptionData->remark = 'added at bill time';
                        $prescriptionData->prescribed_by = auth()->user()->id;
                        $prescriptionData->created_by = auth()->user()->id;
                        $prescriptionData->updated_by = auth()->user()->id;

                        $prescriptionData->save();
                    }
                }
            }
            $billingService = new BillingService();
            $appBranch = Appointment::where('id', $appId)->value('app_branch');

            $incomeData = [
                'bill_type' => 'prescription',
                'bill_no' => $bill_id,
                'bill_date' => $billPaidDate,
                'branch_id' => $appBranch,
                'gpay' => $request->medgpay ?? 0,
                'cash' => $request->medcash ?? 0,
                'card' => $request->medcard ?? 0,
                'card_pay_id' => $request->medmachine ?? null,
                'balance_given' => $request->medbalanceToGiveBack ?? 0,
                'created_by' => auth()->user()->id,
            ];
            $incomeReport = $billingService->saveIncomeReport($incomeData);
            DB::commit();

            //download receipt
            $patientPrescriptionBilling = PatientPrescriptionBilling::with('createdBy')->findOrFail($billing->id);
            // Generate PDF
            $appointment = Appointment::with(['patient', 'doctor', 'branch'])
                ->find($appId);
            $billDetails = PrescriptionDetailBilling::with('medicine')->where('bill_id', $billing->id)->get();
            $clinicDetails = ClinicBasicDetail::first();
            if ($clinicDetails->clinic_logo == '') {
                $clinicLogo = 'public/images/logo-It.png';
            } else {
                $clinicLogo = 'storage/' . $clinicDetails->clinic_logo;
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
            return response()->json([
                'success' => true,
                'message' => 'Medicine bill payment successfully recorded.',
                'pdf' => base64_encode($pdf->output()),
            ]);

            //return redirect()->route('billing')->with('success', 'Billing recorded successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            //return redirect()->back()->with('error', 'Failed to create bill: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the payment.', 'error' => 'An error occurred while processing the payment.: ' . $e->getMessage()], 500);
        }
    }

    public function generateBillId()
    {
        $yearMonth = date('Ym'); // Year and Month
        $latestBill = PatientPrescriptionBilling::where('bill_id', 'like', $yearMonth . '%')
            ->orderBy('bill_id', 'desc')
            ->first();
        $lastBillId = $latestBill ? intval(substr($latestBill->bill_id, -4)) : 0;
        $newBillId = $yearMonth . str_pad($lastBillId + 1, 4, '0', STR_PAD_LEFT);

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
            $clinicLogo = 'storage/' . $clinicDetails->clinic_logo;
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
        $bill_patientId = 'prescriptionbill_' . $appointment->patient_id . '_' . date('Y-m-d') . '.pdf';
        // Save PDF to storage
        $fileName = 'prescriptionBilling_report_' . $bill_patientId;
        $filePath = 'public/pdfs/' . $fileName;
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
