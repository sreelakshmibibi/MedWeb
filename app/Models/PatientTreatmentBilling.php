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

    const BILL_GENERATED = 1;

    const PAYMENT_DONE = 2;

    const BILL_CANCELLED = 3;

    const BILL_GENERATED_WORDS = 'Bill Generated';

    const PAYMENT_DONE_WORDS = 'Payment Done';

    const BILL_CANCELLED_WORDS = 'Bill Cancelled';

    protected $fillable = ['bill_id', 'appointment_id', 'app_id', 'patient_id', 'treatment_total_amount', 'combo_offer_deduction', 'mode_of_payment', 'previous_outstanding', 'doctor_discount', 'amount_to_be_paid', 'amount_paid', 'balance_due', 'balance_to_give_back', 'balance_given', 'consider_for_next_payment', 'tax', 'tax_percentile', 'card_pay_id', 'bank_tax', 'insurance_paid', 'gpay', 'cash', 'card', 'bill_paid_date', 'due_covered_bill_no', 'due_covered_date', 'billed_by', 'status', 'bill_status', 'bill_delete_reason', 'created_by', 'updated_by', 'deleted_by'];

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

    public function billedBy()
    {
        return $this->belongsTo(User::class, 'billed_by');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id', 'patient_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cardPay()
    {
        return $this->belongsTo(CardPay::class, 'card_pay_id');
    }
}
