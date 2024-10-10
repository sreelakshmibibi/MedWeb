<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CardPay;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Http\Requests\PurchaseRequest;
use Yajra\DataTables\DataTables as DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Exception;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:purchase', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clinicDetails = ClinicBasicDetail::first();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->get();
        $cardPay = CardPay::where('status', 'Y')->get();
        $suppliers = Supplier::where('status', 'Y')->get();
        $purchaseditems = [];
        $mode = "create";

        return view('purchase.purchase.index', compact('clinicDetails', 'clinicBranches', 'cardPay', 'suppliers', 'purchaseditems', 'mode'));
    }

    public function getSupplierDetails($id)
    {
        $supplier = Supplier::find($id);
        $latestBalanceDue = null;
        // $latestBalanceDue = Purchase::where('supplier_id', $id)
        //     ->orderBy('entrydate', 'desc')
        //     ->orderBy('id', 'desc')
        //     ->value('balance_due');

        if ($supplier) {
            $latestPurchase = Purchase::where('supplier_id', $id)
                ->where('status', 'Y')
                ->orderBy('entrydate', 'desc')
                ->orderBy('id', 'desc')
                ->first(['balance_due', 'balance_to_give_back', 'balance_given']);
            // Check if a purchase record was found
            if ($latestPurchase) {
                if ($latestPurchase->balance_given == 'N') {
                    $latestBalanceDue = $latestPurchase->balance_due - $latestPurchase->balance_to_give_back;
                } else {
                    $latestBalanceDue = $latestPurchase->balance_due;
                }
            }
            return response()->json([
                'phone' => $supplier->phone,
                'address' => $supplier->address,
                'gst' => $supplier->gst,
                'balancedue' => $latestBalanceDue
            ]);
        }
        return response()->json(null, 404);
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

    public function store(PurchaseRequest $request)
    {
        \Log::info('Request Data:', $request->all()); // Log request data

        try {
            DB::beginTransaction();
            $data = $request->validated();

            // Add the created_by field
            $data['created_by'] = auth()->id(); // Get the ID of the authenticated user
            $data['purchase_category'] = 'O';
            // Handle multiple file uploads if they exist
            if ($request->hasFile('billfile')) {
                $filePaths = [];
                foreach ($request->file('billfile') as $file) {
                    $filePaths[] = $file->store('purchases', 'public'); // Store in the 'purchases' directory
                }
                $data['billfile'] = json_encode($filePaths); // Save paths as JSON
            }

            // Log before final insertion
            \Log::info('Data before creating purchase:', $data);

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
                'status' => 'Y',
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
            \Log::info('Purchase created successfully:', $purchase->toArray());

            // Prepare purchase items
            $items = [];
            foreach ($request->input('item') as $index => $item) {
                $items[] = [
                    'purchase_id' => $purchase->id,
                    'name' => $item,
                    'price' => $request->input('price')[$index],
                    'quantity' => $request->input('quantity')[$index],
                    'amount' => $request->input('itemAmount')[$index],
                    'created_by' => $data['created_by'],
                    'created_at' => $purchase->created_at,
                    'status' => 'Y',
                ];
            }

            // Insert the data into the PurchaseItem table
            PurchaseItem::insert($items);
            \Log::info('Purchase items added successfully.');
            DB::commit();
            return response()->json(['success' => 'Purchase added successfully!', 'purchase' => $purchase, 'status' => 201], 201);
        } catch (Exception $e) {
            DB::rollback();
            \Log::error('Error storing purchase:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to store purchase.'], 500);
        }
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {

            $purchases = Purchase::select('purchases.*', 'suppliers.name as supplier', 'clinic_branches.clinic_address as branch') // Select fields from both tables
                ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id') // Join the expense_categories table
                ->join('clinic_branches', 'purchases.branch_id', '=', 'clinic_branches.id') // Join the clinic_branches table
                ->where('purchases.purchase_category', '=', 'O')
                ->orderBy('entrydate', 'desc')
                ->orderBy('id', 'desc')
                ->get();
            if (!$purchases)
                abort(404);
            $purchases->transform(function ($purchase) {
                $purchase->branch = str_replace('<br>', ' ', $purchase->branch);
                return $purchase;
            });

            return DataTables::of($purchases)
                ->addIndexColumn()
                ->addColumn('bill_amount', function ($row) {
                    return $row->total_currentbill - $row->discount;
                })->addColumn('mode', function ($row) {
                    $gpay = $row->gpay ?? 0;
                    $cash = $row->cash ?? 0;
                    $card = $row->card ?? 0;
                    return "GPay : " . $gpay . "<br> Cash : " . $cash . "<br> Card : " . $card;
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
                    $base64Id = base64_encode($row->id);
                    $idEncrypted = Crypt::encrypt($base64Id);
                    $btn = '<a href="' . route('purchase.view', $idEncrypted) . '" class="me-1 waves-effect waves-light btn btn-circle btn-info btn-view btn-xs" title="view"><i class="fa fa-eye"></i></a>';
                    if ($row->status == 'Y') {
                        $btn .= '<a href="' . route('purchase.edit', $idEncrypted) . '" class="me-1 waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs" title="edit"><i class="fa fa-pencil"></i></a>
                        <button type="button" class="me-1 waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-cancel-lab-bill" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';
                    }
                    if (!empty($row->billfile)) {
                        $btn .= '<button type="button" data-id="' . $row->id . '" title="download bills"
                                class="ms-1 waves-effect waves-light btn btn-circle btn-secondary btn-xs downloadBills"
                                ><i class="fa-solid fa-download"></i></button>';
                    }

                    return $btn;
                })
                ->rawColumns(['bill_amount', 'mode', 'status', 'action'])
                ->make(true);
        }
        $clinicBasicDetails = ClinicBasicDetail::first();
        // Return the view with menu items
        return view('purchase.purchase.history', compact('clinicBasicDetails'));
    }

    public function downloadBills($id)
    {
        $purchase = Purchase::findOrFail($id);
        if (!$purchase)
            abort(404);
        $entrydate = $purchase->entrydate;
        $bills = json_decode($purchase->billfile); // Assuming billfile stores JSON encoded paths

        if (empty($bills)) {
            // return redirect()->back()->with('error', 'No bills to download.');
            // return redirect()->back()->with('error', 'No bills to download.');
            return response()->with('error', 'No bills to download.');
        }

        // Create a new zip file
        $zip = new \ZipArchive();
        $zipFileName = 'bills_purchase_' . $entrydate . '.zip';
        $zipFilePath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            return redirect()->back()->with('error', 'Could not create ZIP file.');
        }

        foreach ($bills as $filePath) {
            $fullPath = storage_path('app/public/' . $filePath);
            if (file_exists($fullPath)) {
                $zip->addFile($fullPath, basename($filePath));
            }
        }

        $zip->close();

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = base64_decode(Crypt::decrypt($id));
        $clinicDetails = ClinicBasicDetail::first();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->get();
        $cardPay = CardPay::where('status', 'Y')->get();
        $suppliers = Supplier::where('status', 'Y')->get();

        // Get the purchase record
        $purchase = Purchase::findOrFail($id);
        if (!$purchase)
            abort(404);

        // Get the supplier ID from the purchase record
        $supplierId = $purchase->supplier_id;

        // Get the selected supplier
        $selectedSupplier = Supplier::find($supplierId);
        $purchaseditems = PurchaseItem::where('purchase_id', $id)->get();

        $mode = "view";

        return view('purchase.purchase.index', compact('clinicDetails', 'clinicBranches', 'cardPay', 'suppliers', 'purchase', 'selectedSupplier', 'purchaseditems', 'mode'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = base64_decode(Crypt::decrypt($id));
        $clinicDetails = ClinicBasicDetail::first();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->get();
        $cardPay = CardPay::where('status', 'Y')->get();
        $suppliers = Supplier::where('status', 'Y')->get();
        // Get the purchase record
        $purchase = Purchase::findOrFail($id);
        if (!$purchase)
            abort(404);

        // Get the supplier ID from the purchase record
        $supplierId = $purchase->supplier_id;

        // Get the selected supplier
        $selectedSupplier = Supplier::find($supplierId);
        $purchaseditems = PurchaseItem::where('purchase_id', $id)->get();
        $mode = "edit";

        return view('purchase.purchase.index', compact('clinicDetails', 'clinicBranches', 'cardPay', 'suppliers', 'purchase', 'selectedSupplier', 'purchaseditems', 'mode'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseRequest $request)
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

            // Add the updated_by field
            $data['updated_by'] = auth()->id(); // Get the ID of the authenticated user
            $data['created_by'] = auth()->id();
            $data['purchase_category'] = 'O';

            // Handle multiple file uploads if they exist
            if ($request->hasFile('billfile')) {
                $filePaths = [];
                foreach ($request->file('billfile') as $file) {
                    $filePaths[] = $file->store('purchases', 'public'); // Store in the 'purchases' directory
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
            \Log::info('Purchase updated successfully:', $purchase->toArray());

            // Prepare purchase items
            $items = [];
            foreach ($request->input('item') as $index => $item) {
                $items[] = [
                    'purchase_id' => $purchase->id,
                    'name' => $item,
                    'price' => $request->input('price')[$index],
                    'quantity' => $request->input('quantity')[$index],
                    'amount' => $request->input('itemAmount')[$index],
                    // 'updated_by' => $data['updated_by'],
                    'created_by' => $data['created_by'],
                    'created_at' => now(),
                ];
            }

            // Update or insert the data into the PurchaseItem table
            PurchaseItem::where('purchase_id', $purchase->id)->delete(); // Remove old items
            PurchaseItem::insert($items);
            \Log::info('Purchase items updated successfully.');
            DB::commit();
            return response()->json(['success' => 'Purchase updated successfully!', 'purchase' => $purchase, 'status' => 200], 200);
        } catch (Exception $e) {
            DB::rollback();
            \Log::error('Error updating purchase:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update purchase.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        try {
            DB::beginTransaction();
            $purchase = Purchase::find($id);
            if (!$purchase)
                abort(404);
            $itemStatusUpdate = PurchaseItem::where('purchase_id', $id)
                ->update(['status' => 'N', 'updated_by' => auth()->id()]);
            if ($itemStatusUpdate) {
                $purchase->purchase_delete_reason = $request->reason;
                $purchase->status = 'N';
                $purchase->deleted_by = auth()->id();
                if ($purchase->save()) {
                    DB::commit();
                    return response()->json(['success' => 'Purchase cancelled successfully']);
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'Purchase Error! Please try again.']);
                }
            } else {
                DB::rollBack();
                return response()->json(['error' => 'Item Error ! Please try again.']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
