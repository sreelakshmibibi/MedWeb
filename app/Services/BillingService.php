<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\MenuItem;
use App\Models\PatientDetailBilling;
use App\Models\PatientRegistrationFee;
use App\Models\PatientTreatmentBilling;
use App\Models\ToothExamination;
use App\Models\TreatmentComboOffer;
use Carbon\Carbon;
use DateTime;
use App\Models\IncomeReport;
use App\Models\CardPay;
use App\Models\TreatmentStatus;

class BillingService
{
    public function individualTreatmentAmounts($id, $patient_id)
    {
        // Find the appointment by ID
        $appointment = Appointment::find($id);
    
        // Fetch previous tooth examinations if a parent appointment exists
        $toothExaminationsPrevious = null;
        if ($appointment->app_parent_id != null) {
            $toothExaminationsPrevious = ToothExamination::with([
                'teeth:id,teeth_name,teeth_image',
                'treatment',
                'treatmentPlan',
                'toothScore:id,score',
                'disease:id,name',
                'xRayImages:id,tooth_examination_id,xray,status',
            ])
            ->where('app_id', $appointment->app_parent_id)
            ->where('patient_id', $patient_id)
            ->where('status', 'Y')
            ->get();
        }
    
        // Fetch current tooth examinations with related data
        $toothExaminations = ToothExamination::with([
            'teeth:id,teeth_name,teeth_image',
            'treatment',
            'treatmentPlan',
            'toothScore:id,score',
            'disease:id,name',
            'xRayImages:id,tooth_examination_id,xray,status',
        ])
        ->where('app_id', $id)
        ->where('patient_id', $patient_id)
        ->where('status', 'Y')
        ->get();
    
        // Initialize arrays and variables
        $individualTreatmentAmounts = [];
        $selectedTreatmentIds = [];
        $selectedTreatmentPlanIds = [];
        $individualTreatmentPlanAmounts = [];
        $totalCost = 0;
    
        // Create a map of previous treatments by tooth_id and treatment_id
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
    
        // Process current tooth examinations and filter out follow-up treatments
        foreach ($toothExaminations as $toothExamination) {
            $treatment = $toothExamination->treatment;
            $treatmentPlan = $toothExamination->treatmentPlan;
    
            if (!$treatment) {
                continue; // Skip if no treatment data is found
            }
    
            $treatmentId = $treatment->id;
            $treatmentName = $treatment->treat_name;
            $treatmentCost = floatval($treatment->treat_cost);
            $discount_from = $treatment->discount_from;
            $discount_to = $treatment->discount_to;
            $discount_percentage = floatval($treatment->discount_percentage);
    
            // Calculate discounted cost if applicable
            $currentDate = date('Y-m-d');
            $discountCost = $treatmentCost;
    
            if (
                $discount_from !== null && $discount_to !== null &&
                $currentDate >= $discount_from && $currentDate <= $discount_to &&
                $discount_percentage !== null
            ) {
                $discountCost = $treatmentCost * (1 - $discount_percentage / 100);
            }
    
            // Skip if treatment is marked as follow-up in previous examinations
            if (isset($previousTreatments[$toothExamination->teeth_id]) &&
                isset($previousTreatments[$toothExamination->teeth_id][$treatmentId])) {
                    continue;
            }
    
            // Initialize treatment entry if not already set
            if (!isset($individualTreatmentAmounts[$treatmentId])) {
                $individualTreatmentAmounts[$treatmentId] = [
                    'treat_id' => $treatmentId,
                    'treat_name' => $treatmentName,
                    'cost' => $treatmentCost,
                    'discount_percentage' => $discount_percentage,
                    'treat_cost' => $discountCost,
                    'quantity' => 0,
                    'subtotal' => 0,
                    'type' => 'treatment'
                ];
            }
    
            // Handle treatment plan if it exists
            $planCost = 0;
            if ($treatmentPlan) {
                $planId = $treatmentPlan->id;
                $planCost = floatval($treatmentPlan->cost); // Ensure cost is float
    
                // Initialize treatment plan entry if not already set
                if (!isset($individualTreatmentPlanAmounts[$planId])) {
                    $individualTreatmentPlanAmounts[$planId] = [
                        'treat_id' => $planId,
                        'treat_name' => $treatmentPlan->plan,
                        'cost' => $planCost,
                        'discount_percentage' => 0, // Assuming no discount for plans
                        'treat_cost' => $planCost,
                        'quantity' => 0,
                        'subtotal' => 0,
                        'type' => 'treatmentPlan'
                    ];
                }
    
                // Increment quantity and update subtotal for this treatment plan
                $individualTreatmentPlanAmounts[$planId]['quantity']++;
                $individualTreatmentPlanAmounts[$planId]['subtotal'] += $planCost;
                $selectedTreatmentPlanIds[] = $planId;
    
                // Accumulate total cost for the treatment plan
                $totalCost += $planCost;
            }
    
            // Increment quantity and update subtotal for this treatment
            $individualTreatmentAmounts[$treatmentId]['quantity']++;
            $individualTreatmentAmounts[$treatmentId]['subtotal'] += $discountCost;
            $selectedTreatmentIds[] = $treatmentId;
    
            // Accumulate total cost for the treatment
            $totalCost += $discountCost;
        }
    
        // Return the processed data
        return [
            'individualTreatmentAmounts' => $individualTreatmentAmounts,
            'individualTreatmentPlanAmounts' => $individualTreatmentPlanAmounts,
            'totalCost' => $totalCost,
            'selectedTreatmentIds' => array_unique($selectedTreatmentIds), // Ensure unique treatment IDs
            'selectedTreatmentPlanIds' => array_unique($selectedTreatmentPlanIds), // Ensure unique treatment plan IDs
        ];
    }
    


