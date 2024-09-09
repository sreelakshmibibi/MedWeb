<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;

class TechnicianController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:technician', ['only' => ['index']]);
        $this->middleware('permission:technician_add', ['only' => ['store']]);
        $this->middleware('permission:technician_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:technician_remove', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $technicians = Technician::all();
            
            $dataTable = DataTables::of($technicians)
                ->addIndexColumn()
                ->addColumn('lab_details', function ($row) {
                    $details = [];
                    if (!empty($row->lab_name)) {
                        $details[] = $row->lab_name;
                    }
                    if (!empty($row->lab_address)) {
                        $details[] = $row->lab_address;
                    }
                    if (!empty($row->lab_contact)) {
                        $details[] = $row->lab_contact;
                    }
                    
                    // Join with <br> only if there are details to show
                    return implode('<br>', $details);
                    //return $row->lab_name."<br>". $row->lab_address . "<br>". $row->lab_contact;
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
                    if (Auth::user()->can('technician_edit')) {
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>';
                    } 
                    if (Auth::user()->can('technician cost')) {
                        $base64Id = base64_encode($row->id);
                        $idEncrypted = Crypt::encrypt($base64Id);
                        $btn .= '<a href="'. route('technicianCost', ['id' => $idEncrypted]).'" class="waves-effect waves-light btn btn-circle btn-info btn-xs me-1" title="Add lab cost for each treatment plan"><i class="fas fa-hand-holding-usd"></i></a>';
                    }
                    if (Auth::user()->can('technician_remove')) {
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';
                    }
                    
                    return $btn;
                });
            return $dataTable->rawColumns(['lab_details', 'status', 'action'])
                ->make(true);
        }
        return view('technician.index');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $technician = new Technician();
        $technician->name = $request->tech_name;
        $technician->phone_number = $request->input('tech_phone');
        $technician->lab_name = $request->lab_name;
        $technician->lab_address = $request->lab_address;
        $technician->lab_contact = $request->lab_phone;
        $technician->status = $request->status;

        if ($technician->save()) {
            $message = 'Technician added successfully.';
            return $request->ajax()
                ? response()->json(['success' => $message])
                : redirect()->route('technician.index')->with('success', $message);
        } else {
            $message = 'Failed adding technician.';
            return $request->ajax()
                ? response()->json(['error' => $message])
                : redirect()->back()->with('error', $message);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $technician = Technician::find($id);
        if (!$technician) {
            abort(404);
        }

        return $technician;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $technician = Technician::findorFail($request->edit_tech_id);
        if (!$technician)
            abort(404);
        $technician->name = $request->edit_tech_name;
        $technician->phone_number = $request->edit_tech_phone;
        $technician->lab_name = $request->edit_lab_name;
        $technician->lab_address = $request->edit_lab_address;
        $technician->lab_contact = $request->edit_lab_phone;
        $technician->status = $request->edit_status;
    
        if ($technician->save()) {
            if ($request->ajax()) {
                return response()->json(['success' => 'Technician details updated successfully.']);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['success' => 'Technician details updation failed.']);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $technician = Technician::findOrFail($id);
        if (!$technician)
            abort(404);
        $technician->deleted_by = Auth::user()->id;
        $technician->save();
        $technician->delete();

        return response()->json(['success', 'Technician deleted successfully.'], 201);
    }
}
