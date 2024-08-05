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
        'policy_holder_type',
        'is_primary_insurance',
        'prim_ins_id',
        'prim_ins_insured_name',
        'prim_ins_insured_dob',
        'prim_ins_company_id',
        'prim_ins_company',
        'prim_ins_com_address',
        'prim_ins_group_name',
        'prim_ins_group_number',
        'prim_ins_policy_start_date',
        'prim_ins_policy_end_date',
        'prim_ins_relation_to_insured',
        'is_secondary_insurance',
        'sec_ins_id',
        'sec_ins_insured_name',
        'sec_ins_insured_dob',
        'sec_ins_company_id',
        'sec_ins_company',
        'sec_ins_com_address',
        'sec_ins_group_name',
        'sec_ins_group_number',
        'sec_ins_policy_start_date',
        'sec_ins_policy_end_date',
        'sec_ins_relation_to_insured',
        'status',
    ];

    protected $dates = ['deleted_at'];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id', 'patient_id');
    }
}
