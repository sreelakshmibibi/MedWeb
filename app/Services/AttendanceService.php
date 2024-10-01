<?php

namespace App\Services;

use App\Models\EmployeeAttendance;
use App\Models\User;

class AttendanceService
{
    public function getAttendance($selectedDate, $clinicBranchId = null) 
    {
        // Fetch all users with their attendance status
        $usersWithAttendanceQuery = User::with(['attendances' => function($query) use ($selectedDate) {
            $query->whereDate('login_date', $selectedDate);
        }])

        ->whereHas('staffProfile'); // Ensure users have a staff profile

        // Filter by branch if applicable
        if ($clinicBranchId) {
            $usersWithAttendanceQuery->whereHas('staffProfile', function($query) use ($clinicBranchId) {
                $query->where('clinic_branch_id', $clinicBranchId); // Filter by branch
            });
        }

        // Get users with attendance information
        $usersWithAttendance = $usersWithAttendanceQuery->get()
            ->map(function ($user) {
                // Check if there is attendance for the selected date
                $attendance = $user->attendances->first();

                return [
                    'user_id' => $user->id,
                    'name' => str_replace("<br>", " ", $user->name),
                    'attendance_status' => $attendance ? $attendance->attendance_status : null, // Return null if no attendance entry
                    'login_time' => $attendance ? $attendance->login_time : null,
                    'logout_time' => $attendance ? $attendance->logout_time : null,
                    'worked_hours' => $attendance ? $attendance->worked_hours : null,
                ];
            });

        return $usersWithAttendance;
    }
}
