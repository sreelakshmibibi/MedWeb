<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\TreatmentCostRequest;
use App\Models\Treatment;
use App\Models\TreatmentType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class TreatmentCostController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings treatment_cost', ['only' => ['index', 'store', 'update', 'edit', 'destroy']]);
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $treatments = TreatmentType::orderBy('treat_name', 'asc')->get();

            return DataTables::of($treatments)
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

                    $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="'.$row->id.'"
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="'.$row->id.'" title="delete">
                        <i class="fa fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('settings.treatment_cost.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TreatmentCostRequest $request)
    {
        try {
            // Create a new treatment instance
            $treatment = new TreatmentType();
            $treatment->treat_name = ucwords(strtolower($request->input('treat_name')));
            $treatment->treat_cost = $request->input('treat_cost');
            $treatment->status = $request->input('status');
            $treatment->discount_percentage = $request->input('discount');
            $treatment->discount_from = $request->input('discount_from');
            $treatment->discount_to = $request->input('discount_to');

            // Save the treatment
            $i = $treatment->save();
            if ($i) {
                return redirect()->back()->with('success', 'Treatment cost created successfully');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create treatment cost : '.$e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $treatment = TreatmentType::find($id);
        if (! $treatment) {
            abort(404);
        }

        return $treatment;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TreatmentCostRequest $request, $id)
    {
        try {
            $treatment = TreatmentType::findOrFail($request->edit_treatment_cost_id);

            // Update treatment cost fields based on form data
            $treatment->treat_name = ucwords(strtolower($request->treat_name));
            $treatment->treat_cost = $request->treat_cost;
            $treatment->status = $request->status;
            $treatment->discount_percentage = $request->input('discount');
            $treatment->discount_from = $request->input('discount_from');
            $treatment->discount_to = $request->input('discount_to');

            // Save the updated treatment cost
            $treatment->save();

            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['success' => 'Treatment cost updated successfully.']);
            }

            // Redirect back with success message for non-AJAX request
            return redirect()->back()->with('success', 'Treatment cost updated successfully.');

        } catch (\Exception $e) {
            // Handle any unexpected errors
            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to update treatment cost. Please try again.'], 500);
            }

            // Redirect back with error message for non-AJAX request
            return redirect()->back()->with('error', 'Failed to update treatment cost. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $treatment = TreatmentType::findOrFail($id);
        $treatment->delete();

        return response()->json(['success', 'Treatment cost deleted successfully.'], 201);
    }
}
