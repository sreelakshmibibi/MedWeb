<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentStatus extends Model
{
    use HasFactory;

    const COMPLETED = 1;

    const FOLLOWUP = 2;

    const COMPLETED_WORDS = 'Completed';

    const FOLLOWUP_WORDS = 'Follow up required';

    protected $fillable = ['status'];

    public static function statusToWords($status)
    {
        switch ($status) {

            case self::COMPLETED:
                return self::COMPLETED_WORDS;
            case self::FOLLOWUP:
                return self::FOLLOWUP_WORDS;
            default:
                return 'Unknown'; // Handle unknown statuses gracefully
        }
    }
}
