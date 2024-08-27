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
    const RowAll = 5;
    const Row_All_Desc = "All";

    const Row_1_Desc = "Primary Maxillary Dentition";
    const Row_2_Desc = "Permanent Maxillary Dentition";
    const Row_3_Desc = "Permanent Mandibular Dentition";
    const Row_4_Desc = "Primary Mandibular Dentition";
    protected $fillable = ['teeth_nos'];
}
