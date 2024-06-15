<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientProfile extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['patient_id', 'national_id', 'first_name', 'last_name', 'gsm', 'gender', 'birth_date', 'age', 'address', 'area', 'state', 'nationality', 'pin', 'registration_date', 'visit_count', 'pstatus', 'regby'];
    protected $dates = ['deleted_at'];
}
