<?php

namespace App\Http\Controllers;

use App\Models\OrderPlaced;
use App\Models\Technician;
use App\Models\TeethRow;
use App\Models\ToothExamination;
use App\Services\ReportService;
use Illuminate\Http\Request;

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
                ->where('billable', 'Y');
        
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
        $totalAmount = 0;
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
                $totalAmount += $d->lab_cost;
            }
        }
    
        return response()->json(['data' => $treatmentPlan, 'totalAmount' => $totalAmount]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
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
