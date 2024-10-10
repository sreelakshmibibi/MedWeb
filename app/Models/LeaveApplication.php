<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class LeaveApplication extends Model
{
    use HasFactory;
    use SoftDeletes;
    const Applied = 1;
    const Approved = 2;
    const Rejected = 3;

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'leave_from',
        'leave_to',
        'days',
        'compensation_date',
        'leave_reason',
        'leave_file',
        'leave_status',
        'rejection_reason',
        'rejected_by',
        'approved_by',
        'deleted_by',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($leave) {
            $leave->created_by = Auth::id(); // Set created_by to current user's ID
            $leave->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($leave) {
            $leave->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

}
