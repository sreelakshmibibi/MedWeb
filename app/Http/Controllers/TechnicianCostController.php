<?php

namespace App\Http\Controllers;

use App\Models\TreatmentPlanTechnicianCost;
use App\Services\ReportService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class TechnicianCostController extends Controller
{
    public function index(Request $request)
    {
        $reportService = new ReportService();
        $technicianId = base64_decode(Crypt::decrypt($request->id));
        $treatmentPlans = $reportService->getTreatmentPlans();
        $technicianCost = TreatmentPlanTechnicianCost::where('technician_id', $technicianId)->where('status','Y')->get();
        return view('technician.cost', compact('treatmentPlans', 'technicianId', 'technicianCost'));
    }

    public function store(Request $request) {
        try {
            DB::beginTransaction();
            $technicianId = $request->input('technician_id');
            $costs = $request->input('cost'); // Correct the key to match the input data
    
            // Ensure the costs array is not empty
            if (empty($costs)) {
                return response()->json(['error' => 'No costs provided.'], 400);
            }
            $existData = TreatmentPlanTechnicianCost::where('technician_id', $technicianId)
            ->update(['status' => 'N']);
            $i = 0;
            foreach ($costs as $cost) {
                // Insert the new record with updated cost
                $newCost = new TreatmentPlanTechnicianCost();
                $newCost->technician_id = $technicianId;
                $newCost->treatment_plan_id = $cost['plan_id'];
                $newCost->cost = $cost['cost'];
                $i = $newCost->save();
                
            }
            if ($i) {
                DB::commit();
                return response()->json(['success' => 'Costs saved successfully.']);
        
            } else {
                DB::rollback();
                return response()->json(['error' => 'Costs saved unsuccessfully.']);
        
            }
    
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    
}
