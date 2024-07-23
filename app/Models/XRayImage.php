<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class XRayImage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['tooth_examination_id', 'xray', 'status', 'created_by', 'updated_by'];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        // Before creating a new record
        static::creating(function ($xray) {
            $xray->created_by = Auth::id(); // Set created_by to current user's ID
            $xray->updated_by = Auth::id();
        });

        // Before updating an existing record
        static::updating(function ($xray) {
            $xray->updated_by = Auth::id(); // Set updated_by to current user's ID
        });
    }

    public function toothExamination()
    {
        return $this->belongsTo(ToothExamination::class);
    }
}