    public function getAppointmentCount($patient_id, $appointmentId)
    {
        $count = Appointment::where('patient_id', $patient_id)
            ->where('id', '<=', $appointmentId)
            ->where('status', 'Y')
            ->where('app_status', AppointmentStatus::COMPLETED)
            ->count();

        return $count;
    }

    public function getConsultationFees($patient_id, $feesFrequency)
    {
        // Fetch the latest appointment
        $latestAppointment = Appointment::with(['patient', 'doctor', 'branch'])
            ->where('app_status', AppointmentStatus::COMPLETED)
            ->where('patient_id', $patient_id)
            ->orderBy('app_date', 'desc')
            ->first();

        // Fetch the second-most-recent appointment
        $previousAppointment = Appointment::with(['patient', 'doctor', 'branch'])
            ->where('app_status', AppointmentStatus::COMPLETED)
            ->where('patient_id', $patient_id)
            ->where('app_date', '<', $latestAppointment->app_date) // Ensure it's before the latest appointment
            ->orderBy('app_date', 'desc')
            ->first();
        if ($latestAppointment && $previousAppointment) {
            // Parse the dates from the appointment objects
            $latestDate = Carbon::parse($latestAppointment->app_date);
            $previousDate = Carbon::parse($previousAppointment->app_date);

            // Calculate the difference
            $dateDifference = $latestDate->diffInDays($previousDate); // Difference in days
            if ($feesFrequency <= $dateDifference) {
                return 1;
            } else {
                return 0;
            }

        } else {
            return 1;
        }
    }

    public function getOffers(array $selectedTreatmentIds = [], $selectedOffer)
    {

        if ($selectedTreatmentIds != []) {
            return TreatmentComboOffer::with('treatments')
                ->where('status', 'Y')
                ->whereDate('offer_from', '<=', date('Y-m-d'))
                ->whereDate('offer_to', '>=', date('Y-m-d'))
                ->get()
                ->filter(function ($combOffer) use ($selectedTreatmentIds) {
                    // Get treatment IDs for the combo offer
                    $comboOfferTreatmentIds = $combOffer->treatments->pluck('id')->toArray();

                    // Check if all treatment IDs in the combo offer are present in the selected treatments
                    return empty(array_diff($comboOfferTreatmentIds, $selectedTreatmentIds));
                })
                ->mapWithKeys(function ($combOffer) use ($selectedOffer) {
                    return [
                        $combOffer->id => [
                            'id' => $combOffer->id,
                            'selected' => $combOffer->id == $selectedOffer ? 1 : 0,
                            'treatment' => $combOffer->treatments->pluck('treat_name')->implode(', '),
                            'treatment_ids' => $combOffer->treatments->pluck('id')->toArray(),
                            'cost' => number_format((float) $combOffer->treatments->sum('treat_cost'), 3, '.', ','),
                            'offer' => number_format((float) $combOffer->offer_amount, 3, '.', ','),
                        ]
                    ];
                });
            } else {
                return [];
            }
    }

