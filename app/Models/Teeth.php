<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Teeth extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['teeth_name', 'position', 'direction', 'teeth_image', 'is_pediatric', 'position_no', 'created_by', 'updated_by'];
    // protected static function booted()
    // {
    //     // Before creating a new record
    //     static::creating(function ($teeth) {
    //         $teeth->created_by = Auth::id(); // Set created_by to current user's ID
    //         $teeth->updated_by = Auth::id();
    //     });

    //     // Before updating an existing record
    //     static::updating(function ($teeth) {
    //         $teeth->updated_by = Auth::id(); // Set updated_by to current user's ID
    //     });
    // }
}
