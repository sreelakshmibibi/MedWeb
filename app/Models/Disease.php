<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Disease extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['icd_code', 'name', 'description', 'status', 'created_by',
    'updated_by'];


protected $dates = ['deleted_at'];
protected static function booted()
{
    // Before creating a new record
    static::creating(function ($disease) {
        $disease->created_by = Auth::id(); // Set created_by to current user's ID
        $disease->updated_by = Auth::id();
    });

    // Before updating an existing record
    static::updating(function ($disease) {
        $disease->updated_by = Auth::id(); // Set updated_by to current user's ID
    });
}
}
