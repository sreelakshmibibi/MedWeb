<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveTypeRequest;
use App\Models\Department;
use App\Models\LeaveType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
     public function index(Request $request)
     {
         
         if ($request->ajax()) {
 
             $leaveType = LeaveType::orderBy('type', 'asc')->get();
 
             return DataTables::of($leaveType)
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
                         data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>';
                        //  <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        //  <i class="fa fa-trash"></i></button>';
 
                     return $btn;
                 })
                 ->rawColumns(['status', 'action'])
                 ->make(true);
         }
 
         // Return the view with menu items
         return view('settings.leaveType.index');
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
    
     public function store(LeaveTypeRequest $request): JsonResponse
     {
         try {
             // Create a new leave type entry
             $leaveType = LeaveType::create([
                 'type' => $request->type,
                 'description' => $request->description,
                 'duration' => $request->duration,
                 'duration_type' => $request->duration_type,
                 'payment_status' => $request->payment_status,
                 'status' => $request->status,
             ]);
 
             // Return success response
             return response()->json(['success' => 'Leave type created successfully!', 'data' => $leaveType], 201);
         } catch (\Exception $e) {
             // Return error response
             return response()->json(['error' => 'Failed to create leave type.'], 500);
         }
     }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $leaveType = LeaveType::find($id);
        if (!$leaveType)
        abort(404);

        return $leaveType;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $leaveType = LeaveType::findOrFail($request->input('edit_leaveType_id'));

        // Update the leave type fields
        $leaveType->type = $request->input('edit_type');
        $leaveType->description = $request->input('edit_description');
        $leaveType->duration = $request->input('edit_duration');
        $leaveType->duration_type = $request->input('edit_duration_type');
        $leaveType->payment_status = $request->input('edit_payment_status');
        $leaveType->status = $request->input('edit_status');

    // Save the updated leave type
        if ($leaveType->save()) {
            return response()->json(['success' => 'Leave type updated successfully.']);
        } else {
            return response()->json(['error' => 'Failed to update leave type.'], 500);
        }
    }

}
