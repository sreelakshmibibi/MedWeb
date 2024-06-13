<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicDetail extends Model
{
    use HasFactory;
    protected $fillable = ['clinic_name', 'clinic_logo', 'clinic_address'. 'clinic_type_id'];
}
