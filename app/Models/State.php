<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['state', 'country_id'];
    protected $dates = ['deleted_at'];

    public function clinicBranches()
    {
        return $this->hasMany(ClinicBranch::class, 'state_id', 'id');
    }
}
