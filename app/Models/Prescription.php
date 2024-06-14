<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'app_id',
        'medid',
        'endate',
        'medd_name',
        'str',
        'dose',
        'days',
        'duration',
        'remark',
        'prby',
        'finalsave',
        'status',
    ];
}
