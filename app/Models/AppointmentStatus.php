<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentStatus extends Model
{
    use HasFactory;
    use SoftDeletes;
    const SCHEDULED = 1;
    const WAITING = 2;
    const UNAVAILABLE = 3;
    const CANCELLED = 4;

    const COMPLETED = 5;
    const BILLING = 6;
    const PROCEDURE = 7;
    const MISSED = 8;
    const RESCHEDULED = 9;
    const SCHEDULED_WORDS = "SCHEDULED";
    const WAITING_WORDS = "WAITING";
    const UNAVAILABLE_WORDS = "UNAVAILABLE";
    const CANCELLED_WORDS = "CANCELLED";

    const COMPLETED_WORDS = "COMPLETED";
    const BILLING_WORDS = "BILLING";
    const PROCEDURE_WORDS = "PROCEDURE";
    const MISSED_WORDS = "MISSED";
    const RESCHEDULED_WORDS = "RESCHEDULED";

    protected $fillable = ['status', 'st_color', 'tx_color', 'stat', 'indrop'];

    protected $dates = ['deleted_at'];

    public static function statusToWords($status)
    {
        switch ($status) {
            case self::SCHEDULED:
                return self::SCHEDULED_WORDS;
            case self::WAITING:
                return self::WAITING_WORDS;
            case self::UNAVAILABLE:
                return self::UNAVAILABLE_WORDS;
            case self::CANCELLED:
                return self::CANCELLED_WORDS;
            case self::COMPLETED:
                return self::COMPLETED_WORDS;
            case self::BILLING:
                return self::BILLING_WORDS;
            case self::PROCEDURE:
                return self::PROCEDURE_WORDS;
            case self::MISSED:
                return self::MISSED_WORDS;
            case self::RESCHEDULED:
                return self::RESCHEDULED_WORDS;
            default:
                return 'Unknown'; // Handle unknown statuses gracefully
        }
    }
}
