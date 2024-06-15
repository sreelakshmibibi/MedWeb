<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Symptom extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'patient_id',
        'app_id',
        'symptom',
        'doctor_id',
        'cdate',
        'status',
        'updt',
    ];
    protected $dates = ['deleted_at'];
}
