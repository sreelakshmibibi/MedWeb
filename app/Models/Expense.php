<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['billdate', 'branch_id', 'name', 'amount', 'category_id', 'billfile', 'status', 'branch_id', 'created_by', 'updated_by'];
    protected $dates = ['deleted_at'];
}
