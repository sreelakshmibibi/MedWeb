<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class TreatmentComboOffer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'offer_amount',
        'offer_from',
        'offer_to',
        'created_by',
        'updated_by',
        'status',
    ];

    protected $dates = ['deleted_at'];

    public function treatments()
    {
        return $this->belongsToMany(TreatmentType::class, 'combo_offer_treatments', 'combo_offer_id', 'treatment_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($treatmentComboOffer) {
            $treatmentComboOffer->created_by = Auth::id(); // Set created_by to current user's ID
            $treatmentComboOffer->updated_by = Auth::id(); // Set updated_by to current user's ID
        });

        // Before updating an existing record
        static::updating(function ($treatmentComboOffer) {
            $treatmentComboOffer->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }
}
