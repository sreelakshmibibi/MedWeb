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

    protected $fillable = [
        'patient_id',
        'first_name',
        'last_name',
        'aadhaar_no',
        'date_of_birth',
        'gender',
        'blood_group',
        'phone',
        'alternate_phone',
        'email',
        'address1',
        'address2',
        'city_id',
        'state_id',
        'country_id',
        'pincode',
        'marital_status',
        'visit_count',
        'status',
        'created_by',
        'updated_by',
    ];

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

    public function toothExamination()
    {
        return $this->hasMany(ToothExamination::class, 'patient_id', 'patient_id');
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

    public function nextDoctorBranchAppointment($doctorId, $branchId)
    {
        return $this->hasOne(Appointment::class, 'patient_id', 'patient_id')
            ->where('app_date', '>', now())
            ->where('doctor_id', $doctorId)
            ->where('branch_id', $branchId)
            ->where('status', 'Y')
            ->where('app_status', AppointmentStatus::SCHEDULED)
            ->orderBy('app_date')
            ->orderBy('app_time')
            ->first(); // Retrieve the first matching appointment
    }

    public function lastAppointment()
    {
        return $this->hasOne(Appointment::class, 'patient_id', 'patient_id')
            ->where('app_parent_id', null)
            ->where('app_type', 2)
            ->with(['doctor', 'branch'])
            ->latest();
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

    // public function history()
    // {
    //     return $this->hasMany(History::class, 'patient_id', 'patient_id');
    // }

    public function history()
    {

        $lastAppointmentId = optional($this->lastAppointment)->id; // Use optional to avoid null reference

        return $this->hasMany(History::class, 'patient_id', 'patient_id')
            ->when($lastAppointmentId, function ($query) use ($lastAppointmentId) {
                $query->where('app_id', $lastAppointmentId);
            });
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id', 'patient_id');
    }
}
