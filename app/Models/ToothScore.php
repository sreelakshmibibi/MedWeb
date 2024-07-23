<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToothScore extends Model
{
    use HasFactory;

    protected $fillable = ['score'];

    public function toothExaminations()
    {
        return $this->hasMany(ToothExamination::class, 'tooth_score_id', 'id');
    }
}
