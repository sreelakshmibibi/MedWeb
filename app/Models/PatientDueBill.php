<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PatientDueBill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bill_id',
        'patient_id',
        'appointment_id',
        'treatment_bill_id',
        'total_amount',
        'gpay',
        'cash',
        'card',
        'card_pay_id',
        'paid_amount',
        'balance_given',
        'bill_paid_date',
        'status',
        'created_by',
        'updated_by',
    ];

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

    // Relationships

    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id', 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function treatmentBilling()
    {
        return $this->belongsTo(PatientTreatmentBilling::class, 'treatment_bill_id');
    }

    public function cardPay()
    {
        return $this->belongsTo(CardPay::class, 'card_pay_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
