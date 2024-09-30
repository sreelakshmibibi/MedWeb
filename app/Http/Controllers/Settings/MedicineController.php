<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\MedicineRequest;
use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\MedicinePurchaseItem;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;
use Illuminate\Support\Facades\Log;

class MedicineController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings medicines', ['only' => ['index', 'store', 'update', 'edit', 'destroy']]);

    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $medicinePurchaseItems = MedicinePurchaseItem::with(['purchase.supplier', 'medicine']) // Eager load purchase, supplier, and medicine
            ->get();
            //Log::info('$medicinePurchaseItems: '.$medicinePurchaseItems);
            return DataTables::of($medicinePurchaseItems)
                ->addIndexColumn() 
                ->addColumn('med_bar_code', function ($row) {
                    return $row->medicine->med_bar_code ?? ''; 
                })
                ->addColumn('med_name', function ($row) {
                    return $row->medicine->med_name ?? ''; 
                })
                ->addColumn('med_company', function ($row) {
                    return $row->medicine->med_company ?? ''; 
                })
                ->addColumn('stock', function ($row) {
                    return $row->medicine->stock ?? 'N/A'; 
                })
                ->addColumn('batch_no', function ($row) {
                    return $row->batch_no ?? 'N/A'; 
                })
                ->addColumn('med_price', function ($row) {
                    return $row->med_price ?? 0;
                })
                ->addColumn('expiry_date', function ($row) {
                    return $row->expiry_date ? $row->expiry_date : ''; 
                })
                ->addColumn('supplier', function ($row) {
                    return $row->purchase ? $row->purchase->supplier->name: 'N/A'; 
                })
                ->addColumn('total_quantity', function ($row) {
                    return $row->total_quantity ?? 'N/A'; 
                })
                ->addColumn('purchase_amount', function ($row) {
                    return $row->purchase_amount ?? 'N/A'; 
                })
                ->addColumn('purchase_date', function ($row) {
                    return $row->purchase ? $row->purchase->invoice_date : ''; 
                })
                ->addColumn('status', function ($row) {
                    if ($row->medicine->stock_status == "In Stock") {
                        return '<span class="text-success" title="in stock"><i class="fa-solid fa-circle-check"></i></span>';
                    } else {
                        return '<span class="text-danger" title="out of stock"><i class="fa-solid fa-circle-xmark"></i></span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>';
                // <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                // <i class="fa fa-trash"></i></button>
                })
                ->rawColumns(['status', 'action']) 
                ->make(true);

        }

        // Fetch suppliers where status is active
        $suppliers = Supplier::where('status', 'Y')
            ->orderBy('name', 'asc')
            ->get();

        return view('settings.medicine.index', compact('suppliers'));
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
            $medicineEntry->supplier_id = $request->input('med_supplier');
            $medicineEntry->med_purchase_amount = $request->input('med_purchase_amount');
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $medicine = Medicine::with(['purchaseItems.supplier'])->find($id);
        $medicine = MedicinePurchaseItem::with(['purchase.supplier', 'medicine'])
        ->find($id); 
        Log::info('$EditmedicinePurchaseItems: '.$medicine);
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
            $medicinePurchaseItem = MedicinePurchaseItem::findOrFail($request->edit_medicine_purchase_id);
            $medicinePurchaseItem->med_price = $request->med_price;
            $medicinePurchaseItem->expiry_date = $request->expiry_date;
            $medicinePurchaseItem->package_type = $request->package_type;
            // $medicinePurchaseItem->units_per_package = $request->units_per_package;
            // $medicinePurchaseItem->package_count = $request->package_count;
            // $medicinePurchaseItem->total_quantity = $request->total_quantity;
            // $medicinePurchaseItem->purchase_amount = $request->med_purchase_amount;
            $medicinePurchaseItem->save();

            $medicine = Medicine::findOrFail($request->edit_medicine_id);
            // Update medicine fields based on form data
            $medicine->med_bar_code = $request->med_bar_code;
            $medicine->med_name = ucwords(strtolower($request->med_name));
            $medicine->med_company = ucwords($request->med_company);
            $medicine->med_remarks = $request->med_remarks;
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
