<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppoinmentStatus extends Model
{
    use HasFactory;
    protected $fillable = ['status', 'st_color', 'tx_color', 'stat', 'indrop'];
}
