<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PatientProfile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['patient_id', 'first_name', 'last_name', 'aadhaar_no', 'date_of_birth', 'gender', 'phone', 'alternate_phone', 'email', 'address1', 'address2', 'city_id', 'state_id', 'country_id', 'pincode', 'visit_count', 'status', 'created_by',
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
}
