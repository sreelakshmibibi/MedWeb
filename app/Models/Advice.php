<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advice extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'app_id',
        'advice',
        'doctor_id',
        'cdate',
        'status',
        'updt',
    ];
}
