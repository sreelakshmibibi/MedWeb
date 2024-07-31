<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class InsuranceCompany extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['company_name', 'claim_type', 'status'];
    protected $dates = ['deleted_at'];
    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($insurance) {
            $insurance->created_by = Auth::id(); // Set created_by to current user's ID
            $insurance->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($insurance) {
            $insurance->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }
}
