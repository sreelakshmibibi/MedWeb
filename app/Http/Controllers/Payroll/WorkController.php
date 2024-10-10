<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()) {
            EmployeeAttendance::create([
                'user_id' => Auth::user()->id,
                'login_date' => now()->toDateString(),
                'login_time' => Carbon::createFromFormat('H:i', now()->format('H:i')),
                'attendance_status' => EmployeeAttendance::PRESENT,
                
            ]);

            return response()->json(['message' => 'Attendance recorded successfully.']);
        } else {
            return response()->json(['error' => 'Attendance recorded unsuccessfully.']);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Assuming the user has one attendance record for the day
        $attendance = EmployeeAttendance::where('user_id', $request->user_id)
            ->where('login_date', now()->toDateString())
            ->first();

        if ($attendance) {
            $attendance->logout_date = $request->logout_date;
            $attendance->logout_time = $request->logout_time;
            
            // Calculate worked hours
            $loginTime = Carbon::createFromFormat('H:i:s', $attendance->login_time);
            $logoutTime = Carbon::createFromFormat('H:i:s', $request->logout_time);
            
            if ($logoutTime->lessThan($loginTime)) {
                // Handle this scenario (e.g., return an error message)
                return response()->json(['error' => 'Logout time cannot be earlier than login time.'], 400);
            }

            $diff = $loginTime->diff($logoutTime);
            // Check if logout time is before login time
            $workedHours = sprintf('%02d:%02d', $diff->h, $diff->i, $diff->s);

            $attendance->worked_hours = $workedHours;
            $attendance->save();

            return response()->json(['message' => 'Attendance updated successfully.']);
        }

        return response()->json(['message' => 'Attendance record not found.'], 404);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
