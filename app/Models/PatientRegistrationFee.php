<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PatientRegistrationFee extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'amount',
        'paid_at',
        'payment_method',
        'created_by',
        'updated_by',
        'status',
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

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id', 'patient_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
