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
use App\Services\StaffService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Services\CommonService;
use App\Services\DoctorAvaialbilityService;

use App\Notifications\WelcomeVerifyNotification;

class StaffListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $successMessage = $request->query('success_message');

        if ($successMessage) {
            session()->flash('success', $successMessage);
        }

        if ($request->ajax()) {
            $staff = StaffProfile::with('user')->get();

            return DataTables::of($staff)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return str_replace("<br>", " ", $row->user->name);
                })
                // ->addColumn('email', function ($row) {
                //     return $row->user->email;
                // })
                ->addColumn('role', function ($row) {
                    $role = '';
                    if ($row->user->is_doctor) {
                        $role .= '<span class="btn-sm badge badge-success-light">Doctor</span>';
                    }
                    if ($row->user->is_nurse) {
                        $role .= '<span class="btn-sm badge badge-warning-light">Nurse</span>';
                    }
                    if ($row->user->is_admin) {
                        $role .= '<span class="btn-sm badge badge-primary-light">Admin</span>';
                    }
                    if ($row->user->is_reception) {
                        $role .= '<span class="btn-sm badge badge-info-light">Others</span>';
                    }
                    return $role;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('staff.staff_list.view', $row->id) . '" class="waves-effect waves-light btn btn-circle btn-info btn-xs me-1" title="view"><i class="fa fa-eye"></i></a>';
                    $btn .= '<a href="' . route('staff.staff_list.edit', $row->id) . '" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit"><i class="fa fa-pencil"></i></a>';
                    $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-xs" data-bs-toggle="modal" data-bs-target="#modal-status" data-id="' . $row->id . '" title="change status"><i class="fa-solid fa-sliders"></i></button>';
                    $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="change status"><i class="fa-solid fa-trash"></i></button>';
                    return $btn;
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
            if (isset($request->edit_user_id) && $request->edit_user_id != null) {
                $user = User::find($request->edit_user_id);
                $staffProfile = StaffProfile::where('user_id', $request->edit_user_id)->first();
            } else {
                $user = new User();
                $password = Str::random(10);
                $user->password = Hash::make($password);
                $staffProfile = new StaffProfile();
            }

            $user->name = $request->title . "<br> " . $request->firstname . "<br>" . $request->lastname;
            $user->email = $request->email;
            $roles = [
                User::IS_ADMIN => false,
                User::IS_DOCTOR => false,
                User::IS_NURSE => false,
                User::IS_RECEPTION => false,
            ];

            // Update roles based on request input
            foreach ($request->role as $role) {
                switch ($role) {
                    case User::IS_ADMIN:
                        $roles[User::IS_ADMIN] = true;
                        break;
                    case User::IS_DOCTOR:
                        $roles[User::IS_DOCTOR] = true;
                        break;
                    case User::IS_NURSE:
                        $roles[User::IS_NURSE] = true;
                        break;
                    case User::IS_RECEPTION:
                        $roles[User::IS_RECEPTION] = true;
                        break;
                    default:
                        throw new \Exception('Invalid role specified.');
                }
            }

            // Set user roles
            $user->is_admin = $roles[User::IS_ADMIN];
            $user->is_doctor = $roles[User::IS_DOCTOR];
            $user->is_nurse = $roles[User::IS_NURSE];
            $user->is_reception = $roles[User::IS_RECEPTION];

            // Save the user
            if ($user->save()) {
                // Assign roles using Spatie/Permission package (if used)
                $user->syncRoles(array_keys(array_filter($roles)));



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
                    $staffProfile->com_city_id = $request->com_city_id;
                    $staffProfile->com_state_id = $request->com_state_id;
                    $staffProfile->com_country_id = $request->com_country_id;
                    $staffProfile->com_pincode = $request->com_pincode;
                }

                if ($request->hasFile('profile_photo')) {
                    $profilePath = $request->file('profile_photo')->store('profile-photos', 'public');
                    $staffProfile->photo = $profilePath;
                }
                $staffProfile->status = "Y";

                if ($staffProfile->save()) {
                    if ($user->is_doctor) {
                        $staffService = new StaffService();
                        if ($staffService->saveDoctorAvailability($request, $user->id)) {
                            DB::commit();
                        } else {
                            DB::rollBack();
                        }
                    } else {
                        DB::commit();
                    }
                } else {
                    DB::rollBack();
                }
            } else {
                DB::rollBack();
            }

            //example user
            $user = User::find('19');

            $token = $request->route()->parameter('token');

            $user->token = $token;

            // Send welcome notification
            $user->notify(new WelcomeVerifyNotification($user->name, $user->email, $user->password, $user->$token));

            return redirect()->route('staff.staff_list')->with('success', 'Staff created successfully');
        } catch (\Exception $e) {
            // echo "<pre>";
            // print_r($e->getMessage());

            DB::rollback();
            // exit;
            return redirect()->back()->with('error', 'Failed to create staff: ' . $e->getMessage());
        }
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staffProfile = StaffProfile::with('user')->find($id);
        abort_if(!$staffProfile, 404);

        $userDetails = $staffProfile->user;
        $departments = Department::where('status', 'Y')->get();
        $userTypes = UserType::where('status', 'Y')->get();
        $commonService = new CommonService();
        $name = $commonService->splitNames($userDetails->name);
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        $availability = DoctorWorkingHour::where('user_id', $staffProfile->user_id)->get();
        $availabilityCount = DoctorWorkingHour::where('user_id', $staffProfile->user_id)->groupBy('clinic_branch_id')->count();
        $doctorAvailability = new DoctorAvaialbilityService();
        $availableBranches = $doctorAvailability->availableBranchAndTimings($staffProfile->user_id);
        $countries = Country::all();
        return view('staff.staff_list.edit', compact('name', 'countries', 'userTypes', 'departments', 'staffProfile', 'userDetails', 'availability', 'clinicBranches', 'availabilityCount', 'availability', 'availableBranches'));
    }

    public function changeStatus(string $id)
    {
        $staffProfile = StaffProfile::with('user')->find($id);
        abort_if(!$staffProfile, 404);
        if ($staffProfile) {
            $active = 'N';
            $inActive = 'Y';
            if ($staffProfile->status == $active) {
                $staffProfile->status = $inActive;
            } else {
                $staffProfile->status = $active;
            }
            $staffProfile->save();
            return redirect()->route('staff.staff_list')->with('success', 'Status updated successfully');
        }

    }

    public function destroy($id)
    {
        $staffProfile = StaffProfile::findOrFail($id);
        $staffProfile->delete();

        return response()->json(['success', 'Staff deleted successfully.'], 201);
    }

    public function view(string $id)
    {
        $staffProfile = StaffProfile::with('user')->find($id);
        abort_if(!$staffProfile, 404);

        $userDetails = $staffProfile->user;
        $departments = Department::where('status', 'Y')->get();
        $userTypes = UserType::where('status', 'Y')->get();
        $commonService = new CommonService();
        $name = $commonService->splitNames($userDetails->name);
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        $availability = DoctorWorkingHour::where('user_id', $staffProfile->user_id)->get();
        $availabilityCount = DoctorWorkingHour::where('user_id', $staffProfile->user_id)->groupBy('clinic_branch_id')->count();
        $doctorAvailability = new DoctorAvaialbilityService();
        $availableBranches = $doctorAvailability->availableBranchAndTimings($staffProfile->user_id);
        $countries = Country::all();
        return view('staff.staff_list.view', compact('name', 'countries', 'userTypes', 'departments', 'staffProfile', 'userDetails', 'availability', 'clinicBranches', 'availabilityCount', 'availability', 'availableBranches'));
    }


}

