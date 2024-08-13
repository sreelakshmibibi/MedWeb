<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\TodayRequest;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\AppointmentType;
use App\Models\PatientProfile;
use App\Models\Department;
use App\Models\StaffProfile;
use App\Models\User;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\Console\Attribute\AsCommand;
use Yajra\DataTables\DataTables as DataTables;

class TodayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $selectedDate = date('Y-m-d');

            // Example: Fetch data from your model based on selected date
            $appointments = Appointment::whereDate('app_date', $selectedDate);
            if (!Auth::user()->is_admin) {
                $appointments = $appointments->where('doctor_id', Auth::user()->id);
            }
            $appointments = $appointments->with(['patient', 'doctor', 'branch'])
                ->orderBy('token_no', 'ASC')
                ->get();
            return DataTables::of($appointments)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    // return str_replace("<br>", " ", $row->patient->first_name . " " . $row->patient->last_name);
                    // $parent_id = $row->app_parent_id ? $row->app_parent_id : $row->id;
                    $name = str_replace("<br>", " ", $row->patient->first_name . " " . $row->patient->last_name);
                    // $name1 = "<a href='" . route('treatment', $row->id) . "' class='waves-effect waves-light' title='open treatment' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace("<br>", " ", $row->patient->first_name . " " . $row->patient->last_name) . "' >" . $name . "</i></a>";
                    return $name;
                })
                ->addColumn('doctor', function ($row) {
                    return str_replace("<br>", " ", $row->doctor->name);
                })
                ->addColumn('age', function ($row) {
                    $dob = new DateTime($row->patient->date_of_birth); // Create a DateTime object from the DOB
                    $now = new DateTime(); // Current date and time
                    $interval = $now->diff($dob); // Difference between current date and DOB
                    return $interval->y;
                })

                ->addColumn('phone', function ($row) {
                    // return str_replace("<br>", " ", $row->patient->first_name . " " . $row->patient->last_name);
                    // $parent_id = $row->app_parent_id ? $row->app_parent_id : $row->id;
                    $phone = $row->patient->phone;
                    // $name1 = "<a href='" . route('treatment', $row->id) . "' class='waves-effect waves-light' title='open treatment' data-id='{$row->id}' data-parent-id='{$parent_id}' data-patient-id='{$row->patient->patient_id}' data-patient-name='" . str_replace("<br>", " ", $row->patient->first_name . " " . $row->patient->last_name) . "' >" . $name . "</i></a>";
                    return $phone;
                })
                ->addColumn('app_type', function ($row) {
                    return $row->app_type == AppointmentType::NEW ? AppointmentType::NEW_WORDS :
                        ($row->app_type == AppointmentType::FOLLOWUP ? AppointmentType::FOLLOWUP_WORDS : '');
                })
                ->addColumn('status', function ($row) {
                    // $statusMap = [
                    //     AppointmentStatus::SCHEDULED => 'badge-success',
                    //     AppointmentStatus::WAITING => 'badge-warning',
                    //     AppointmentStatus::UNAVAILABLE => 'badge-warning-light',
                    //     AppointmentStatus::CANCELLED => 'badge-danger',
                    //     AppointmentStatus::COMPLETED => 'badge-success-light',
                    //     AppointmentStatus::BILLING => 'badge-primary',
                    //     AppointmentStatus::PROCEDURE => 'badge-secondary',
                    //     AppointmentStatus::MISSED => 'badge-danger-light',
                    //     AppointmentStatus::RESCHEDULED => 'badge-info',
                    // ];
    
                    $statusMap = [
                        AppointmentStatus::SCHEDULED => 'text-success',
                        AppointmentStatus::WAITING => 'text-warning',
                        AppointmentStatus::UNAVAILABLE => 'text-dark',
                        AppointmentStatus::CANCELLED => 'text-danger',
                        AppointmentStatus::COMPLETED => 'text-muted',
                        AppointmentStatus::BILLING => 'text-primary',
                        AppointmentStatus::PROCEDURE => 'text-secondary',
                        AppointmentStatus::MISSED => 'text-white',
                        AppointmentStatus::RESCHEDULED => 'text-info',
                    ];
                    $btnClass = isset($statusMap[$row->app_status]) ? $statusMap[$row->app_status] : '';
                    return "<span class=' {$btnClass}'>" . AppointmentStatus::statusToWords($row->app_status) . "</span>";

                    // return "<span class='btn d-block btn-xs badge {$btnClass}'>" . AppointmentStatus::statusToWords($row->app_status) . "</span>";
                })

                ->addColumn('action', function ($row) {
                    $btn = null;
                    $base64Id = base64_encode($row->id);
                    $idEncrypted = Crypt::encrypt($base64Id);
                    if ($row->app_status != AppointmentStatus::RESCHEDULED && $row->app_status != AppointmentStatus::CANCELLED) {
                        $btn = "<a href='" . route('treatment', $idEncrypted) . "' class='waves-effect waves-light btn btn-circle btn-info btn-xs me-1' title='view' data-id='{$row->id}' ><i class='fa-solid fa-eye'></i></a>";
                    } else {
                        $btn = "";
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('patient.today.index');
    }

    public function getTotal()
    {
        // Perform logic to calculate or retrieve the total
        $total = PatientProfile::where('status', 'Y')->count(); // Replace with your actual logic to get the total
        $totalStaff = StaffProfile::where('status', 'Y')->count();
        $totalDoctor = StaffProfile::where('status', 'Y')->whereNot('license_number', NULL)->count();
        $totalOthers = $totalStaff - $totalDoctor;

        return response()->json(['total' => $total, 'totalStaff' => $totalStaff, 'totalDoctor' => $totalDoctor, 'totalOthers' => $totalOthers]);
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
    public function store(TodayRequest $request)
    {
        // try {
        //     // Create a new department instance
        //     $department = new PatientProfile();
        //     $department->department = $request->input('department');
        //     $department->status = $request->input('status');
        //     $department->clinic_type_id = 1;

        //     // Save the department
        //     $department->save();

        //     return redirect()->back()->with('success', 'Department created successfully');
        // } catch (\Exception $e) {
        //     return redirect()->back()->with('error', 'Failed to create department: ' . $e->getMessage());
        // }

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
        $patient = PatientProfile::find($id);
        if (!$patient) {
            abort(404);
        }
        return $patient;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // $department = Department::findOrFail($request->edit_department_id);

        // // Update department fields based on form data
        // $department->department = $request->department;
        // $department->status = $request->status;

        // // Save the updated department
        // $department->save();

        // return redirect()->back()->with('success', 'Department updated successfully.');
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
