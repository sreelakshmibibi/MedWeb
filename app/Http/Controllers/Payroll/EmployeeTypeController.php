<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeTypeRequest;
use App\Models\EmployeeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class EmployeeTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employeetype', ['only' => ['index']]);
        $this->middleware('permission:employeetype create', ['only' => ['store']]);
        $this->middleware('permission:employeetype update', ['only' => ['edit', 'update']]);
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        if ($request->ajax()) {

            $employeeType = EmployeeType::orderBy('employee_type', 'asc')->get();

            return DataTables::of($employeeType)
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
                    if (Auth::user()->can('employeetype update')) {
                        $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-employee-type-edit" ><i class="fa fa-pencil"></i></button>';
                        // $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        // <i class="fa fa-trash"></i></button>';
                    }
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        // Return the view with menu items
        return view('payroll.employeeType.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Create a new department instance
            $employeeType = new EmployeeType();
            // $department->department = $request->input('department');
            // Capitalize each word in the department name before assigning it
            $employeeType->employee_type = ucwords(strtolower($request->input('employee_type')));
            $employeeType->status = $request->input('status');
            
            // Save the department
            $i = $employeeType->save();
            if ($i) {
                if ($request->ajax()) {
                    return response()->json(['success' => 'Employee Type created successfully.']);
                }
                return redirect()->back()->with('success', 'Employee Type created successfully');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Employee Type not created.'. $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'Failed to create Employee Type: ' . $e->getMessage());
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employeeType = EmployeeType::find($id);
        if (!$employeeType) {
            abort(404);
        }

        return $employeeType;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $employeeType = EmployeeType::findOrFail($request->edit_employee_type_id);

            // Update department fields based on form data
            $employeeType->employee_type = $request->edit_employee_type;
            $employeeType->status = $request->edit_status;

            // Save the updated department
            $employeeType->save();

            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['success' => 'Employee Type updated successfully.']);
            }

            // Redirect back with success message for non-AJAX request
            return redirect()->back()->with('success', 'Employee Type updated successfully.');
        } catch (\Exception $e) {
            // Handle any unexpected errors
            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to update Employee Type. Please try again.'.$e->getMessage()], 500);
            }

            // Redirect back with error message for non-AJAX request
            return redirect()->back()->with('error', 'Failed to update Employee Type. Please try again.');
        }
    }
}
