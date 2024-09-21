<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Technician extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'phone_number', 'lab_name',
    'lab_address', 'lab_contact', 'created_by','updated_by', 'deleted_by' ];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($technician) {
            $technician->created_by = Auth::id(); // Set created_by to current user's ID
            $technician->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($technician) {
            $technician->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    public function labBills()
    {
        return $this->hasMany(LabBill::class);
    }
}
