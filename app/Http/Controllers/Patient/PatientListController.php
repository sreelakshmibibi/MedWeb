<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\PatientListRequest;
use App\Models\PatientProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\ClinicBranch;
use App\Models\Country;
use App\Models\Department;
use App\Models\State;
use App\Models\UserType;


use Yajra\DataTables\DataTables as DataTables;

class PatientListController extends Controller
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

                    $btn1 = '<button type="button" class="waves-effect waves-light btn btn-circle btn-info btn-edit btn-xs me-1" title="view" data-id="' . $row->id . '"
                             ><i class="fa fa-eyes"></i></button><button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                            data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                            <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                            <i class="fa fa-trash"></i></button>';

                    return $btn1;
                })
                ->rawColumns(['status', 'action'])
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
        $userTypes = UserType::where('status', 'Y')->get();
        $departments = Department::where('status', 'Y')->get();
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])->where('clinic_status', 'Y')->get();
        return view('patient.patient_list.add', compact('countries', 'states', 'cities', 'userTypes', 'departments', 'clinicBranches'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientListRequest $request)
    {
        try {
            // Create a new department instance
            $patient = new PatientProfile();
            $patient->patient = $request->input('patient');
            $patient->status = $request->input('status');
            $patient->clinic_type_id = 1;

            // Save the department
            $patient->save();

            return redirect()->back()->with('success', 'Patient created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create patient: ' . $e->getMessage());
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
