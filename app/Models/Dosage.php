<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dosage extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['dos_name', 'status'];
    protected $dates = ['deleted_at'];
}
