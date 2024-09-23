<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:supplier', ['only' => ['index']]);
        $this->middleware('permission:supplier add', ['only' => ['store']]);
        $this->middleware('permission:supplier edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:supplier delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $technicians = Supplier::all();
            
            $dataTable = DataTables::of($technicians)
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
                    if (Auth::user()->can('supplier edit')) {
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>';
                    } 
                    if (Auth::user()->can('supplier delete')) {
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';
                    }
                    
                    return $btn;
                });
            return $dataTable->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('suppliers.index');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->phone = $request->input('phone');
        $supplier->gst = $request->gst;
        $supplier->address = $request->address;
        $supplier->status = $request->status;

        if ($supplier->save()) {
            $message = 'Supplier added successfully.';
            return $request->ajax()
                ? response()->json(['success' => $message])
                : redirect()->route('suppliers.index')->with('success', $message);
        } else {
            $message = 'Failed adding supplier.';
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
        $supplier = Supplier::find($id);
        if (!$supplier) {
            abort(404);
        }

        return $supplier;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $supplier = Supplier::findorFail($request->edit_supplier_id);
        if (!$supplier)
            abort(404);
        $supplier->name = $request->edit_supplier_name;
        $supplier->phone = $request->edit_supplier_phone;
        $supplier->gst = $request->edit_supplier_gst;
        $supplier->address = $request->edit_supplier_address;
        $supplier->status = $request->edit_status;
    
        if ($supplier->save()) {
            if ($request->ajax()) {
                return response()->json(['success' => 'Supplier details updated successfully.']);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['success' => 'Supplier details updation failed.']);
            }
        }
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        if (!$supplier)
            abort(404);
        $supplier->delete();

        return response()->json(['success', 'Supplier deleted successfully.'], 201);
    }
}
