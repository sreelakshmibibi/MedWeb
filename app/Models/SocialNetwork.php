<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialNetwork extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['network', 'status'];
    protected $dates = ['deleted_at'];
}
