<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class MedicinePurchaseItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_id',
        'medicine_id',
        'batch_no',
        'med_price',
        'expiry_date',
        'units_per_package',
        'package_count',
        'total_quantity',
        'package_type',
        'purchase_unit_price',
        'purchase_amount',
        'status',
        'used_stock',
        'balance',
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($medicinePurchaseItem) {
            $medicinePurchaseItem->created_by = Auth::id(); // Set created_by to current user's ID
            $medicinePurchaseItem->updated_by = Auth::id(); // Set updated_by to current user's ID
        });

        // Before updating an existing record
        static::updating(function ($medicinePurchaseItem) {
            $medicinePurchaseItem->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'id');
    }

}
