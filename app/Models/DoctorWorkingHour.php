<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DoctorWorkingHour extends Model
{
    use HasFactory;
    protected $fillable = ['user_id' , 'week_day', 'from_time', 'to_time', 'created_by', 'updated_by'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($doctorWorkingHours) {
            $doctorWorkingHours->created_by = Auth::id(); // Set created_by to current user's ID
            $doctorWorkingHours->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($doctorWorkingHours) {
            $doctorWorkingHours->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }
}
