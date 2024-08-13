<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insurance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'insurance_company_id',
        'policy_number',
        'policy_end_date',
        'created_by',
        'updated_by',
        'status',
        'insured_name',
        'insured_dob',
    ];

    protected $dates = ['deleted_at'];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id', 'patient_id');
    }
}
