<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'branch_id',
        'invoice_no',
        'invoice_date',
        'supplier_id',
        'category',
        'item_subtotal',
        'delivery_charge',
        'gst',
        'total_currentbill',
        'discount',
        'previous_due',
        'amount_to_be_paid',
        'gpay',
        'cash',
        'card',
        'amount_paid',
        'balance_due',
        'balance_to_give_back',
        'balance_given',
        'consider_for_next_payment',
        'billfile',
        'created_by',
        'purchase_category',
        'updated_by',
        'status',
        'purchase_delete_reason'
    ];
    protected $dates = ['deleted_at'];
}
