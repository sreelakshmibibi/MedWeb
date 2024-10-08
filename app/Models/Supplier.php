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
    protected $fillable = ['name', 'phone', 'address', 'gst', 'status', 'created_by', 'updated_by', 'deleted_by'];
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
        static::deleting(function ($patient) {
            // Optionally, handle logic before soft delete
            // For example, log the deletion or modify fields
            $patient->deleted_by = Auth::id(); // You would need to add a `deleted_by` column
            $patient->save(); // Save changes before the record is actually deleted
        });

    }

}
