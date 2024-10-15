<?php

namespace App\Http\Controllers\Purchases;
use App\Models\CardPay;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\Medicine;
use App\Models\MedicinePurchaseItem;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Http\Requests\MedicinePurchaseRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class MedicinePurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:medicine purchase', ['only' => ['index', 'show', 'purchaseHistory']]);
        $this->middleware('permission:medicine purchase store', ['only' => ['store']]);
        $this->middleware('permission:medicine purchase cancel', ['only' => ['destroy']]);
        $this->middleware('permission:medicine purchase update', ['only' => ['edit', 'update']]);

    }
    public function index()
    {
        $clinicDetails = ClinicBasicDetail::first();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->get();
        $cardPay = CardPay::where('status', 'Y')->get();
        $suppliers = Supplier::where('status', 'Y')->get();
        $medicines = Medicine::where('status', 'Y')->get();
        $purchaseditems = [];
        $mode = "create";

        return view('purchase.medicine_purchase.index', compact('clinicDetails', 'clinicBranches', 'cardPay', 'suppliers', 'medicines', 'purchaseditems', 'mode'));
    }



    public function store(MedicinePurchaseRequest $request)
    {
        \Log::info('Request Data:', $request->all()); // Log request data

        try {
            $data = $request->validated();

            // Add the created_by field
            $data['created_by'] = auth()->id(); // Get the ID of the authenticated user
            $data['purchase_category'] = 'M';
            // Handle multiple file uploads if they exist
            if ($request->hasFile('billfile')) {
                $filePaths = [];
                foreach ($request->file('billfile') as $file) {
                    $filePaths[] = $file->store('medicinepurchases', 'public'); // Store in the 'purchases' directory
                }
                $data['billfile'] = json_encode($filePaths); // Save paths as JSON
            }

            // Log before final insertion
            \Log::info('Data before creating medicine purchase:', $data);

            // Extract supplier details
            $supplierDetails = $request->input('supplier');
            $supplierId = null;

            // Check if the supplier is a new one or an existing one
            if (!is_numeric($supplierDetails['name'])) {
                // Store the supplier data
                $supplierData = [
                    'name' => ucwords(strtolower($supplierDetails['name'])),
                    'phone' => $supplierDetails['phone'],
                    'address' => $supplierDetails['address'],
                    'gst' => $supplierDetails['gst_no'],
                    'status' => 'Y',
                    'created_by' => $data['created_by'],
                ];
                $supplier = Supplier::create($supplierData);
                $supplierId = $supplier->id;

                // Log successful supplier creation
                \Log::info('Supplier created successfully:', $supplier->toArray());
            } else {
                $supplierId = $supplierDetails['name']; // Use the supplier ID directly
            }



            $data['consider_for_next_payment'] = $request->has('consider_for_next_payment') ? 'Y' : 'N';
            $data['itemBalance_given'] = $request->has('itemBalance_given') ? 'Y' : 'N';

            // Prepare purchase data
            $purchaseData = [
                'branch_id' => $data['branch_id'],
                'purchase_category' => $data['purchase_category'],
                'invoice_no' => $data['invoice_no'],
                'invoice_date' => $data['invoice_date'],
                'supplier_id' => $supplierId,
                'category' => $data['category'],
                'item_subtotal' => $data['itemtotal'],
                'delivery_charge' => $data['deliverycharge'],
                'gst' => $data['tax'],
                'total_currentbill' => $data['currentbilltotal'],
                'discount' => $data['discount'],
                'previous_due' => $data['previousOutStanding'],
                'amount_to_be_paid' => $data['grandTotal'],
                'billfile' => $data['billfile'] ?? null,
                'created_by' => auth()->id(),
                'entrydate' => now()->toDateString(),
            ];

            // Add conditional fields based on category
            if ($data['category'] !== 'C') {
                $purchaseData = array_merge($purchaseData, [
                    'gpay' => $data['itemgpay'] ?? null,
                    'cash' => $data['itemcash'] ?? null,
                    'card' => $data['itemcard'] ?? null,
                    'amount_paid' => $data['itemAmountPaid'] ?? null,
                    'balance_due' => $data['balance'] ?? null,
                    'balance_to_give_back' => $data['itemBalanceToGiveBack'] ?? null,
                    'balance_given' => $data['itemBalance_given'] ?? 'N', // Assuming you store 'Y' or 'N'
                    'consider_for_next_payment' => $data['consider_for_next_payment'] ?? 'N',
                ]);
            } else {
                $purchaseData['amount_paid'] = 0;
                $purchaseData['balance_due'] = $data['grandTotal'];
                $purchaseData['balance_given'] = 'N';
                $purchaseData['consider_for_next_payment'] = 'N';
            }

            $purchase = Purchase::create($purchaseData);
            \Log::info('Medicine purchase created successfully:', $purchase->toArray());

            // Prepare purchase items
            $items = [];
            foreach ($request->input('item') as $index => $item) {
                // Check if the medicine is a new one or an existing one
                if (!is_numeric($item)) {
                    // Store the medicine data
                    $medicineData = [
                        'med_name' => ucwords(strtolower($item)),
                        'stock' => $request->input('quantity')[$index],
                        'stock_status' => 'In Stock',
                        'status' => 'Y',
                        'created_by' => $data['created_by'],
                    ];
                    $medicine = Medicine::create($medicineData);
                    $medicineId = $medicine->id;

                    // Log successful medicine creation
                    \Log::info('Medicine created successfully:', $medicine->toArray());
                } else {
                    $medicineId = $item;
                    $medicine = Medicine::findOrFail($item);
                    $medicine->stock = $medicine->stock + $request->input('quantity')[$index];
                    $medicine->stock_status = 'In Stock';
                    $medicine->status = 'Y';
                    $medicine->save();
                }
                $items[] = [
                    'purchase_id' => $purchase->id,
                    'medicine_id' => $medicineId,
                    'purchase_unit_price' => $request->input('price')[$index],
                    'batch_no' => $request->input('batchNo')[$index],
                    'expiry_date' => $request->input('expiryDate')[$index],
                    'units_per_package' => $request->input('unitsPerPackage')[$index],
                    'package_count' => $request->input('packageCount')[$index],
                    'package_type' => $request->input('packageType')[$index],
                    'total_quantity' => $request->input('quantity')[$index],
                    'purchase_amount' => $request->input('itemAmount')[$index],
                    'status' => 'Y',
                    'used_stock' => 0,
                    'balance' => $request->input('quantity')[$index],
                    'created_by' => $data['created_by'],
                    'created_at' => $purchase->created_at,
                ];
            }

            // Insert the data into the PurchaseItem table
            MedicinePurchaseItem::insert($items);
            \Log::info('Medicine purchase items added successfully.');

            return response()->json(['success' => 'Medicine purchase added successfully!', 'purchase' => $purchase, 'status' => 201], 201);
        } catch (Exception $e) {
            \Log::error('Error storing  medicine purchase:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to store medicine purchase.'], 500);
        }

    }

    public function purchaseHistory(Request $request)
    {
        if ($request->ajax()) {

            // Fetch purchases
            $purchases = Purchase::select(
                'purchases.*',
                'suppliers.name as supplier',
                'clinic_branches.clinic_address as branch'
            )
                ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id') // Join the suppliers table
                ->join('clinic_branches', 'purchases.branch_id', '=', 'clinic_branches.id') // Join the clinic_branches table
                ->where('purchases.purchase_category', '=', 'M')
                ->orderBy('purchases.entrydate', 'desc')
                ->orderBy('purchases.id', 'desc')
                ->get();

            if ($purchases->isEmpty()) {
                return response()->json(['message' => 'No purchases found'], 404);
            }


            $purchases->transform(function ($purchase) {
                $purchase->branch = str_replace('<br>', ' ', $purchase->branch);
                return $purchase;
            });

            return DataTables::of($purchases)
                ->addIndexColumn()
                ->addColumn('bill_amount', function ($row) {
                    $totalCurrentBill = $row->total_currentbill ?? 0;
                    $discount = $row->discount ?? 0;
                    return $totalCurrentBill - $discount;
                })
                ->addColumn('mode', function ($row) {
                    $gpay = $row->gpay ?? 0;
                    $cash = $row->cash ?? 0;
                    $card = $row->card ?? 0;
                    return "GPay : " . $gpay . "<br> Cash : " . $cash . "<br> Card : " . $card;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'Y') {
                        return '<span class="text-success" title="active"><i class="fa-solid fa-circle-check"></i></span>';
                    } else {
                        return '<span class="text-danger" title="inactive"><i class="fa-solid fa-circle-xmark"></i></span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    if (isset($row->id)) {
                        $base64Id = base64_encode($row->id);
                        $idEncrypted = Crypt::encrypt($base64Id);
                        $btn = '';
                        if (Auth::user()->can('medicine purchase')) {
                            $btn .= '<a href="' . route('medicine.purchase.view', $idEncrypted) . '" class="me-1 waves-effect waves-light btn btn-circle btn-info btn-view btn-xs" title="view"><i class="fa fa-eye"></i></a>';
                        }

                        if ($row->status == 'Y' && Auth::user()->can('medicine purchase update')) {
                            $btn .= '<a href="' . route('medicine.purchase.edit', $idEncrypted) . '" class="me-1 waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs" title="edit"><i class="fa fa-pencil"></i></a>';
                        }
                        if ($row->status == 'Y' && Auth::user()->can('medicine purchase cancel')) {
                            $btn .= '<button type="button" class="me-1 waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-cancel-lab-bill" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';
                        }
                        if (!empty($row->billfile)) {
                            $btn .= '<button type="button" data-id="' . $row->id . '" title="download bills"
                                class="ms-1 waves-effect waves-light btn btn-circle btn-secondary btn-xs downloadBills"
                                ><i class="fa-solid fa-download"></i></button>';
                        }
                        return $btn;
                    }
                    return '';
                })
                ->rawColumns(['bill_amount', 'mode', 'status', 'action'])
                ->make(true);
        }

        $clinicBasicDetails = ClinicBasicDetail::first();
        // Return the view with menu items
        return view('purchase.medicine_purchase.history', compact('clinicBasicDetails'));
    }

    public function show(string $id)
    {
        $id = base64_decode(Crypt::decrypt($id));
        $purchase = Purchase::findOrFail($id);
        if (!$purchase)
            abort(404);

        $clinicDetails = ClinicBasicDetail::first();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->get();
        $cardPay = CardPay::where('status', 'Y')->get();
        $suppliers = Supplier::where('status', 'Y')->get();
        $supplierId = $purchase->supplier_id;
        $selectedSupplier = Supplier::find($supplierId);
        $medicines = Medicine::where('status', 'Y')->get();
        $medicinePurchaseItems = MedicinePurchaseItem::where('purchase_id', $id)
            ->get();

        $mode = "view";

        return view('purchase.medicine_purchase.index', compact('clinicDetails', 'clinicBranches', 'cardPay', 'suppliers', 'purchase', 'selectedSupplier', 'mode', 'medicines', 'medicinePurchaseItems'));

    }

    public function edit(string $id)
    {
        $id = base64_decode(Crypt::decrypt($id));
        $purchase = Purchase::findOrFail($id);
        if (!$purchase)
            abort(404);

        $clinicDetails = ClinicBasicDetail::first();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->get();
        $cardPay = CardPay::where('status', 'Y')->get();
        $suppliers = Supplier::where('status', 'Y')->get();
        $supplierId = $purchase->supplier_id;
        $selectedSupplier = Supplier::find($supplierId);
        $medicinePurchaseItems = MedicinePurchaseItem::where('purchase_id', $id)->get();
        $medicines = Medicine::where('status', 'Y')->get();
        $mode = "edit";

        return view('purchase.medicine_purchase.index', compact('clinicDetails', 'clinicBranches', 'cardPay', 'suppliers', 'purchase', 'selectedSupplier', 'medicinePurchaseItems', 'mode', 'medicines'));

    }

    public function update(MedicinePurchaseRequest $request)
    {

        $id = $request->input('purchase_id');
        \Log::info('Update Request Data:', $request->all()); // Log request data

        try {
            DB::beginTransaction();
            $data = $request->validated();

            // Find the existing purchase
            $purchase = Purchase::findOrFail($id);
            if (!$purchase)
                abort(404);

            $data['updated_by'] = auth()->id(); // Get the ID of the authenticated user
            $data['created_by'] = auth()->id();
            $data['purchase_category'] = 'M';

            // Handle multiple file uploads if they exist
            if ($request->hasFile('billfile')) {
                $filePaths = [];
                foreach ($request->file('billfile') as $file) {
                    $filePaths[] = $file->store('medicinepurchases', 'public'); // Store in the 'purchases' directory
                }
                $data['billfile'] = json_encode($filePaths); // Save paths as JSON
            }

            // Log before final update
            \Log::info('Data before updating purchase:', $data);

            // Extract supplier details
            $supplierDetails = $request->input('supplier');
            $supplierId = null;

            // Check if the supplier is a new one or an existing one
            if (!is_numeric($supplierDetails['name'])) {
                // Store the supplier data
                $supplierData = [
                    'name' => $supplierDetails['name'],
                    'phone' => $supplierDetails['phone'],
                    'address' => $supplierDetails['address'],
                    'gst' => $supplierDetails['gst_no'],
                    'status' => 'Y',
                    'created_by' => $data['created_by'],
                ];
                // Check if supplier already exists, otherwise create a new one
                $supplier = Supplier::where('name', $supplierDetails['name'])->first();
                if (!$supplier) {
                    $supplier = Supplier::create($supplierData);
                } else {
                    $supplier->update($supplierData);
                }
                $supplierId = $supplier->id;

                // Log successful supplier update
                \Log::info('Supplier updated successfully:', $supplier->toArray());
            } else {
                $supplierId = $supplierDetails['name']; // Use the supplier ID directly
            }

            $data['consider_for_next_payment'] = $request->has('consider_for_next_payment') ? 'Y' : 'N';
            $data['itemBalance_given'] = $request->has('itemBalance_given') ? 'Y' : 'N';

            // Prepare purchase data for update
            $purchaseData = [
                'branch_id' => $data['branch_id'],
                'purchase_category' => $data['purchase_category'],
                'invoice_no' => $data['invoice_no'],
                'invoice_date' => $data['invoice_date'],
                'supplier_id' => $supplierId,
                'category' => $data['category'],
                'item_subtotal' => $data['itemtotal'],
                'delivery_charge' => $data['deliverycharge'],
                'gst' => $data['tax'],
                'total_currentbill' => $data['currentbilltotal'],
                'discount' => $data['discount'],
                'previous_due' => $data['previousOutStanding'],
                'amount_to_be_paid' => $data['grandTotal'],
                'billfile' => $data['billfile'] ?? $purchase->billfile, // Keep existing if not updated
                'updated_by' => auth()->id(),
            ];

            // Add conditional fields based on category
            if ($data['category'] !== 'C') {
                $purchaseData = array_merge($purchaseData, [
                    'gpay' => $data['itemgpay'] ?? null,
                    'cash' => $data['itemcash'] ?? null,
                    'card' => $data['itemcard'] ?? null,
                    'amount_paid' => $data['itemAmountPaid'] ?? null,
                    'balance_due' => $data['balance'] ?? null,
                    'balance_to_give_back' => $data['itemBalanceToGiveBack'] ?? null,
                    'balance_given' => $data['itemBalance_given'] ?? 'N',
                    'consider_for_next_payment' => $data['consider_for_next_payment'] ?? 'N',
                ]);
            } else {
                $purchaseData['amount_paid'] = 0;
                $purchaseData['balance_due'] = $data['grandTotal'];
                $purchaseData['balance_given'] = 'N';
                $purchaseData['consider_for_next_payment'] = 'N';
            }

            // Update the purchase
            $purchase->update($purchaseData);
            \Log::info('Medicine purchase updated successfully:', $purchase->toArray());

            $items = [];

            foreach ($request->input('item') as $index => $item) {
                // Check if the medicine is a new one or an existing one
                if (!is_numeric($item)) {
                    // Store the medicine data
                    $medicineData = [
                        'med_name' => ucwords(strtolower($item)),
                        'stock' => $request->input('quantity')[$index],
                        'stock_status' => 'In Stock',
                        'status' => 'Y',
                        'created_by' => $data['created_by'],
                    ];
                    $medicine = Medicine::create($medicineData);
                    $medicineId = $medicine->id;

                    // Log successful medicine creation
                    \Log::info('Medicine created successfully:', $medicine->toArray());
                } else {
                    $medicineId = $item;
                    $medicine = Medicine::findOrFail($item);
                    $exQuantity = $request->input('exquantity')[$index];
                    $exStock = $medicine->stock - $exQuantity;
                    if ($exStock <= 0) {
                        $exStock = 0;
                    }
                    $medicine->stock = $exStock + $request->input('quantity')[$index];
                    $medicine->stock_status = 'In Stock';
                    $medicine->status = 'Y';
                    $medicine->save();
                }
                $items[] = [
                    'purchase_id' => $purchase->id,
                    'medicine_id' => $medicineId,
                    'purchase_unit_price' => $request->input('price')[$index],
                    'batch_no' => $request->input('batchNo')[$index],
                    'expiry_date' => $request->input('expiryDate')[$index],
                    'units_per_package' => $request->input('unitsPerPackage')[$index],
                    'package_count' => $request->input('packageCount')[$index],
                    'package_type' => $request->input('packageType')[$index],
                    'total_quantity' => $request->input('quantity')[$index],
                    'purchase_amount' => $request->input('itemAmount')[$index],
                    'status' => 'Y',
                    'used_stock' => 0,
                    'balance' => $request->input('quantity')[$index],
                    'created_by' => $data['created_by'],
                    'created_at' => $purchase->created_at,
                ];
            }

            // Update or insert the data into the MedicinePurchaseItem table
            MedicinePurchaseItem::where('purchase_id', $purchase->id)->delete(); // Remove old items
            MedicinePurchaseItem::insert($items);
            \Log::info('Medicine purchase items updated successfully.');
            DB::commit();
            return response()->json(['success' => 'Medicine purchase updated successfully!', 'purchase' => $purchase, 'status' => 200], 200);
        } catch (Exception $e) {
            DB::rollback();
            \Log::error('Error updating medicine purchase:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update medicine purchase.'], 500);
        }
    }

    public function destroy(string $id, Request $request)
    {
        try {
            DB::beginTransaction();

            $purchase = Purchase::find($id);
            if (!$purchase) {
                abort(404);
            }

            $purchaseItems = MedicinePurchaseItem::where('purchase_id', $id)->get();

            foreach ($purchaseItems as $item) {
                $medicine = Medicine::find($item->medicine_id);
                if ($medicine) {
                    // Deduct stock
                    $medicine->stock -= $item->total_quantity;
                    if ($medicine->stock <= 0) {
                        $medicine->stock = 0;// Ensure stock doesn't go negative
                        $medicine->stock_status = 'Out of Stock';
                    }
                    $medicine->updated_by = auth()->id(); // Update user who changed the stock
                    $medicine->save();
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'Medicine not found!']);
                }
            }


            $itemStatusUpdate = MedicinePurchaseItem::where('purchase_id', $id)
                ->update(['status' => 'N', 'updated_by' => auth()->id()]);


            if ($itemStatusUpdate) {
                $purchase->purchase_delete_reason = $request->reason;
                $purchase->status = 'N';
                $purchase->deleted_by = auth()->id();
                if ($purchase->save()) {
                    DB::commit();
                    return response()->json(['success' => 'Purchase cancelled and stock updated successfully']);
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'Purchase Error! Please try again.']);
                }
            } else {
                DB::rollBack();
                return response()->json(['error' => 'Item Error! Please try again.']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }


}
