<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ClinicBasicDetailRequest;
use App\Http\Requests\Settings\ClinicBranchRequest;
use App\Models\City;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClinicBranchController extends Controller
{
    /**
     * Display a listing of the resource.m
     */
    public function index(Request $request)
    {
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        if ($request->ajax()) {

            $clinics = ClinicBranch::with(['country', 'state', 'city']);

            return DataTables::of($clinics)
                ->addIndexColumn()
                ->addColumn('clinic_address', function ($row) {
                   $clinicAddress = explode("<br>", $row->clinic_address);
                    $clinicAddress = implode(", ",$clinicAddress) . ', ' .
                        $row->city->city . ', ' .
                        $row->state->state . ', ' .
                        $row->country->country . ', ' .
                        "Pincode - " . $row->pincode;
        
                    return $clinicAddress;
                })
                ->addColumn('action', function ($row) {
                    
                    $btn = '<div class="d-flex">
                    <button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="'.$row->id.'"
                        data-bs-target="#modal-edit-clinic" ><i class="fa fa-pencil"></i></button>
                      ';
                       if ($row->clinic_status == 'Y') {
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete-clinic" data-id="'.$row->id.'" data-status="'.$row->clinic_status.'"  title="Make inactive">
                        <i class="fa fa-trash"></i></button> </div>';
                    } else {
                        $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete-clinic" data-id="'.$row->id.'" data-status="'.$row->clinic_status.'" title="Make active">
                        <i class="fa fa-trash"></i></button> </div>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('settings.clinics.clinic_form', compact('countries', 'states', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ClinicBasicDetailRequest $request)
    {
        try {
            $clinic_name = $request->input('clinic_name');
            $clinic_website = $request->input('clinic_website');
            $clinic_logo = null; // Initialize clinic_logo variable

            // Check if a file for clinic_logo was uploaded
            if ($request->hasFile('clinic_logo')) {
                $logoPath = $request->file('clinic_logo')->store('clinic-logos', 'public');
                $clinic_logo = $logoPath;
            }

            // Find the existing ClinicBasicDetail record by clinic_name
            $clinic = ClinicBasicDetail::first();

            if ($clinic) {
                // Update existing record
                $clinic->clinic_website = $clinic_website;
                $clinic->clinic_name = $clinic_name;

                // Only update clinic_logo if a new file was uploaded
                if ($clinic_logo !== null && $clinic_logo !== $clinic->clinic_logo) {
                    $clinic->clinic_logo = $clinic_logo;
                }

                $clinic->save();
            } else {
                // Create new record
                $clinic = ClinicBasicDetail::create([
                    'clinic_name' => $clinic_name,
                    'clinic_website' => $clinic_website,
                    'clinic_logo' => $clinic_logo,
                ]);
            }

            // Optionally, you can return a response or redirect as per your application flow
            return response()->json(['message' => 'Clinic details updated or created successfully']);

        } catch (\Exception $e) {
            // Log the exception or handle it in a way appropriate for your application
            // For example:
            // Log::error('Error creating clinic: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create or update clinic details'], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ClinicBranchRequest $request)
    {
        try {
            $clinic = new ClinicBranch();
            $clinic->clinic_email = $request->input('clinic_email');
            $clinic->clinic_phone = $request->input('clinic_phone');
            $clinic->is_main_branch = $request->input('branch_active');
            $clinic->clinic_address = $request->input('clinic_address1')."<br>". $request->input('clinic_address2');
            $clinic->country_id = $request->input('clinic_country');
            $clinic->state_id = $request->input('clinic_state');
            $clinic->city_id = $request->input('clinic_city');
            $clinic->pincode = $request->input('clinic_pincode');
            $clinic->clinic_status = "Y";
            $clinic->clinic_type_id = 1;
            // Handle clinic logo if uploaded
            if ($request->hasFile('clinic_logo')) {
                $logoPath = $request->file('clinic_logo')->store('clinic-logos', 'public');
                $clinic->clinic_logo = $logoPath;
            }

            // Save the clinic
            $i = $clinic->save();
           
            if ($i) {
                return redirect()->route('clinic.index')->with('success', 'Clinic created successfully');
            }
    
        } catch (\Exception $e) {
           return redirect()->back()->with('error', 'Failed to create clinic: ' . $e->getMessage());
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
        $clinicBranch = ClinicBranch::find($id);
        if (!$clinicBranch) {
            abort(404);
        }
        return $clinicBranch;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $clinic = ClinicBranch::findOrFail($request->edit_clinic_id);
            
            // Update clinic fields based on form data
            $clinic->clinic_email = $request->clinic_email;
            $clinic->clinic_phone = $request->clinic_phone;
            $clinic->is_main_branch = $request->edit_branch_active;
            $clinic->clinic_address = $request->clinic_address1."<br>". $request->clinic_address2;
            $clinic->country_id = $request->clinic_country;
            $clinic->state_id = $request->clinic_state;
            $clinic->city_id = $request->clinic_city;
            $clinic->pincode = $request->clinic_pincode;
            $clinic->clinic_type_id = 1;
            // Save the updated clinic
            $i = $clinic->save();
           if ($i) {
                return redirect()->route('clinic.index')->with('success', 'Clinic updated successfully');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update clinic. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function statusChange(string $id, string $status)
    {
        /*Make status inactive*/
        $clinicBranch = ClinicBranch::findOrFail($id);
        $statusChange = $status == 'Y' ? 'N' : 'Y';
        $statusText = $status == 'Y' ? 'Clinic deactivated successfully' : 'Clinic activated successfully';
        $clinicBranch->clinic_status = $statusChange;
        $clinicBranch->save();

        return redirect()->route('settings.clinic')->with('success', $statusText);

    }
    
}
