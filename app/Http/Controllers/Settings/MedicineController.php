<?php

namespace App\Http\Controllers\Settings;

use App\Models\Medicine;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;
use App\Http\Requests\Settings\MedicineRequest;

class MedicineController extends Controller
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
                ->addColumn('action', function ($row) {

                    $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="'.$row->id.'"
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="'.$row->id.'" title="delete">
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
            // Create a new medicine instance
            $medicineEntry = new Medicine();
            $medicineEntry->med_bar_code = $request->input('med_bar_code');
            $medicineEntry->med_name = $request->input('med_name');
            $medicineEntry->med_company = $request->input('med_company');
            $medicineEntry->med_strength = $request->input('med_strength');
            $medicineEntry->med_remarks = $request->input('med_remarks');
            $medicineEntry->med_price = $request->input('med_price');
            $medicineEntry->expiry_date = $request->input('expiry_date');
            $medicineEntry->quantity = $request->input('quantity');
            $medicineEntry->stock_status = $request->input('stock_status');
            $medicineEntry->status = $request->input('status'); 

            $saved = $medicineEntry->save();

            if ($saved) {
                return redirect()->back()->with('success', 'Medicine entry created successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to create medicine entry. Please try again.');
            }
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create medicine entry: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Medicine $medicine)
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
    public function update(MedicineRequest $request)
    {
        try {
            $medicine = Medicine::findOrFail($request->edit_medicine_id);
            
            // Update medicine fields based on form data
            $medicine->med_bar_code = $request->med_bar_code;
            $medicine->med_name = $request->med_name;
            $medicine->med_company = $request->med_company;
            $medicine->med_strength = $request->med_strength;
            $medicine->med_remarks = $request->med_remarks;
            $medicine->med_price = $request->med_price;
            $medicine->expiry_date = $request->expiry_date;
            $medicine->quantity = $request->quantity;
            $medicine->stock_status = $request->stock_status;
            $medicine->status = $request->status; 
            
            // Save the updated medicine
            $medicine->save();

            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['success' => 'Medicine details updated successfully.']);
            }

            // Redirect back with success message for non-AJAX request
            return redirect()->back()->with('success', 'Medicine details updated successfully.');
            
        } catch (\Exception $e) {
            // Handle any unexpected errors
            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to update medicine details. Please try again.'], 500);
            }

            // Redirect back with error message for non-AJAX request
            return redirect()->back()->with('error', 'Failed to update medicine details. Please try again.');
        }
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
