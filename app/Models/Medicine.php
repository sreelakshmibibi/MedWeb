<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['med_bar_code', 'med_name', 'med_company', 'med_remarks', 'med_price', 'expiry_date', 'med_strength', 'quantity', 'stock_status', 'status'];
    protected $dates = ['deleted_at'];
}
