<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\PatientListRequest;
use App\Models\PatientList;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;


use Yajra\DataTables\DataTables as DataTables;

class PatientListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $departments = Department::query();
            return DataTables::of($departments)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {

                    $btn = '<span class="btn badge badge-danger-light">New Patient</span>';

                    return $btn;
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        if ($request->ajax()) {

            $departments = Department::query();
            return DataTables::of($departments)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('patient.patient_list.index');
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
    public function store(PatientListRequest $request)
    {
        try {
            // Create a new department instance
            $department = new Department();
            $department->department = $request->input('department');
            $department->status = $request->input('status');
            $department->clinic_type_id = 1;

            // Save the department
            $department->save();

            return redirect()->back()->with('success', 'Department created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create department: ' . $e->getMessage());
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
        $department = Department::find($id);
        if (!$department) {
            abort(404);
        }
        return $department;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $department = Department::findOrFail($request->edit_department_id);

        // Update department fields based on form data
        $department->department = $request->department;
        $department->status = $request->status;

        // Save the updated department
        $department->save();

        return redirect()->back()->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json(['success', 'Department deleted successfully.'], 201);
    }
}
