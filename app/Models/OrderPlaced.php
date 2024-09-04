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
    const PLACED_DESC = "Order Placed";
    const DELIVERED_DESC = "Order Delivered";
    const CANCELLED_DESC = "Order Cancelled";
    const PENDING_DESC = "Order Pending";

    protected $fillable = ['tooth_examination_id', 'patient_id', 'treatment_plan_id', 'technician_id', 'order_placed_on', 'delivery_expected_on','delivered_on','order_status', 'order_cancel_reason', 'cancelled_on','created_by', 'updated_by', 'cancelled_by', 'deleted_by'];

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


    
}
