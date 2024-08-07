<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentType extends Model
{
    use HasFactory;
    use SoftDeletes;
    const NEW = 2;
    const FOLLOWUP = 1;
    const NEW_WORDS = "NEW";
    const FOLLOWUP_WORDS = "FOLLOW UP";
    protected $fillable = ['type', 'status'];

    protected $dates = ['deleted_at'];
}
