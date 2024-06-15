<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\ClinicBranch;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClinicBranchController extends Controller
{
    /**
     * Display a listing of the resource.m
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $clinics = ClinicBranch::query();

            return DataTables::of($clinics)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<div class="d-flex"><a href="#" class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-1">
                            <i class="fa fa-pencil"></i></a><a href="#" class="waves-effect waves-circle btn btn-circle btn-danger btn-xs">
                            <i class="fa fa-trash"></i></a></div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('settings.clinics.clinic_form');
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
        //
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
