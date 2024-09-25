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
        $latestBalanceDue = null;
        // $latestBalanceDue = Purchase::where('supplier_id', $id)
        //     ->orderBy('entrydate', 'desc')
        //     ->orderBy('id', 'desc')
        //     ->value('balance_due');

        if ($supplier) {
            $latestPurchase = Purchase::where('supplier_id', $id)
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
                ];
            }

            // Insert the data into the PurchaseItem table
            PurchaseItem::insert($items);
            \Log::info('Purchase items added successfully.');

            return response()->json(['success' => 'Purchase added successfully!', 'purchase' => $purchase], 201);
        } catch (\Exception $e) {
            \Log::error('Error storing purchase:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to store purchase.'], 500);
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
