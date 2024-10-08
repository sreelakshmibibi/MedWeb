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
use App\Models\EmployeeLeave;
use App\Http\Requests\Payroll\SalaryRequest;
use Illuminate\Support\Facades\DB;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ClinicBasicDetail;
use App\Models\Department;

class EmployeeSalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:salary', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $staff = StaffProfile::with('user')
            ->where('status', 'Y')
            ->orderBy('id', 'asc')
            ->get();

        if ($request->ajax()) {
            $staff = StaffProfile::with(['clinicBranch']) // Make sure this relationship exists
                ->where('status', 'Y')
                ->get();
            $staff->transform(function ($staff) {
                // Split the clinic_branch_id into an array
                $branchIds = explode(',', $staff->clinic_branch_id);
                
                // Retrieve the branches based on the IDs
                $branches = ClinicBranch::whereIn('id', $branchIds)->get();

                // Map the branch addresses and join them into a single string
                $branchAddresses = $branches->map(function ($branch) {
                    return $branch->clinic_address ? str_replace('<br>', ' ', $branch->clinic_address) : '-';
                })->join(', '); // Join with a comma and space

                // Assign the branch addresses back to the staff object as a string
                $staff->branch = $branchAddresses;

                return $staff;
            });
            return DataTables::of($staff)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return str_replace("<br>", " ", $row->user->name);
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
                ->addColumn('action', function ($row) {
                    $base64Id = base64_encode($row->id);
                    $idEncrypted = Crypt::encrypt($base64Id);
                    $salary = Salary::where('user_id', $row->user->id)->where('status', 'Y')->first();

                    if (!$salary) {
                        $btn = '<a href="' . route('salary.create', $idEncrypted) . '" class="me-1 waves-effect waves-light btn btn-circle btn-primary btn-view btn-xs" title="Add salary"><i class="fa fa-plus"></i></a>';
                    } else {
                        $btn = '<a href="' . route('salary.view', $idEncrypted) . '" class="me-1 waves-effect waves-light btn btn-circle btn-info btn-view btn-xs" title="View salary"><i class="fa fa-eye"></i></a>
                    <a href="' . route('salary.edit', $idEncrypted) . '" class="me-1 waves-effect waves-light btn btn-circle btn-success btn-view btn-xs" title="Edit salary"><i class="fa fa-pencil"></i></a>
                    <button type="button" class="me-1 waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-cancel-lab-bill" data-id="' . $row->user->id . '" title="Delete Salary">
                        <i class="fa fa-trash"></i></button>
                        <a href="#" class="waves-effect waves-light btn btn-circle btn-secondary btn-download btn-xs text-dark btn-salaryslip-pdf-generate" title="Download Salary Slip" data-id="' . $row->user->id . '"><i class="fa-solid fa-download"></i></a>';

                    }
                    return $btn;
                })
                ->rawColumns(['name', 'role', 'action'])
                ->make(true);
        }

        // Return the view with menu items
        return view('payroll.salary.index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        $id = base64_decode(Crypt::decrypt($id));
        $staff = StaffProfile::with('user')->find($id);
        if (!$staff) {
            abort(404);
        }
        $employeeType = EmployeeType::orderBy('employee_type', 'asc')->get();
        $payHeads = PayHead::orderBy('head_type', 'asc')->get();
        $EPayHeads = PayHead::where('type', 'E')->orderBy('head_type', 'asc')->get();
        $SAPayHeads = PayHead::where('type', 'SA')->orderBy('head_type', 'asc')->get();
        $SDPayHeads = PayHead::where('type', 'SD')->orderBy('head_type', 'asc')->get();

        $mode = "create";
        return view('payroll.salary.create', compact('staff', 'employeeType', 'payHeads', 'mode', 'EPayHeads', 'SAPayHeads', 'SDPayHeads'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SalaryRequest $request)
    {
        \Log::info('Request Data for Create:', $request->all());

        try {
            DB::beginTransaction();
            $data = $request->validated();
            \Log::info('Validated Data for Create:', $data);

            $data['created_by'] = auth()->id(); // Get the ID of the authenticated user
            $userId = $request->input('user_id');

            // Create new Employee Leave
            // $leaveData = [
            //     'user_id' => $userId,
            //     'employee_type_id' => $data['emp_type'],
            //     'casual_leave_monthly' => $data['casual_leaves'],
            //     'sick_leave_monthly' => $data['sick_leaves'],
            //     'with_effect_from' => $request->input('with_effect_from'),
            //     'status' => 'Y',
            //     'created_by' => $data['created_by'],
            // ];
            // EmployeeLeave::create($leaveData);

            // Handle Earnings, Additions, and Deductions
            $this->handleSalaryEntries($request, $userId, 'create');

            // Create Salary
            Salary::create([
                'user_id' => $userId,
                'employee_type_id' => $data['emp_type'],
                'salary' => $data['salary'],
                'netsalary' => $data['netsalary'],
                'ctc' => $data['ctc'],
                'etotal' => $data['earningstotal'],
                'satotal' => $data['additionstotal'],
                'sdtotal' => $data['deductionstotal'],
                'status' => 'Y',
                'created_by' => $data['created_by'],
            ]);

            \Log::info('Salary items created successfully.');
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
    public function show(string $id)
    {
        $id = base64_decode(Crypt::decrypt($id));
        $staff = StaffProfile::with('user')->find($id);
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

        // $employeeLeave = EmployeeLeave::where('user_id', $userId)->where('status', 'Y')->first();
        // if (!$employeeLeave) {
        //     // Handle the case where no salary record exists for this user
        //     abort(404);
        // }

        $salary = Salary::where('user_id', $userId)->where('status', 'Y')->first();
        if (!$salary) {
            // Handle the case where no salary record exists for this user
            abort(404);
        }

        // return view('payroll.salary.create', compact('staff', 'employeeType', 'payHeads', 'mode', 'EPayHeads', 'SAPayHeads', 'SDPayHeads', 'employeesalary', 'employeeLeave', 'salary'));
        return view('payroll.salary.create', compact('staff', 'employeeType', 'payHeads', 'mode', 'EPayHeads', 'SAPayHeads', 'SDPayHeads', 'employeesalary', 'salary'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = base64_decode(Crypt::decrypt($id));
        $staff = StaffProfile::with('user')->find($id);
        if (!$staff) {
            abort(404);
        }
        $employeeType = EmployeeType::orderBy('employee_type', 'asc')->get();
        $payHeads = PayHead::orderBy('head_type', 'asc')->get();
        $EPayHeads = PayHead::where('type', 'E')->orderBy('head_type', 'asc')->get();
        $SAPayHeads = PayHead::where('type', 'SA')->orderBy('head_type', 'asc')->get();
        $SDPayHeads = PayHead::where('type', 'SD')->orderBy('head_type', 'asc')->get();

        $mode = "edit";
        $userId = $staff->user_id;
        $employeesalary = EmployeeSalary::where('user_id', $userId)->where('status', 'Y')->get(); // Get all salary records for the user
        if (!$employeesalary) {
            // Handle the case where no salary record exists for this user
            abort(404);
        }

        // $employeeLeave = EmployeeLeave::where('user_id', $userId)->where('status', 'Y')->first();
        // if (!$employeeLeave) {
        //     // Handle the case where no salary record exists for this user
        //     abort(404);
        // }

        $salary = Salary::where('user_id', $userId)->where('status', 'Y')->first();
        if (!$salary) {
            // Handle the case where no salary record exists for this user
            abort(404);
        }

        // \Log::info($employeeLeave);
        // return view('payroll.salary.create', compact('staff', 'employeeType', 'payHeads', 'mode', 'EPayHeads', 'SAPayHeads', 'SDPayHeads', 'employeesalary', 'employeeLeave', 'salary'));
        return view('payroll.salary.create', compact('staff', 'employeeType', 'payHeads', 'mode', 'EPayHeads', 'SAPayHeads', 'SDPayHeads', 'employeesalary', 'salary'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SalaryRequest $request)
    {
        \Log::info('Request Data for Update:', $request->all());

        try {
            DB::beginTransaction();
            $data = $request->validated();
            \Log::info('Validated Data for Update:', $data);

            $data['updated_by'] = auth()->id(); // Get the ID of the authenticated user
            $userId = $request->input('user_id');

            // Update Employee Leave
            // $leaveData = [
            //     'user_id' => $userId,
            //     'employee_type_id' => $data['emp_type'],
            //     'casual_leave_monthly' => $data['casual_leaves'],
            //     'sick_leave_monthly' => $data['sick_leaves'],
            //     'with_effect_from' => $request->input('with_effect_from'),
            //     'status' => 'Y',
            //     'updated_by' => $data['updated_by'],
            // ];
            // EmployeeLeave::where('user_id', $userId)->update($leaveData);

            // Handle Earnings, Additions, and Deductions
            $this->handleSalaryEntries($request, $userId, 'update');

            // Update Salary
            Salary::where('user_id', $userId)->update([
                'employee_type_id' => $data['emp_type'],
                'salary' => $data['salary'],
                'netsalary' => $data['netsalary'],
                'ctc' => $data['ctc'],
                'etotal' => $data['earningstotal'],
                'satotal' => $data['additionstotal'],
                'sdtotal' => $data['deductionstotal'],
                'status' => 'Y',
                'updated_by' => $data['updated_by'],
            ]);

            \Log::info('Salary items updated successfully.');
            DB::commit();
            return response()->json(['success' => 'Salary updated successfully!', 'status' => 200], 200);
        } catch (Exception $e) {
            DB::rollback();
            \Log::error('Error updating salary:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update salary.'], 500);
        }
    }

    private function handleSalaryEntries($request, $userId, $action)
    {
        // Handle Earnings
        $earningspayHeadIds = $request->input('earningspay_head_id');
        $earningsamounts = $request->input('earningsamount');
        $earningseffectDates = $request->input('earningseffect_date');

        foreach ($earningspayHeadIds as $index => $payHeadId) {
            if ($action === 'update') {
                // Update existing record and include updated_by
                EmployeeSalary::updateOrCreate(
                    ['user_id' => $userId, 'pay_head_id' => $payHeadId],
                    [
                        'amount' => $earningsamounts[$index],
                        'with_effect_from' => $earningseffectDates[$index],
                        // 'created_by' => auth()->id(), // Keep the original creator
                        'updated_by' => auth()->id(), // Set the updater
                        'status' => 'Y',
                    ]
                );
            } else {
                // Create new record
                EmployeeSalary::create([
                    'user_id' => $userId,
                    'pay_head_id' => $payHeadId,
                    'amount' => $earningsamounts[$index],
                    'with_effect_from' => $earningseffectDates[$index],
                    'created_by' => auth()->id(),
                    'status' => 'Y',
                ]);
            }
        }

        // Handle Additions
        $additionspayHeadIds = $request->input('additionspay_head_id');
        $additionsamounts = $request->input('additionsamount');
        $additionseffectDates = $request->input('additionseffect_date');

        foreach ($additionspayHeadIds as $index => $payHeadId) {
            if ($action === 'update') {
                // Update existing record and include updated_by
                EmployeeSalary::updateOrCreate(
                    ['user_id' => $userId, 'pay_head_id' => $payHeadId],
                    [
                        'amount' => $additionsamounts[$index],
                        'with_effect_from' => $additionseffectDates[$index],
                        // 'created_by' => auth()->id(), // Keep the original creator
                        'updated_by' => auth()->id(), // Set the updater
                        'status' => 'Y',
                    ]
                );
            } else {
                // Create new record
                EmployeeSalary::create([
                    'user_id' => $userId,
                    'pay_head_id' => $payHeadId,
                    'amount' => $additionsamounts[$index],
                    'with_effect_from' => $additionseffectDates[$index],
                    'created_by' => auth()->id(),
                    'status' => 'Y',
                ]);
            }
        }

        // Handle Earnings
        $deductionspayHeadIds = $request->input('deductionspay_head_id');
        $deductionsamounts = $request->input('deductionsamount');
        $deductionseffectDates = $request->input('deductionseffect_date');

        foreach ($deductionspayHeadIds as $index => $payHeadId) {
            if ($action === 'update') {
                // Update existing record and include updated_by
                EmployeeSalary::updateOrCreate(
                    ['user_id' => $userId, 'pay_head_id' => $payHeadId],
                    [
                        'amount' => $deductionsamounts[$index],
                        'with_effect_from' => $deductionseffectDates[$index],
                        // 'created_by' => auth()->id(), // Keep the original creator
                        'updated_by' => auth()->id(), // Set the updater
                        'status' => 'Y',
                    ]
                );
            } else {
                // Create new record
                EmployeeSalary::create([
                    'user_id' => $userId,
                    'pay_head_id' => $payHeadId,
                    'amount' => $deductionsamounts[$index],
                    'with_effect_from' => $deductionseffectDates[$index],
                    'created_by' => auth()->id(),
                    'status' => 'Y',
                ]);
            }
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        try {
            DB::beginTransaction();

            // Find the salary record
            $salary = Salary::where('user_id', $id)->first();
            if (!$salary) {
                abort(404);
            }

            // Update EmployeeSalary status
            $employeesalaryUpdate = EmployeeSalary::where('user_id', $id)
                ->update(['status' => 'N', 'updated_by' => auth()->id()]);

            if ($employeesalaryUpdate) {
                // Update EmployeeLeave status
                // $employeeleaveUpdate = EmployeeLeave::where('user_id', $id)
                //     ->update(['status' => 'N', 'updated_by' => auth()->id()]);

                // if ($employeeleaveUpdate) {
                // Update salary record
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
                // } else {
                //     DB::rollBack();
                //     return response()->json(['error' => 'Item Error ! Please try again.']);
                // }
            } else {
                DB::rollBack();
                return response()->json(['error' => 'Item Error ! Please try again.']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function generatesalaryslipPdf(Request $request)
    {
        $userId = $request->input('user_id');
        $patientId = $request->input('patient_id');

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
        // $employeeLeave = EmployeeLeave::where('user_id', $userId)
        //     ->where('status', 'Y')->first();
        $salary = Salary::where('user_id', $userId)->where('status', 'Y')->first();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->where('is_main_branch', 'Y')
            ->first();

        $currency = $clinicDetails->currency;
        $department = Department::where('id', $staff->department_id)->first();
        // Pass data to the PDF view
        $pdf = Pdf::loadView('pdf.salaryslip_pdf', [
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
        ])->setPaper('A4', 'portrait');

        // Download the generated PDF
        return $pdf->download('salaryslip.pdf');
    }

}
