<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class EmployeeAttendance extends Model
{
    use HasFactory;
    use SoftDeletes;
    const PRESENT = 1;
    const ON_LEAVE = 2;


    protected $fillable = ['user_id', 'login_date', 'login_time', 'logout_date', 'logout_time', 'worked_hours','attendance_status','created_by', 'updated_by'];
    protected $dates = ['deleted_at'];
    
    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($attendance) {
            $attendance->created_by = Auth::id(); // Set created_by to current user's ID
            $attendance->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($attendance) {
            $attendance->updated_by = Auth::id(); // Set updated_by to current user's ID
        });

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
