<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\InsuranceCompanyRequest;
use App\Models\InsuranceCompany;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InsuranceController extends Controller
{
    protected $commonService;

    // public function __construct(CommonService $commonService)
    // {
    //     $this->commonService = $commonService;
    // }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $insurances = InsuranceCompany::orderBy('company_name', 'asc')->get();

            return DataTables::of($insurances)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->status == 'Y') {
                        $btn1 = '<span class="text-success" title="active"><i class="fa-solid fa-circle-check"></i></span>';
                    } else {
                        $btn1 = '<span class="text-danger" title="inactive"><i class="fa-solid fa-circle-xmark"></i></span>';
                    }
                    return $btn1;
                })
                ->addColumn('action', function ($row) {

                    $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        // Return the view with menu items
        return view('settings.insurance.index');
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
    public function store(InsuranceCompanyRequest $request)
    {
        
        try {
            // Create a new department instance
            $insurance = new InsuranceCompany();
            // $department->department = $request->input('department');
            // Capitalize each word in the department name before assigning it
            $insurance->company_name = ucwords(strtolower($request->input('company_name')));
            $insurance->claim_type = ucwords(strtolower($request->input('claim_type')));
            $insurance->status = $request->input('status');
            
            // Save the department
            $i = $insurance->save();
            if ($i) {
                return redirect()->back()->with('success', 'Insurance created successfully');
            }
        } catch (\Exception $e) {
            echo "<pre>"; print_r($e->getMessage());echo "</pre>";
            exit;
            return redirect()->back()->with('error', 'Failed to add insurance company : ' . $e->getMessage());
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
        $insurance = InsuranceCompany::find($id);
        if (!$insurance) {
            abort(404);
        }

        return $insurance;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $insurance = InsuranceCompany::findOrFail($request->edit_insurance_id);

            // Update department fields based on form data
            $insurance->company_name = $request->company_name;
            $insurance->status = $request->status;
            $insurance->claim_type = $request->claim_type;
            // Save the updated department
            $insurance->save();

            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['success' => 'Insurance company details updated successfully.']);
            }

            // Redirect back with success message for non-AJAX request
            return redirect()->back()->with('success', 'Insurance company details updated successfully.');
        } catch (\Exception $e) {
            // Handle any unexpected errors
            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to update insurance. Please try again.'], 500);
            }

            // Redirect back with error message for non-AJAX request
            return redirect()->back()->with('error', 'Failed to update insurance. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $insurance = InsuranceCompany::findOrFail($id);
        $insurance->delete();

        return response()->json(['success', 'Insurance deleted successfully.'], 201);
    }
}
