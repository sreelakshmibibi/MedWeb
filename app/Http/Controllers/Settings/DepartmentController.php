<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DepartmentRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;


use Yajra\DataTables\DataTables as DataTables;

class DepartmentController extends Controller
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
                ->addColumn('action', function ($row) {

                    $btn = ' <div class="d-flex"><a href="#" class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5">
                            <i class="fa fa-pencil"></i></a>
                            <a href="#" class="waves-effect waves-circle btn btn-circle btn-danger btn-xs">
                            <i class="fa fa-trash"></i></a></div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('settings.department.index');
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
    public function store(DepartmentRequest $request)
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
        //
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
