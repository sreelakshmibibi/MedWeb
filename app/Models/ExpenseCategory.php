<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['category', 'status', 'created_by', 'updated_by'];
    protected $dates = ['deleted_at'];
}
