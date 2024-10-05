<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\ClinicBasicDetail;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\MenuItem;
use App\Models\PatientTreatmentBilling;
use App\Models\StaffProfile;
use Carbon\Carbon;
use DateTime;

class LeaveService
{
    public function getFinancialYear()
    {
        $financialYearDetails = ClinicBasicDetail::select('financial_year_start', 'financial_year_end')
            ->first();

        if ($financialYearDetails) {
            $currentDate = Carbon::now();
            $currentYear = $currentDate->year;
            $currentMonth = $currentDate->month;

            if ($currentMonth >= $financialYearDetails->financial_year_start) {
                $startOfFinancialYear = Carbon::createFromDate($currentYear, $financialYearDetails->financial_year_start, 1);
                $endOfFinancialYear = Carbon::createFromDate($currentYear + 1, $financialYearDetails->financial_year_end, 31);
            } else {
                $startOfFinancialYear = Carbon::createFromDate($currentYear - 1, $financialYearDetails->financial_year_start, 1);
                $endOfFinancialYear = Carbon::createFromDate($currentYear, $financialYearDetails->financial_year_end, 31);
            }

            return [
                'start' => $startOfFinancialYear->toDateString(),
                'end' => $endOfFinancialYear->toDateString(),
                'startMonth' => $financialYearDetails->financial_year_start,
                'endMonth' => $financialYearDetails->financial_year_end,
                'monthsElapsed' => $currentMonth - $financialYearDetails->financial_year_start + 1
            ];
        }

        return null;
    }

    public function getJoiningDate($userId)
    {
        // return StaffProfile::where('user_id', $userId)->value('date_of_joining');
        return Carbon::parse("2024-04-10");
    }

    public function getLeaveAppliedCount($leaveType, $financialYearDetails)
    {
        if (!$financialYearDetails) {
            return 0;
        }

        $startDate = Carbon::parse($financialYearDetails['start']);
        $endDate = Carbon::parse($financialYearDetails['end']);

        return LeaveApplication::where('leave_type_id', $leaveType)
            ->where('leave_status', LeaveApplication::Applied)
            ->whereBetween('leave_from', [$startDate, $endDate])
            ->orWhereBetween('leave_to', [$startDate, $endDate])
            ->orWhere(function ($query) use ($startDate, $endDate) {
                $query->where('leave_from', '<=', $startDate)
                    ->where('leave_to', '>=', $endDate);
            })
            ->count();
    }

    public function getLeaveCount($leaveType)
    {
        return LeaveType::where('id', $leaveType)->value('duration');
    }

    public function getDaysDifference($leaveFrom, $leaveTo)
    {
        $holidays = Holiday::whereBetween('holiday_on', [$leaveFrom, $leaveTo])
            ->pluck('holiday_on')
            ->map(function($date) {
                return Carbon::parse($date)->format('Y-m-d'); // Convert to standard format
            })->toArray();

        $daysCount = 0;

        foreach (new \DatePeriod($leaveFrom, new \DateInterval('P1D'), $leaveTo->addDay()) as $date) {
            $carbonDate = Carbon::instance($date); // Convert to Carbon instance

            // Check if the day is a Sunday or a holiday
            if ($carbonDate->isSunday() || in_array($carbonDate->format('Y-m-d'), $holidays)) {
                continue; // Skip Sundays and holidays
            }
            
            $daysCount++; // Increment count for valid days
        }
        return $daysCount;
    }
}

