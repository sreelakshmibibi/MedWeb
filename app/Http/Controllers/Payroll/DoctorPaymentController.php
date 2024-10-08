<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorPaymentRequest;
use App\Models\ClinicBranch;
use App\Models\DoctorPayment;
use App\Models\Salary;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;

class DoctorPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:visiting doctor payment', ['only' => ['index']]);
        $this->middleware('permission:visiting doctor payment save', ['only' => ['store']]);
        $this->middleware('permission:visiting doctor payment cancel', ['only' => ['destroy']]);
        
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
                ->where('visiting_doctor', 1)
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
                    $btn = '<a href="' . route('doctorPayment.create', $idEncrypted) . '" class="me-1 waves-effect waves-light btn btn-circle btn-primary btn-view btn-xs" title="Payment"><i class="fa fa-plus"></i></a>';
                    return $btn;
                })
                ->rawColumns(['name', 'role', 'action'])
                ->make(true);
        }

        // Return the view with menu items
        return view('payroll.doctorPayment.index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, string $idEncrypted)
    {
        $id = base64_decode(Crypt::decrypt($idEncrypted));
        $staff = StaffProfile::with('user')->find($id);
        if (!$staff) {
            abort(404);
        }
        $totalPaid = DoctorPayment::where('user_id', $staff->user_id)
                    ->where('status', 'Y')
                    ->sum('amount');
                    
        $history = DoctorPayment::where('user_id', $staff->user_id)
        ->orderBy('paid_on', 'desc')
        ->get();
        

        if ($request->ajax()) {
            
            return DataTables::of($history)
                ->addIndexColumn()
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
                        if (Auth::user()->can('visiting doctor payment cancel')) {
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
  
        return view('payroll.doctorPayment.create', compact('staff', 'idEncrypted', 'totalPaid'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DoctorPaymentRequest $request)
    {
         // Create a new payment record
        $payment = new DoctorPayment();
        $payment->user_id = $request->user_id;
        $payment->amount = $request->amount;
        $payment->paid_on = $request->paid_on;
        $payment->remarks = $request->remarks;
        $payment->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $history = DoctorPayment::find($id);
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
