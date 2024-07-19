<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComboOfferTreatment extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'combo_offer_id',
        'treatment_id',
    ];

    /**
     * Get the combo offer associated with this treatment.
     */
    public function comboOffer()
    {
        return $this->belongsTo(TreatmentComboOffer::class, 'combo_offer_id');
    }

    /**
     * Get the treatment associated with this combo offer.
     */
    public function treatment()
    {
        return $this->belongsTo(TreatmentType::class, 'treatment_id');
    }
}
