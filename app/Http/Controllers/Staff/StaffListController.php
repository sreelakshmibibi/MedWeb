<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffListRequest;
use App\Http\Requests\Staff\StaffProfileRequest;
use App\Models\City;
use App\Models\ClinicBranch;
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
use Spatie\Permission\Models\Role;
use App\Services\CommonService;
use App\Services\DoctorAvaialbilityService;

class StaffListController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $successMessage = $request->query('success_message');

        if ($successMessage) {
            // Flash success message to session
            session()->flash('success', $successMessage);
        }

        if ($request->ajax()) {
            $staff = StaffProfile::with('user')->get();

            return DataTables::of($staff)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->user->email;
                })

                ->addColumn('role', function ($row) {
                    $role = null;
                    // Assuming you want to dynamically set the role badge based on user attributes
                    if ($row->user->is_doctor) {
                        $role .= '<span class="btn-sm badge badge-success-light">Doctor</span>';
                    }if ($row->user->is_nurse) {
                        $role .= '<span class="btn-sm badge badge-warning-light">Nurse</span>';
                    }if ($row->user->is_admin) {
                        $role .= '<span class="btn-sm badge badge-primary-light">Admin</span>';
                    }if ($row->user->is_reception) {
                        $role .= '<span class="btn-sm badge badge-info-light">Others</span>';
                    }
                    return $role;
                })
                ->addColumn('action', function ($row) {
                    // $btn1 = '<a href="' . route('staff.staff_list.edit', $row->id) . '" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit"><i class="fa fa-pencil"></i></a>';
                    // $btn2 = '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete"><i class="fa fa-trash"></i></button>';
                    // return $btn1 . $btn2;
                    $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-info btn-xs me-1" title="view">
                    <i class="fa fa-eye"></i></button>';
                    $btn1 = '<a href="' . route('staff.staff_list.edit', $row->id) . '" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit"><i class="fa fa-pencil"></i></a>';
                    // $btn2 = '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete"><i class="fa fa-trash"></i></button>';
                    $btn3 = '<button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-xs" data-bs-toggle="modal" data-bs-target="#modal-status" data-id="' . $row->id . '" title="change status"><i class="fa-solid fa-sliders"></i></button>';
                    return $btn . $btn1 . $btn3;
                })
                ->rawColumns(['name', 'role', 'action'])
                ->make(true);
        }

        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $departments = Department::where('status', 'Y')->get();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        return view('staff.staff_list.index', compact('countries', 'states', 'cities', 'departments', 'clinicBranches'));
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
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        return view('staff.staff_list.add', compact('countries', 'states', 'cities', 'userTypes', 'departments', 'clinicBranches'));

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
            $user->name = $request->title . "<br> " . $request->firstname . "<br>" . $request->lastname;
            $user->email = $request->email;

            // Set user role based on request
            foreach ($request->role as $role) {
                switch ($role) {
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
            }

            // Set default password
            $password = Str::random(10);
            $user->password = Hash::make($password);
            $user->save();

            // Create staff profile
            $staffProfile = new StaffProfile();
            $staffProfile->user_id = $user->id;
            $staffProfile->staff_id = "MEDWEB" . $user->id;
            $staffProfile->clinic_branch_id = $request->clinic_branch_id;
            $staffProfile->fill($request->only([
                'aadhaar_no',
                'date_of_birth',
                'phone',
                'gender',
                'address1',
                'address2',
                'city_id',
                'state_id',
                'country_id',
                'pincode',
                'date_of_joining',
                'qualification',
                'department_id',
                'specialization',
                'years_of_experience',
                'license_number',
                'subspecialty',
                'designation'
            ]));
            if ($request->add_checkbox == "on") {
                $staffProfile->com_address1 = $request->address1;
                $staffProfile->com_address2 = $request->address2;
                $staffProfile->com_city_id = $request->city_id;
                $staffProfile->com_state_id = $request->state_id;
                $staffProfile->com_country_id = $request->country_id;
                $staffProfile->com_pincode = $request->pincode;
            } else {
                $staffProfile->com_address1 = $request->com_address1;
                $staffProfile->com_address2 = $request->com_address1;
                $staffProfile->com_city_id = $request->com_address1;
                $staffProfile->com_state_id = $request->com_address1;
                $staffProfile->com_country_id = $request->com_address1;
                $staffProfile->com_pincode = $request->com_address1;

            }

            // Save profile photo if provided
            if ($request->hasFile('profile_photo')) {
                $profilePath = $request->file('profile_photo')->store('profile-photos', 'public');
                $staffProfile->photo = $profilePath;
            }
            $staffProfile->status = "Y";

            $staffProfile->save();

            // If user is a doctor, save availability
            if ($user->is_doctor) {
                $this->saveDoctorAvailability($request, $user->id);
            }

            //Assign role to the user
            if ($user->is_admin) {
                $role = Role::findById(User::IS_ADMIN);
                $user->assignRole($role);
            }
            if ($user->is_doctor) {
                $role = Role::findById(User::IS_DOCTOR);
                $user->assignRole($role);
            }
            if ($user->is_nurse) {
                $role = Role::findById(User::IS_NURSE);
                $user->assignRole($role);
            }
            if ($user->is_reception) {
                $role = Role::findById(User::IS_RECEPTION);
                $user->assignRole($role);
            }

            DB::commit();

            // Send welcome email (you can implement this part)

            return redirect()->route('staff.staff_list')->with('success', 'Staff created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create staff: ' . $e->getMessage());
        }
    }


    private function saveDoctorAvailability(Request $request, $userId)
    {
        $weekDays = [
            WeekDay::MONDAY,
            WeekDay::TUESDAY,
            WeekDay::WEDNESDAY,
            WeekDay::THURSDAY,
            WeekDay::FRIDAY,
            WeekDay::SATURDAY,
            WeekDay::SUNDAY
        ];

        // Get the actual number of rows (count of clinic_branch_id inputs)
        $count = $request->input('row_count', 0);

        for ($i = 0; $i <= $count; $i++) {
            foreach ($weekDays as $day) {
                // Construct the keys dynamically
                $fromKey = strtolower($day) . '_from' . $i;
                $toKey = strtolower($day) . '_to' . $i;
                $clinicBranchKey = 'clinic_branch_id' . $i;

                // Check if fromKey is present in request, if not continue to next iteration
                if (!$request->has($fromKey)) {
                    continue;
                }

                // Extract values from request
                $clinic_branch_id = $request->input($clinicBranchKey);
                $from_time = $request->input($fromKey);
                $to_time = $request->input($toKey);
                if ($from_time != null) {
                    // Create and save DoctorWorkingHour instance
                    $availability = new DoctorWorkingHour();
                    $availability->user_id = $userId;
                    $availability->week_day = $day;
                    $availability->clinic_branch_id = $clinic_branch_id;
                    $availability->from_time = $from_time;
                    $availability->to_time = $to_time;
                    $availability->status = 'Y';
                    $availability->save();
                }
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
        $staffProfile = StaffProfile::find($id);
        $userDetails = User::find($staffProfile->user_id);
        $availability = null;
        if ($userDetails->is_doctor == 1) {
            $availability = DoctorWorkingHour::where('user_id', $staffProfile->user_id)
                ->where('status', 'Y')
                ->get();
        }
        if (!$staffProfile) {
            abort(404);
        }
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $departments = Department::where('status', 'Y')->get();
        $userTypes = UserType::where('status', 'Y')->get();
        $commonService = new CommonService();
        $name = $commonService->splitNames($userDetails->name);
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        $availability = DoctorWorkingHour::where('user_id', $staffProfile->user_id)->get();
        $availabilityCount = DoctorWorkingHour::where('user_id', $staffProfile->user_id)->groupBy('clinic_branch_id')->count();
        $doctorAvailability = new DoctorAvaialbilityService();
        $availableBranches = $doctorAvailability->availableBranchAndTimings($staffProfile->user_id);
        return view('staff.staff_list.edit', compact('name', 'countries', 'states', 'cities', 'userTypes', 'departments', 'staffProfile', 'userDetails', 'availability', 'clinicBranches','availabilityCount', 'availability', 'availableBranches'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

    }
}
