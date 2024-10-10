<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeSalary extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'pay_head_id',
        'amount',
        'with_effect_from',
        'created_by',
        'updated_by',
        'status',
    ];
    protected $dates = ['deleted_at'];
}
