<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PayHead extends Model
{
    use HasFactory;
    use SoftDeletes;
    const E = 'E' ;
    const SD = 'SD';
    const SA = 'SA' ;
    const SA_WORDS = 'Statutory Additions';
    const SD_WORDS = 'Statutory Deductions';
    const E_WORDS = 'Earnings';

    protected $fillable = ['head_type', 'type', 'status', 'created_by', 'updated_by'];
    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($payhead) {
            $payhead->created_by = Auth::id(); // Set created_by to current user's ID
            $payhead->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($payhead) {
            $payhead->updated_by = Auth::id(); // Set updated_by to current user's ID
        });

    }

}
