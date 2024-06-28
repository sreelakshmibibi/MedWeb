<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['department', 'clinic_type_id', 'status', 'created_by',
        'updated_by'];

    
    protected $dates = ['deleted_at'];
    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($department) {
            $department->created_by = Auth::id(); // Set created_by to current user's ID
            $department->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($department) {
            $department->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }
}
