<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\ClinicBranch;
use App\Models\Country;
use App\Models\PatientProfile;
use App\Models\Teeth;
use App\Services\CommonService;
use App\Services\DoctorAvaialbilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id, Request $request)
    {
        $appointment = Appointment::with(['patient', 'doctor', 'branch'])->find($id);
        abort_if(!$appointment, 404);
        // Format clinic address
        $clinicAddress = implode(", ", [
            str_replace("<br>", ", ", $appointment->branch->clinic_address),
            $appointment->branch->city->city,
            $appointment->branch->state->state
        ]);

        // Update appointment object with formatted clinic address
        $appointment->clinic_branch = $clinicAddress;

        // Format date and time
        $appointment->app_date = date('d-m-Y', strtotime($appointment->app_date));
        $appointment->app_time = date('H:i', strtotime($appointment->app_time));

        $patientProfile = PatientProfile::with(['lastAppointment'])->find($appointment->patient->id);
        //$patient = PatientProfile::find($id);
        abort_if(!$patientProfile, 404);
        $appointment = $patientProfile->lastAppointment;
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        $countries = Country::all();
        $commonService = new CommonService();
        $doctorAvailabilityService = new DoctorAvaialbilityService();
        $appointmentStatuses = AppointmentStatus::all();
        $name = $commonService->splitNames($patientProfile->first_name);
        $date = Carbon::parse($patientProfile->lastAppointment->app_date)->toDateString(); // 'Y-m-d'
        $carbonDate = Carbon::parse($date);
        $weekday = $carbonDate->format('l');
        $workingDoctors = $doctorAvailabilityService->getTodayWorkingDoctors($patientProfile->lastAppointment->app_branch, $weekday);
        $appDate = $appointment->app_date;
        $appTime = $appointment->app_time;
        // Combine date and time into a single datetime string
        $dateTimeString = "{$appDate} {$appTime}";
        // Parse the combined datetime string as it is already in IST
        $dateTime = Carbon::parse($dateTimeString)
            ->format('Y-m-d\TH:i');

        $tooth = Teeth::all();

        if ($request->ajax()) {

            return DataTables::of($appointment)
                ->addIndexColumn()
                ->addColumn('doctor', function ($row) {
                    return str_replace("<br>", " ", $row->doctor->name);
                })
                ->addColumn('branch', function ($row) {
                    if (!$row->branch) {
                        return '';
                    }
                    $address = implode(", ", explode("<br>", $row->branch->clinic_address));
                    return implode(", ", [$address, $row->branch->city->city, $row->branch->state->state]);
                })
                ->addColumn('status', function ($row) {
                    $statusMap = [
                        AppointmentStatus::SCHEDULED => 'badge-success-light',
                        AppointmentStatus::WAITING => 'badge-success-light',
                        AppointmentStatus::UNAVAILABLE => 'badge-danger-light',
                        AppointmentStatus::CANCELLED => 'badge-danger-light',
                        AppointmentStatus::COMPLETED => 'badge-success-light',
                        AppointmentStatus::BILLING => 'badge-success-light',
                        AppointmentStatus::PROCEDURE => 'badge-success-light',
                        AppointmentStatus::MISSED => 'badge-danger-light',
                        AppointmentStatus::RESCHEDULED => 'badge-success-light',
                    ];
                    $btnClass = isset($statusMap[$row->app_status]) ? $statusMap[$row->app_status] : '';
                    return "<span class='btn-sm badge {$btnClass}'>" . AppointmentStatus::statusToWords($row->app_status) . "</span>";
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        // return view('appointment.treatment');
        return view('appointment.treatment', compact('name', 'patientProfile', 'countries', 'appointment', 'clinicBranches', 'appointmentStatuses', 'workingDoctors', 'dateTime', 'tooth'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
