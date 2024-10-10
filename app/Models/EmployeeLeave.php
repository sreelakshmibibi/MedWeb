<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeLeave extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'employee_type_id',
        'casual_leave_monthly',
        'sick_leave_monthly',
        'with_effect_from',
        'created_by',
        'updated_by',
        'status',
    ];
    protected $dates = ['deleted_at'];
}