<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\MenuItem;
use App\Models\PatientTreatmentBilling;
use Carbon\Carbon;
use DateTime;

class CommonService
{
    public function getMenuItems()
    {
        return MenuItem::whereNull('parent_id')
            ->where('status', 'Y')
            ->with(['children' => function ($query) {
                $query->where('status', 'Y')
                    ->orderBy('order_no');
            }])
            ->orderBy('order_no')
            ->get();
    }


    public function splitNames($name)
    {
        $split_name = explode('<br>', $name);
        return $split_name;
    }

    public function generateUniqueAppointmentId($appDate)
    {
        // Parse the provided appointment date
        $appointmentDate = Carbon::parse($appDate);

        // Get the year and month from the provided appointment date
        $year = $appointmentDate->format('Y');
        $month = $appointmentDate->format('m');

        // Count the number of appointments created in the current month
        $appointmentCount = Appointment::whereYear('app_date', $year)
            ->whereMonth('app_date', $month)
            ->count();
        // Increment the count by 1
        $newAppointmentNumber = $appointmentCount + 1;

        // Concatenate the year, month, and the incremented count to form the appointment ID
        $appId = 'APP' . $year . $month . str_pad($newAppointmentNumber, 4, '0', STR_PAD_LEFT);
        //Log::info('$appId: '.$appId);

        return $appId;
    }

    public function generateTokenNo($doctorId, $appDate)
    {
        $maxToken = Appointment::where('doctor_id', $doctorId)
        ->whereDate('app_date', $appDate)
        ->max('token_no');
        $tokenNo = $maxToken ? $maxToken + 1 : 1;
        return $tokenNo;
    }

    public function checkexisting($doctorId, $appDate, $appTime, $clinicBranchId, $patientId)
    {
      return Appointment::where('doctor_id', $doctorId)
                ->where('app_date', $appDate)
                ->where('app_time', $appTime)
                ->where('app_branch', $clinicBranchId)
                ->where('patient_id', $patientId)
                ->exists();
    }

    function calculateAge($dob) {
        // Calculate the difference between the DOB and current date
        $dob = new DateTime($dob);
        $now = new DateTime();
        $diff = $now->diff($dob);
    
        // Extract years, months, and days from the difference
        $years = $diff->y;
        $months = $diff->m;
        $days = $diff->d;
    
        // Format the age string
        $ageString = '';
        if ($years > 0) {
            $ageString .= $years . ' year';
            if ($years > 1) {
                $ageString .= 's';
            }
            $ageString .= ' ';
        }
        if ($months > 0) {
            $ageString .= $months . ' month';
            if ($months > 1) {
                $ageString .= 's';
            }
            $ageString .= ' ';
        }
        if ($days > 0) {
            $ageString .= $days . ' day';
            if ($days > 1) {
                $ageString .= 's';
            }
        }
    
        return $ageString;
    }
    
    public function generateUniqueBillingId()
    {
        $appointmentDate = Carbon::parse(date('Y-m-d'));

        // Get the year and month from the provided appointment date
        $year = $appointmentDate->format('y'); // Use 'Y' for four-digit year
        $month = $appointmentDate->format('m'); // 'm' for two-digit month
        $day = $appointmentDate->format('d'); // 'm' for two-digit month
        
        // Count the number of appointments created in the current month and year
        $appointmentCount = PatientTreatmentBilling::whereYear('created_at', $appointmentDate->format('Y'))
            ->whereMonth('created_at', $month)
            ->count();
            
        // Increment the count by 1
        $newAppointmentNumber = $appointmentCount + 1;

        // Concatenate the year, month, and the incremented count to form the appointment ID
        $appId = $year . $month . $day. str_pad($newAppointmentNumber, 3, '0', STR_PAD_LEFT);
        //Log::info('$appId: '.$appId);

        return $appId;
    }

    public function numberToWords($number) {
        $dictionary = [
            0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five',
            6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten', 11 => 'eleven',
            12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen',
            17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety',
            100 => 'hundred', 1000 => 'thousand', 1000000 => 'million', 1000000000 => 'billion'
        ];
    
        if (!is_numeric($number)) return false;
    
        $number = (float)$number;
    
        if ($number < 0) return 'negative ' . $this->numberToWords(abs($number));
    
        $string = '';
        $fraction = '';
    
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
            $fraction = rtrim($fraction, '0'); // Remove trailing zeros
        }
    
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = floor($number / 10) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) $string .= '-' . $dictionary[$units];
                break;
            case $number < 1000:
                $hundreds = floor($number / 100);
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' hundred';
                if ($remainder) $string .= ' and ' . $this->numberToWords($remainder);
                break;
            case $number < 1000000:
                $thousands = floor($number / 1000);
                $remainder = $number % 1000;
                $string = $this->numberToWords($thousands) . ' thousand';
                if ($remainder) $string .= ' ' . $this->numberToWords($remainder);
                break;
            case $number < 1000000000:
                $millions = floor($number / 1000000);
                $remainder = $number % 1000000;
                $string = $this->numberToWords($millions) . ' million';
                if ($remainder) $string .= ' ' . $this->numberToWords($remainder);
                break;
            default:
                $billions = floor($number / 1000000000);
                $remainder = $number % 1000000000;
                $string = $this->numberToWords($billions) . ' billion';
                if ($remainder) $string .= ' ' . $this->numberToWords($remainder);
                break;
        }
    
        if ($fraction) {
            $string .= ' point';
            foreach (str_split($fraction) as $digit) {
                $string .= ' ' . $dictionary[$digit];
            }
        }
    
        return $string;
    }
    


    // Other common methods can be added here
}
