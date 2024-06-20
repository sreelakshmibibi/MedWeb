<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicBasicDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['clinic_name', 
    'clinic_logo', 
    'clinic_website',
    'clinic_type_id',
    ];    
    protected $dates = ['deleted_at'];
}
