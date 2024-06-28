<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['code', 'country', 'phonecode'];
    protected $dates = ['deleted_at'];

    // Define relationship with ClinicBranch
    public function clinicBranches()
    {
        return $this->hasMany(ClinicBranch::class, 'country_id', 'id');
    }
}
