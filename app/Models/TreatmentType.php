<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentType extends Model
{
    use HasFactory;
    protected $fillable = [
        'treat_name',
        'treat_cost',
        'status',
    ];
}
