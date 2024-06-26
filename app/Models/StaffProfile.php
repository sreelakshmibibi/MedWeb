<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class StaffProfile extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [ 'user_id','staff_id', 'clinic_branch_id', 'title','aadhaar_no','date_of_birth', 'gender', 'phone', 'address1', 'address2', 'city_id', 'state_id', 'country_id', 'pincode','com_address1', 'com_address2', 'com_city_id', 'com_state_id', 'com_country_id', 'com_pincode','photo', 'date_of_joining','date_of_relieving', 'qualification', 'department_id', 'specialization', 'years_of_experience', 'license_number', 'subspecialty','status', 'created_by','updated_by',
    ];
    protected $dates = ['deleted_at'];
    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($staffProfile) {
            $staffProfile->created_by = Auth::id(); // Set created_by to current user's ID
            $staffProfile->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($staffProfile) {
            $staffProfile->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
