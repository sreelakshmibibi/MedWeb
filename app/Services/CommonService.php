<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\MenuItem;
use Carbon\Carbon;

class CommonService
{
    public function getMenuItems()
    {
        return MenuItem::whereNull('parent_id')
            ->where('status', 'Y')
            ->with(['children' => function ($query) {
                $query->where('status', 'Y')
                    ->orderBy('order_no');
            }])
            ->orderBy('order_no')
            ->get();
    }


    public function splitNames($name)
    {
        $split_name = explode('<br>', $name);
        return $split_name;
    }

    public function generateUniqueAppointmentId()
    {
        // Get the current year and month in the format 'Ym'
        $yearMonth = Carbon::now()->format('Ym');
        // Count the number of appointments created in the current month
        $appointmentCount = Appointment::whereYear('app_date', Carbon::now()->year)
            ->whereMonth('app_date', Carbon::now()->month)
            ->count();
        // Increment the count by 1
        $newAppointmentNumber = $appointmentCount + 1;

        // Concatenate the year, month, and the incremented count to form the appointment ID
        $appId = 'APP' . $yearMonth . str_pad($newAppointmentNumber, 4, '0', STR_PAD_LEFT);
        //Log::info('$appId: '.$appId);

        return $appId;
    }

    // Other common methods can be added here
}