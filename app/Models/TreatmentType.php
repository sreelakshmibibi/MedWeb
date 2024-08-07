<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class TreatmentType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'treat_name',
        'treat_cost',
        'discount_percentage',
        'discount_from',
        'discount_to',
        'status',
        'created_by',
        'updated_by',
    ];

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

    public function comboOffers()
    {
        return $this->belongsToMany(TreatmentComboOffer::class, 'combo_offer_treatment', 'treatment_id', 'combo_offer_id');
    }
}
