<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'app_id',
        'history',
        'doctor_id',
        'cdate',
        'status',
        'updt',
    ];
}
