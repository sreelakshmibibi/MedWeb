<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['med_bar_code', 'med_name', 'med_strength', 'med_remarks', 'med_price', 'med_status', 'status', 'med_date', 'med_last_update'];
    protected $dates = ['deleted_at'];
}
