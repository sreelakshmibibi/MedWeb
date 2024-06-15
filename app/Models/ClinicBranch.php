<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicBranch extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['clinic_name', 
    'clinic_email', 
    'clinic_logo', 
    'clinic_address',
    'city_id',
    'state_id',
    'country_id',
    'pincode',
    'is_main_branch',
    'phone_number', 
    'clinic_website', 
    'clinic_type_id'];
    protected $dates = ['deleted_at'];
}
