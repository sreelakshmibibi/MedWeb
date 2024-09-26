<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\HolidayRequest;
use App\Models\ClinicBranch;
use App\Models\Holiday;
use App\Services\ReportService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HolidayController extends Controller
{
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
                    $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';

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
        return $holiday;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
