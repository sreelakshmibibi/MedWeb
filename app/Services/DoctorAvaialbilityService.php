<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\DoctorWorkingHour;
use App\Models\LeaveApplication;
use App\Models\StaffProfile;
use App\Models\WeekDay;
use Carbon\Carbon;

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

    public function getTodayWorkingDoctors($branchId, $weekday, $date, $time = null)
    {
        // Start with a query for working hours based on weekday and status
        $query = DoctorWorkingHour::where('week_day', $weekday)
            ->where('status', 'Y');
    
        // Apply branch filter if provided
        if ($branchId) {
            $query->where('clinic_branch_id', $branchId);
        }
    
        // Get the list of working doctors
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
                ->where('leave_status', '<>', LeaveApplication::Rejected)
                ->exists();
    
            return !$isOnLeave;
        });
        if ($time != null) {
             // Filter out doctors based on their working hours for the given time
            $workingDoctors = $workingDoctors->filter(function ($workingHour) use ($time, $branchId) {
                $doctorId = $workingHour->user_id;
        
                return DoctorWorkingHour::where('user_id', $doctorId)
                    ->where('week_day', $workingHour->week_day)
                    ->where('status', 'Y') // Assuming 'Y' indicates the doctor is available
                    ->where('clinic_branch_id', $branchId)
                    ->whereTime('from_time', '<=', $time)
                    ->whereTime('to_time', '>=', $time)
                    ->exists();
        });
    
        }
       
        // Add appointment counts to each doctor
        $workingDoctors->each(function ($doctor) {
            $doctor->appointments_count = Appointment::where('doctor_id', $doctor->user_id)
                ->whereDate('app_date', today()) // Assuming appointments are filtered by today’s date
                ->count();
    
            $doctor->appointments_completed_count = Appointment::where('doctor_id', $doctor->user_id)
                ->where('app_status', 5) // Assuming '5' indicates completed status
                ->whereDate('app_date', today()) // Assuming appointments are filtered by today’s date
                ->count();
        });
    
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

    public function getAllDoctors()
    {
        return StaffProfile::with('user')
        ->whereHas('user', function ($query) {
            $query->where('is_doctor', 1);
        })
        ->get();
    }

    function calculateLeavesTaken($userId)
    {
        // Define the start and end dates of the financial year
        $financialYearStart = Carbon::create(date('Y'), 4, 1); // April 1st of the current year
        $financialYearEnd = Carbon::create(date('Y') + 1, 3, 31); // March 31st of the next year
        $startMonthYear = $financialYearStart->format('F Y'); // Example: "April 2024"
        $endMonthYear = $financialYearEnd->format('F Y'); // Example: "March 2025"
    
        // Query leave applications for the user within the financial year
        $leaves = LeaveApplication::where('user_id', $userId)
            ->whereBetween('leave_from', [$financialYearStart, $financialYearEnd])
            ->orWhereBetween('leave_to', [$financialYearStart, $financialYearEnd])
            ->where('leave_status', 2) // Only include approved leaves
            ->get();

        $totalLeaves = 0;

        foreach ($leaves as $leave) {
            // Calculate the number of days for each leave record
            $start = Carbon::parse($leave->leave_from);
            $end = Carbon::parse($leave->leave_to);
            $totalLeaves += $end->diffInDays($start) + 1; // +1 to include the end day
        }

        return $totalLeaves. ' ( ' . $startMonthYear . ' - ' . $endMonthYear . ' ) ';
    }
}
