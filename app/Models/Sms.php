<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sms extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['sms_name', 'sms_description', 'status'];
    protected $dates = ['deleted_at'];
}
