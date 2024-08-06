<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffProfileRequest;
use App\Models\Appointment;
use App\Models\City;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\Country;
use App\Models\Department;
use App\Models\DoctorWorkingHour;
use App\Models\StaffProfile;
use App\Models\State;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserVerify;
use App\Notifications\WelcomeVerifyNotification;
use App\Services\StaffService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use App\Services\CommonService;
use App\Services\DoctorAvaialbilityService;
use Illuminate\Support\Facades\Crypt;

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
                ->addColumn('role', function ($row) {
                    $role = '';
                    if ($row->user->is_doctor) {
                        $role .= '<span class="d-block  badge badge-success-light mb-1">Doctor</span>';
                    }
                    if ($row->user->is_nurse) {
                        $role .= '<span class="d-block  badge badge-warning-light mb-1">Nurse</span>';
                    }
                    if ($row->user->is_admin) {
                        $role .= '<span class="d-block  badge badge-primary-light mb-1">Admin</span>';
                    }
                    if ($row->user->is_reception) {
                        $role .= '<span class="d-block  badge badge-info-light mb-1">Others</span>';
                    }
                    return $role;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'Y') {
                        $btn1 = '<span class="text-success" title="active"><i class="fa-solid fa-circle-check"></i></span>';
                    } else {
                        $btn1 = '<span class="text-danger" title="inactive"><i class="fa-solid fa-circle-xmark"></i></span>';
                    }
                    return $btn1;
                })

                ->addColumn('action', function ($row) {
                    $base64Id = base64_encode($row->id);
                    $idEncrypted = Crypt::encrypt($base64Id);
                    $btn = '<a href="' . route('staff.staff_list.view', $idEncrypted) . '" class="waves-effect waves-light btn btn-circle btn-info btn-xs me-1" title="view"><i class="fa fa-eye"></i></a>';
                    $btn .= '<a href="' . route('staff.staff_list.edit', $idEncrypted) . '" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit"><i class="fa fa-pencil"></i></a>';
                    $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-xs me-1" data-bs-toggle="modal" data-bs-target="#modal-status" data-id="' . $row->id . '" title="change status"><i class="fa-solid fa-sliders"></i></button>';
                    $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete"><i class="fa-solid fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['name', 'role', 'status', 'action'])
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
        $clinic = ClinicBasicDetail::first();
        $consultationFees = $clinic->consultation_fees;
        return view('staff.staff_list.add', compact('countries', 'states', 'cities', 'userTypes', 'departments', 'clinicBranches', 'consultationFees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StaffProfileRequest $request)
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
            $staffName = $request->title . "" . ucwords(strtolower($request->firstname)) . " " . ucwords(strtolower($request->lastname));
            $user->name = $request->title . "<br> " . ucwords(strtolower($request->firstname)) . "<br>" . ucwords(strtolower($request->lastname));
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
                    'years_of_experience',
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
                if ($user->is_doctor) {
                    $staffProfile->specialization = $request->specialization;
                    $staffProfile->subspecialty = $request->subspecialty;
                    $staffProfile->license_number = $request->license_number;
                    $staffProfile->consultation_fees = $request->consultation_fees;
                } else if ($user->is_nurse) {
                    $staffProfile->license_number = $request->license_number_nurse;
                } else {
                    $staffProfile->clinic_branch_id = $request->clinic_branch_id;
                }
                $staffProfile->status = "Y";
                if ($staffProfile->save()) {
                    if ($user->is_doctor) {
                        $staffService = new StaffService();
                        if ($staffService->saveDoctorAvailability($request, $user->id)) {
                            DB::commit();
                            if (!isset($request->edit_user_id)) {
                                $token = Str::random(64);
                                UserVerify::create(['user_id' => $user->id, 'token' => $token]);
                                $welcomeNotification = new WelcomeVerifyNotification($staffName, $request->email, $password, $token);
                                $user->notify($welcomeNotification);
                            }
                        } else {
                            DB::rollBack();
                            return response()->json(['error' => 'Failed to create doctor: Availbilty of time slots required'], 422);
                        }
                    } else {
                        DB::commit();
                        if (!isset($request->edit_user_id)) {
                            $token = Str::random(64);
                            UserVerify::create(['user_id' => $user->id, 'token' => $token]);
                            $welcomeNotification = new WelcomeVerifyNotification($staffName, $request->email, $password, $token);
                            $user->notify($welcomeNotification);
                        }
                    }
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'Failed to create staff: Error in experience'], 422);
                }
            } else {
                DB::rollBack();
                return response()->json(['error' => 'Failed to create staff.'], 422);
            }

            return redirect()->route('staff.staff_list')->with('success', 'Staff created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to create staff: ' . $e->getMessage()], 422);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = base64_decode(Crypt::decrypt($id));
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

    public function view(string $id, Request $request)
    {
        $id = base64_decode(Crypt::decrypt($id));
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
        $states = State::all();
        $cities = City::all();

        $totalUniquePatients = 0;
        $malePatientsCount = 0;
        $femalePatientsCount = 0;

        if ($request->ajax()) {
            $staffId = $request->input('userId');
            $appointments = Appointment::where('doctor_id', $staffId)
                ->with(['patient', 'doctor', 'branch'])
                ->get();

            return DataTables::of($appointments)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return str_replace("<br>", " ", ucwords(strtolower($row->patient->first_name)) . " " . ucwords(strtolower($row->patient->last_name)));
                })
                ->addColumn('branch', function ($row) {
                    if (!$row->branch) {
                        return '';
                    }
                    $address = implode(", ", explode("<br>", $row->branch->clinic_address));
                    return implode(", ", [$address, $row->branch->city->city, $row->branch->state->state]);
                })
                ->addColumn('phone', function ($row) {
                    return $row->patient->phone;
                })
                ->addColumn('action', function ($row) {
                    $button = "<button type='button' class='waves-effect waves-light btn btn-circle btn-info btn-xs' title='view'><i class='fa fa-eye'></i></button>";
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $appointments = Appointment::where('doctor_id', $staffProfile->user_id)
            ->with(['patient', 'doctor', 'branch'])
            ->get();

        // Extract the patients from the appointments
        $patients = $appointments->pluck('patient')->unique('id');

        // Count the total number of unique patients
        $totalUniquePatients = $patients->count();

        // Count the number of male and female patients
        $malePatientsCount = $patients->where('gender', 'M')->count();
        $femalePatientsCount = $patients->where('gender', 'F')->count();

        return view(
            'staff.staff_list.view',
            compact(
                'name',
                'countries',
                'states',
                'cities',
                'userTypes',
                'departments',
                'staffProfile',
                'userDetails',
                'availability',
                'clinicBranches',
                'availabilityCount',
                'availability',
                'availableBranches',
                'totalUniquePatients',
                'malePatientsCount',
                'femalePatientsCount'
            )
        );
    }
}