<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ToothExamination extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'patient_id', 'app_id', 'tooth_id', 'row_id', 'tooth_score_id', 'chief_complaint', 'hpi', 'dental_examination', 'disease_id', 'diagnosis', 'treatment_id', 'treatment_plan_id', 'shade_id', 'upper_shade',
                'middle_shade',
                'lower_shade',
                'metal_trial',
                'bisq_trial',
                'finish','instructions', 'xray', 'lingual_condn', 'labial_condn', 'occulusal_condn', 'distal_condn', 'mesial_condn', 'palatal_condn', 'buccal_condn', 'treatment_status', 'anatomy_image', 'remarks', 'created_by', 'updated_by', 'status','deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($toothExamination) {
            $toothExamination->created_by = Auth::id(); // Set created_by to current user's ID
            $toothExamination->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($toothExamination) {
            $toothExamination->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
        static::deleting(function ($toothExamination) {
            $toothExamination->deleted_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id', 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'app_id', 'id');
    }

    public function teeth()
    {
        return $this->belongsTo(Teeth::class, 'tooth_id', 'teeth_name');
    }

    public function treatment()
    {
        return $this->belongsTo(TreatmentType::class, 'treatment_id', 'id');
    }

    public function treatmentPlan()
    {
        return $this->belongsTo(TreatmentPlan::class, 'treatment_plan_id', 'id');
    }

    public function toothScore()
    {
        return $this->belongsTo(ToothScore::class, 'tooth_score_id', 'id');
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class, 'disease_id', 'id');
    }

    public function treatmentStatus()
    {
        return $this->belongsTo(TreatmentStatus::class, 'treatment_status', 'id'); // Relationship to TreatmentStatus
    }

    public function xRayImages()
    {
        return $this->hasMany(XRayImage::class)
            ->where('status', 'Y');
    }

    public function lingualCondition()
    {
        return $this->belongsTo(SurfaceCondition::class, 'lingual_condn', 'id');
    }

    public function labialCondition()
    {
        return $this->belongsTo(SurfaceCondition::class, 'labial_condn', 'id');
    }

    public function occulusalCondition()
    {
        return $this->belongsTo(SurfaceCondition::class, 'occulusal_condn', 'id');
    }

    public function distalCondition()
    {
        return $this->belongsTo(SurfaceCondition::class, 'distal_condn', 'id');
    }

    public function mesialCondition()
    {
        return $this->belongsTo(SurfaceCondition::class, 'mesial_condn', 'id');
    }

    public function palatalCondition()
    {
        return $this->belongsTo(SurfaceCondition::class, 'palatal_condn', 'id');
    }

    public function buccalCondition()
    {
        return $this->belongsTo(SurfaceCondition::class, 'buccal_condn', 'id');
    }

    public function shade()
    {
        return $this->belongsTo(Shade::class, 'shade_id', 'id');
    }
}
