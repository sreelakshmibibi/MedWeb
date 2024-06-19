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
    'clinic_phone', 
    'clinic_website', 
    'clinic_type_id',
    'clinic_status'];    
    protected $dates = ['deleted_at'];
    
    // Define the relationship with Country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    // Define the relationship with State
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    // Define the relationship with City
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
