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

    protected $fillable = [
        'med_bar_code',
        'med_name',
        'med_company',
        'med_remarks',
        'stock',
        'stock_status',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Automatically set created_by and updated_by for new records
        static::creating(function ($medicine) {
            $medicine->created_by = Auth::id(); // Set created_by to current user's ID
            $medicine->updated_by = Auth::id(); // Set updated_by to current user's ID
        });

        // Automatically update updated_by on record updates
        static::updating(function ($medicine) {
            $medicine->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'medicine_id', 'id');
    }

    public function purchaseItems()
    {
        return $this->hasMany(MedicinePurchaseItem::class, 'medicine_id', 'id');
    }

    public function latestMedicinePurchaseItem()
    {
        return $this->hasOne(MedicinePurchaseItem::class, 'medicine_id')->latest('id');
    }


}
