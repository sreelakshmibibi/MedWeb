<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DoctorPayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'amount', 'paid_on', 'remarks', 'status', 'delete_reason'];
    protected $dates = ['deleted_at'];
    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($doctorPayment) {
            $doctorPayment->created_by = Auth::id(); // Set created_by to current user's ID
            $doctorPayment->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($doctorPayment) {
            $doctorPayment->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }
}
