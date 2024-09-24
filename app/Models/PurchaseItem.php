<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PurchaseItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'purchase_id',
        'name',
        'price',
        'quantity',
        'amount',
        'created_by',
        'updated_by'
    ];
    protected $dates = ['deleted_at'];
}