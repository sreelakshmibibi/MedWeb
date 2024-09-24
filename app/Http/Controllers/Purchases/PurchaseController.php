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

class PurchaseController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:purchase', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);

    // }
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
        return view('purchase.purchase.index', compact('clinicDetails', 'clinicBranches', 'cardPay', 'suppliers'));
    }

    public function getSupplierDetails($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            return response()->json([
                'phone' => $supplier->phone,
                'address' => $supplier->address,
                'gst' => $supplier->gst,
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
            $data = $request->validated();

            // Add the created_by field
            $data['created_by'] = auth()->id(); // Get the ID of the authenticated user

            // Handle multiple file uploads if they exist
            if ($request->hasFile('billfile')) {
                $filePaths = [];
                foreach ($request->file('billfile') as $file) {
                    $filePaths[] = $file->store('purchases', 'public'); // Store in the 'expenses' directory
                }
                $data['billfile'] = json_encode($filePaths); // Save paths as JSON or handle accordingly
            }

            if ($data['name']) {
                return;
            }
            // Check if the supplier is a new one or an existing one
            $supplierId = null;
            if (!is_numeric($data['name'])) {
                // Store the supplier data
                $supplierData = [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'gst' => $data['gst_no'],
                ];
                $supplier = Supplier::create($supplierData);
                $supplierId = $supplier->id;
            } else {
                $supplierId = $data['name']; // Assuming name can also be supplier ID in this case
            }
            // Store the purchase data
            $purchaseData = [
                'branch_id' => $data['branch_id'],
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
                'gpay' => $data['itemgpay'],
                'cash' => $data['itemcash'],
                'card' => $data['itemcard'],
                'amount_paid' => $data['itemAmountPaid'],
                'balance_due' => $data['balance'],
                'balance_to_give_back' => $data['itemBalanceToGiveBack'],
                'balance_given' => $data['itemBalance_given'],
                'consider_for_next_payment' => $data['consider_for_next_payment'],
                'billfile' => $data['billfile'] ?? null,
                'created_by' => $data['created_by'],
            ];

            $purchase = Purchase::create($purchaseData);

            $items = [];
            foreach ($data as $index => $item) {
                $items[] = [
                    'item' => $item,
                    'price' => $request->price[$index],
                    'quantity' => $request->quantity[$index],
                    'itemAmount' => $request->itemAmount[$index],
                    // Add any other fields needed, e.g., 'purchase_id' if applicable
                ];
            }

            // Insert the data into the PurchaseItem table
            PurchaseItem::insert($items);

            //     // Create the purchase record
            //     $purchaseitem = PurchaseItem::create($data);
            return response()->json(['success' => 'Purcahse added successfully!', 'expense' => $purchase], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to store purcahse.'], 500);
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
