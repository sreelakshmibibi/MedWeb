<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $fillable = ['med_bar_code', 'med_name', 'med_strength', 'med_remarks', 'med_price', 'med_status', 'status', 'med_date', 'med_last_update'];
}
