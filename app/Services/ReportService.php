<?php

namespace App\Services;

use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\ComboOfferTreatment;
use App\Models\Disease;
use App\Models\DoctorWorkingHour;
use App\Models\MenuItem;
use App\Models\Treatment;
use App\Models\TreatmentComboOffer;
use App\Models\TreatmentPlan;
use App\Models\TreatmentType;
use App\Models\User;
use App\Models\WeekDay;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class ReportService
{
    public function getTreatments()
    {
        return TreatmentType::orderBy('treat_name', 'asc')->get();
    }

    public function getDiseases()
    {
        return Disease::orderBy('name', 'asc')->get();
    }

    public function getDoctors()
    {
        return User::where('is_doctor', 1)
                    ->get();
    }

    public function getBranches()
    {
        $branchesList = [];
       $clinicBranches =  ClinicBranch::with(['country', 'state', 'city'])->get();
        foreach ($clinicBranches as $clinicBranch) 
        {
            $clinicAddress = explode("<br>", $clinicBranch->clinic_address);
            $clinicAddress = implode(", ", $clinicAddress) . ', ' .
                $clinicBranch->city->city . ', ' .
                $clinicBranch->state->state . ', ' .
                $clinicBranch->country->country . ', ' .
                "Pincode - " . $clinicBranch->pincode;
            $branchesList[] = ['id'=> $clinicBranch->id, 'name'=> $clinicAddress];
        }
        return $branchesList;
    }

    public function getTreatmentPlans()
    {
        return TreatmentPlan::all();
    }

    public function getStaff()
    {
        return User::all();
    }

    public function getBillStaff()
    {
        return User::all();
    }

    public function getAttendanceStaff()
    {
        return User::whereHas('staffProfile', function ($query) {
            $query->where('visiting_doctor', 0);
        })
        ->select('id', 'name')
        ->get();
    }

    public function getComboOffers()
    {
        return TreatmentComboOffer::with('treatments')->get();
    }

    public function getYears()
    {
        $years = [];
        $record = ClinicBasicDetail::first();
        $years = [];

    if ($record) {
        // Get the starting year from the created_at field
        $startedYear = $record->created_at->format('Y');

        // Get the current year
        $currentYear = Carbon::now()->format('Y');

        // Generate an array of years from the startedYear to the currentYear
        for ($year = $startedYear; $year <= $currentYear; $year++) {
            $years[] = $year;
        }
    }

    return $years;
        
        
    }
}

