<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Medicine extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['med_bar_code', 'med_name', 'med_company', 'med_remarks', 'med_price', 'expiry_date', 'units_per_package', 'package_count', 'total_quantity', 'package_type', 'stock_status', 'status', 'created_by',
        'updated_by'];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($clinic) {
            $clinic->created_by = Auth::id(); // Set created_by to current user's ID
            $clinic->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($clinic) {
            $clinic->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'medicine_id', 'id');
    }
}
