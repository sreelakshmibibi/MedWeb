<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffListRequest;
use App\Http\Requests\Staff\StaffProfileRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\DoctorWorkingHour;
use App\Models\PatientProfile;
use App\Models\StaffProfile;
use App\Models\State;
use App\Models\User;
use App\Models\UserType;
use App\Models\WeekDay;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables as DataTables;
use Illuminate\Support\Str;

class StaffListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $patient = PatientProfile::query();
            return DataTables::of($patient)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    //choose from the below 3 according to the status
                    $btn = '<span class="btn-sm badge badge-danger-light">New Patient</span>';
                    $btn = '<span class="btn-sm badge badge-success-light">Recovered</span>';
                    $btn = '<span class="btn-sm badge badge-warning-light">In Treatment</span>';

                    return $btn;
                })
                ->addColumn('action', function ($row) {

                    $btn1 = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';

                    return $btn1;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $departments = Department::where('status', 'Y')->get();
        return view('staff.staff_list.index', compact('countries', 'states', 'cities', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $userTypes = UserType::where('status', 'Y')->get();
        $departments = Department::where('status', 'Y')->get();
        return view('staff.staff_list.add', compact('countries', 'states', 'cities', 'userTypes', 'departments'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Create a new user instance
            $user = new User();
            $user->name = $request->firstname . " " . $request->lastname;
            $user->email = $request->email;

            // Set user role based on request
            switch ($request->role) {
                case User::IS_ADMIN:
                    $user->is_admin = true;
                    break;
                case User::IS_DOCTOR:
                    $user->is_doctor = true;
                    break;
                case User::IS_NURSE:
                    $user->is_nurse = true;
                    break;
                case User::IS_RECEPTION:
                    $user->is_reception = true;
                    break;
                default:
                    throw new \Exception('Invalid role specified.');
            }

            // Set default password
            $password = Str::random(10);
            $user->password = Hash::make($password);
            $user->save();

            // Create staff profile
            $staffProfile = new StaffProfile();
            $staffProfile->user_id = $user->id;
            $staffProfile->staff_id = "MEDWEB" . $user->id;
            $staffProfile->fill($request->only([
                'date_of_birth', 'phone', 'gender', 'address1', 'address2', 'city_id', 'state_id',
                'country_id', 'pincode', 'date_of_joining', 'qualification', 'department_id',
                'specialization', 'years_of_experience', 'license_number', 'subspecialty'
            ]));
            
            // Save profile photo if provided
            if ($request->hasFile('profile')) {
                $profilePath = $request->file('profile')->store('profile-photos', 'public');
                $staffProfile->photo = $profilePath;
            }

            $staffProfile->save();

            // If user is a doctor, save availability
            if ($user->is_doctor) {
                $this->saveDoctorAvailability($request, $user->id);
            }

            DB::commit();

            // Send welcome email (you can implement this part)

            return redirect()->back()->with('success', 'Staff created successfully');
        } catch (\Exception $e) {
            print_r($e->getMessage());

            DB::rollBack();
exit;
            return redirect()->back()->with('error', 'Failed to create staff: ' . $e->getMessage());
        }
    }


    private function saveDoctorAvailability(Request $request, $userId)
    {
        $weekDays = [
            WeekDay::MONDAY, WeekDay::TUESDAY, WeekDay::WEDNESDAY, WeekDay::THURSDAY,
            WeekDay::FRIDAY, WeekDay::SATURDAY, WeekDay::SUNDAY
        ];

        foreach ($weekDays as $day) {
            $fromKey = strtolower($day) . '_from';
            $toKey = strtolower($day) . '_to';

            if ($request->$fromKey !== null) {
                $availability = new DoctorWorkingHour();
                $availability->user_id = $userId;
                $availability->week_day = $day;
                $availability->from_time = $request->$fromKey;
                $availability->to_time = $request->$toKey;
                $availability->save();
            }
        }
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
        $patient = PatientProfile::findOrFail($request->edit_department_id);

        // Update department fields based on form data
        $patient->patient = $request->patient;
        $patient->status = $request->status;

        // Save the updated department
        $patient->save();

        return redirect()->back()->with('success', 'Patient updated successfully.');
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
