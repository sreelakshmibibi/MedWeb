<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\MedicineRequest;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;


use Yajra\DataTables\DataTables as DataTables;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $medicine = Medicine::query();
            return DataTables::of($medicine)
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

        return view('settings.medicine.index');
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
    public function store(MedicineRequest $request)
    {
        try {
            // Create a new department instance
            $medicine = new Medicine();
            $medicine->medicine = $request->input('department');
            $medicine->status = $request->input('status');
            $medicine->clinic_type_id = 1;

            // Save the department
            $medicine->save();

            return redirect()->back()->with('success', 'Medicine created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create medicine: ' . $e->getMessage());
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
        $medicine = Medicine::find($id);
        if (!$medicine) {
            abort(404);
        }
        return $medicine;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $medicine = Medicine::findOrFail($request->edit_medicine_id);

        // Update department fields based on form data
        $medicine->medicine = $request->medicine;
        $medicine->status = $request->status;

        // Save the updated Medicine
        $medicine->save();

        return redirect()->back()->with('success', 'Medicine updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return response()->json(['success', 'Medicine deleted successfully.'], 201);
    }
}
