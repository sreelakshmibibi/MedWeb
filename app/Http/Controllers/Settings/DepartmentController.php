<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DepartmentRequest;
use App\Models\Department;
// use App\Services\CommonService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

use App\Models\User;

use App\Notifications\WelcomeVerifyNotification;

class DepartmentController extends Controller
{
    protected $commonService;

    public function __construct()
    {
        $this->middleware('permission:settings departments', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        if ($request->ajax()) {

            $departments = Department::orderBy('department', 'asc')->get();

            return DataTables::of($departments)
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
        return view('settings.department.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request)
    {
        try {
            // Create a new department instance
            $department = new Department();
            // $department->department = $request->input('department');
            // Capitalize each word in the department name before assigning it
            $department->department = ucwords(strtolower($request->input('department')));
            $department->status = $request->input('status');
            $department->clinic_type_id = 1;

            // Save the department
            $i = $department->save();
            if ($i) {
                if ($request->ajax()) {
                    return response()->json(['success' => 'Department created successfully.']);
                }
                return redirect()->back()->with('success', 'Department created successfully');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Department not created.'. $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'Failed to create department: ' . $e->getMessage());
        }

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
        try {
            $department = Department::findOrFail($request->edit_department_id);

            // Update department fields based on form data
            $department->department = $request->department;
            $department->status = $request->status;

            // Save the updated department
            $department->save();

            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['success' => 'Department updated successfully.']);
            }

            // Redirect back with success message for non-AJAX request
            return redirect()->back()->with('success', 'Department updated successfully.');
        } catch (\Exception $e) {
            // Handle any unexpected errors
            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to update department. Please try again.'.$e->getMessage()], 500);
            }

            // Redirect back with error message for non-AJAX request
            return redirect()->back()->with('error', 'Failed to update department. Please try again.');
        }
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
