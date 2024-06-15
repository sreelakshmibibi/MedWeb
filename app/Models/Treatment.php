<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treatment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'patient_id',
        'app_id',
        'treat_id',
        'qty',
        'nursing_remark',
        'treat_date',
        'doneby',
        'status',
    ];
    protected $dates = ['deleted_at'];
}
