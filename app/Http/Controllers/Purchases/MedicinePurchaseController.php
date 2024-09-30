<?php

namespace App\Http\Controllers\Purchases;
use App\Models\CardPay;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\Medicine;
use App\Models\MedicinePurchaseItem;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Http\Requests\MedicinePurchaseRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedicinePurchaseController extends Controller
{
    public function index()
    {
        $clinicDetails = ClinicBasicDetail::first();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->get();
        $cardPay = CardPay::where('status', 'Y')->get();
        $suppliers = Supplier::where('status', 'Y')->get();
        $medicines = Medicine::where('status', 'Y')->get();

        return view('purchase.medicine_purchase.index', compact('clinicDetails', 'clinicBranches', 'cardPay', 'suppliers', 'medicines'));
    }



    public function store(MedicinePurchaseRequest $request)
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
            \Log::info('Medicine Purchase created successfully:', $purchase->toArray());

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
                    'created_by' => $data['created_by'],
                    'created_at' => $purchase->created_at,
                ];
            }

            // Insert the data into the PurchaseItem table
            MedicinePurchaseItem::insert($items);
            \Log::info('Medicine purchase items added successfully.');

            return response()->json(['success' => 'Medicine purchase added successfully!', 'purchase' => $purchase], 201);
        } catch (\Exception $e) {
            \Log::error('Error storing  medicine purchase:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to store medicine purchase.'], 500);
        }

    }
}
