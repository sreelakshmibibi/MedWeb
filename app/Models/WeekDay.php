<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeekDay extends Model
{
    use HasFactory;
    use SoftDeletes;

    const MONDAY = "Monday";
    const TUESDAY = "Tuesday";
    const WEDNESDAY = "Wednesday";
    const THURSDAY = "Thursday";
    const FRIDAY = "Friday";
    const SATURDAY = "Saturday";
    const SUNDAY = "Sunday";
    protected $fillable = ['name', 'acronym', 'status'];
    protected $dates = ['deleted_at'];
}
