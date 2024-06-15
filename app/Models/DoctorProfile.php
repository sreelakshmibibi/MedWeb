<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorProfile extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['user_id', 'phone', 'department_id', 'specialization', 'years_of_experience', 'license_number', 'subspecialty', 'address', 'area', 'state', 'nationality', 'pin', 'date_of_birth', 'gender', 'photo'];
    protected $dates = ['deleted_at'];
}
