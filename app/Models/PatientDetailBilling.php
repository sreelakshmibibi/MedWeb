<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PatientDetailBilling extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [ 'billing_id', 'treatment_id', 'consultation_registration', 'quantity','cost', 'discount', 'amount', 'created_by', 'updated_by' ];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($bill) {
            $bill->created_by = Auth::id(); // Set created_by to current user's ID
            $bill->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($bill) {
            $bill->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    public function treatment()
    {
        return $this->belongsTo(TreatmentType::class, 'treatment_id', 'id');
    }

}