    public function savePatientDetailBilling($billingId, $request, $index)
    {
        $patientDetailBilling = new PatientDetailBilling();
        $patientDetailBilling->billing_id = $billingId;
        $treatmentType = trim(strtolower($request->input('treatmentType' . $index)));
        if ($treatmentType == 'treatment') {
            $patientDetailBilling->treatment_id = $request->input('treatmentId' . $index);
        } else if ($treatmentType == 'treatmentplan') {
        
            $patientDetailBilling->plan_id = $request->input('treatmentId' . $index);
        }
    
        $patientDetailBilling->quantity = $request->input('tquantity' . $index);
        $patientDetailBilling->cost = (float) str_replace(',', '', $request->input('cost' . $index));
        $patientDetailBilling->discount = (float) $request->input('discount_percentage' . $index);
        $patientDetailBilling->amount = (float) str_replace(',', '', $request->input('subtotal' . $index));
        $patientDetailBilling->save();
    }    

    public function saveAdditionalCharges($billingId, $inputs)
    {
        $charges = [
            'treatmentReg' => ['cost' => 'regCost', 'amount' => 'regAmount'],
            'consultationFees' => ['cost' => 'consultationFeesCost', 'amount' => 'consultationFeesAmount']
        ];

        foreach ($charges as $key => $fields) {
            if (isset($inputs[$key]) && $inputs[$key] !== null) {
                $patientDetailBilling = new PatientDetailBilling();
                $patientDetailBilling->billing_id = $billingId;
                $patientDetailBilling->consultation_registration = $inputs[$key];
                $patientDetailBilling->quantity = 1;
                $patientDetailBilling->cost = (float) str_replace(',', '', $inputs[$fields['cost']]);
                $patientDetailBilling->discount = 0;
                $patientDetailBilling->amount = (float) str_replace(',', '', $inputs[$fields['amount']]);
                $patientDetailBilling->save();
            }
        }
    }

    public function previousOutstanding($appointmentId, $patientId)
    {;
        
        $previousOutStanding = 0;
        $previousBill = PatientTreatmentBilling::where('patient_id', $patientId)
            ->where('appointment_id', '<', $appointmentId)
            ->where('status', 'Y')
            ->orderBy('appointment_id', 'desc') // Order by descending to get the most recent previous appointment
            ->first(); // Get the first result which will be the closest previous appointment

        // Check if a previous appointment was founde
        if ($previousBill) {
            if ($previousBill->bill_status == PatientTreatmentBilling::PAYMENT_DONE) {
                $previousOutStanding += $previousBill->balance_due;
            }
            if ($previousBill->bill_status == PatientTreatmentBilling::BILL_GENERATED) {
                $previousOutStanding += $previousBill->amount_to_be_paid;
            }

        }
        return $previousOutStanding;
    }

    public function generateBillId()
    {
        $yearMonth = date('Ym'); // Year and Month
        $latestBill = PatientRegistrationFee::where('bill_id', 'like', $yearMonth . '%')
            ->orderBy('bill_id', 'desc')
            ->first();
        $lastBillId = $latestBill ? intval(substr($latestBill->bill_id, -4)) : 0;
        $newBillId = $yearMonth . str_pad($lastBillId + 1, 4, '0', STR_PAD_LEFT);

        return $newBillId;
    }

    public function saveIncomeReport(array $inputData)
    {
        $netPaid = $inputData['cash'] + $inputData['gpay'] + $inputData['card'];
        $machineTax = 0;
        if ($inputData['card_pay_id'] != null) {
            $cardTax = CardPay::where('id', $inputData['card_pay_id'])->pluck('service_charge_perc')->first();
            $machineTax = $inputData['card'] * ($cardTax / 100);
        }
         
        $netIncome = $netPaid - $machineTax - $inputData['balance_given'];

        $data = [
            'bill_type' => $inputData['bill_type'],
            'bill_no' => $inputData['bill_no'],
            'bill_date' => $inputData['bill_date'],
            'branch_id' => $inputData['branch_id'],
            'net_paid' => $netPaid,
            'cash' => $inputData['cash'],
            'gpay' => $inputData['gpay'],
            'card' => $inputData['card'],
            'card_pay_id' => $inputData['card_pay_id'] ?? null,
            'machine_tax' => $machineTax,
            'balance_given' => $inputData['balance_given'],
            'net_income' => $netIncome,
            'created_by' => $inputData['created_by'],
        ];

        $incomeReport = new IncomeReport();
        $incomeReport->fill($data);
        $incomeReport->save();

        return $incomeReport;
    }
}
