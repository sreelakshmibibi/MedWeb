<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['status', 'st_color', 'tx_color', 'stat', 'indrop'];

    protected $dates = ['deleted_at'];
}
