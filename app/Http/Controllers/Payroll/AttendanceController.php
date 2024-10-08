<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAttendance;
use App\Models\StaffProfile;
use App\Models\User;
use App\Models\LeaveApplication;
use App\Models\Holiday;
use App\Services\AttendanceService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClinicBasicDetail;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        return view('payroll.attendance.index', compact('branches', 'clinicBranchId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clinicBranchId = null;
        if (!Auth::user()->is_doctor) {
            $clinicBranchId = StaffProfile::where('user_id', Auth::id())
                ->pluck('clinic_branch_id')
                ->first();
        }
        $attendanceService = new AttendanceService();
        $usersWithAttendance = $attendanceService->getAttendance($request->date, $request->serviceBranch);
        return response()->json($usersWithAttendance);
    }

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
            $workedHours = $request->worked_hours[$index];

            // Create or update the attendance record
            EmployeeAttendance::updateOrCreate(
                [
                    'user_id' => $request->user_id[$index],
                    'login_date' => $request->selected_date, // Today's date
                    'logout_date' => $request->selected_date, // Today's date

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

    public function getMonthwiseAttendance(Request $request)
    {
        if ($request->ajax()) {
            $month = $request->input('month');
            $year = $request->input('year');
            $branch = $request->input('branch');
            $employeeId = $request->input('employee');

            $startDate = Carbon::create($year, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();

            // If a specific employee is selected
            if ($employeeId) {
                return $this->getEmployeeAttendanceData($employeeId, $startDate, $endDate, $branch);
            } else {
                // If no employee is selected, return the consolidated report
                return $this->getConsolidatedAttendanceData($startDate, $endDate, $branch);
            }
        }

        // Non-AJAX request: Load the view and pass necessary data
        $clinicBasicDetails = ClinicBasicDetail::first();
        $reportService = new ReportService();
        $branches = $reportService->getBranches();
        $years = $reportService->getYears();
        $employees = $reportService->getStaff();

        return view('payroll.attendance.monthlyReport', compact('clinicBasicDetails', 'branches', 'years', 'employees'));
    }

    /**
     * Get attendance data for a specific employee.
     */

    private function getEmployeeAttendanceData($employeeId, $startDate, $endDate, $branch)
    {
        // Get employee profile, designation, and branch
        $employeeProfile = StaffProfile::where('user_id', $employeeId)->first();
        $designation = $employeeProfile->designation ?? 'N/A';
        $branch = $employeeProfile->clinicBranch ? str_replace('<br>', ', ', $employeeProfile->clinicBranch->clinic_address) : 'N/A';

        $daysInMonth = $this->initializeDaysInMonth($startDate, $endDate);

        $today = Carbon::now()->format('Y-m-d');

        // Fetch employee attendance data
        $attendanceData = EmployeeAttendance::where('user_id', $employeeId)
            ->whereYear('login_date', $startDate->year)
            ->whereMonth('login_date', $startDate->month)
            ->get();

        // Process the attendance data
        foreach ($attendanceData as $attendance) {
            $date = $attendance->login_date;
            if ($attendance->attendance_status == EmployeeAttendance::PRESENT) {
                $daysInMonth[$date]['attendance_status'] = 'Present';
            } elseif ($attendance->attendance_status == EmployeeAttendance::ON_LEAVE) {
                $daysInMonth[$date]['attendance_status'] = 'On Leave';
            }
        }

        // Fetch employee leave data
        $leaveData = LeaveApplication::with('leaveType')
            ->where('user_id', $employeeId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('leave_from', [$startDate, $endDate])
                    ->orWhereBetween('leave_to', [$startDate, $endDate]);
            })
            ->get();

        foreach ($leaveData as $leave) {
            $leaveStart = Carbon::parse($leave->leave_from);
            $leaveEnd = Carbon::parse($leave->leave_to);

            for ($date = $leaveStart->copy(); $date <= $leaveEnd; $date->addDay()) {
                $formattedDate = $date->format('Y-m-d');
                if (isset($daysInMonth[$formattedDate])) {
                    $daysInMonth[$formattedDate]['attendance_status'] = 'On Leave';
                    $daysInMonth[$formattedDate]['leave_status'] = $this->getLeaveStatus($leave->leave_status);
                    $daysInMonth[$formattedDate]['leave_type'] = $leave->leaveType->type ?? 'Unknown';
                }
            }
        }

        // Fetch holidays
        $holidays = Holiday::whereBetween('holiday_on', [$startDate, $endDate])->get();
        $branchId = $employeeProfile->clinicBranch->id ?? null;

        // Filter holidays based on employee branch
        $applicableHolidays = $holidays->filter(function ($holiday) use ($branchId) {
            $holidayBranches = json_decode($holiday->branches);
            // If branches are not set, or null is allowed, or the employee branch is included
            return is_null($holidayBranches) || (is_array($holidayBranches) && in_array(null, $holidayBranches)) || in_array($branchId, $holidayBranches);
        });

        // Process applicable holidays
        foreach ($applicableHolidays as $holiday) {
            $holidayDate = $holiday->holiday_on;
            if (isset($daysInMonth[$holidayDate])) {
                $daysInMonth[$holidayDate]['is_working_day'] = false;
                $daysInMonth[$holidayDate]['attendance_status'] = 'Holiday';
            }
        }

        // Prepare data for DataTables
        $attendanceRecords = [];
        foreach ($daysInMonth as $date => $data) {
            // Skip future dates or set a relevant status
            if ($date > $today) {
                //$data['attendance_status'] = 'Future';
                continue;
            }

            $attendanceRecords[] = [
                'name' => str_replace('<br>', ' ', $employeeProfile->user->name),
                'designation' => $designation,
                'branch' => $branch,
                'date' => $date,
                'attendanceStatus' => $data['attendance_status'],
                'leaveStatus' => $data['leave_status'] ?? 'N/A',
                'leaveType' => $data['leave_type'] ?? 'N/A',
            ];
        }

        return DataTables::of($attendanceRecords)
            ->addIndexColumn()
            ->make(true);
    }


    /**
     * Get consolidated attendance data for all employees in the selected month.
     */
    private function getConsolidatedAttendanceData($startDate, $endDate, $branch)
    {
        $today = Carbon::today();

        // Check if the selected month is entirely in the future (both startDate and endDate are in the future)
        if ($startDate->gt($today)) {
            // Return an empty DataTables response if the selected month is in the future
            return DataTables::of([])->make(true);
        }

        // Adjust endDate if it's in the future (so it doesn't consider future dates)
        if ($endDate->gt($today)) {
            $endDate = $today;
        }

        // Fetch employees as before
        $employees = User::with(['staffProfile', 'staffProfile.clinicBranch'])
            ->when($branch, function ($query, $branchId) {
                $query->whereHas('staffProfile.clinicBranch', function ($query) use ($branchId) {
                    $query->where('id', $branchId);
                });
            })
            ->get();

        // Fetch holidays in the adjusted date range
        $holidays = Holiday::whereBetween('holiday_on', [$startDate, $endDate])->get();

        $attendanceSummary = [];

        foreach ($employees as $user) {
            $staffProfile = $user->staffProfile;

            if (!$staffProfile) {
                continue; // Skip if no staff profile
            }

            $employeeId = $user->id;
            $branchId = $staffProfile->clinicBranch->id ?? null;

            // Calculate total working days, ensure it doesn't go negative
            $totalWorkingDays = max(0, floor($startDate->diffInDays($endDate) + 1));

            // Filter applicable holidays
            $applicableHolidays = $holidays->filter(function ($holiday) use ($branchId) {
                $holidayBranches = json_decode($holiday->branches);
                return is_null($holidayBranches) || (is_array($holidayBranches) && in_array(null, $holidayBranches)) || in_array($branchId, $holidayBranches);
            });

            // Adjust working days by removing holidays, ensure it doesn't go negative
            $totalWorkingDays = max(0, $totalWorkingDays - $applicableHolidays->count());

            // Fetch attendance data, capped by today
            $attendanceData = EmployeeAttendance::where('user_id', $employeeId)
                ->whereBetween('login_date', [$startDate, $endDate])
                ->get();

            // Count present and absent days
            $presentDays = $attendanceData->where('attendance_status', EmployeeAttendance::PRESENT)->count();
            $absentDays = max(0, $totalWorkingDays - $presentDays);

            // Fetch leave data only for approved leaves
            $leaveData = LeaveApplication::with('leaveType')
                ->where('user_id', $employeeId)
                ->where('leave_status', LeaveApplication::Approved) // Only get approved leaves
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('leave_from', [$startDate, $endDate])
                        ->orWhereBetween('leave_to', [$startDate, $endDate]);
                })
                ->get();

            // Count the total approved leave days (including all leave types)
            $totalLeaveDays = $leaveData->sum(function ($leave) {
                $leaveFrom = Carbon::parse($leave->leave_from);
                $leaveTo = Carbon::parse($leave->leave_to);
                return $leaveFrom->diffInDays($leaveTo) + 1; // Ensure to sum the total days for all leave types
            });

            // Count specific types of leave
            $casualLeave = $leaveData->where('leaveType.type', 'Casual Leave')->count();
            $sickLeave = $leaveData->where('leaveType.type', 'Sick Leave')->count();

            // Update total absent days
            $totalAbsentDays = max(0, $absentDays - $totalLeaveDays);

            $attendanceSummary[] = [
                'name' => str_replace('<br>', ' ', $user->name),
                'designation' => $staffProfile ? $staffProfile->designation : 'N/A',
                'totalWorkingDays' => $totalWorkingDays,
                'presentDays' => $presentDays,
                'absentDays' => $absentDays,
                'casualLeave' => $casualLeave,
                'sickLeave' => $sickLeave,
                'totalLeave' => $totalLeaveDays,
                'totalAbsent' => $totalAbsentDays,
                'branch' => $staffProfile ? str_replace('<br>', ', ', $staffProfile->clinicBranch->clinic_address) : 'N/A',
            ];
        }

        return DataTables::of($attendanceSummary)
            ->addIndexColumn()
            ->make(true);
    }


    private function initializeDaysInMonth($startDate, $endDate)
    {
        $daysInMonth = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $daysInMonth[$date->format('Y-m-d')] = [
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->format('l'),
                'is_working_day' => true,  // Default to true
                'attendance_status' => 'Absent',  // Default to absent
                'leave_status' => 'No Leave', // Default to no leave
                'leave_type' => null, // No leave type by default
            ];
        }

        return $daysInMonth;
    }


    // Helper function to get leave status as a readable string
    private function getLeaveStatus($status)
    {
        switch ($status) {
            case LeaveApplication::Applied:
                return 'Applied';
            case LeaveApplication::Approved:
                return 'Approved';
            case LeaveApplication::Rejected:
                return 'Rejected';
            default:
                return 'Unknown';
        }
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
