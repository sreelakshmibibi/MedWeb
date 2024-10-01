<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\HolidayRequest;
use App\Models\ClinicBranch;
use App\Models\Holiday;
use App\Services\ReportService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class HolidayController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:holidays', ['only' => ['index']]);
        $this->middleware('permission:holidays create', ['only' => ['store']]);
        $this->middleware('permission:holidays update', ['only' => ['edit', 'update']]);
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        if ($request->ajax()) {

            $holidays = Holiday::orderBy('holiday_on', 'asc')->get();

            return DataTables::of($holidays)
                ->addIndexColumn()
                ->addColumn('branches', function ($row) {
                    $branchIds = json_decode($row->branches, true);
                    if (empty($branchIds) || json_decode($row->branches) === [null]) {
                        return "All Branches";
                    } else {
                       // Decode JSON branch ids and get branch names
                    
                    // Ensure to use the correct relation to get the branch names
                    $branches = ClinicBranch::whereIn('id', $branchIds)
                        ->with(['city', 'state', 'country']) // Include the necessary relations
                        ->get()
                        ->map(function ($branch) {
                              $clinicAddress = explode("<br>", $branch->clinic_address);
           
                            return implode(", ", $clinicAddress)  . ', ' .
                                   $branch->city->city . ', ' .
                                   $branch->state->state . ', ' .
                                   $branch->country->country . ', ' .
                                   "Pincode - " . $branch->pincode;
                        })->toArray();

                    return implode(', ', $branches);
                    }
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
                    if (Auth::user()->can('holidays update')) {
                        $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-holiday-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-holiday-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';
                    }
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        // Return the view with menu items
        $reportService = new ReportService();
        $branches = $reportService->getBranches();
        return view('payroll.holiday.index', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HolidayRequest $request)
    {
        try {
            // Check if the holiday date already exists
            $existingHoliday = Holiday::where('holiday_on', $request->holiday_on)->first();

            if ($existingHoliday) {
                return response()->json(['error' => 'Holiday on this date already exists.'], 409);
            }

            $holiday = new Holiday();
            $holiday->holiday_on = $request->holiday_on;
            $holiday->reason = $request->reason;
            $holiday->branches = !empty($request->branches) ? json_encode($request->branches) : null;
            if ($holiday->save()){
                return response()->json(['success'=> 'Holiday saved successfully']);
            } else {
                return response()->json(['error'=> 'Holiday save failed']);
            }

        } catch (Exception  $e) {
            return response()->json(data: ['error'=> $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $holiday = Holiday::find($id);
        if (!$holiday)
            abort(404);
        $holiday->branches = json_decode($holiday->branches, true);
        return $holiday;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $holiday = Holiday::find($request->edit_holiday_id);
            if (!$holiday)
                abort(404);
            
            $existingHoliday = Holiday::where('holiday_on', $request->edit_holiday_on)
                ->where('id', '!=', $holiday->id) // Exclude the current holiday ID
                ->first();
    
            if ($existingHoliday) {
                return response()->json(['error' => 'A holiday on this date already exists.'], 409); // Conflict
            }
    
            $holiday->holiday_on = $request->edit_holiday_on;
            $holiday->reason = $request->edit_reason;
            $holiday->branches = !empty($request->edit_branches) ? json_encode($request->edit_branches) : null;
            if ($holiday->save()){
                return response()->json(['success'=> 'Holiday updated successfully']);
            } else {
                return response()->json(['error'=> 'Holiday update failed']);
            }

        } catch (Exception  $e) {
            return response()->json(data: ['error'=> $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $holiday = Holiday::find($id);
        if (!$holiday)
            abort(404);

        $holiday->status = 'N';
        $holiday->delete_reason = $request->deleteReason;
        if($holiday->save()) {
            if ($holiday->delete()) {
                return response()->json(['success'=> 'Holiday deleted successfully']);
            }
        } else {
            return response()->json(['success'=> 'Holiday deletion failed']);
        }
    }
}
