<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PatientTreatmentBilling extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [ 'bill_id', 'appointment_id', 'app_id','patient_id', 'treatment_total_amount','combo_offer_deduction', 'mode_of_payment', 'previous_outstanding', 'doctor_discount', 'amount_to_be_paid', 'amount_paid', 'balance_due', 'insurance_paid', 'status', 'created_by', 'updated_by' ];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($bill) {
            $bill->created_by = Auth::id(); // Set created_by to current user's ID
            $bill->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($bill) {
            $bill->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

}