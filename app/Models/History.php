<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class History extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'app_id',
        'history',
        'doctor_id',
        'status',
        'created_by',
        'updated_by',
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

    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id'); // Adjust 'patient_id' if necessary
    }
}
