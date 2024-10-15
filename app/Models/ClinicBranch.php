<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ClinicBranch extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
    'clinic_email', 
    'clinic_address',
    'city_id',
    'state_id',
    'country_id',
    'pincode',
    'is_main_branch',
    'is_cosmetic_clinic',
    'clinic_phone', 
    'clinic_website', 
    'clinic_type_id',
    'clinic_status',
    'created_by',
    'updated_by'];    
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
