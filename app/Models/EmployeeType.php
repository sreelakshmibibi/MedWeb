<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmployeeType extends Model
{
    use HasFactory;
    protected $fillable = ['employee_type', 'status', 'created_by', 'updated_by'];
    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($empType) {
            $empType->created_by = Auth::id(); // Set created_by to current user's ID
            $empType->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($empType) {
            $empType->updated_by = Auth::id(); // Set updated_by to current user's ID
        });

    }
}
