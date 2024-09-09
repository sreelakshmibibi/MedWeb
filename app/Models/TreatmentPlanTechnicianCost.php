<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class TreatmentPlanTechnicianCost extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['treatment_plan_id', 'techician_id', 'cost', 'status', 'created_by', 'updated_by', 'deleted_by'];
    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($technicianCost) {
            $technicianCost->created_by = Auth::id(); // Set created_by to current user's ID
            $technicianCost->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($technicianCost) {
            $technicianCost->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

}
