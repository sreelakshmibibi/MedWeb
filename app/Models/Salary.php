<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'with_effect_from',
        'salary',
        'netsalary',
        'ctc',
        'etotal',
        'satotal',
        'sdtotal',
        'created_by',
        'updated_by',
        'status',
    ];
    protected $dates = ['deleted_at'];

}