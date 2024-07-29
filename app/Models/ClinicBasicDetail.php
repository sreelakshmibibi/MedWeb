<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ClinicBasicDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['clinic_name', 
    'clinic_logo', 
    'clinic_website',
    'clinic_insurance_available',
    'patient_registration_fees',
    'consultation_fees',
    'consultation_fees_frequency',
    'clinic_type_id',
    'created_by',
    'updated_by'
    ];    
    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($clinic) {
            $clinic->created_by = Auth::id(); // Set created_by to current user's ID
            $clinic->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($clinic) {
            $clinic->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }
}
