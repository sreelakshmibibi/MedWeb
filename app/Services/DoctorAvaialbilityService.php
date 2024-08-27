<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\DoctorWorkingHour;
use App\Models\LeaveApplication;
use App\Models\WeekDay;

class DoctorAvaialbilityService
{
    public function availableBranchAndTimings($userId)
    {
        $clinicBranches = DoctorWorkingHour::select('clinic_branch_id')
            ->where('user_id', $userId)
            ->where('status', 'Y')
            ->groupBy('clinic_branch_id')
            ->get();

        $availableBranches = [];

        foreach ($clinicBranches as $clinicBranch) {
            $timings = DoctorWorkingHour::where('user_id', $userId)
                ->where('clinic_branch_id', $clinicBranch->clinic_branch_id)
                ->where('status', 'Y')
                ->get();

            $availableTimings = [
                'clinic_branch_id' => $clinicBranch->clinic_branch_id,
                'timings' => [],
            ];

            foreach ($timings as $timing) {
                $day = $timing->week_day;

                // Assign timings based on the weekday
                switch ($day) {
                    case WeekDay::SUNDAY:
                        $availableTimings['timings']['sunday_from'] = $timing->from_time;
                        $availableTimings['timings']['sunday_to'] = $timing->to_time;
                        break;
                    case WeekDay::MONDAY:
                        $availableTimings['timings']['monday_from'] = $timing->from_time;
                        $availableTimings['timings']['monday_to'] = $timing->to_time;
                        break;
                    case WeekDay::TUESDAY:
                        $availableTimings['timings']['tuesday_from'] = $timing->from_time;
                        $availableTimings['timings']['tuesday_to'] = $timing->to_time;
                        break;
                    case WeekDay::WEDNESDAY:
                        $availableTimings['timings']['wednesday_from'] = $timing->from_time;
                        $availableTimings['timings']['wednesday_to'] = $timing->to_time;
                        break;
                    case WeekDay::THURSDAY:
                        $availableTimings['timings']['thursday_from'] = $timing->from_time;
                        $availableTimings['timings']['thursday_to'] = $timing->to_time;
                        break;
                    case WeekDay::FRIDAY:
                        $availableTimings['timings']['friday_from'] = $timing->from_time;
                        $availableTimings['timings']['friday_to'] = $timing->to_time;
                        break;
                    case WeekDay::SATURDAY:
                        $availableTimings['timings']['saturday_from'] = $timing->from_time;
                        $availableTimings['timings']['saturday_to'] = $timing->to_time;
                        break;
                    default:
                        // Handle any other cases or errors
                        break;
                }
            }

            // Add branch timings to the list
            $availableBranches[] = $availableTimings;
        }

        // Now $availableBranches contains all branches with their respective timings
        return $availableBranches;
    }

    public function getTodayWorkingDoctors($branchId, $weekday, $date)
    {

        $query = DoctorWorkingHour::where('week_day', $weekday)
            ->where('status', 'Y');

        if ($branchId) {
            $query->where('clinic_branch_id', $branchId);
        }

        
        // Count appointments for each working doctor
        $workingDoctors = $query->with('user')->get();
        // Filter out doctors who are on leave
        $workingDoctors = $workingDoctors->filter(function ($workingHour) use ($date) {
            $doctorId = $workingHour->user_id;
    
            // Check if the doctor is on leave for the given date
            $isOnLeave = LeaveApplication::where('user_id', $doctorId)
                ->where(function ($query) use ($date) {
                    $query->whereBetween('leave_from', [$date, $date])
                          ->orWhereBetween('leave_to', [$date, $date])
                          ->orWhere(function ($query) use ($date) {
                              $query->where('leave_from', '<=', $date)
                                    ->where('leave_to', '>=', $date);
                          });
                })
                ->whereNot('leave_status', LeaveApplication::Rejected)
                ->exists();
    
            return !$isOnLeave;
        });
    
        foreach ($workingDoctors as $doctor) {
            $doctor->appointments_count = Appointment::where('doctor_id', $doctor->user_id)
                ->whereDate('app_date', today()) // Assuming appointments are filtered by today’s date
                ->count();
            $doctor->appointments_completed_count = Appointment::where('doctor_id', $doctor->user_id)
                ->where('app_status', 5)
                ->whereDate('app_date', today()) // Assuming appointments are filtered by today’s date
                ->count();
        }

        return $workingDoctors;
    }

    public function getExistingAppointments($branchId, $appDate, $doctorId)
    {
        $query = Appointment::where('status', 'Y')
            ->where('app_status', AppointmentStatus::SCHEDULED);
        if ($branchId) {
            $query->where('app_branch', $branchId);
        }
        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }
        if ($appDate) {
            $query->where('app_date', $appDate);
        }

        return $query->get('app_time');

    }

    public function checkAllocatedAppointments($branchId, $appDate, $doctorId, $appTime)
    {
        // Start building the query with basic conditions
        $query = Appointment::where('status', 'Y')
            ->where('app_status', AppointmentStatus::SCHEDULED);

        // Apply optional filters based on parameters
        if ($branchId) {
            $query->where('app_branch', $branchId);
        }
        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }
        if ($appDate) {
            $query->whereDate('app_date', $appDate); // Assuming app_date is a date field
        }
        if ($appTime) {
            $query->where('app_time', $appTime); // Assuming app_time is a time field
        }

        // Execute the query and return the result
        $appointments = $query->get(['app_time']); // Fetch only app_time field

        return $appointments;
    }

    public function checkAppointmentDate($branchId, $appDate, $doctorId, $patientId)
    {
        $query = Appointment::where('status', 'Y')
            ->where('app_status', AppointmentStatus::SCHEDULED);

        // Apply optional filters based on parameters
        if ($branchId) {
            $query->where('app_branch', $branchId);
        }
        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }
        if ($appDate) {
            $query->whereDate('app_date', $appDate); // Assuming app_date is a date field
        }
        if ($patientId) {
            $query->where('patient_id', $patientId); // Assuming app_date is a date field
        }
        // Execute the query and return the result
        $appointments = $query->exists(); // Use exists() for a boolean check

        return $appointments ? 1 : 0;

    }

    public function isDoctorAvailable($branchId, $doctorId, $weekDay, $time)
    {
        $workingHour = DoctorWorkingHour::where('user_id', $doctorId)
            ->where('week_day', $weekDay)
            ->where('status', 'Y') // Assuming 'Y' indicates the doctor is available
            ->where('clinic_branch_id', $branchId)
            ->whereTime('from_time', '<=', $time)
            ->whereTime('to_time', '>=', $time)
            ->first();

        return !is_null($workingHour);
    }
}
