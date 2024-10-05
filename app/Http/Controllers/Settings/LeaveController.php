<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\LeaveApplicationRequest;
use App\Models\Medicine;
use App\Services\LeaveService;
use Illuminate\Http\Request;
use App\Http\Requests\Settings\MedicineRequest;
use App\Http\Requests\Settings\LeaveRequest;
use App\Models\EmployeeAttendance;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables as DataTables;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:leave', ['only' => ['index']]);
        $this->middleware('permission:leave apply', ['only' => ['create', 'store', 'update', 'edit', 'destroy']]);
        $this->middleware('permission:leave approve', ['only' => ['approveLeave', 'rejectLeave']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $leaveTypes = LeaveType::where('status', 'Y')
        ->orderBy('type', 'asc')
        ->get();

        if ($request->ajax()) {
            $leaves = null;
            if (Auth::user()->can('leave approve')) {
                $leaves = LeaveApplication::with('user', 'leaveType')->orderBy('leave_from', 'desc')->get();

            } else {
                $leaves = LeaveApplication::where('user_id', Auth::user()->id)
                ->with('leaveType')
                ->orderBy('leave_from', 'desc')->get();
            }


            $dataTable = DataTables::of($leaves)
                ->addIndexColumn()
                ->addColumn('leave_applied_dates', function ($row) {
                    $leaveFrom = Carbon::parse($row->leave_from);
                    $leaveTo = Carbon::parse($row->leave_to);
                    // $differenceInDays = $leaveFrom->diffInDays($leaveTo) + 1; // Adding 1 because both start and end dates are inclusive
                    return $leaveFrom->format('d-m-Y') . ' to ' . $leaveTo->format('d-m-Y') . ' (' . $row->days . ' days)';
                })
                ->addColumn('leave_type', function ($row) {
                    return $row->leaveType->type;
                })
                ->addColumn('status', function ($row) {
                    if ($row->leave_status == LeaveApplication::Applied) {
                        // return "Applied";
                        return "<span class='text-info'>Applied</span";
                    } else if ($row->leave_status == LeaveApplication::Approved) {
                        // return "Approved";
                        return "<span class='text-success'>Approved</span";
                    } else if ($row->leave_status == LeaveApplication::Rejected) {
                        // return "Rejected" . " ( " . $row->rejection_reason . " ) ";
                        return "<span class='text-danger'>Rejected( " . $row->rejection_reason . " )</span";
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = null;
                    if (Auth::user()->can('leave approve')) {
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-primary btn-approve btn-xs me-1" title="approve" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-approve" ><i class="fa fa-check"></i></button>
                        ';
                        if ($row->leave_from >= date('Y-m-d')) {
                            $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-reject btn-xs me-1" data-bs-toggle="modal" data-bs-target="#modal-reject" data-id="' . $row->id . '" title="reject"><i class="fa fa-close"></i></button>';
                        }
                    }


                    if (Auth::user()->id = $row->user_id && $row->leave_status == LeaveApplication::Applied) {
                        // $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        // data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        $btn .=  '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';
                    }
                    return $btn;
                });
            if (Auth::user()->can('leave approve')) {
                $dataTable->addColumn('staff', function ($row) {
                    return str_replace("<br>", " ", $row->user->name);
                });
            }
            return $dataTable->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('settings.leave.index', compact('leaveTypes'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(LeaveApplicationRequest $request)
    {
        $userId = Auth::user()->id;
        $leaveFrom = Carbon::parse($request->input('leave_from'));
        $leaveTo = Carbon::parse($request->input('leave_to'));
       
        // Check for existing leave application
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

        $leaveService = new LeaveService();
        $leaveType = $request->input('leave_type');
        $financialYearDetails = $leaveService->getFinancialYear();
        
        if (!$financialYearDetails) {
            $message = 'Financial year details not found.';
            return $request->ajax()
                ? response()->json(['error' => $message])
                : redirect()->back()->with('error', $message);
        }

        $joiningDate = $leaveService->getJoiningDate($userId);
        $leaveCountPerMonth = $leaveService->getLeaveCount($leaveType);
        $leaveAppliedCount = $leaveService->getLeaveAppliedCount($leaveType, $financialYearDetails);
        // $leaveFrom = Carbon::parse($leaveFrom);
        // $leaveTo = Carbon::parse($leaveTo);
        
        // $differenceInDays = $leaveFrom->diffInDays($leaveTo) + 1; // Adding 1 because both start and end dates are inclusive
        $differenceInDays = $leaveService->getDaysDifference(Carbon::parse($request->input('leave_from')), Carbon::parse($request->input('leave_to')));
        // Determine available months for leave
        $monthsAvailable = 12;
        if ($joiningDate >= $financialYearDetails['start'] && $joiningDate <= $financialYearDetails['end']) {
            if ($leaveFrom->greaterThan($joiningDate)) {
                // Calculate the whole month difference
                $monthsAvailable = $joiningDate->diffInMonths(Carbon::parse($request->input('leave_from')));
                
                // Add 1 for the current month if it's after the first of the month
                if ($leaveFrom->day > 1) {
                    $monthsAvailable += 1; // +1 for the current month
                }
            } else {
                // If leaveFrom is before or equal to joiningDate, set monthsAvailable to 0
                $monthsAvailable = 0;
            }
        } 
        

        if ($leaveType == 2) { // Assuming 2 is for casual leave
            $monthsElapsed = Carbon::now()->month - $financialYearDetails['startMonth'] + 1; // +1 for current month
            $leavesTaken = LeaveApplication::where('leave_type_id', $leaveType)
                ->where('user_id', $userId)
                ->whereIn('leave_status', [LeaveApplication::Applied,LeaveApplication::Approved])
                ->whereBetween('leave_from', [$financialYearDetails['start'], $financialYearDetails['end']])
                ->get();
            // Calculate total days taken
            $totalDaysTaken = $leavesTaken->sum(function ($leave) use($leaveService) {
                // Convert leave_from and leave_to to Carbon instances
                $leave_From = \Carbon\Carbon::parse($leave->leave_from);
                $leave_To = \Carbon\Carbon::parse($leave->leave_to);
            
                // Ensure leave_to is not before leave_from
                return $leave_To >= $leave_From 
                    ? $leaveService->getDaysDifference($leave_From,$leave_To) // +1 to include both days
                    : 0;
            });
            
            // If you want to handle the case where there are no leaves taken
            $totalDaysTaken = $totalDaysTaken ?: 0; // Set to 0 if no leaves taken
            

            // Calculate available casual leaves
            $availableCasualLeaves = min($monthsElapsed, round($monthsAvailable)) - $totalDaysTaken;
            $availableCasualLeaves = max($availableCasualLeaves, 0); // Ensure non-negative
                        
            if ($availableCasualLeaves <= 0) {
                    return $request->ajax()
                        ? response()->json(['error' => 'No casual leaves available.'])
                        : redirect()->back()->with('error', 'No casual leaves available.');
                
            } else if ($differenceInDays > $availableCasualLeaves) {
                return $request->ajax()
                ? response()->json(['error' => 'Days requested('. $differenceInDays.') is more than the casual leaves available('. $availableCasualLeaves.' Days).'])
                : redirect()->back()->with('error', 'Days requested('. $differenceInDays.') is more than the casual leaves available('. $availableCasualLeaves.' Days).');
            }
            
        } else if ($leaveType == 1) {
            if ($differenceInDays == 1) {
                $leavesTaken = LeaveApplication::where('leave_type_id', $leaveType)
                    ->where('user_id', $userId)
                    ->whereIn('leave_status', [LeaveApplication::Applied,LeaveApplication::Approved])
                    ->whereBetween('leave_from', [$financialYearDetails['start'], $financialYearDetails['end']])
                    ->count();
                if ($leavesTaken > 0) {
                    return $request->ajax()
                ? response()->json(['error' => 'Already taken sick leave for the selected month.'])
                : redirect()->back()->with('error', 'Already taken sick leave for the selected month.');
                
                } else {
                    return $request->ajax()
                    ? response()->json(['error' => 'Days requested is more than the sick leave.'])
                    : redirect()->back()->with('error', 'Days requested is more than the sick leav.');
                }
            }
        }
            

        // Create a new leave application
        $leaveApplication = new LeaveApplication();
        $leaveApplication->user_id = $userId;
        $leaveApplication->leave_type_id = $leaveType;
        $leaveApplication->leave_from = $leaveFrom;
        $leaveApplication->leave_to = Carbon::parse($request->input('leave_to'));
        $leaveApplication->days = $differenceInDays;
        if ($request->hasFile('leave_file')) {
            $filePath = $request->file('leave_file')->store('leave-file', 'public');
            $leaveApplication->leave_file = $filePath;
        } 
        if ($leaveType == 19) { // Check for compensation leave type
            $leaveApplication->compensation_date = $request->compensation_date;
        }
        
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
        $leaveService = new LeaveService();
        $leaveApplication->leave_type_id = $request->editleave_type;
        $leaveApplication->leave_from = $request->editleave_from;
        $leaveApplication->leave_to = $request->editleave_to;
        $leaveFrom = Carbon::parse($$request->editleave_from);
        $leaveTo = Carbon::parse($$request->editleave_to);
        // $differenceInDays = $leaveFrom->diffInDays($leaveTo) + 1; // Adding 1 because both start and end dates are inclusive
        $differenceInDays = $leaveService->getDaysDifference($leaveFrom, $leaveTo);

        $leaveApplication->days = $differenceInDays;
        $leaveApplication->leave_reason = $request->editreason;
        if ($request->input('editleave_type') == 19) {
            $leaveApplication->compensation_date = $request->editcompensation_date;   
        }
        
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

    public function checkCompensationDate(Request $request)
{
    $request->validate([
        'compensation_date' => 'required|date',
    ]);

    $compensationDate = $request->input('compensation_date');
    $userId = Auth::user()->id;

    // Check if the compensation date is a holiday
    $holiday = Holiday::where('holiday_on', $compensationDate)->first();

    if ($holiday) {
        // If it's a holiday, check for attendance
        $attendance = EmployeeAttendance::where('user_id', $userId)
            ->where('login_date', $compensationDate)
            ->first();

        if (!$attendance) {
            return response()->json(['errors' => ['attendance' => 'Attendance entry must exist for the Compensation Date.']], 400);
        }

        // Check if there are any leave applications for the user on that date
        $previousLeave = LeaveApplication::where('user_id', $userId)
            ->where('compensation_date', $compensationDate)
            ->whereIn('leave_status', [LeaveApplication::Applied, LeaveApplication::Approved]) 
            ->exists();

        if ($previousLeave) {
            return response()->json(['errors' => ['leave' => 'Leave cannot be applied for a date where previous compensation leave exists.']], 400);
        }

        // If all checks pass, return a success response
        return response()->json(['success' => 'Compensation date is valid.']);
    } else {
        // If not a holiday, check if it's a Sunday
        $selectedDate = new DateTime($compensationDate);
        if ($selectedDate->format('N') !== '7') { // 7 is Sunday
            return response()->json(['errors' => ['holiday' => 'Compensation Date must be a holiday or a Sunday.']], 400);
        }

        // If it's a Sunday, check for attendance
        $attendance = EmployeeAttendance::where('user_id', $userId)
            ->where('login_date', $compensationDate)
            ->first();

        if (!$attendance) {
            return response()->json(['errors' => ['attendance' => 'Attendance entry must exist for the Compensation Date.']], 400);
        }

        // Check if there are any leave applications for the user on that date
        $previousLeave = LeaveApplication::where('user_id', $userId)
            ->where('leave_from', '<=', $compensationDate)
            ->where('leave_to', '>=', $compensationDate)
            ->exists();

        if ($previousLeave) {
            return response()->json(['errors' => ['leave' => 'Leave cannot be applied on a date where previous leave exists.']], 400);
        }

        // If all checks pass, return a success response
        return response()->json(['success' => 'Compensation date is valid.']);
    }
}


}
