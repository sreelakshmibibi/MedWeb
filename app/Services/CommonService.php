<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\MenuItem;
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

    public function checkexisting($doctorId, $appDate, $appTime, $clinicBranchId)
    {
      return Appointment::where('doctor_id', $doctorId)
                ->where('app_date', $appDate)
                ->where('app_time', $appTime)
                ->where('app_branch', $clinicBranchId)
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
    
    

    // Other common methods can be added here
}
