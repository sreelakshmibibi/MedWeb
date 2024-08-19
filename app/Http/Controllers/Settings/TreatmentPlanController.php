<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\TreatmentPlanRequest;
use App\Models\TreatmentPlan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TreatmentPlanController extends Controller
{
    protected $commonService;
    public function __construct()
    {
        $this->middleware('permission:treatment_plan', ['only' => ['index', 'store', 'update', 'edit', 'destroy']]);
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $plans = TreatmentPlan::orderBy('plan', 'asc')->get();

            return DataTables::of($plans)
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
        return view('settings.treatment_plan.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TreatmentPlanRequest $request)
    {
        
        try {
            // Create a new department instance
            $plan = new TreatmentPlan();
            // $department->department = $request->input('department');
            // Capitalize each word in the department name before assigning it
            $plan->plan = ucwords(strtolower($request->input('plan')));
            $plan->cost = $request->input('cost');
            $plan->status = $request->input('status');
            
            // Save the department
            $i = $plan->save();
            if ($i) {
                return redirect()->back()->with('success', 'Plan added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add plan : ' . $e->getMessage());
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $plan = TreatmentPlan::find($id);
        if (!$plan) {
            abort(404);
        }

        return $plan;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $plan = TreatmentPlan::findOrFail($request->edit_plan_id);

            // Update department fields based on form data
            $plan->plan = $request->plan;
            $plan->status = $request->status;
            $plan->cost = $request->cost;
            // Save the updated department
            $plan->save();

            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['success' => 'Treatment Plan updated successfully.']);
            }

            // Redirect back with success message for non-AJAX request
            return redirect()->back()->with('success', 'Treatment Plan updated successfully.');
        } catch (\Exception $e) {
            // Handle any unexpected errors
            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to update Treatment Plan. Please try again.'], 500);
            }

            // Redirect back with error message for non-AJAX request
            return redirect()->back()->with('error', 'Failed to update Treatment Plan. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plan = TreatmentPlan::findOrFail($id);
        $plan->delete();

        return response()->json(['success', 'Treatment Plan deleted successfully.'], 201);
    }
}
