<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\MenuItem;
use App\Models\PatientTreatmentBilling;
use App\Models\TrialLogin;
use Carbon\Carbon;
use DateTime;

class TrialService
{
    public function isTrialValid()
    {
        $trialPeriod = TrialLogin::first();
        $currentDate = date('Y-m-d');

        // Check if trial period is null or if the current date is outside the range
        if (is_null($trialPeriod) || 
            $currentDate < $trialPeriod->from_date || 
            $currentDate > $trialPeriod->to_date) {
            
            return false; // Trial is not valid
        }

        return true; // Trial is valid
    }
}
