<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Requests\Settings\MedicineRequest;
use App\Http\Requests\Settings\LeaveRequest;
use Yajra\DataTables\DataTables as DataTables;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $medicines = Medicine::orderBy('med_name', 'asc')->get();

            return DataTables::of($medicines)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    // if ($row->stock_status == "In Stock") {
                    //     $btn1 = '<span class="badge badge-success-light d-inline-block w-100">in stock</span>';
                    // } else {
                    //     $btn1 = '<span class="badge badge-danger-light d-inline-block w-100">out of stock</span>';
                    // }
                    if ($row->stock_status == "In Stock") {
                        $btn1 = '<span class="text-success" title="in stock"><i class="fa-solid fa-circle-check"></i></span>';
                    } else {
                        $btn1 = '<span class="text-danger" title="out of stock"><i class="fa-solid fa-circle-xmark"></i></span>';
                    }
                    return $btn1;
                })
                ->addColumn('action', function ($row) {

                    $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('settings.leave.index');
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
    public function store(Request $request)
    {

    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

    }

    public function view(Request $request)
    {
        if ($request->ajax()) {

            $medicines = Medicine::orderBy('med_name', 'asc')->get();

            return DataTables::of($medicines)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    // if ($row->stock_status == "In Stock") {
                    //     $btn1 = '<span class="badge badge-success-light d-inline-block w-100">in stock</span>';
                    // } else {
                    //     $btn1 = '<span class="badge badge-danger-light d-inline-block w-100">out of stock</span>';
                    // }
                    if ($row->stock_status == "In Stock") {
                        $btn1 = '<span class="text-success" title="in stock"><i class="fa-solid fa-circle-check"></i></span>';
                    } else {
                        $btn1 = '<span class="text-danger" title="out of stock"><i class="fa-solid fa-circle-xmark"></i></span>';
                    }
                    return $btn1;
                })
                ->addColumn('action', function ($row) {

                    $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-primary btn-approve btn-xs me-1" title="approve" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-approve" ><i class="fa fa-check"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('settings.leave.staff_leave');
    }
}
