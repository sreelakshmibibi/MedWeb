<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['city', 'state_id'];
    protected $dates = ['deleted_at'];

    public function clinicBranches()
    {
        return $this->hasMany(ClinicBranch::class, 'city_id', 'id');
    }
    
}
