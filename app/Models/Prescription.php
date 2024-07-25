<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Prescription extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'app_id',
        'medicine_id',
        'medicine_name',
        'medicine_company',
        'dosage_id',
        'duration',
        'advice',
        'remark',
        'prescribed_by',
        'finalsave',
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

    /**
     * Define the relationship with the Medicine model.
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'id');
    }

    /**
     * Define the relationship with the Dosage model.
     */
    public function dosage()
    {
        return $this->belongsTo(Dosage::class, 'dosage_id', 'id');
    }

    /**
     * Define the relationship with the PatientProfile model.
     */
    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id', 'patient_id');
    }

    /**
     * Define the relationship with the Appointment model.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'app_id', 'id');
    }

    /**
     * Define the relationship with the User model for the prescribed_by field.
     */
    public function prescribedBy()
    {
        return $this->belongsTo(User::class, 'prescribed_by', 'id');
    }
}
