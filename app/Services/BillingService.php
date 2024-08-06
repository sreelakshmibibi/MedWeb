<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\MenuItem;
use App\Models\ToothExamination;
use App\Models\TreatmentComboOffer;
use Carbon\Carbon;
use DateTime;

class BillingService
{
    public function individualTreatmentAmounts($id, $patient_id)
    {
        // Fetch tooth examinations with related data
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
        $totalCost = 0;


        // Aggregate data by treatment
        foreach ($toothExaminations as $toothExamination) {
        $treatment = $toothExamination->treatment;
        $treatmentId = $treatment->id;
        $treatmentName = $treatment->treat_name;
        $treatmentCost = floatval($treatment->treat_cost); // Convert to float
        $discount_from = $treatment->discount_from;
        $discount_to = $treatment->discount_to;
        $discount_percentage = floatval($treatment->discount_percentage); // Convert to float

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

        // Initialize treatment entry if not already set
        if (!isset($individualTreatmentAmounts[$treatmentId])) {
            $individualTreatmentAmounts[$treatmentId] = [
                'treat_id' => $treatmentId,
                'treat_name' => $treatmentName,
                'cost' => $treatmentCost,
                'discount_percentage' => $discount_percentage,
                'treat_cost' => $discountCost, // Discounted cost
                'quantity' => 0,
                'subtotal' => 0,
            ];
        }

        // Increment quantity and update subtotal for this treatment
        $individualTreatmentAmounts[$treatmentId]['quantity']++;
        $individualTreatmentAmounts[$treatmentId]['subtotal'] += $discountCost;

        // Accumulate total cost
        $totalCost += $discountCost;
        }
        /* code to include */
        /* consulting fees */
        /* REgistration fees for new patient */

        return ['individualTreatmentAmounts' => $individualTreatmentAmounts, 'totalCost' => $totalCost];

    }

    public function getAppointmentCount($patient_id) 
    {
        $count = Appointment::where('patient_id', $patient_id)
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

    public function getOffers()
    {
        return TreatmentComboOffer::with('treatments')
                        ->where('status', 'Y')
                        ->whereDate('offer_from', '<=', date('Y-m-d'))
                        ->whereDate('offer_to', '>=', date('Y-m-d'))
                        ->get()
                        ->mapWithKeys(function ($combOffer) {
                            return [
                                $combOffer->id => [
                                    'id' => $combOffer->id,
                                    'treatment' => $combOffer->treatments->pluck('treat_name')->implode(', '),
                                    'cost' => number_format((float)$combOffer->treatments->sum('treat_cost'), 3, '.', ','),
                                    'offer' => number_format((float) $combOffer->offer_amount, 3, '.', ',')
                                ]
                            ];
                        });
                        
    }
}
