<?php

namespace App\Http\Controllers;

use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\OrderPlaced;
use App\Models\Technician;
use App\Models\TeethRow;
use App\Models\ToothExamination;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanTechnicianCost;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $query = ToothExamination::with(['treatmentPlan', 'patient', 'shade','appointment']);
        
        // Apply filters based on request inputs
        if ($request->filled('serviceFromDate')) {
            $query->whereDate('tooth_examinations.created_at', '>=', $request->serviceFromDate);
        }
    
        if ($request->filled('serviceToDate')) {
            $query->whereDate('tooth_examinations.created_at', '<=', $request->serviceToDate);
        }
    
        if ($request->filled('serviceBranch')) {
            // Filter by app_branch in the related appointment table
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->where('app_branch', $request->serviceBranch);
            });
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
                    "instructions" => $d->instructions,
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
             DB::beginTransaction();
             $selectedToothExaminations = $request->selectedRows;
             $orderDate = $request->order_date;
             $expectedDate = $request->delivery_expected;
             $technicianId = $request->technician_id;
             $branch = $request->branch;
             $i = 0;
     
             foreach ($selectedToothExaminations as $examination) {
                 $orderPlaced = new OrderPlaced();
                 $orderPlaced->tooth_examination_id = $examination;
                 $exam = ToothExamination::find($examination);
                 $labAmount = TreatmentPlanTechnicianCost::where('treatment_plan_id', $exam->treatment_plan_id)
                                ->where('technician_id', $technicianId)
                                ->where('status', 'Y')
                                ->first();
                 $orderPlaced->treatment_plan_id = $exam->treatment_plan_id;
                 $orderPlaced->patient_id = $exam->patient_id;
                 $orderPlaced->technician_id = $technicianId;
                 $orderPlaced->order_placed_on = $orderDate;
                 $orderPlaced->delivery_expected_on = $expectedDate;
                 $orderPlaced->order_status = OrderPlaced::PLACED;
                 $orderPlaced->lab_cost = $labAmount->cost ?? Null;
                 $i = $orderPlaced->save();
             }
     
             if ($i) {
                 DB::commit();
     
                 $orders = OrderPlaced::with('toothExamination')
                     ->where('order_placed_on', $orderDate)
                     ->where('technician_id', $technicianId)
                     ->where('delivery_expected_on', $expectedDate)
                     ->get();
                $lab = Technician::find($technicianId);
                 $clinicDetails = ClinicBasicDetail::first();
                 $clinicLogo = $clinicDetails->clinic_logo == '' ? 'public/images/logo-It.png' : 'storage/' . $clinicDetails->clinic_logo;
                 $clinicBranch = ClinicBranch::with(['country', 'state', 'city'])->find($branch);
     
                 $pdf = Pdf::loadView('pdf.lab_order_pdf', [
                     'orders' => $orders,
                     'clinicDetails' => $clinicDetails,
                     'clinicLogo' => $clinicLogo,
                     'currency' => $clinicDetails->currency,
                     'branch' => $clinicBranch,
                     'orderDate' => $orderDate,
                     'expectedDelivery' => $expectedDate,
                     'lab' => $lab,
                 ])->setPaper('A4', 'landscape');
     
                 $labOrder = 'labOrder_' . date('Y-m-d_H-i-s') . '.pdf';
                 $filePath = 'public/pdfs/' . $labOrder;
     
                 if (!Storage::exists('pdfs')) {
                     Storage::makeDirectory('pdfs', 0777, true);
                 }
     
                 Storage::put($filePath, $pdf->output());
     
                 if (Storage::exists($filePath)) {
                    return response()->json(['pdfUrl' => Storage::url($filePath)]);
                         
                 } else {
                     return response()->json(['error' => 'File not found.']);
                 }
             } else {
                 DB::rollBack();
                 return response()->json(['error' => 'error']);
             }
         } catch (Exception $e) {
             DB::rollBack();
             return response()->json(['error' => $e->getMessage()]);
         }
     }
     

     

     
}
