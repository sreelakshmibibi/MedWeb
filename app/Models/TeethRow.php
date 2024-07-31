<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeethRow extends Model
{
    use HasFactory;
    const Row1 = 1;
    const Row2 = 2;
    const Row3 = 3;
    const Row4 = 4;
    protected $fillable = ['teeth_nos'];
}
