<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class TreatmentPlan extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['plan', 'cost', 'status'];
    protected $dates = ['deleted_at'];
    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($plan) {
            $plan->created_by = Auth::id(); // Set created_by to current user's ID
            $plan->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($plan) {
            $plan->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    public function toothExaminations()
    {
        return $this->hasMany(ToothExamination::class, 'treatment_plan_id', 'id');
    }

    public function labAmount()
    {
        return $this->hasMany(TreatmentPlanTechnicianCost::class, 'treatment_plan_id', 'id');
    }
}
