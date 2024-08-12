<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PrescriptionDetailBilling extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bill_id',
        'medicine_id',
        'quantity',
        'cost',
        'discount',
        'amount',
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

    public function patientPrescriptionBilling()
    {
        return $this->belongsTo(PatientPrescriptionBilling::class, 'bill_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
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
