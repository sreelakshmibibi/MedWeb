<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\PatientListRequest;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\City;
use App\Models\ClinicBranch;
use App\Models\Country;
use App\Models\DoctorWorkingHour;
use App\Models\PatientProfile;
use App\Models\State;
use App\Services\CommonService;
use App\Services\DoctorAvaialbilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables as DataTables;

class PatientListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $patient = PatientProfile::with(['latestAppointment', 'nextAppointment', 'country', 'state', 'city'])->get();

            return DataTables::of($patient)
                ->addIndexColumn()
                ->addColumn('first_name', function ($row) {
                    return str_replace('<br>', ' ', $row->first_name);
                })
                ->addColumn('record_status', function ($row) {
                    return $row->status;
                })
                ->addColumn('appointment_status', function ($row) {
                    if ($row->latestAppointment) {
                        $btn = '';

                        //return $row->latestAppointment->app_status;
                        if ($row->latestAppointment->app_status == 1) {
                            $btn = "<span class='d-block badge badge-success-light'>Scheduled</span>";
                        } elseif ($row->latestAppointment->app_status == 2) {
                            $btn = '<span class="d-block badge badge-success-light">Waiting</span>';
                        } elseif ($row->latestAppointment->app_status == 3) {
                            $btn = '<span class="d-block badge badge-danger-light">Unavailable</span>';
                        } elseif ($row->latestAppointment->app_status == 4) {
                            $btn = '<span class="d-block badge badge-danger-light">Cancelled</span>';
                        } elseif ($row->latestAppointment->app_status == 5) {
                            $btn = '<span class="d-block badge badge-success-light">Completed</span>';
                        } elseif ($row->latestAppointment->app_status == 6) {
                            $btn = '<span class="d-block badge badge-success-light">Billing</span>';
                        } elseif ($row->latestAppointment->app_status == 7) {
                            $btn = '<span class="d-block badge badge-success-light">Procedure</span>';
                        } elseif ($row->latestAppointment->app_status == 8) {
                            $btn = '<span class="d-block badge badge-danger-light">Missed</span>';
                        } elseif ($row->latestAppointment->app_status == 9) {
                            $btn = '<span class="d-block badge badge-success-light">Re-Scheduled</span>';
                        }

                        return $btn;
                    }

                    return 'N/A';
                })
                ->addColumn('address', function ($row) {
                    $address = $row->address1 . ', ' . $row->address2 . ', ' . $row->city->city . ', ' .
                        $row->state->state . ', ' .
                        $row->country->country . ', ' .
                        'Pincode - ' . $row->pincode;

                    return $address;
                })
                ->addColumn('appointment', function ($row) {
                    if ($row->latestAppointment) {
                        return $row->latestAppointment->app_date . ' ' . $row->latestAppointment->app_time;
                    }

                    return 'N/A';
                })
                ->addColumn('next_appointment', function ($row) {
                    if ($row->nextAppointment) {
                        return $row->nextAppointment->app_date . ' ' . $row->nextAppointment->app_time;
                    }

                    return 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('staff.staff_list.view', $row->id) . '" class="waves-effect waves-light btn btn-circle btn-info btn-xs me-1" title="view"><i class="fa fa-eye"></i></a>';
                    $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-xs" data-bs-toggle="modal" data-bs-target="#modal-status" data-id="' . $row->id . '" title="change status"><i class="fa-solid fa-sliders"></i></button>';
                    if (auth()->user()->hasRole('Admin')) {
                        $btn .= '<a href="' . route('patient.patient_list.edit', $row->id) . '" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit"><i class="fa fa-pencil"></i></a>';
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="Delete"><i class="fa-solid fa-trash"></i></button>';
                    }

                    return $btn;
                })
                ->rawColumns(['appointment_status', 'action'])
                ->make(true);
        }

        return view('patient.patient_list.index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        // Get the first branch ID
        $firstBranchId = optional($clinicBranches->first())->id;
        $currentDayName = Carbon::now()->englishDayOfWeek;
        $workingDoctors = $this->getTodayWorkingDoctors($firstBranchId, $currentDayName);
        $appointmentStatuses = AppointmentStatus::all(); // Get all appointment statuses

        return view('patient.patient_list.add', compact('countries', 'states', 'cities', 'clinicBranches', 'workingDoctors', 'appointmentStatuses'));

    }

    public function getTodayWorkingDoctors($branchId, $weekday)
    {

        $query = DoctorWorkingHour::where('week_day', $weekday)
            ->where('status', 'Y');

        if ($branchId) {
            $query->where('clinic_branch_id', $branchId);
        }

        return $query->with('user')->get();
    }

    public function fetchDoctors($branchId, Request $request)
    {
        // Extract the date part from appdate
        $date = Carbon::parse($request->input('appdate'))->toDateString(); // 'Y-m-d'
        $carbonDate = Carbon::parse($date);
        $weekday = $carbonDate->format('l');
        $workingDoctors = $this->getTodayWorkingDoctors($branchId, $weekday);

        return response()->json($workingDoctors);
    }

    public function fetchExistingAppointments($branchId, Request $request)
    {
        $appDateTime = Carbon::parse($request->input('appdate'));
        $appDate = $appDateTime->toDateString(); // Extract date
        $appTime = $appDateTime->toTimeString(); // Extract time 
        $doctor_id = $request->input('doctor_id');
        $doctorAvailabilityService = new DoctorAvaialbilityService();
        $existingAppointments = $doctorAvailabilityService->getExistingAppointments($branchId, $appDate, $doctor_id);
        $checkAllocated = $doctorAvailabilityService->checkAllocatedAppointments($branchId, $appDate, $doctor_id, $appTime);
        $response = [
            "existingAppointments" => $existingAppointments,
            "checkAllocated" => $checkAllocated
        ];
        return response()->json($response);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientListRequest $request)
    {
        //
        try {
            //Log::info('$appId: '.$request);
            DB::beginTransaction();
            // Generate a unique patient ID using the current date
            $date = now()->format('Ymd'); // Get the current date in YYYYMMDD format
            $today = Carbon::today();

            // Get the maximum patient_id for today and increment it
            $latestPatient = DB::table('patient_profiles')
                ->whereDate('created_at', $today)
                ->orderByDesc('patient_id')
                ->first();

            if ($latestPatient) {
                // Extract the count from the latest patient_id (last 3 digits)
                $latestCount = (int) substr($latestPatient->patient_id, -3);
                $dailyCount = $latestCount + 1;
            } else {
                // No patient IDs for today, start with 001
                $dailyCount = 1;
            }

            $uniquePatientId = $date . sprintf('%03d', $dailyCount);

            // Store the patient data
            $patient = new PatientProfile();
            $patient->patient_id = $uniquePatientId; // Generate a unique patient_id
            $patient->first_name = $request->input('title') . '<br> ' . $request->input('firstname');
            $patient->last_name = $request->input('lastname');
            $patient->gender = $request->input('gender');
            $patient->date_of_birth = $request->input('date_of_birth');
            $patient->aadhaar_no = $request->input('aadhaar_no');
            $patient->email = $request->input('email');
            $patient->phone = $request->input('phone');
            $patient->alternate_phone = $request->input('alter_phone');
            $patient->blood_group = $request->input('blood_group');
            $patient->address1 = $request->input('address1');
            $patient->address2 = $request->input('address2');
            $patient->country_id = $request->input('country_id');
            $patient->state_id = $request->input('state_id');
            $patient->city_id = $request->input('city_id');
            $patient->pincode = $request->input('pincode');
            $patient->created_by = auth()->user()->id;
            $patient->updated_by = auth()->user()->id;
            if ($patient->save()) {

                // Get the maximum token number for the chosen doctor and today's date
                $doctorId = $request->input('doctor2');
                $todayDate = now()->toDateString();
                $maxToken = Appointment::where('doctor_id', $doctorId)
                    ->whereDate('created_at', $todayDate)
                    ->max('token_no');
                $tokenNo = $maxToken ? $maxToken + 1 : 1;

                $appDateTime = Carbon::parse($request->input('appdate'));
                $appDate = $appDateTime->toDateString(); // Extract date
                $appTime = $appDateTime->toTimeString(); // Extract time

                // Check if an appointment with the same date and time already exists for the given doctor
                $existingAppointment = Appointment::where('doctor_id', $doctorId)
                    ->where('app_date', $appDate)
                    ->where('app_time', $appTime)
                    ->first();

                if ($existingAppointment) {
                    DB::rollBack();

                    return response()->json(['error' => 'An appointment already exists for the given date, time, and doctor.'], 422);
                }

                // Store the appointment data
                $appointment = new Appointment();
                //$appointment->app_id = Appointment::max('app_id') + 1; // Generate a unique app_id
                $appointment->app_id = $this->generateUniqueAppointmentId();
                $appointment->patient_id = $patient->patient_id;
                $appointment->app_date = $appDate;
                $appointment->app_time = $appTime;
                $appointment->token_no = $tokenNo;
                $appointment->doctor_id = $request->input('doctor2');
                $appointment->app_branch = $request->input('clinic_branch_id0');
                $appointment->app_type = 1;
                $appointment->height_cm = $request->input('height');
                $appointment->weight_kg = $request->input('weight');
                $appointment->blood_pressure = $request->input('bp');
                $appointment->referred_doctor = $request->input('rdoctor');
                $appointment->app_status = $request->input('appstatus');
                $appointment->created_by = auth()->user()->id;
                $appointment->updated_by = auth()->user()->id;
                if ($appointment->save()) {
                    DB::commit();

                    return redirect()->route('patient.patient_list')->with('success', 'Patient and appointment added successfully');
                } else {
                    DB::rollBack();

                    return redirect()->back()->with('error', 'Failed to create appointment');
                }
            } else {
                DB::rollBack();

                return redirect()->back()->with('error', 'Failed to create patient');
            }

            // Redirect back with a success message
            //return redirect()->route('patient.patient_list')->with('success', 'Patient added successfully');
        } catch (\Exception $e) {

            DB::rollback();

            // exit;
            return response()->json(['error' => 'Failed to create patient: ' . $e->getMessage()], 422);
        }

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
        $patientProfile = PatientProfile::with(['lastAppointment'])->find($id);
        //$patient = PatientProfile::find($id);
        abort_if(!$patientProfile, 404);
        $appointment = $patientProfile->lastAppointment;
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        $countries = Country::all();
        $commonService = new CommonService();
        $appointmentStatuses = AppointmentStatus::all();
        $name = $commonService->splitNames($patientProfile->first_name);
        $date = Carbon::parse($patientProfile->lastAppointment->app_date)->toDateString(); // 'Y-m-d'
        $carbonDate = Carbon::parse($date);
        $weekday = $carbonDate->format('l');
        $workingDoctors = $this->getTodayWorkingDoctors($patientProfile->lastAppointment->app_branch, $weekday);
        $appDate = $appointment->app_date;
        $appTime = $appointment->app_time;
        // Combine date and time into a single datetime string
        $dateTimeString = "{$appDate} {$appTime}";
        // Parse the combined datetime string as it is already in IST
        $dateTime = Carbon::parse($dateTimeString)
            ->format('Y-m-d\TH:i');

        return view('patient.patient_list.edit', compact('name', 'patientProfile', 'countries', 'appointment', 'clinicBranches', 'appointmentStatuses', 'workingDoctors', 'dateTime'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientListRequest $request)
    {
        try {
            DB::beginTransaction();

            // Update the patient data
            $patient = PatientProfile::findOrFail($request->edit_patient_id);
            $patient->fill([
                'first_name' => $request->title . '<br> ' . $request->firstname,
                'last_name' => $request->input('lastname'),
                'gender' => $request->input('gender'),
                'date_of_birth' => $request->input('date_of_birth'),
                'aadhaar_no' => $request->input('aadhaar_no'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'alternate_phone' => $request->input('alter_phone'),
                'blood_group' => $request->input('blood_group'),
                'address1' => $request->input('address1'),
                'address2' => $request->input('address2'),
                'country_id' => $request->input('country_id'),
                'state_id' => $request->input('state_id'),
                'city_id' => $request->input('city_id'),
                'pincode' => $request->input('pincode'),
                'updated_by' => auth()->user()->id,
            ]);

            if (!$patient->save()) {
                //throw new \Exception('Failed to update patient');
                return redirect()->back()->with('error', 'Failed to update patient');
            }

            // Update the appointment data
            $appointment = Appointment::findOrFail($request->edit_app_id);
            $appDateTime = Carbon::parse($request->input('appdate'));
            $appDate = $appDateTime->toDateString();
            $appTime = $appDateTime->toTimeString();

            // Check if an appointment with the same date, time, and doctor already exists
            $existingAppointment = Appointment::where('doctor_id', $request->input('doctor2'))
                ->where('app_date', $appDate)
                ->where('app_time', $appTime)
                ->where('id', '!=', $appointment->id) // Exclude current appointment
                ->exists();

            if ($existingAppointment) {
                return response()->json(['error' => 'An appointment already exists for the given date, time, and doctor.'], 422);
            }

            $appointment->fill([
                'app_date' => $appDate,
                'app_time' => $appTime,
                'doctor_id' => $request->input('doctor2'),
                'app_branch' => $request->input('clinic_branch_id0'),
                'app_type' => 1,
                'height_cm' => $request->input('height'),
                'weight_kg' => $request->input('weight'),
                'blood_pressure' => $request->input('bp'),
                'referred_doctor' => $request->input('rdoctor'),
                'app_status' => $request->input('appstatus'),
                'updated_by' => auth()->user()->id,
            ]);

            if (!$appointment->save()) {
                //throw new \Exception('Failed to update appointment');
                return redirect()->back()->with('error', 'Failed to update appointment');
            }

            DB::commit();

            return redirect()->route('patient.patient_list')->with('success', 'Patient and appointment updated successfully');
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Failed to update patient: ' . $e->getMessage()], 422);
        }
    }

    public function changeStatus(string $id)
    {
        $patientProfile = PatientProfile::find($id);
        abort_if(!$patientProfile, 404);
        if ($patientProfile) {
            $active = 'N';
            $inActive = 'Y';
            if ($patientProfile->status == $active) {
                $patientProfile->status = $inActive;
            } else {
                $patientProfile->status = $active;
            }
            $patientProfile->save();

            return redirect()->route('patient.patient_list')->with('success', 'Status updated successfully');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $patient = PatientProfile::findOrFail($id);
        $patient->delete();

        return response()->json(['success', 'Patient deleted successfully.'], 201);
    }
}