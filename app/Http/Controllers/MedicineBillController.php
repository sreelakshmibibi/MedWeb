<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicBasicDetail;
use App\Models\ClinicBranch;
use App\Models\Prescription;
use App\Models\TreatmentComboOffer;
use App\Services\BillingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MedicineBillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($appointmentId)
    {
       // print_r($request->all());exit;
       $id = base64_decode(Crypt::decrypt($appointmentId));
       $appointment = Appointment::with(['patient', 'doctor', 'branch'])
                       ->find($id);
       $billingService = new BillingService();
       $clinicBasicDetails = ClinicBasicDetail::first();
       $isMedicineProvided = (ClinicBranch::find($appointment->app_branch))->is_medicine_provided;
       $prescriptions = Prescription::with(['medicine', 'dosage'])
                            ->where('app_id', $appointment->id)
                           ->where('patient_id', $appointment->patient_id)
                           ->where('status', 'Y')
                           ->get();
       $medicineTotal = 0;
       // Pass variables to the view
       return view('billing.medicine', compact('appointment', 'clinicBasicDetails', 'isMedicineProvided', 'prescriptions', 'medicineTotal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
