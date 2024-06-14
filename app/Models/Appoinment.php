<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appoinment extends Model
{
    use HasFactory;
    protected $fillable = [
        'app_id',
        'patient_id',
        'user',
        'file_no',
        'Date',
        'Time',
        'tim_to',
        'slot',
        'doctor_id',
        'type',
        'status',
        'app_note',
        'nursing_note',
        'docnurnote',
        'yesno',
        'doctor_note',
        'dcheck',
        'dton',
        'dref',
        'nd',
        'nby',
        'dby',
        'dtbill',
        'ap_stat',
        'recto',
        'next_app_dt',
        'nextbr',
        'next_app_time',
        'walk_in_tim',
        'con_strt_time',
        'con_end_time',
        'biltime',
        'wby',
        'prby',
        'buk_by',
        'updt_by',
        'wkoap',
        'filyes',
        'imfil',
        'br'
    ];

}
