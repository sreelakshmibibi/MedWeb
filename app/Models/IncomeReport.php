<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class IncomeReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bill_type',
        'bill_no',
        'bill_date',
        'branch_id',
        'net_paid',
        'cash',
        'gpay',
        'card',
        'card_pay_id',
        'machine_tax',
        'balance_given',
        'net_income',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($bill) {
            $bill->created_by = Auth::id(); // Set created_by to current user's ID
            $bill->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($bill) {
            $bill->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    /**
     * Get the user who created the income report.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the income report.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the card payment details associated with the income report.
     */
    public function cardPay()
    {
        return $this->belongsTo(CardPay::class, 'card_pay_id');
    }
}
