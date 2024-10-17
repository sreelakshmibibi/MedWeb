<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaffProfile;
use App\Models\ClinicBranch;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;
use App\Models\EmployeeType;
use App\Models\PayHead;
use App\Models\EmployeeSalary;
use App\Models\Salary;
use App\Models\Holiday;
use App\Models\SalaryAdvance;
use App\Models\EmployeeMonthlySalary;
use App\Models\EmployeeAttendance;
use App\Models\LeaveApplication;
use App\Http\Requests\payroll\MonthlySalaryRequest;
use Illuminate\Support\Facades\DB;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ClinicBasicDetail;
use App\Models\Department;
use App\Services\ReportService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class MonthlySalaryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:monthly salary view', ['only' => ['index', 'show']]);
        $this->middleware('permission:monthly salary create', ['only' => ['store']]);
        $this->middleware('permission:monthly salary delete', ['only' => ['destroy']]);
        $this->middleware('permission:monthly salary edit', ['only' => ['edit', 'update']]);

    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $month = $request->input('month');
            $year = $request->input('year');
            $branch = $request->input('branch');
            $employeeId = $request->input('employee');

            // If employee ID is passed, fetch specific employee salary data
            if ($employeeId) {
                return $this->getEmployeeSalaryData($employeeId, $month, $year);
            } else {

                // Fetch staff excluding visiting doctors
                $staff = StaffProfile::with(['clinicBranch', 'user'])
                    ->when($branch, function ($query, $branchId) {
                        return $query->whereRaw("FIND_IN_SET(?, clinic_branch_id)", [$branchId]);
                    })
                    ->where('status', 'Y')
                    ->where('visiting_doctor', 0)
                    ->get();

                $staff->transform(function ($staff) {
                    $branchIds = explode(',', $staff->clinic_branch_id);
                    $branches = ClinicBranch::whereIn('id', $branchIds)->pluck('clinic_address')->toArray();
                    $branchAddresses = array_map(function ($address) {
                        return str_replace('<br>', ' ', $address);
                    }, $branches);
                    $staff->branch = !empty($branchAddresses) ? implode(', ', $branchAddresses) : '-';

                    return $staff;
                });


                return DataTables::of($staff)
                    ->addIndexColumn()
                    ->addColumn('name', function ($row) {
                        return str_replace("<br>", " ", $row->user->name);
                    })
                    ->addColumn('month', function ($row) use ($month, $year) {
                        return Carbon::createFromDate($year, $month)->format('F Y'); // Example: "January 2024"
                    })
                    ->addColumn('role', function ($row) {
                        $role = '';
                        if ($row->user->is_admin) {
                            $role .= '<span class="d-block  badge badge-primary mb-1">Admin</span>';
                        }
                        if ($row->user->is_doctor) {
                            $role .= '<span class="d-block  badge badge-success mb-1">Doctor</span>';
                        }
                        if ($row->user->is_nurse) {
                            $role .= '<span class="d-block  badge badge-info mb-1">Nurse</span>';
                        }
                        if ($row->user->is_reception) {
                            $role .= '<span class="d-block  badge badge-secondary mb-1">Others</span>';
                        }
                        return $role;
                    })
                    ->addColumn('action', function ($row) use ($month, $year) {
                        $base64Id = base64_encode($row->user_id);
                        $idEncrypted = Crypt::encrypt($base64Id);
                        $salary = EmployeeMonthlySalary::where('user_id', $row->user->id)
                            ->where('month', $month)
                            ->where('year', $year)
                            ->where('status', 'Y')
                            ->first();
                        $btn = '';
                        $employeeBasicSalary = Salary::where('user_id', $row->user_id)->where('status', 'Y')->first();

                        if (!$salary && Auth::user()->can('monthly salary view')) {
                            $currentMonth = Carbon::now()->format('m');
                            $currentYear = Carbon::now()->format('Y');
                            if ($employeeBasicSalary && ($year < $currentYear || ($year == $currentYear && $month <= $currentMonth))) {
                                $btn = '<a href="' . route('salary.monthly.create', ['month' => $month, 'year' => $year, 'id' => $idEncrypted]) . '" class="me-1 waves-effect waves-light btn btn-circle btn-primary btn-view btn-xs" title="Add salary"><i class="fa fa-plus"></i></a>';
                            }
                        } else {
                            if (Auth::user()->can('monthly salary view')) {
                                $btn .= '<a href="' . route('salary.monthly.view', ['month' => $month, 'year' => $year, 'id' => $idEncrypted]) . '" class="me-1 waves-effect waves-light btn btn-circle btn-info btn-view btn-xs" title="View salary"><i class="fa fa-eye"></i></a>
                                        <a href="#" class="waves-effect waves-light btn btn-circle btn-secondary btn-download btn-xs text-dark btn-salaryslip-pdf-generate" title="Download Salary Slip" data-id="' . $idEncrypted . '" data-month="' . $month . '" data-year="' . $year . '"><i class="fa-solid fa-download"></i></a>';
                            }

                            // if (Auth::user()->can('monthly salary edit')) {
                            //     $btn .= '   <a href="' . route('salary.monthly.edit', ['month' => $month, 'year' => $year, 'id' => $idEncrypted]) . '" class="me-1 waves-effect waves-light btn btn-circle btn-success btn-view btn-xs" title="Edit salary"><i class="fa fa-pencil"></i></a>';
                            // }
                            if (Auth::user()->can('monthly salary delete')) {
                                $currentMonth = Carbon::now()->format('m');
                                $currentYear = Carbon::now()->format('Y');
                                $previousMonth = Carbon::now()->subMonth()->format('m');
                                $previousYear = Carbon::now()->subMonth()->format('Y');

                                if (($month == $currentMonth && $year == $currentYear) || ($month == $previousMonth && $year == $previousYear)) {
                                    $sabase64Id = base64_encode($salary->id);
                                    $salaryIdEncrypted = Crypt::encrypt($sabase64Id);
                                    $btn .= '  <button type="button" class="me-1 waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-cancel-salary-bill" data-salary-id="' . $salaryIdEncrypted . '" data-id="' . $idEncrypted . '" title="Delete Salary">
                                    <i class="fa fa-trash"></i></button>';
                                }
                            }
                        }
                        return $btn;
                    })
                    ->rawColumns(['name', 'role', 'action'])
                    ->make(true);
            }
        }

        $clinicBasicDetails = ClinicBasicDetail::first();
        $reportService = new ReportService();
        $branches = $reportService->getBranches();
        $years = $reportService->getYears();
        $employees = $reportService->getAttendanceStaff();

        return view('payroll.monthlySalary.index', compact('clinicBasicDetails', 'branches', 'years', 'employees'));
    }

    private function getEmployeeSalaryData($employeeId, $month, $year)
    {
        // Get employee profile, designation, and branch
        $employeeProfile = StaffProfile::with('user', 'clinicBranch')
            ->where('user_id', $employeeId)
            ->first();

        if (!$employeeProfile) {
            return response()->json(['error' => 'Employee profile not found'], 404);
        }

        $designation = $employeeProfile->designation ?? 'N/A';

        // Get the branch details
        $branchIds = explode(',', $employeeProfile->clinic_branch_id);
        $branches = implode(', ', array_map(function ($branchId) {
            $branch = ClinicBranch::find($branchId);
            return $branch ? str_replace('<br>', ' ', $branch->clinic_address) : 'Unknown';
        }, $branchIds));


        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        // Check if salary is processed for the given month and year
        $salary = EmployeeMonthlySalary::where('user_id', $employeeId)
            ->where('month', $month)
            ->where('year', $year)
            ->where('status', 'Y')
            ->first();

        $salaryRecords = [];
        $employeeBasicSalary = Salary::where('user_id', $employeeId)->where('status', 'Y')->first();

        if ($salary) {
            $paidDays = $salary->paid_days + round($salary->partially_paid_days / 2, 1);
            $unpaidDays = $salary->unpaid_days + round($salary->partially_paid_days / 2, 1);
            $monthlyDeduction = $salary->deduction_reason ? $salary->deduction_reason . ' : ' . $salary->monthly_deduction : $salary->monthly_deduction;
            $salaryMonth = Carbon::createFromDate($year, $month)->format('F Y');
            // Salary processed, return salary details
            $salaryRecords[] = [
                'name' => str_replace('<br>', ' ', $employeeProfile->user->name),
                'designation' => $designation,
                'branch' => $branches,
                'month' => $salaryMonth,
                'working_days' => $salary->working_days,
                'paid_days' => $paidDays,
                'unpaid_days' => $unpaidDays,
                'net_salary' => $salary->basic_salary,
                'absence_deduction' => $salary->absence_deduction,
                'monthly_deduction' => $monthlyDeduction,
                'incentives' => $salary->incentives,
                'total_salary' => $salary->total_salary,
                'previous_due' => $salary->previous_due,
                'advance_given' => $salary->advance_given,
                'monthly_salary' => $salary->amount_to_be_paid,
                'salary_paid' => $salary->amount_paid,
                'balance_due' => $salary->balance_due,
                'paid_on' => $salary->paid_on,
            ];
        } else {
            // Salary not processed, calculate working days, paid, unpaid, and partially paid days
            $salaryData = $this->generateEmployeeSalaryData($employeeId, $startDate, $endDate);
            $salaryMonth = Carbon::createFromDate($year, $month)->format('F Y');

            if ($salaryData) {

                $salaryRecords[] = [
                    'name' => str_replace('<br>', ' ', $employeeProfile->user->name),
                    'designation' => $designation,
                    'branch' => $branches,
                    'month' => $salaryMonth,
                    'working_days' => $salaryData['totalWorkingDays'],
                    'paid_days' => $salaryData['paidDays'],
                    'unpaid_days' => $salaryData['unPaidDays'],
                    'net_salary' => $employeeBasicSalary ? $employeeBasicSalary->netsalary : 'Not processed',
                    'absence_deduction' => 'Not processed',
                    'monthly_deduction' => 'Not processed',
                    'incentives' => 'Not processed',
                    'total_salary' => 'Not processed',
                    'previous_due' => 'Not processed',
                    'advance_given' => 'Not processed',
                    'monthly_salary' => 'Not processed',
                    'salary_paid' => 'Not processed',
                    'balance_due' => 'Not processed',
                    'paid_on' => 'Not Paid',
                ];
            }
        }

        return DataTables::of($salaryRecords)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($month, $year, $employeeId, $employeeBasicSalary) {
                $base64Id = base64_encode($employeeId);
                $idEncrypted = Crypt::encrypt($base64Id);
                $salary = '';
                $salary = EmployeeMonthlySalary::where('user_id', $employeeId)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->where('status', 'Y')
                    ->first();
                $btn = '';

                if (!$salary && Auth::user()->can('monthly salary view')) {
                    $currentMonth = Carbon::now()->format('m');
                    $currentYear = Carbon::now()->format('Y');
                    if ($employeeBasicSalary && ($year < $currentYear || ($year == $currentYear && $month <= $currentMonth))) {
                        $btn = '<a href="' . route('salary.monthly.create', ['month' => $month, 'year' => $year, 'id' => $idEncrypted]) . '" class="me-1 waves-effect waves-light btn btn-circle btn-primary btn-view btn-xs" title="Add salary"><i class="fa fa-plus"></i></a>';
                    }
                } else {
                    if (Auth::user()->can('monthly salary view')) {
                        $btn .= '<a href="' . route('salary.monthly.view', ['month' => $month, 'year' => $year, 'id' => $idEncrypted]) . '" class="me-1 waves-effect waves-light btn btn-circle btn-info btn-view btn-xs" title="View salary"><i class="fa fa-eye"></i></a>
                                <a href="#" class="waves-effect waves-light btn btn-circle btn-secondary btn-download btn-xs text-dark btn-salaryslip-pdf-generate" title="Download Salary Slip" data-id="' . $idEncrypted . '" data-month="' . $month . '" data-year="' . $year . '"><i class="fa-solid fa-download"></i></a>';
                    }
                    // if (Auth::user()->can('monthly salary edit')) {
                    //    $btn .= '   <a href="' . route('salary.monthly.edit', ['month' => $month, 'year' => $year, 'id' => $idEncrypted]) . '" class="me-1 waves-effect waves-light btn btn-circle btn-success btn-view btn-xs" title="Edit salary"><i class="fa fa-pencil"></i></a>';
                    // }
                    if (Auth::user()->can('monthly salary delete')) {
                        $currentMonth = Carbon::now()->format('m');
                        $currentYear = Carbon::now()->format('Y');
                        $previousMonth = Carbon::now()->subMonth()->format('m');
                        $previousYear = Carbon::now()->subMonth()->format('Y');

                        if (($month == $currentMonth && $year == $currentYear) || ($month == $previousMonth && $year == $previousYear)) {
                            $sabase64Id = base64_encode($salary->id);
                            $salaryIdEncrypted = Crypt::encrypt($sabase64Id);
                            $btn .= '  <button type="button" class="me-1 waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-cancel-salary-bill" data-salary-id="' . $salaryIdEncrypted . '" data-id="' . $idEncrypted . '" title="Delete Salary">
                            <i class="fa fa-trash"></i></button>';
                        }
                    }
                }
                return $btn;
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }


    private function generateEmployeeSalaryData($employeeId, $startDate, $endDate)
    {
        $employeeProfile = StaffProfile::where('user_id', $employeeId)->first();
        if (!$employeeProfile) {
            return null;
        }

        $branchIds = explode(',', $employeeProfile->clinic_branch_id);

        $today = Carbon::today();
        if ($startDate->gt($today)) {
            return null;
        }

        if ($endDate->gt($today)) {
            $endDate = $today;
        }

        // Fetch holidays
        $holidays = Holiday::whereBetween('holiday_on', [$startDate, $endDate])->get();

        // Calculate total working days
        $totalWorkingDays = max(0, floor($startDate->diffInDays($endDate) + 1));

        // Count Sundays as holidays
        $sundayCount = 0;
        for ($date = clone $startDate; $date <= $endDate; $date->addDay()) {
            if ($date->isSunday()) {
                $sundayCount++;
            }
        }

        // Calculate holidays applicable to all branches the employee works in
        $applicableHolidays = $holidays->filter(function ($holiday) use ($branchIds) {
            $holidayBranches = json_decode($holiday->branches, true);

            return is_null($holidayBranches) || in_array(null, $holidayBranches) ||
                count(array_intersect($branchIds, $holidayBranches)) === count($branchIds);
        });

        // Subtract holidays and Sundays from total working days
        $holidayCount = $applicableHolidays->count() + $sundayCount;
        $totalWorkingDays = max(0, $totalWorkingDays - $holidayCount);

        // Fetch attendance data
        $attendanceData = EmployeeAttendance::where('user_id', $employeeId)
            ->whereBetween('login_date', [$startDate, $endDate])
            ->get();

        // Count present days
        $presentDays = $attendanceData->where('attendance_status', EmployeeAttendance::PRESENT)->count();
        $absentDays = max(0, $totalWorkingDays - $presentDays);



        // Fetch leave data for approved leaves
        $leaveData = LeaveApplication::with('leaveType')
            ->where('user_id', $employeeId)
            ->where('leave_status', LeaveApplication::Approved)
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
        $paidLeave = $leaveData->where('leaveType.payment_status', 'Paid')->count();
        $partiallyPaidLeave = $leaveData->where('leaveType.payment_status', 'Partially Paid')->count();
        $unPaidLeave = $leaveData->where('leaveType.payment_status', 'Not Paid')->count();

        // Update total absent days after accounting for approved leaves
        $totalAbsentDays = max(0, $absentDays - $totalLeaveDays);
        $totalunpaidDays = $totalAbsentDays + $unPaidLeave + round($partiallyPaidLeave / 2, 1);
        $totalpaidDays = $presentDays + $paidLeave + round($partiallyPaidLeave / 2, 1);
        //Log::info('$totalpaidDays'.$totalpaidDays);
        $totalUnpaidInput = $totalAbsentDays + $unPaidLeave;
        $totalPaidInput = $presentDays + $paidLeave;


        return [
            'totalWorkingDays' => $totalWorkingDays,
            'paidDays' => $totalpaidDays,
            'unPaidDays' => $totalunpaidDays,
            'presentDays' => $presentDays,
            'absentDays' => $absentDays,
            'paidLeave' => $paidLeave,
            'unPaidLeave' => $unPaidLeave,
            'partiallyPaidLeave' => $partiallyPaidLeave,
            'totalLeave' => $totalLeaveDays,
            'totalAbsent' => $totalAbsentDays,
            'totalUnpaidInput' => $totalUnpaidInput,
            'totalPaidInput' => $totalPaidInput,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($month, $year, string $id)
    {
        $id = base64_decode(Crypt::decrypt($id));
        $staff = StaffProfile::with('user')->where('user_id', $id)->first();
        
        if (!$staff) {
            abort(404);
        }
        $employeeType = EmployeeType::orderBy('employee_type', 'asc')->get();
        $payHeads = PayHead::orderBy('head_type', 'asc')->get();
        $EPayHeads = PayHead::where('type', 'E')->orderBy('head_type', 'asc')->get();
        $SAPayHeads = PayHead::where('type', 'SA')->orderBy('head_type', 'asc')->get();
        $SDPayHeads = PayHead::where('type', 'SD')->orderBy('head_type', 'asc')->get();
        $employeesalary = EmployeeSalary::where('user_id', $id)->where('status', 'Y')->get();
        $salary = Salary::where('user_id', $id)->where('status', 'Y')->first();
        $monthlySalary = EmployeeMonthlySalary::where('user_id', $id)
            ->where('month', $month)
            ->where('year', $year)
            ->where('status', 'Y')
            ->first();
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $salaryData = $this->generateEmployeeSalaryData($id, $startDate, $endDate);
        Log::info('Salary Data: ' . json_encode($salaryData));
        // Fetch balance due from the previous month
        $previousMonth = Carbon::create($year, $month)->subMonth();
        $previousMonthSalary = EmployeeMonthlySalary::where('user_id', $id)
            ->where('month', $previousMonth->month)
            ->where('year', $previousMonth->year)
            ->where('status', 'Y')
            ->first();

        $previousBalanceDue = $previousMonthSalary ? $previousMonthSalary->balance_due : 0;

        // Fetch advance for the current month
        $currentMonthAdvance = SalaryAdvance::where('user_id', $id)
            ->where('month', $month)
            ->where('year', $year)
            ->where('status', 'Y')
            ->first();

        $mode = "create";
        return view('payroll.monthlySalary.create', compact('staff', 'employeeType', 'payHeads', 'mode', 'EPayHeads', 'SAPayHeads', 'SDPayHeads', 'monthlySalary', 'salary', 'employeesalary', 'month', 'year', 'salaryData', 'previousBalanceDue', 'currentMonthAdvance'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(MonthlySalaryRequest $request)
    {
        \Log::info('Request Data for Create:', $request->all());
        try {
            DB::beginTransaction();

            $monthlySalary = new EmployeeMonthlySalary();
            $monthlySalary->user_id = $request->input('user_id'); // Assuming the employee's user_id is the same as staff_id
            $monthlySalary->month = $request->input('month');
            $monthlySalary->year = $request->input('year');
            $monthlySalary->working_days = $request->input('totalWorkingDays');
            $monthlySalary->paid_days = $request->input('paid_days');
            $monthlySalary->unpaid_days = $request->input('unpaid_days');
            $monthlySalary->partially_paid_days = $request->input('partially_paid_days');
            $monthlySalary->salary_id = $request->input('salary_id');
            $monthlySalary->basic_salary = $request->input('basic_salary');
            $monthlySalary->absence_deduction = $request->input('lossOfPay');
            $monthlySalary->incentives = $request->input('incentive');
            $monthlySalary->monthly_deduction = $request->input('monthlyDeduction');
            $monthlySalary->deduction_reason = $request->input('deductionReason');
            $monthlySalary->total_deduction = $request->input('monthlyDeductionsTotal');
            $monthlySalary->total_earnings = $request->input('earningstotal');
            $monthlySalary->ctc = $request->input('ctc');
            $monthlySalary->total_salary = $request->input('netsalary');
            $monthlySalary->previous_due = $request->input('previousDue');
            $monthlySalary->advance_id = $request->input('advance_id');
            $monthlySalary->advance_given = $request->input('advance');
            $monthlySalary->amount_to_be_paid = $request->input('monthlySalary');
            $monthlySalary->amount_paid = $request->input('total_paid') ?? null;
            $monthlySalary->balance_due = $monthlySalary->amount_to_be_paid - ($monthlySalary->amount_paid ?? 0);
            $monthlySalary->paid_on = $request->input('paid_on') ?? now()->toDateString();
            $monthlySalary->cash = $request->input('medcash') ?? 0;
            $monthlySalary->bank = $request->input('medbank') ?? 0;
            $monthlySalary->status = 'Y';
            $monthlySalary->created_by = auth()->user()->id;

            // Save the monthly salary data
            $monthlySalary->save();

            \Log::info('Employee salary data saved successfully.');
            DB::commit();
            return response()->json(['success' => 'Salary created successfully!', 'status' => 201], 201);

        } catch (Exception $e) {
            DB::rollback();
            \Log::error('Error creating salary:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to create salary.'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($month, $year, string $id)
    {
        $id = base64_decode(Crypt::decrypt($id));
        $staff = StaffProfile::with('user')->where('user_id', $id)->first();
        if (!$staff) {
            abort(404);
        }
        $employeeType = EmployeeType::orderBy('employee_type', 'asc')->get();
        $payHeads = PayHead::orderBy('head_type', 'asc')->get();
        $EPayHeads = PayHead::where('type', 'E')->orderBy('head_type', 'asc')->get();
        $SAPayHeads = PayHead::where('type', 'SA')->orderBy('head_type', 'asc')->get();
        $SDPayHeads = PayHead::where('type', 'SD')->orderBy('head_type', 'asc')->get();

        $mode = "view";
        $userId = $staff->user_id;
        $employeesalary = EmployeeSalary::where('user_id', $userId)->where('status', 'Y')->get(); // Get all salary records for the user
        if (!$employeesalary) {
            // Handle the case where no salary record exists for this user
            abort(404);
        }

        $salary = Salary::where('user_id', $userId)->where('status', 'Y')->first();
        $monthlySalary = EmployeeMonthlySalary::where('user_id', $id)
            ->where('month', $month)
            ->where('year', $year)
            ->where('status', 'Y')
            ->first();
        if (!$salary || !$monthlySalary) {
            // Handle the case where no salary record exists for this user
            abort(404);
        }

        // return view('payroll.salary.create', compact('staff', 'employeeType', 'payHeads', 'mode', 'EPayHeads', 'SAPayHeads', 'SDPayHeads', 'employeesalary', 'employeeLeave', 'salary'));
        return view('payroll.monthlySalary.create', compact('staff', 'employeeType', 'payHeads', 'mode', 'EPayHeads', 'SAPayHeads', 'SDPayHeads', 'employeesalary', 'salary', 'monthlySalary', 'month', 'year'));

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        try {
            DB::beginTransaction();
            $id = base64_decode(Crypt::decrypt($id));
            $employeeId = base64_decode(Crypt::decrypt($request->employee));
            Log::info('$id' . $id);
            Log::info('$employeeId' . $employeeId);

            // Find the salary record
            $salary = EmployeeMonthlySalary::where('user_id', $employeeId)
                ->where('id', $id)->first();
            if (!$salary) {
                abort(404);
            }
            Log::info('$employeeId' . $salary);
            // Update EmployeeMonthlySalary status


            $salary->delete_reason = $request->reason;
            $salary->status = 'N';
            $salary->deleted_by = auth()->id();

            if ($salary->save()) {
                DB::commit();
                return response()->json(['success' => 'Salary cancelled successfully']);
            } else {
                DB::rollBack();
                return response()->json(['error' => 'Salary Error! Please try again.']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function generatesalaryslipPdf($month, $year, Request $request)
    {
        $userId = base64_decode(Crypt::decrypt($request->input('userId')));


        $clinicDetails = ClinicBasicDetail::first();
        if ($clinicDetails->clinic_logo == '') {
            $clinicLogo = 'public/images/logo-It.png';
        } else {
            $clinicLogo = 'storage/' . $clinicDetails->clinic_logo;
        }
        $staff = StaffProfile::with('user')->where('user_id', $userId)->first();
        if (!$staff) {
            return response()->json(['error' => 'Staff not found'], 404);
        }
        $employeesalary = EmployeeSalary::where('user_id', $userId)->where('status', 'Y')->get();
        $employeeType = EmployeeType::orderBy('employee_type', 'asc')->get();
        $EPayHeads = PayHead::where('type', 'E')->orderBy('head_type', 'asc')->get();
        $SAPayHeads = PayHead::where('type', 'SA')->orderBy('head_type', 'asc')->get();
        $SDPayHeads = PayHead::where('type', 'SD')->orderBy('head_type', 'asc')->get();

        $salary = Salary::where('user_id', $userId)->where('status', 'Y')->first();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->where('is_main_branch', 'Y')
            ->first();

        $currency = $clinicDetails->currency;
        $department = Department::where('id', $staff->department_id)->first();
        $monthlySalary = EmployeeMonthlySalary::where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->where('status', 'Y')
            ->first();
        // Pass data to the PDF view
        $pdf = Pdf::loadView('pdf.monthlysalaryslip_pdf', [
            'staff' => $staff,
            'EPayHeads' => $EPayHeads,
            'SAPayHeads' => $SAPayHeads,
            'SDPayHeads' => $SDPayHeads,
            'employeesalary' => $employeesalary,
            'salary' => $salary,
            'clinicDetails' => $clinicDetails,
            'clinicLogo' => $clinicLogo,
            'currency' => $currency,
            'clinicBranches' => $clinicBranches,
            'department' => $department,
            'monthlySalary' => $monthlySalary,
        ])->setPaper('A4', 'portrait');

        // Download the generated PDF
        return $pdf->download('salaryslip.pdf');
    }
}
