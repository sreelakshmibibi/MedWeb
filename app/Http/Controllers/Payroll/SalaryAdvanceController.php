<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryAdvanceRequest;
use App\Models\ClinicBranch;
use App\Models\Salary;
use App\Models\SalaryAdvance;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;

class SalaryAdvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */ public function index(Request $request)
    {
        $staff = StaffProfile::with('user')
            ->where('status', 'Y')
            ->orderBy('id', 'asc')
            ->get();

        if ($request->ajax()) {
            $staff = StaffProfile::with(['clinicBranch']) // Make sure this relationship exists
                ->where('status', 'Y')
                ->where('visiting_doctor', 0)
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
                    $btn = '<a href="' . route('salaryAdvance.create', $idEncrypted) . '" class="me-1 waves-effect waves-light btn btn-circle btn-primary btn-view btn-xs" title="Payment"><i class="fa fa-plus"></i></a>';
                    return $btn;
                })
                ->rawColumns(['name', 'role', 'action'])
                ->make(true);
        }

        // Return the view with menu items
        return view('payroll.salaryAdvance.index');

    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, string $idEncrypted)
    {
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];
        $id = base64_decode(Crypt::decrypt($idEncrypted));
        $staff = StaffProfile::with('user')->find($id);
        if (!$staff) {
            abort(404);
        }
       
        $netSalary = Salary::where('user_id', $staff->user_id)
        ->where('status', 'Y')
        ->value('netsalary');
        if (!$netSalary) {
            $netSalary = 0;
        }
        
        $history = SalaryAdvance::where('user_id', $staff->user_id)
        ->orderBy('paid_on', 'desc')
        ->get();
       
        if ($request->ajax()) {
            
            return DataTables::of($history)
                ->addIndexColumn()
                ->addColumn('month', function ($row) use ($months) {
                    return $months[$row->month]. " " . $row->year;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'Y') {
                        $btn1 = '<span class="text-success" title="active"><i class="fa-solid fa-circle-check"></i></span>';
                    } else {
                        $btn1 = '<span class="text-danger" title="inactive"><i class="fa-solid fa-circle-xmark"></i></span>';
                    }
                    return $btn1;
                })

                ->addColumn('action', function ($row) {
                    $btn = null;
                    if ($row->status == 'Y') {
                         if (Auth::user()->can('salary advance cancel')) {
                            $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-doctorPaymemt-delete" data-id="' . $row->id . '" title="delete">
                            <i class="fa fa-trash"></i></button>';
                         }
                    } else {
                        $btn .= $row->delete_reason;
                    }
                    
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        
        }
  
        return view('payroll.salaryAdvance.create', compact('staff', 'idEncrypted', 'months', 'netSalary'));

        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SalaryAdvanceRequest $request)
    {
        $netSalary = Salary::where('user_id', $request->user_id)
        ->where('status', 'Y')
        ->value('netsalary');
        if (!$netSalary) {
            $netSalary = 0;
        }
        if ($netSalary >= $request->amount) {
            $payment = new SalaryAdvance();
            $payment->user_id = $request->user_id;
            $payment->month = $request->month;
            $payment->year = $request->year;
            $payment->amount = $request->amount;
            $payment->paid_on = $request->paid_on;
            $payment->remarks = $request->remarks;
            $payment->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully!',
            ]);
        } else {
            return response()->json([
                'message' => 'Advance amount cannot be greater than salary!',
            ], 422);
        }
         // Create a new payment record
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $history = SalaryAdvance::find($id);
        if (!$history)
            abort(404);
        $history->delete_reason = $request->deleteReason;
        $history->status = 'N';
        if ($history->save()) {
            return response()->json([
            'success' => true,
            'message' => 'Payment record deleted successfully!',
            ]);
        }
    }
}
