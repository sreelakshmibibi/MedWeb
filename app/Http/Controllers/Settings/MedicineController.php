<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\MedicineRequest;
use App\Models\Medicine;
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
            $medicineEntry->med_name = ucwords(strtolower($request->input('med_name')));
            $medicineEntry->med_company = ucwords($request->input('med_company'));
            $medicineEntry->med_remarks = $request->input('med_remarks');
            $medicineEntry->med_price = $request->input('med_price');
            $medicineEntry->expiry_date = $request->input('expiry_date');
            $medicineEntry->units_per_package = $request->input('units_per_package');
            $medicineEntry->package_count = $request->input('package_count');
            $medicineEntry->total_quantity = $request->input('total_quantity');
            $medicineEntry->package_type = $request->input('package_type');
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
    public function update(MedicineRequest $request, $id)
    {
        try {
            $medicine = Medicine::findOrFail($request->edit_medicine_id);

            // Update medicine fields based on form data
            $medicine->med_bar_code = $request->med_bar_code;
            $medicine->med_name = ucwords(strtolower($request->med_name));
            $medicine->med_company = ucwords($request->med_company);
            $medicine->med_remarks = $request->med_remarks;
            $medicine->med_price = $request->med_price;
            $medicine->expiry_date = $request->expiry_date;
            $medicine->units_per_package = $request->units_per_package;
            $medicine->package_count = $request->package_count;
            $medicine->total_quantity = $request->total_quantity;
            $medicine->package_type = $request->package_type;
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
