<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Appointment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'app_id',
        'patient_id',
        'app_date',
        'app_time',
        'token_no',
        'doctor_id',
        'app_branch',
        'app_type',
        'height_cm',
        'weight_kg',
        'blood_pressure',
        'referred_doctor',
        'appointment_note',
        'nursing_note',
        'doctor_note',
        'doctor_check',
        'app_status',
        'app_parent_id',
        'consult_start_time',
        'consult_end_time',
        'status',
        'app_status_change_reason',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($appointment) {
            $appointment->created_by = Auth::id(); // Set created_by to current user's ID
            $appointment->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($appointment) {
            $appointment->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id', 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(ClinicBranch::class, 'app_branch', 'id');
    }
}