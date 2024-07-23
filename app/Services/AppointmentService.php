<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;
use DateTime;

class AppointmentService
{
    public function getLatestAppointment($id, $appDate, $patientId)
    {
        return Appointment::where('patient_id', $patientId)
            ->where('app_date', '<=', $appDate)
            ->count();
    }

    public function getPreviousAppointments($id, $appDate, $patientId)
    {

        // return Appointment::with([
        //     'doctor:id,name',
        //     'branch:id,clinic_address,city_id,state_id',
        //     'toothExamination.teeth:id,teeth_name,teeth_image',
        //     'toothExamination.treatment:id,treat_name',
        //     'toothExamination.treatmentStatus:id,status',
        //     'toothExamination.disease:id,name',
        // ])
        //     ->where('patient_id', $patientId)
        //     ->where('app_date', '<', $appDate)
        //     ->orderBy('app_date', 'desc')
        //     ->orderBy('app_time', 'desc')
        //     ->get();
        return Appointment::with([
            'doctor:id,name',
            'branch:id,clinic_address,city_id,state_id',
            'toothExamination' => function ($query) {
                $query->where('status', 'Y') // Filter ToothExamination records by status
                    ->with([
                        'teeth:id,teeth_name,teeth_image',
                        'treatment:id,treat_name',
                        'treatmentStatus:id,status',
                        'disease:id,name',
                    ]);
            },
        ])
            ->where('patient_id', $patientId)
            ->where('app_date', '<', $appDate)
            ->orderBy('app_date', 'desc')
            ->orderBy('app_time', 'desc')
            ->get();
    }

    public function splitNames($name)
    {
        $split_name = explode('<br>', $name);

        return $split_name;
    }

    public function generateUniqueAppointmentId()
    {
        // Get the current year and month in the format 'Ym'
        $yearMonth = Carbon::now()->format('Ym');
        // Count the number of appointments created in the current month
        $appointmentCount = Appointment::whereYear('app_date', Carbon::now()->year)
            ->whereMonth('app_date', Carbon::now()->month)
            ->count();
        // Increment the count by 1
        $newAppointmentNumber = $appointmentCount + 1;

        // Concatenate the year, month, and the incremented count to form the appointment ID
        $appId = 'APP'.$yearMonth.str_pad($newAppointmentNumber, 4, '0', STR_PAD_LEFT);
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

    public function calculateAge($dob)
    {
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
            $ageString .= $years.' year';
            if ($years > 1) {
                $ageString .= 's';
            }
            $ageString .= ' ';
        }
        if ($months > 0) {
            $ageString .= $months.' month';
            if ($months > 1) {
                $ageString .= 's';
            }
            $ageString .= ' ';
        }
        if ($days > 0) {
            $ageString .= $days.' day';
            if ($days > 1) {
                $ageString .= 's';
            }
        }

        return $ageString;
    }

    // Other common methods can be added here
}
