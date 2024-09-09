<?php

namespace App\Http\Controllers;

use App\Models\OrderPlaced;
use App\Models\Technician;
use App\Models\TeethRow;
use App\Models\ToothExamination;
use App\Models\TreatmentPlan;
use App\Services\ReportService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaceOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:order place', ['only' => ['index', 'create']]);
        $this->middleware('permission:order place store', ['only' => ['store']]);
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reportService = new ReportService();
        $treatmentPlans = $reportService->getTreatmentPlans();
        $branches = $reportService->getBranches();
        $technicians = Technician::all();
        return view('orders.index', compact('branches', 'treatmentPlans', 'technicians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $query = ToothExamination::with(['treatmentPlan', 'patient', 'shade']);
        
        // Apply filters based on request inputs
        if ($request->filled('serviceFromDate')) {
            $query->whereDate('tooth_examinations.created_at', '>=', $request->serviceFromDate);
        }
    
        if ($request->filled('serviceToDate')) {
            $query->whereDate('tooth_examinations.created_at', '<=', $request->serviceToDate);
        }
    
        if ($request->filled('serviceBranch')) {
            $query->where('a.app_branch', $request->serviceBranch);
        }
        
        if ($request->filled('serviceTreatmentPlan')) {
            $query->where('tooth_examinations.treatment_plan_id', $request->serviceTreatmentPlan);
        }
    
        $data = $query->get();
    
        $treatmentPlan = [];
        foreach ($data as $d) {
            if ($d->treatment_plan_id != null) {
                $patientName = str_replace('<br>', ' ', $d->patient->first_name) . ' ' . $d->patient->last_name;
                $shade = $d->shade != null ? $d->shade->shade_name : "null";
                $tooth = null;
                if ($d->tooth_id != null) {
                    $tooth = $d->tooth_id;
                } else if ($d->row_id != null) {
                    switch ($d->row_id) {
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
                    "patient_id" => $d->patient_id,
                    "name" => $patientName,
                    "tooth_id" => $tooth,
                    "plan" => $d->treatmentPlan->plan,
                    "shade" => $shade,
                    "instructions" => $d->instructions
                ];
            }
        }
    
        // Remove duplicate entries
        // $treatmentPlan = array_unique($treatmentPlan, SORT_REGULAR);
        $treatmentPlanArray = [];
        foreach ($treatmentPlan as $entry) {
            // Create a unique key based on patient_id, plan, and tooth_id
            $key = $entry['patient_id'] . '-' . $entry['plan'] . '-' . $entry['tooth_id'];
            
            // Store the entry in the associative array
            $treatmentPlanArray[$key] = $entry;
        }
        
        // Convert associative array back to a regular indexed array
        $treatmentPlan = array_values($treatmentPlanArray);
        $ordersPlaced = OrderPlaced::where('order_status', OrderPlaced::PLACED)
        ->orWhere('order_status', OrderPlaced::PENDING)
        ->orWhere('order_status', OrderPlaced::DELIVERED)
        ->pluck('tooth_examination_id')->toArray();
        $treatmentPlan = array_filter($treatmentPlanArray, function ($treatment) use ($ordersPlaced) {
            return !in_array($treatment['id'], $ordersPlaced);
        });
        return response()->json(['data' => $treatmentPlan]);
    }
    

    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $request)
     {
         try {
             $selectedToothExaminations = $request->selectedRows;
             $orderDate = $request->order_date;
             $expectedDate = $request->delivery_expected;
             $technicianId = $request->technician_id;
     
             foreach ($selectedToothExaminations as $examination) {
                 // Create a new OrderPlaced instance for each tooth examination
                 $orderPlaced = new OrderPlaced();
                 
                 $orderPlaced->tooth_examination_id = $examination;
                 $exam = ToothExamination::find($examination);
                 $orderPlaced->treatment_plan_id = $exam->treatment_plan_id;
                 $orderPlaced->patient_id = $exam->patient_id;
                 $orderPlaced->technician_id = $technicianId;
                 $orderPlaced->order_placed_on = $orderDate;
                 $orderPlaced->delivery_expected_on = $expectedDate;
                 $orderPlaced->order_status = OrderPlaced::PLACED;
     
                 // Save the OrderPlaced instance
                 $orderPlaced->save();
             }
             //take print on placing order
             return response()->json(['success' => 'Order placed successfully.']);  
         } catch (Exception $e) {
             return response()->json(['error' => $e->getMessage()]);
         }
     }
     
}
