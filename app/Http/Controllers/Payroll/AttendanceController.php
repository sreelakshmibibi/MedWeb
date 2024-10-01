<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAttendance;
use App\Models\StaffProfile;
use App\Models\User;
use App\Services\AttendanceService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reportService = new ReportService();
        $branches = $reportService->getBranches();
        
        // Get the date for attendance check
        $selectedDate = date('Y-m-d');
        $clinicBranchId = null;

        // Get the clinic branch ID if the user is not a doctor
        if (!Auth::user()->is_doctor) {
            $clinicBranchId = StaffProfile::where('user_id', Auth::id())
                ->pluck('clinic_branch_id')
                ->first();
        }
        $attendanceService = new AttendanceService();
        $usersWithAttendance = $attendanceService->getAttendance($selectedDate, $clinicBranchId);
        return view('payroll.attendance.index', compact('branches', 'usersWithAttendance', 'clinicBranchId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create(Request $request)
    // {
    //     $attendanceService = new AttendanceService();
    //     $usersWithAttendance = $attendanceService->getAttendance($request->attendance_date, $request->serviceBranch);
    //     dd($usersWithAttendance);
    //     return response()->json($usersWithAttendance);
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        foreach ($request->attendance_status as $index => $status) {
            // Calculate worked hours in HH:MM:SS format
            $loginTime = new \DateTime($request->login_time[$index]);
            $logoutTime = new \DateTime($request->logout_time[$index]);
    
            // Calculate the difference
            $diff = $logoutTime->diff($loginTime);
    
            // Format worked hours
            $workedHours = sprintf('%02d:%02d:%02d', $diff->h, $diff->i, $diff->s);
    
            // Create or update the attendance record
            EmployeeAttendance::updateOrCreate(
                [
                    'user_id' => $request->user_id[$index],
                    'login_date' => date('Y-m-d'), // Today's date
                    'logout_date' => date('Y-m-d'), // Today's date

                ],
                [
                    'attendance_status' => $status,
                    'login_time' => $request->login_time[$index],
                    'logout_time' => $request->logout_time[$index],
                    'worked_hours' => $workedHours, // Store in HH:MM:SS format
                ]
            );
        }
    
        // Redirect back with a success message
        return redirect()->route('attendance')->with('success', 'Attendance saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Implement logic for showing a specific resource if needed
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Implement logic for editing a specific resource if needed
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Implement logic for updating a specific resource if needed
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Implement logic for removing a specific resource if needed
    }
}
