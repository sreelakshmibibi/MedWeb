<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $filable = ['name', 'phone', 'address', 'gst', 'created_by', 'updated_by'];
    protected $dates = ['deleted_at'];
    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($supplier) {
            $supplier->created_by = Auth::id(); // Set created_by to current user's ID
            $supplier->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($supplier) {
            $supplier->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }
}
