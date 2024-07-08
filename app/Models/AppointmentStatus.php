<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentStatus extends Model
{
    use HasFactory;
    use SoftDeletes;
    const SCHEDULED = 1;
    const WAITING = 2;
    const UNAVAILABLE = 3;
    const CANCELLED = 4;

    const COMPLETED = 5;
    const BILLING = 6;
    const PROCEDURE = 7;
    const MISSED = 8;
    const RESCHEDULED = 9;

    protected $fillable = ['status', 'st_color', 'tx_color', 'stat', 'indrop'];

    protected $dates = ['deleted_at'];
}
