<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use HasFactory;
    use SoftDeletes;
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
    protected $dates = ['deleted_at'];
}
