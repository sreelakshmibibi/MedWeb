<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    const IS_ADMIN = 2;
    const IS_DOCTOR = 3;
    const IS_NURSE = 4;
    const IS_RECEPTION = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_doctor',
        'is_nurse',
        'is_reception',
        'created_by',
        'updated_by'
    ];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($user) {
            $user->created_by = Auth::id(); // Set created_by to current user's ID
            $user->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($user) {
            $user->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }
    
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function staffProfile()
    {
        return $this->hasOne(StaffProfile::class);
    }

    public function doctorWorkingHours()
    {
        return $this->hasMany(DoctorWorkingHour::class);
    }
}
