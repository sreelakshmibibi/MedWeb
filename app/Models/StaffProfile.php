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
    protected $fillable = ['user_id', 'phone', 'department_id', 'specialization', 'years_of_experience', 'license_number', 'subspecialty', 'address', 'area', 'state', 'nationality', 'pin', 'date_of_birth', 'gender', 'photo'];
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
