<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAttendance;
use App\Models\StaffProfile;
use App\Models\User;
use App\Models\LeaveApplication;
use App\Models\ClinicBranch;
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

    public function store(Request $request)
{
    foreach ($request->attendance_status as $index => $status) {
        // Check if login_time and logout_time are set
        $loginTime = !empty($request->login_time[$index]) ? new \DateTime($request->login_time[$index]) : null;
        $logoutTime = !empty($request->logout_time[$index]) ? new \DateTime($request->logout_time[$index]) : null;

        // Initialize worked hours
        $workedHours = '00:00:00';

        if ($loginTime && $logoutTime) {
            // Calculate the difference only if both times are valid
            $diff = $logoutTime->diff($loginTime);
            // Format worked hours in HH:MM:SS
            $workedHours = sprintf('%02d:%02d:%02d', $diff->h, $diff->i, $diff->s);
        }

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

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     foreach ($request->attendance_status as $index => $status) {
    //         // Calculate worked hours in HH:MM:SS format
    //         $loginTime = new \DateTime($request->login_time[$index]);
    //         $logoutTime = new \DateTime($request->logout_time[$index]);

    //         // Calculate the difference
    //         $diff = $logoutTime->diff($loginTime);

    //         // Format worked hours
    //         $workedHours = $request->worked_hours[$index];

    //         // Create or update the attendance record
    //         EmployeeAttendance::updateOrCreate(
    //             [
    //                 'user_id' => $request->user_id[$index],
    //                 'login_date' => $request->selected_date, // Today's date
    //                 'logout_date' => $request->selected_date, // Today's date

    //             ],
    //             [
    //                 'attendance_status' => $status,
    //                 'login_time' => $request->login_time[$index],
    //                 'logout_time' => $request->logout_time[$index],
    //                 'worked_hours' => $workedHours ? $workedHours : '00:00:00', // Store in HH:MM:SS format
    //             ]
    //         );
    //     }

    //     // Redirect back with a success message
    //     return redirect()->route('attendance')->with('success', 'Attendance saved successfully.');
    // }

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

        $clinicBasicDetails = ClinicBasicDetail::first();
        $reportService = new ReportService();
        $branches = $reportService->getBranches();
        $years = $reportService->getYears();
        $employees = $reportService->getAttendanceStaff();

        return view('payroll.attendance.monthlyReport', compact('clinicBasicDetails', 'branches', 'years', 'employees'));
    }

    /**
     * Get attendance data for a specific employee.
     */
    private function getEmployeeAttendanceData($employeeId, $startDate, $endDate, $branch)
    {
        // Get employee profile, designation, and branch
        $employeeProfile = StaffProfile::with('user', 'clinicBranch')
            ->where('user_id', $employeeId)
            ->first();
        $designation = $employeeProfile->designation ?? 'N/A';


        $branchIds = explode(',', $employeeProfile->clinic_branch_id);
        $branches = implode(', ', array_map(function ($branchId) {
            $branch = ClinicBranch::find($branchId);
            return $branch ? str_replace('<br>', ' ', $branch->clinic_address) : 'Unknown';
        }, $branchIds));

        $isDoctor = $employeeProfile->user->is_doctor;
        $daysInMonth = $this->initializeDaysInMonth($startDate, $endDate);

        $today = Carbon::now()->format('Y-m-d');

        // Fetch employee attendance data
        $attendanceData = EmployeeAttendance::where('user_id', $employeeId)
            ->whereYear('login_date', $startDate->year)
            ->whereMonth('login_date', $startDate->month)
            ->get();


        foreach ($attendanceData as $attendance) {
            $date = $attendance->login_date;
            if ($attendance->attendance_status == EmployeeAttendance::PRESENT) {
                $daysInMonth[$date]['attendance_status'] = 'Present';
            } elseif ($attendance->attendance_status == EmployeeAttendance::ON_LEAVE) {
                $daysInMonth[$date]['attendance_status'] = 'Absent';
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
                    $daysInMonth[$formattedDate]['leave_status'] = $leave->leaveType->type . ' (' . $this->getLeaveStatus($leave->leave_status) . ')';
                }
            }
        }

        // Fetch holidays
        $holidays = Holiday::whereBetween('holiday_on', [$startDate, $endDate])->get();

        $employeeBranchIds = explode(',', $employeeProfile->clinic_branch_id);

        foreach ($daysInMonth as $date => $data) {
            // Check if the date is a Sunday
            if (Carbon::parse($date)->isSunday()) {
                $daysInMonth[$date]['is_working_day'] = false; // Mark it as not a working day
                $daysInMonth[$date]['attendance_status'] = 'Holiday'; // Set attendance status to 'Holiday'
                continue; // Skip to the next date
            }
            
            // Flag to track if all branches the employee works in have a holiday
            $allBranchesOnHoliday = true;

            foreach ($employeeBranchIds as $branchId) {

                $applicableHoliday = $holidays->filter(function ($holiday) use ($branchId, $date) {
                    $holidayBranches = json_decode($holiday->branches, true);

                    return (is_null($holidayBranches) || in_array(null, $holidayBranches) || in_array($branchId, $holidayBranches))
                        && $holiday->holiday_on == $date;
                })->first();
                // If no holiday is found for this branch, it's not a holiday for this employee for this date
                if (!$applicableHoliday) {
                    $allBranchesOnHoliday = false;
                    break;
                }
            }

            // If all branches the employee works in have a holiday on this date, mark it as a holiday
            if ($allBranchesOnHoliday && isset($daysInMonth[$date])) {
                $daysInMonth[$date]['is_working_day'] = false;
                $daysInMonth[$date]['attendance_status'] = 'Holiday';
            }
        }

        $attendanceRecords = [];
        foreach ($daysInMonth as $date => $data) {

            if ($date > $today) {
                continue;
            }

            $leaveStatus = $data['attendance_status'] == 'Present' ? '-' : ($data['leave_status'] ?? '');

            $attendanceStatus = $this->formatAttendanceStatusAsLabel($data['attendance_status']);

            $attendanceRecords[] = [
                'name' => str_replace('<br>', ' ', $employeeProfile->user->name),
                'designation' => $designation,
                'branch' => $branches,
                'date' => $date,
                'attendanceStatus' => $attendanceStatus,
                'leaveStatus' => $leaveStatus,
            ];
        }

        return DataTables::of($attendanceRecords)
            ->addIndexColumn()
            ->rawColumns(['attendanceStatus'])
            ->make(true);
    }

    /**
     * Format attendance status.
     */
    private function formatAttendanceStatusAsLabel($status)
    {
        switch ($status) {
            case 'Present':
                return '<span class="btn d-block btn-xs badge badge-success">Present</span>';
            case 'On Leave':
                return '<span class="btn d-block btn-xs badge badge-warning">On Leave</span>';
            case 'Holiday':
                return '<span class="btn d-block btn-xs badge badge-gray">Holiday</span>';
            default:
                return '<span class="btn d-block btn-xs badge badge-danger">' . $status . '</span>';
        }
    }

    /**
     * Get consolidated attendance data for all employees in the selected month.
     */
    private function getConsolidatedAttendanceData($startDate, $endDate, $branch)
    {
        $today = Carbon::today();
        if ($startDate->gt($today)) {
            return DataTables::of([])->make(true);
        }

        if ($endDate->gt($today)) {
            $endDate = $today;
        }

        // Fetch employees
        $employees = User::with(['staffProfile', 'staffProfile.clinicBranch'])
            ->when($branch, function ($query, $branchId) {
                $query->whereHas('staffProfile', function ($query) use ($branchId) {
                    $query->whereRaw("FIND_IN_SET(?, clinic_branch_id)", [$branchId]);
                });
            })
            ->get();

        // Fetch holidays
        $holidays = Holiday::whereBetween('holiday_on', [$startDate, $endDate])->get();

        $attendanceSummary = [];

        foreach ($employees as $user) {
            $staffProfile = $user->staffProfile;

            if (!$staffProfile) {
                continue; // Skip if no staff profile
            }

            $employeeId = $user->id;

            // Get the employee's branch IDs as an array
            $branchIds = explode(',', $staffProfile->clinic_branch_id);

            // Calculate total working days
            $totalWorkingDays = max(0, floor($startDate->diffInDays($endDate) + 1));

            // Count Sundays as holidays
            $sundayCount = 0;
            for ($date = clone $startDate; $date <= $endDate; $date->addDay()) {
                if ($date->isSunday()) {
                    $sundayCount++;
                }
            }

            // Calculate holidays that apply to all branches the employee works in
            $applicableHolidays = $holidays->filter(function ($holiday) use ($branchIds) {
                $holidayBranches = json_decode($holiday->branches, true); 

                if (is_null($holidayBranches) || in_array(null, $holidayBranches)) {
                    return true; 
                }

                return count(array_intersect($branchIds, $holidayBranches)) === count($branchIds);
            });

            // Subtract the number of holidays from total working days
            $holidayCount = $applicableHolidays->count() + $sundayCount; // Include Sundays;
            $totalWorkingDays = max(0, $totalWorkingDays - $holidayCount);

            // Fetch attendance data
            $attendanceData = EmployeeAttendance::where('user_id', $employeeId)
                ->whereBetween('login_date', [$startDate, $endDate])
                ->get();

            // Count present and absent days
            $presentDays = $attendanceData->where('attendance_status', EmployeeAttendance::PRESENT)->count();
            $absentDays = max(0, $totalWorkingDays - $presentDays);

            // Fetch leave data for approved leaves
            $leaveData = LeaveApplication::with('leaveType')
                ->where('user_id', $employeeId)
                ->where('leave_status', LeaveApplication::Approved) // Only get approved leaves
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('leave_from', [$startDate, $endDate])
                        ->orWhereBetween('leave_to', [$startDate, $endDate]);
                })
                ->get();

            // Calculate total leave days
            $totalLeaveDays = $leaveData->sum(function ($leave) {
                $leaveFrom = Carbon::parse($leave->leave_from);
                $leaveTo = Carbon::parse($leave->leave_to);
                return $leaveFrom->diffInDays($leaveTo) + 1;
            });

            // Count specific types of leave
            $casualLeave = $leaveData->where('leaveType.type', 'Casual Leave')->count();
            $sickLeave = $leaveData->where('leaveType.type', 'Sick Leave')->count();
            $compensatoryLeave = $leaveData->where('leaveType.type', 'Compensatory Leave')->count();

            // Update total absent days after accounting for approved leaves
            $totalAbsentDays = max(0, $absentDays - $totalLeaveDays);

            $attendanceSummary[] = [
                'name' => str_replace('<br>', ' ', $user->name),
                'designation' => $staffProfile ? $staffProfile->designation : 'N/A',
                'totalWorkingDays' => $totalWorkingDays,
                'presentDays' => $presentDays,
                'absentDays' => $absentDays,
                'casualLeave' => $casualLeave,
                'sickLeave' => $sickLeave,
                'compensatoryLeave' => $compensatoryLeave,
                'totalLeave' => $totalLeaveDays,
                'totalAbsent' => $totalAbsentDays,
                'branch' => $staffProfile ? implode(', ', array_map(function ($branchId) {
                    $branchAddress = ClinicBranch::find($branchId)->clinic_address ?? 'Unknown';
                    return str_replace('<br>', ' ', $branchAddress);
                }, $branchIds)) : 'N/A',
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
                'is_working_day' => true,
                'attendance_status' => 'Absent',
                'leave_status' => '-',
                'leave_type' => null,
            ];
        }

        return $daysInMonth;
    }


    // function to get leave status as a readable string
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
