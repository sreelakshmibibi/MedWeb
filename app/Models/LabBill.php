<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class LabBill extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['from_date', 'to_date', 'technician_id', 'amount_to_be_paid', 'amount_paid', 'balance_due', 'lab_bill_status', 'created_by', 'deleted_by','updated_by'];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($lab) {
            $lab->created_by = Auth::id(); // Set created_by to current user's ID
            $lab->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($lab) {
            $lab->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }
}
