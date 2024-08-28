<?php

namespace App\Http\Controllers;

use App\Models\PatientTreatmentBilling;
use DateTime;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AuditController extends Controller
{
    public function auditCancell(Request $request)
    {
        // Initialize the query builder with selected columns and relationships
        $treatmentQuery = PatientTreatmentBilling::select([
            'id',
            'bill_id',
            'patient_id',
            'bill_paid_date',
            'treatment_total_amount',
            'doctor_discount',
            'combo_offer_deduction',
            'tax',
            'amount_to_be_paid',
            'cash',
            'gpay',
            'card',
            'amount_paid',
            'balance_to_give_back',
            'balance_given',
            'balance_due',
            'bill_delete_reason',
            'billed_by',
            'created_by',
            'updated_by',
            'deleted_by',
            'updated_at'
        ])
        ->with([
            'patient',
            'appointment',
            'billedBy:id,name',
            'updatedBy:id,name',
            'createdBy:id,name',
            'deletedBy:id,name'
        ])
        ->where('bill_status', PatientTreatmentBilling::BILL_CANCELLED);
        
        // Check if 'fromdate' and 'todate' are present and apply date range filter
        if ($request->filled('fromdateaudit') && $request->filled('todateaudit')) {
        $fromDate = $request->fromdateaudit . " 00:00:00";
        $toDate = $request->todateaudit . " 23:59:59";
        $treatmentQuery->whereBetween('updated_at', [$fromDate, $toDate]);
        } elseif ($request->filled('fromdateaudit')) {
        $fromDate = $request->fromdateaudit . " 00:00:00";
        $treatmentQuery->where('updated_at', '>=', $fromDate);
        } elseif ($request->filled('todateaudit')) {
        $toDate = $request->todateaudit . " 23:59:59";
        $treatmentQuery->where('updated_at', '<=', $toDate);
        }

        // Check if 'branch' is present and apply filter on related 'appointment'
        if ($request->filled('branchaudit')) {
        $treatmentQuery->whereHas('appointment', function ($q) use ($request) {
            $q->where('app_branch', $request->branchaudit);
        });
        }

        // Check if 'billedby' is present and apply filter
        if ($request->filled('billedbyaudit')) {
        $treatmentQuery->where('billed_by', $request->billedbyaudit);
        }

        // Check if 'generatedby' is present and apply filter
        if ($request->filled('generatedbyaudit')) {
        $treatmentQuery->where('created_by', $request->generatedbyaudit);
        }

        // Execute the query and retrieve results
        $treatmentBillings = $treatmentQuery->get();

        return DataTables::of($treatmentBillings)
            ->addIndexColumn()
            ->addColumn('billDate', function ($row) {
                return $row->bill_paid_date ?? $row->created_at;
            })
            ->addColumn('patientName', function ($row) {
                return isset($row->patient) ? 
                str_replace('<br>', ' ', $row->patient->first_name) . ' ' . $row->patient->last_name :
                '';
            })
            
            ->addColumn('total', function ($row) {
                return $row->treatment_total_amount ?? 0;
            })
            ->addColumn('discount', function ($row) {
                if (isset($row->doctor_discount) || isset($row->combo_offer_deduction)) {
                    return ($row->doctor_discount ?? 0) + ($row->combo_offer_deduction ?? 0);
                } 
                return $row->discount ?? 0;
            })
            ->addColumn('tax', function ($row) {
                return $row->tax ?? 0;
            })
            ->addColumn('netAmount', function ($row) {
                return $row->amount_to_be_paid ?? $row->amount_to_be_paid ?? 0;
            })
            ->addColumn('cash', function ($row) {
                return $row->cash ?? 0;
            })
            ->addColumn('gpay', function ($row) {
                return $row->gpay ?? 0;
            })
            ->addColumn('card', function ($row) {
                return $row->card ?? 0;
            })
            ->addColumn('totalPaid', function ($row) {
                return $row->amount_paid ?? $row->amount_paid ?? 0;
            })
            ->addColumn('balanceGiven', function ($row) {
                return $row->balance_given ?? $row->balance_given ?? 0;
            })
            ->addColumn('outstanding', function ($row) {
                return $row->balance_due ?? 0;
            })
            ->addColumn('createdBy', function ($row) {
                return str_replace('<br>', ' ', $row->createdBy->name ?? 'N/A');
            })
            ->addColumn('updatedBy', function ($row) {
                return str_replace('<br>', ' ', $row->updatedBy->name ?? 'N/A');
            })
            ->addColumn('deletedBy', function ($row) {
                return str_replace('<br>', ' ', $row->deletedBy->name ?? 'N/A');
            })
            ->addColumn('deleted_at', function ($row) {
                $dateTime = new DateTime($row->updated_at);
                // Format the datetime as a single string
                return $dateTime->format('Y-m-d H:i:s');
            })
            ->make(true);
    }
    
}
