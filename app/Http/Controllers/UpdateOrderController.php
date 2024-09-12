<?php

namespace App\Http\Controllers;

use App\Models\ClinicBasicDetail;
use App\Models\OrderPlaced;
use App\Models\Shade;
use App\Models\Technician;
use App\Models\TeethRow;
use App\Services\ReportService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UpdateOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:order track', ['only' => ['index']]);
        $this->middleware('permission:order deliver', ['only' => ['update']]);
        $this->middleware('permission:order cancel', ['only' => ['destroy']]);
        $this->middleware('permission:order repeat', ['only' => ['edit','repeat']]);

        
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reportService = new ReportService();
        $plans = $reportService->getTreatmentPlans();
        $shades = Shade::all();
        $branches = $reportService->getBranches();
        $technicians = Technician::all();
        $clinicBasicDetails = ClinicBasicDetail::first();
        if ($request->ajax()) {
            $ordersPlaced = OrderPlaced::with('toothExamination')->orderBy('created_at', 'desc');
            if ($request->filled('serviceFromDate')) {
                $ordersPlaced->whereDate('order_placed_on', '>=', $request->serviceFromDate);
            }
    
            if ($request->filled('serviceToDate')) {
                $ordersPlaced->whereDate('order_placed_on', '<=', $request->serviceToDate);
            }
            
            $ordersPlaced->whereHas('toothExamination.appointment', function($query) use ($request) {
                $query->where('app_branch', $request->serviceBranch);
            });

            if ($request->filled('technician_id')) {
                $ordersPlaced->where('technician_id', $request->technician_id);
            }
    
            if ($request->filled('order_status')) {
                $ordersPlaced->where('order_status', $request->order_status);
            }
    
            $ordersPlaced = $ordersPlaced->get();

            return DataTables::of($ordersPlaced)
                ->addIndexColumn()
                
                ->addColumn('patient', function($row) {
                    $patient = $row->toothExamination->patient;
                    $name = str_replace('<br>', ' ', $patient->first_name) . ' ' . $patient->last_name;
                    return $row->patient_id . " - " .$name;
                })
                ->addColumn('treatment_plan', function($row) {
                   return $row->toothExamination->treatmentPlan->plan;
                   
                })
                ->addColumn('shade', function($row) {
                    return $row->toothExamination->shade_id != null ? $row->toothExamination->shade->shade_name : null;
                    
                 })
                 ->addColumn('instructions', function($row) {
                    return $row->toothExamination->instructions;
                    
                 })
                 ->addColumn('technician', function($row) {
                    return $row->technician->name;
                    
                 })
                 ->addColumn('dates', function($row) {
                    return "P : " . $row->order_placed_on."<br>E : " . $row->order_placed_on."<br>D : " . $row->order_placed_on;
                    
                 })
                 ->addColumn('tooth_id', function($row) {
                    $tooth = null;
                    if ($row->toothExamination->tooth_id != null) {
                        $tooth = $row->toothExamination->tooth_id;
                    } else if ($row->toothExamination->row_id != null) {
                        switch ($row->toothExamination->row_id) {
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
                    return $tooth;
                    
                 })
                ->addColumn('status', function ($row) {
                    $statusMap = [
                        OrderPlaced::PLACED => 'badge-primary',
                        OrderPlaced::CANCELLED => 'badge-danger',
                        OrderPlaced::DELIVERED => 'badge-success',
                        OrderPlaced::PENDING => 'badge-warning',
                        OrderPlaced::REPEAT => 'badge-info',
                    ];
                    $btnClass = isset($statusMap[$row->order_status]) ? $statusMap[$row->order_status] : '';
                    return "<span class='btn d-block btn-xs badge {$btnClass}'>" . OrderPlaced::statusToWords($row->order_status) . '</span>';

                })
                ->addColumn('action', function ($row) {
                    $btn = null; 
                    if ($row->order_status == OrderPlaced::PLACED || $row->order_status == OrderPlaced::PENDING) {
                        //option for delivery updated or cancelled
                        if (Auth::user()->can('order deliver')) {
                            $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-deliver btn-xs me-1" title="Order Delivered" data-bs-toggle="modal" data-id="' . $row->id . '" id="btn-deliver"
                            data-bs-target="#modal-deliver" ><i class="fa fa-check"></i></button>';
                        }
                        if (Auth::user()->can('order cancel')) {
                            $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-cancell btn-xs me-1" title="Cancell Order" data-bs-toggle="modal" data-id="' . $row->id . '" id="btn-cancell"
                            data-bs-target="#modal-cancell" ><i class="fa fa-close"></i></button>';
                        }
                        
                    }
                     if ($row->order_status == OrderPlaced::DELIVERED) {
                        //option for rework
                        if (Auth::user()->can('order repeat')) {
                            $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-reorder btn-xs" data-bs-toggle="modal" data-bs-target="#modal-reorder" data-id="' . $row->id . '" title="Repeat Order" id="btn-reorder" data-patient-name="' . str_replace("<br>", " ", $row->toothExamination->patient->first_name . " " . $row->toothExamination->patient->last_name) . '"
                            data-shade="' . $row->toothExamination->shade_id . '">
                            <i class="fa fa-repeat"></i></button>';
                        }
                    }

                    return $btn;
                })
                ->rawColumns(['status', 'action', 'dates'])
                ->make(true);
        }
        return view('orders.track_order', compact('branches', 'plans', 'technicians', 'shades', 'clinicBasicDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $orderPlaced = OrderPlaced::with('toothExamination')->find($id);
        if (!$orderPlaced)
            abort(404);
        return $orderPlaced;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $orderPlaced = OrderPlaced::find($id);
        if (!$orderPlaced)
            abort(404);
        $orderPlaced->delivered_on = date ('Y-m-d H:i:s');
        $orderPlaced->order_status = OrderPlaced::DELIVERED;
        if ($orderPlaced->save()){
            return response()->json(['success'=>'Order Delivered']);
        } else {
            return response()->json(['error'=>'Order status updation error. Please try again']);

        }
        
    }

    public function repeat(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $orderPlaced = OrderPlaced::with('toothExamination')->find($id);
            if (!$orderPlaced)
                abort(404);
            $orderPlaced->order_repeat_reason = $request->reason;
            $orderPlaced->order_status = OrderPlaced::REPEAT;
            if ($orderPlaced->save()){
                $order = new OrderPlaced();
                $order->tooth_examination_id = $orderPlaced->tooth_examination_id;
                $order->treatment_plan_id =  $orderPlaced->treatment_plan_id;
                $order->patient_id =  $orderPlaced->patient_id;
                $order->technician_id = $orderPlaced->technician_id;
                $order->order_placed_on = $request->order_placed_on;
                $order->delivery_expected_on = $request->delivery_expected_on;
                $order->parent_order_id = $orderPlaced->id;
                $order->billable = $request->billable;
                $order->order_status = OrderPlaced::PLACED;
                $order->billable = $request->billable;
     
                 // Save the OrderPlaced instance
                 if ($order->save()) {
                    DB::commit();
                    return response()->json(['success'=>'Order Repeated successfully']);
                 } else {
                    DB::rollback();
                    return response()->json(['error'=>'Repeat order error. Please try again']);
                 }
                
            } else {
                DB::rollback();
                return response()->json(['error'=>'Repeat order error. Please try again']);

            }
        } catch (Exception $e) {
            DB::rollback();
            echo "<pre>";
            print_r($e->getMessage());
            exit;
            return response()->json(['error'=>'Repeat order error. Please try again']);
        }
        
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $orderPlaced = OrderPlaced::find($id);
        if (!$orderPlaced)
            abort(404);
        $orderPlaced->order_cancel_reason = $request->order_cancel_reason;
        $orderPlaced->cancelled_on = date ('Y-m-d H:i:s');
        $orderPlaced->canceled_by = Auth::user()->id;
        $orderPlaced->order_status = OrderPlaced::CANCELLED;
        if ($orderPlaced->save()){
            return response()->json(['success'=>'Order cancelled']);
        } else {
            return response()->json(['error'=>'Order cancellation error. Please try again']);

        }
        
    }
}
