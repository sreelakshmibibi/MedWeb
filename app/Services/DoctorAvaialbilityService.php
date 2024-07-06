<?php

namespace App\Services;

use App\Models\DoctorWorkingHour;
use App\Models\MenuItem;
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
                'timings' => []
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

    public function getTodayWorkingDoctors($branchId, $weekday)
    {

        $query = DoctorWorkingHour::where('week_day', $weekday)
            ->where('status', 'Y');

        if ($branchId) {
            $query->where('clinic_branch_id', $branchId);
        }

        return $query->with('user')->get();
    }



    
}
