<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class EmployeeMonthlySalary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'working_days',
        'paid_days',
        'unpaid_days',
        'partially_paid_days',
        'salary_id',
        'basic_salary',
        'absence_deduction',
        'incentives',
        'monthly_deduction',
        'deduction_reason',
        'total_salary',
        'advance_id',
        'advance_given',
        'amount_to_be_paid',
        'amount_paid',
        'balance_due',
        'paid_on',
        'cash',
        'bank',
        'status',
        'created_by',
        'updated_by',
        'delete_reason',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($bill) {
            $bill->created_by = Auth::id(); // Set created_by to current user's ID
        });

        // Before updating an existing record
        static::updating(function ($bill) {
            $bill->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salary()
    {
        return $this->belongsTo(Salary::class);
    }

    public function advance()
    {
        return $this->belongsTo(SalaryAdvance::class, 'advance_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
