<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\LabBill;
use App\Models\OrderPlaced;
use App\Models\PatientTreatmentBilling;
use App\Models\Technician;
use App\Models\TeethRow;
use App\Models\ToothExamination;
use App\Services\ReportService;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LabBillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reportService = new ReportService();
        $treatmentPlans = $reportService->getTreatmentPlans();
        $branches = $reportService->getBranches();
        $technicians = Technician::all();
        return view('billing.lab_bill', compact('branches', 'treatmentPlans', 'technicians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $query = OrderPlaced::with(['toothExamination', 'treatmentPlan'])
                ->where('order_status', OrderPlaced::DELIVERED)
                ->where('billable', 'Y')
                ->where('bill_paid', 'N');

        
        // Apply filters based on request inputs
        if ($request->filled('serviceFromDate')) {
            $query->whereDate('delivered_on', '>=', $request->serviceFromDate);
        }
    
        if ($request->filled('serviceToDate')) {
            $query->whereDate('delivered_on', '<=', $request->serviceToDate);
        }
    
        if ($request->filled('serviceBranch')) {
            // Filter by app_branch in the related appointment table
            $query->whereHas('toothExamination.appointment', function ($q) use ($request) {
                $q->where('app_branch', $request->serviceBranch);
            });
        }
        
        if ($request->filled('serviceTechnicianId')) {
            $query->where('technician_id', $request->serviceTechnicianId);
        }
    
        $data = $query->get();
        $bill_amount = 0;
        $treatmentPlan = [];
        foreach ($data as $d) {
            if ($d->treatment_plan_id != null) {
                $patientName = str_replace('<br>', ' ', $d->toothExamination->patient->first_name) . ' ' . $d->toothExamination->patient->last_name;
                $shade = $d->toothExamination->shade != null ? $d->toothExamination->shade->shade_name : "null";
                $tooth = null;
                if ($d->toothExamination->tooth_id != null) {
                    $tooth = $d->toothExamination->tooth_id;
                } else if ($d->toothExamination->row_id != null) {
                    switch ($d->toothExamination->row_id) {
                        case 1:
                            $tooth = TeethRow::Row_1_Desc;
                            break;
                        case 2:
                            $tooth = TeethRow::Row_2_Desc;
                            break;
                        case 3:
                            $tooth = TeethRow::Row_3_Desc;
                            break;
                        case 4:
                            $tooth = TeethRow::Row_4_Desc;
                            break;
                        case 5:
                            $tooth = TeethRow::Row_All_Desc;
                            break;
                    }
                }
    
                $treatmentPlan[] = [
                    "id" => $d->id,
                    "patient_id" => $d->toothExamination->patient_id,
                    "name" => $patientName,
                    "tooth_id" => $tooth,
                    "plan" => $d->treatmentPlan->plan,
                    "shade" => $shade,
                    "instructions" => $d->toothExamination->instructions,
                    "amount" => $d->lab_cost,
                ];
                $bill_amount += $d->lab_cost;
            }
        }
        $previousBill = LabBill::where('branch_id', $request->serviceBranch)
        ->orderBy('id', 'DESC')
        ->first(); // Use first() to get the actual record
    
        $previous_due = 0; // Default value
        
        if ($previousBill) {
            $previous_due = $previousBill->balance_due; // Access the balance_due property
        }
        
        $totalAmount = $bill_amount + $previous_due;
        return response()->json(['data' => $treatmentPlan, 'totalAmount' => $totalAmount, 'previous_due' => $previous_due, 'bill_amount' => $bill_amount]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $labBill = LabBill::create(array_filter([
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'branch_id' => $request->branch_id,
                'technician_id' => $request->technician_id,
                'bill_amount' => $request->bill_amount,
                'previous_due' => $request->previous_due,
                'amount_to_be_paid' => $request->amount_to_be_paid,
                'gpay' => $request->gpay,
                'cash' => $request->cash,
                'card' => $request->card,
                'payment_through' => $request->payment_through,
                'amount_paid' => $request->amount_paid,
                'balance_due' => $request->balance_due,
                'lab_bill_status' => PatientTreatmentBilling::PAYMENT_DONE,
            ]));
            if ($labBill) {
                $orderStatusUpdate = OrderPlaced::where('order_status', OrderPlaced::DELIVERED)
                ->where('billable', 'Y')
                ->whereDate('delivered_on', '>=', $request->from_date)
                ->whereDate('delivered_on', '<=', $request->to_date)
                ->update(['bill_paid' => 'Y']);
                if ($orderStatusUpdate) {
                    DB::commit();
                    return response()->json(['success'=> 'Lab bills paid successfully']);
                }
            } else {
                DB::rollBack();
                return response()->json(['error'=> 'Lab bills payment unsuccessfull']);
            }

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error'=> $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {

            $labBills = LabBill::orderBy('id', 'desc')->get();

            return DataTables::of($labBills)
                ->addIndexColumn()
                ->addColumn('from_date', function ($row) {
                    $date = new DateTime($row->from_date);
                    $formattedDate = $date->format('d-m-Y'); 
                    return $formattedDate;
                })
                ->addColumn('to_date', function ($row) {
                    $date = new DateTime($row->to_date);
                    $formattedDate = $date->format('d-m-Y'); 
                    return $formattedDate;
                })
                ->addColumn('previous_due', function ($row) {
                    return $row->previous_due ?? 0;
                })->addColumn('mode', function ($row) {
                    $gpay = $row->gpay ?? 0;
                    $cash = $row->cash ?? 0;
                    $card = $row->card ?? 0;
                    return "GPay : " .$gpay. "<br> Cash : " . $cash ."<br> Card : ". $card;
                })
                ->addColumn('created_at', function ($row) {
                    $createdAt = $row->created_at;
                    $date = new DateTime($createdAt);
                    $formattedDate = $date->format('Y-m-d H:i:s'); // Format as needed
                    return $formattedDate;
                })
                ->addColumn('status', function ($row) {
                $btn1 = null;
                    if ($row->lab_bill_status == PatientTreatmentBilling::PAYMENT_DONE) {
                        $btn1 = '<span class="text-success" title="active"><i class="fa-solid fa-circle-check"></i></span>';
                    } else if ($row->lab_bill_status == PatientTreatmentBilling::BILL_CANCELLED) {
                        $btn1 = '<span class="text-danger" title="inactive"><i class="fa-solid fa-circle-xmark"></i></span>';
                    }
                    return $btn1;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['status', 'action', 'mode'])
                ->make(true);
        }

        // Return the view with menu items
        return view('billing.lab_previous_bills');
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

    public function oldPayment()
    {

    }
}
