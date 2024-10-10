<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Holiday extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = ['holiday_on', 'branches', 'reason', 'delete_reason', 'status', 'created_by', 'updated_by', 'deleted_by'];
    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($holiday) {
            $holiday->created_by = Auth::id(); // Set created_by to current user's ID
            $holiday->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($holiday) {
            $holiday->updated_by = Auth::id(); // Set updated_by to current user's ID
        });

        static::deleting(function ($holiday) {
            $holiday->deleted_by = Auth::id(); // Set updated_by to current user's ID
        });
    }
}
