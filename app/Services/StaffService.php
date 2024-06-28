<?php

namespace App\Services;

use App\Models\DoctorWorkingHour;
use App\Models\MenuItem;
use App\Models\WeekDay;
use Illuminate\Http\Request;

class StaffService
{
    public function saveDoctorAvailability(Request $request, $userId)
    {
        $existingAvailability = DoctorWorkingHour::where('user_id', $userId)->get();
        if (!empty($existingAvailability)) {
            DoctorWorkingHour::where('user_id', $userId)
                            ->update(['status' => 'N']);
        }
        $weekDays = [
            WeekDay::MONDAY,
            WeekDay::TUESDAY,
            WeekDay::WEDNESDAY,
            WeekDay::THURSDAY,
            WeekDay::FRIDAY,
            WeekDay::SATURDAY,
            WeekDay::SUNDAY
        ];

        // Get the actual number of rows (count of clinic_branch_id inputs)
        $count = $request->input('row_count') != null ? $request->input('row_count') : 1;
        $l = 0;
        for ($i = 1; $i <= $count; $i++) {
            foreach ($weekDays as $day) {
                // Construct the keys dynamically
                $fromKey = strtolower($day) . '_from' . $i;
                $toKey = strtolower($day) . '_to' . $i;
                $clinicBranchKey = 'clinic_branch_id' . $i;

                // Check if fromKey is present in request, if not continue to next iteration
                if (!$request->has($fromKey)) {
                    continue;
                }

                // Extract values from request
                $clinic_branch_id = $request->input($clinicBranchKey);
                $from_time = $request->input($fromKey);
                $to_time = $request->input($toKey);
                if ($from_time != null) {
                    // Create and save DoctorWorkingHour instance
                    $availability = new DoctorWorkingHour();
                    $availability->user_id = $userId;
                    $availability->week_day = $day;
                    $availability->clinic_branch_id = $clinic_branch_id;
                    $availability->from_time = $from_time;
                    $availability->to_time = $to_time;
                    $availability->status = 'Y';
                    $l = $availability->save();
                }
            }
        }
        return $l;
    }


    public function splitNames($name)
    {
        $split_name = explode('<br>', $name);
        return $split_name;
    }

    // Other common methods can be added here
}
