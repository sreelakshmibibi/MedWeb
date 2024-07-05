<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\AppointmentRequest;
use App\Models\City;
use App\Models\ClinicBranch;
use App\Models\Country;
use App\Models\Department;
use App\Models\DoctorWorkingHour;
use App\Models\Appointment;
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

class AppointmentController extends Controller
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
            // Retrieve selected date from the request
            $selectedDate = $request->input('selectedDate');

            // Example: Fetch data from your model based on selected date
            $appointments = Appointment::whereDate('app_date', $selectedDate)
                            ->with('patient')
                            ->get();
            // $appointments = Appointment::orderBy('token', 'asc')->get();

            return DataTables::of($appointments)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return str_replace("<br>", " ", $row->patient->first_name." ".$row->patient->last_name);
                })
                ->addColumn('phone', function ($row) {
                    return $row->patient->phone;
                })
                ->addColumn('action', function ($row) {

                    $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="new booking" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-booking" ><i class="fa fa-plus"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-edit btn-xs me-1" title="reschedule" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-edit" ><i class="fa-solid fa-calendar-days"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="cancel">
                        <i class="fa fa-times"></i></button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $departments = Department::where('status', 'Y')->get();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();

        return view('appointment.index', compact('countries', 'states', 'cities', 'departments', 'clinicBranches'));
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

        return view('appointment.add', compact('countries', 'states', 'cities', 'userTypes', 'departments', 'clinicBranches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // try {
        //     DB::beginTransaction();
        //     if (isset($request->edit_user_id) && $request->edit_user_id != null) {
        //         $user = User::find($request->edit_user_id);
        //         $Appointment = Appointment::where('user_id', $request->edit_user_id)->first();
        //     } else {
        //         $user = new User();
        //         $password = Str::random(10);
        //         $user->password = Hash::make($password);
        //         $Appointment = new Appointment();
        //     }

        //     $user->name = $request->title . "<br> " . $request->firstname . "<br>" . $request->lastname;
        //     $user->email = $request->email;
        //     $roles = [
        //         User::IS_ADMIN => false,
        //         User::IS_DOCTOR => false,
        //         User::IS_NURSE => false,
        //         User::IS_RECEPTION => false,
        //     ];

        //     // Update roles based on request input
        //     foreach ($request->role as $role) {
        //         switch ($role) {
        //             case User::IS_ADMIN:
        //                 $roles[User::IS_ADMIN] = true;
        //                 break;
        //             case User::IS_DOCTOR:
        //                 $roles[User::IS_DOCTOR] = true;
        //                 break;
        //             case User::IS_NURSE:
        //                 $roles[User::IS_NURSE] = true;
        //                 break;
        //             case User::IS_RECEPTION:
        //                 $roles[User::IS_RECEPTION] = true;
        //                 break;
        //             default:
        //                 throw new \Exception('Invalid role specified.');
        //         }
        //     }

        //     // Set user roles
        //     $user->is_admin = $roles[User::IS_ADMIN];
        //     $user->is_doctor = $roles[User::IS_DOCTOR];
        //     $user->is_nurse = $roles[User::IS_NURSE];
        //     $user->is_reception = $roles[User::IS_RECEPTION];

        //     // Save the user
        //     if ($user->save()) {
        //         // Assign roles using Spatie/Permission package (if used)
        //         $user->syncRoles(array_keys(array_filter($roles)));



        //         $Appointment->user_id = $user->id;
        //         $Appointment->staff_id = "MEDWEB" . $user->id;
        //         $Appointment->clinic_branch_id = $request->clinic_branch_id;
        //         $Appointment->fill($request->only([
        //             'aadhaar_no',
        //             'date_of_birth',
        //             'phone',
        //             'gender',
        //             'address1',
        //             'address2',
        //             'city_id',
        //             'state_id',
        //             'country_id',
        //             'pincode',
        //             'date_of_joining',
        //             'qualification',
        //             'department_id',
        //             'specialization',
        //             'years_of_experience',
        //             'license_number',
        //             'subspecialty',
        //             'designation'
        //         ]));

        //         if ($request->add_checkbox == "on") {
        //             $Appointment->com_address1 = $request->address1;
        //             $Appointment->com_address2 = $request->address2;
        //             $Appointment->com_city_id = $request->city_id;
        //             $Appointment->com_state_id = $request->state_id;
        //             $Appointment->com_country_id = $request->country_id;
        //             $Appointment->com_pincode = $request->pincode;
        //         } else {
        //             $Appointment->com_address1 = $request->com_address1;
        //             $Appointment->com_address2 = $request->com_address1;
        //             $Appointment->com_city_id = $request->com_city_id;
        //             $Appointment->com_state_id = $request->com_state_id;
        //             $Appointment->com_country_id = $request->com_country_id;
        //             $Appointment->com_pincode = $request->com_pincode;
        //         }

        //         if ($request->hasFile('profile_photo')) {
        //             $profilePath = $request->file('profile_photo')->store('profile-photos', 'public');
        //             $Appointment->photo = $profilePath;
        //         }
        //         $Appointment->status = "Y";

        //         if ($Appointment->save()) {
        //             if ($user->is_doctor) {
        //                 $staffService = new StaffService();
        //                 if ($staffService->saveDoctorAvailability($request, $user->id)) {
        //                     DB::commit();
        //                 } else {
        //                     DB::rollBack();
        //                 }
        //             } else {
        //                 DB::commit();
        //             }
        //         } else {
        //             DB::rollBack();
        //         }
        //     } else {
        //         DB::rollBack();
        //     }

        //     //example user
        //     $user = User::find('19');

        //     $token = $request->route()->parameter('token');

        //     $user->token = $token;

        //     // Send welcome notification
        //     $user->notify(new WelcomeVerifyNotification($user->name, $user->email, $user->password, $user->$token));

        //     return redirect()->route('staff.staff_list')->with('success', 'Staff created successfully');
        // } catch (\Exception $e) {
        //     // echo "<pre>";
        //     // print_r($e->getMessage());

        //     DB::rollback();
        //     // exit;
        //     return redirect()->back()->with('error', 'Failed to create staff: ' . $e->getMessage());
        // }
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $Appointment = Appointment::with('user')->find($id);
        abort_if(!$Appointment, 404);

        $userDetails = $Appointment->user;
        $departments = Department::where('status', 'Y')->get();
        $userTypes = UserType::where('status', 'Y')->get();
        $commonService = new CommonService();
        $name = $commonService->splitNames($userDetails->name);
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        $availability = DoctorWorkingHour::where('user_id', $Appointment->user_id)->get();
        $availabilityCount = DoctorWorkingHour::where('user_id', $Appointment->user_id)->groupBy('clinic_branch_id')->count();
        $doctorAvailability = new DoctorAvaialbilityService();
        $availableBranches = $doctorAvailability->availableBranchAndTimings($Appointment->user_id);
        $countries = Country::all();
        return view('appointment.edit', compact('name', 'countries', 'userTypes', 'departments', 'Appointment', 'userDetails', 'availability', 'clinicBranches', 'availabilityCount', 'availability', 'availableBranches'));
    }



    public function destroy($id)
    {
        // $Appointment = Appointment::findOrFail($id);
        // $Appointment->delete();

        // return response()->json(['success', 'Staff deleted successfully.'], 201);
    }

    public function view(string $id)
    {
        // $Appointment = Appointment::with('user')->find($id);
        // abort_if(!$Appointment, 404);

        // $userDetails = $Appointment->user;
        // $departments = Department::where('status', 'Y')->get();
        // $userTypes = UserType::where('status', 'Y')->get();
        // $commonService = new CommonService();
        // $name = $commonService->splitNames($userDetails->name);
        // $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        // $availability = DoctorWorkingHour::where('user_id', $Appointment->user_id)->get();
        // $availabilityCount = DoctorWorkingHour::where('user_id', $Appointment->user_id)->groupBy('clinic_branch_id')->count();
        // $doctorAvailability = new DoctorAvaialbilityService();
        // $availableBranches = $doctorAvailability->availableBranchAndTimings($Appointment->user_id);
        // $countries = Country::all();
        return view('appointment.view', compact('name', 'countries', 'userTypes', 'departments', 'Appointment', 'userDetails', 'availability', 'clinicBranches', 'availabilityCount', 'availability', 'availableBranches'));
    }


}

