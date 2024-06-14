<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicBranch extends Model
{
    use HasFactory;
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
}
