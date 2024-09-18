<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPlaced extends Model
{
    use HasFactory;
    use SoftDeletes;
    const PLACED = 1;
    const DELIVERED = 2;
    const CANCELLED = 3;
    const PENDING = 4;
    const REPEAT = 5;

    const PLACED_DESC = "ORDER PLACED";
    const DELIVERED_DESC = "ORDER DELIVERED";
    const CANCELLED_DESC = "ORDER CANCELLED";
    const PENDING_DESC = "ORDER PENDING";
    const REPEAT_DESC = "ORDER REPEAT";


    protected $fillable = ['tooth_examination_id', 'patient_id', 'treatment_plan_id', 'technician_id', 'order_placed_on', 'delivery_expected_on','delivered_on','order_status','lab_cost', 'order_cancel_reason','order_repeat_reason', 'parent_order_id','billable', 'cancelled_on','created_by', 'updated_by', 'canceled_by', 'deleted_by', 'lab_paid'];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($order) {
            $order->created_by = Auth::id(); // Set created_by to current user's ID
            $order->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($order) {
            $order->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }
    public function treatmentPlan()
    {
        return $this->belongsTo(TreatmentPlan::class, 'treatment_plan_id', 'id');
    }

    public function toothExamination()
    {
        return $this->belongsTo(ToothExamination::class, 'tooth_examination_id', 'id');
    }
    public function technician()
    {
        return $this->belongsTo(Technician::class, 'technician_id', 'id');
    }

    public static function statusToWords($status)
    {
        switch ($status) {
            case self::PLACED:
                return self::PLACED_DESC;
            case self::PENDING:
                return self::PENDING_DESC;
            case self::CANCELLED:
                return self::CANCELLED_DESC;
            case self::DELIVERED:
                return self::DELIVERED_DESC;
            case self::REPEAT:
                return self::REPEAT_DESC;
            default:
                return 'Unknown'; // Handle unknown statuses gracefully
        }
    }
    
}
