<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DiseaseRequest;
use App\Models\Disease;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */public function index(Request $request)
    {
        if ($request->ajax()) {

            $diseases = Disease::orderBy('name', 'asc')->get();

            return DataTables::of($diseases)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="'.$row->id.'"
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="'.$row->id.'" title="delete">
                        <i class="fa fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        //return view('settings.department.index');
        // $menuItems = $this->commonService->getMenuItems();

        // Return the view with menu items
        return view('settings.diseases.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiseaseRequest $request)
    {
        try {
            // Create a new department instance
            $disease = new Disease();
            $disease->icd_code = $request->input('icd_code');
            $disease->name = $request->input('name');
            $disease->description = $request->input('description');
            $disease->status = $request->input('status');
           
            // Save the department
            $i = $disease->save();
            if ($i) {
                return redirect()->back()->with('success', 'Disease created successfully');
            }
        } catch (\Exception $e) {
            print_r($e->getMessage());
            exit;
            return redirect()->back()->with('error', 'Failed to create disease: '.$e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $disease = Disease::find($id);
        if (! $disease) {
            abort(404);
        }

        return $disease;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $disease = Disease::findOrFail($request->edit_disease_id);

            // Update department fields based on form data
            $disease->icd_code = $request->edit_icd_code;
            $disease->name = $request->edit_disease;
            $disease->description = $request->edit_description;
            $disease->status = $request->status;

            // Save the updated department
            $disease->save();

            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['success' => 'Disease updated successfully.']);
            }

            // Redirect back with success message for non-AJAX request
            return redirect()->back()->with('success', 'Disease updated successfully.');
        } catch (\Exception $e) {
            // Handle any unexpected errors
            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to update disease. Please try again.'], 500);
            }

            // Redirect back with error message for non-AJAX request
            return redirect()->back()->with('error', 'Failed to update disease. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $disease = Disease::findOrFail($id);
        $disease->delete();

        return response()->json(['success', 'Disease deleted successfully.'], 201);
    }
}
