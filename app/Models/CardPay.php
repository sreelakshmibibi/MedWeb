<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardPay extends Model
{
    use HasFactory;
    protected $fillable = ['card_name', 'service_charge_perc', 'status'];
    
}
