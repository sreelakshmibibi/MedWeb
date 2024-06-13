<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    use HasFactory;
    protected $fillable = ['patient_id', 'national_id', 'first_name', 'last_name', 'gsm', 'gender', 'birth_date', 'age', 'area', 'nationality', 'registration_date', 'visit_count', 'pstatus', 'regby'];
}
