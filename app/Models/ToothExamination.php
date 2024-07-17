<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToothExamination extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'patient_id', 'app_id', 'tooth_id', 'tooth_score_id', 'chief_complaint', 'hpi', 'dental_examination', 'diagnosis', 'treatment', 'x-ray', 'lingual_condn', 'labial_condn', 'occulusal_condn', 'distal_condn', 'mesial_condn', 'palatal_condn', 'buccal_condn', 'treatment_status', 'created_by', 'updated_by', 'status'
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id', 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'app_id', 'app_id');
    }
    
}

