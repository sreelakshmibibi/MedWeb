<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PatientProfile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['patient_id', 'first_name', 'last_name', 'aadhaar_no', 'date_of_birth', 'gender', 'blood_group', 'phone', 'alternate_phone', 'email', 'address1', 'address2', 'city_id', 'state_id', 'country_id', 'pincode', 'visit_count', 'status', 'created_by',
        'updated_by'];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($patient) {
            $patient->created_by = Auth::id(); // Set created_by to current user's ID
            $patient->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($patient) {
            $patient->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'patient_id');
    }

    public function latestAppointment()
    {
        return $this->hasOne(Appointment::class, 'patient_id', 'patient_id')
            ->where('app_date', '<=', now())
            ->latest('app_date')
            ->latest('app_time');
        //return $this->hasOne(Appointment::class, 'patient_id', 'patient_id')->latest();
    }

    public function nextAppointment()
    {
        return $this->hasOne(Appointment::class, 'patient_id', 'patient_id')
            ->where('app_date', '>', now())
            ->oldest('app_date')
            ->oldest('app_time');
    }

    // Define the relationship with Country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    // Define the relationship with State
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    // Define the relationship with City
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
