<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\LeaveApplicationRequest;
use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Requests\Settings\MedicineRequest;
use App\Http\Requests\Settings\LeaveRequest;
use App\Models\LeaveApplication;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables as DataTables;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:leave', ['only' => ['index']]);
        $this->middleware('permission:apply leave', ['only' => ['create', 'store', 'update', 'edit', 'destroy']]);
        $this->middleware('permission:approve leave', ['only' => ['approveLeave', 'rejectLeave']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $leaves = null;
            if (Auth::user()->can('approve leave')) {
                $leaves = LeaveApplication::with('user')->orderBy('leave_from', 'desc')->get();
            } else {
                $leaves = LeaveApplication::where('user_id', Auth::user()->id)
                            ->orderBy('leave_from', 'desc')->get();
            }
            

            $dataTable =  DataTables::of($leaves)
                ->addIndexColumn()
                ->addColumn('leave_applied_dates', function ($row){
                    $leaveFrom = Carbon::parse($row->leave_from);
                    $leaveTo = Carbon::parse($row->leave_to);
                    $differenceInDays = $leaveFrom->diffInDays($leaveTo) + 1; // Adding 1 because both start and end dates are inclusive
                    return $leaveFrom->format('d-m-Y') . ' to ' . $leaveTo->format('d-m-Y') . ' (' . $differenceInDays . ' days)';
                })

                ->addColumn('status', function ($row) {
                    if ($row->leave_status == LeaveApplication::Applied) {
                        return "Applied";
                    } else if ($row->leave_status == LeaveApplication::Approved) {
                        return "Approved";
                    } else if ($row->leave_status == LeaveApplication::Rejected) {
                        return "Rejected" . " ( " . $row->rejection_reason. " ) ";
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = null;
                    if (Auth::user()->can('approve leave')) {
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-approve btn-xs me-1" title="approve" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-approve" >Approve</button>
                        '; 
                        if ($row->leave_from >= date('Y-m-d')) {
                            $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-reject btn-xs" data-bs-toggle="modal" data-bs-target="#modal-reject" data-id="' . $row->id . '" title="delete">Reject</button>';
                        }
                    }
                
                    
                    if (Auth::user()->id = $row->user_id && $row->leave_status == LeaveApplication::Applied) {
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';
                    }
                    return $btn;
                });
                if (Auth::user()->can('approve leave')) {
                    $dataTable->addColumn('staff', function ($row) {
                        return str_replace("<br>", " ", $row->user->name);
                    });
                }
               return $dataTable->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('settings.leave.index');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(LeaveApplicationRequest $request)
    {
        $userId = Auth::user()->id;
        $leaveFrom = $request->input('leave_from');
        $leaveTo = $request->input('leave_to');
        
        // Check if there is an existing leave application for the same user
        $checkExists = LeaveApplication::where('user_id', $userId)
            ->where(function ($query) use ($leaveFrom, $leaveTo) {
                $query->whereBetween('leave_from', [$leaveFrom, $leaveTo])
                    ->orWhereBetween('leave_to', [$leaveFrom, $leaveTo])
                    ->orWhere(function ($query) use ($leaveFrom, $leaveTo) {
                        $query->where('leave_from', '<=', $leaveFrom)
                            ->where('leave_to', '>=', $leaveTo);
                    });
            })
            ->whereNot('leave_status', LeaveApplication::Rejected)
            ->exists();
        
        if ($checkExists) {
            $message = 'Already exists an active leave application for the selected dates.';
            return $request->ajax() 
                ? response()->json(['error' => $message]) 
                : redirect()->back()->with('error', $message);
        }
        
        $leaveApplication = new LeaveApplication();
        $leaveApplication->user_id = $userId;
        $leaveApplication->leave_type = $request->input('leave_type');
        $leaveApplication->leave_from = $leaveFrom;
        $leaveApplication->leave_to = $leaveTo;
        $leaveApplication->leave_reason = $request->input('reason');
        $leaveApplication->leave_status = LeaveApplication::Applied;
        
        if ($leaveApplication->save()) {
            $message = 'Leave applied successfully.';
            return $request->ajax() 
                ? response()->json(['success' => $message]) 
                : redirect()->route('leave.index')->with('success', $message);
        } else {
            $message = 'Leave application failed.';
            return $request->ajax() 
                ? response()->json(['error' => $message]) 
                : redirect()->back()->with('error', $message);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $leave = LeaveApplication::find($id);
        if (!$leave) {
            abort(404);
        }

        return $leave;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $leaveApplication = LeaveApplication::findorFail($request->edit_leave_id);
        if (!$leaveApplication)
            abort(404);
        $leaveApplication->leave_type = $request->editleave_type;
        $leaveApplication->leave_from = $request->editleave_from;
        $leaveApplication->leave_to = $request->editleave_to;
        $leaveApplication->leave_reason = $request->editreason;
        $leaveApplication->leave_status = LeaveApplication::Applied;
        if ($leaveApplication->save()) {
            if ($request->ajax()) {
                return response()->json(['success' => 'Leave application updated successfully.']);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['success' => 'Leave application updation failed.']);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $leave = LeaveApplication::findOrFail($id);
        if (!$leave)
            abort(404);
        $leave->deleted_by = Auth::user()->id;
        $leave->save();
        $leave->delete();

        return response()->json(['success', 'Leave Application deleted successfully.'], 201);
    }

    public function approveLeave($leaveId)
    {
        $leave = LeaveApplication::findOrFail($leaveId);
        if (!$leave)
            abort(404);
        $leave->leave_status = LeaveApplication::Approved;
        $leave->approved_by = Auth::user()->id;
        $leave->save();
        return response()->json(['success', 'Leave Application approved successfully.'], 200);

    }
    public function rejectLeave($leaveId, Request $request)
    {
        $leave = LeaveApplication::findOrFail($leaveId);
        if (!$leave)
            abort(404);
        $leave->leave_status = LeaveApplication::Rejected;
        $leave->rejected_by = Auth::user()->id;
        $leave->rejection_reason = $request->reject_reason;
        $leave->save();
        return response()->json(['success', 'Leave Application rejected successfully.'], 200);
    }
}
