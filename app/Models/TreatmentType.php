<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreatmentType extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'treat_name',
        'treat_cost',
        'status',
    ];
    protected $dates = ['deleted_at'];
}
