<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'phone', 'department_id', 'specialization', 'years_of_experience', 'license_number', 'subspecialty', 'address', 'date_of_birth', 'gender', 'photo'];
}
