<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\ClinicBasicDetail;
use App\Models\StaffProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsController extends Controller
{
    public function sendSms(Request $request)
    {
        try {
            $date = $request->selectedDate;
            $appointments = Appointment::with(['patient', 'branch'])
                ->where('app_status', AppointmentStatus::SCHEDULED)
                ->where('app_date', $date);
            
            // if (!Auth::user()->is_doctor && !Auth::user()->is_admin) {
            //     $clinicBranchId = StaffProfile::where('user_id', Auth::user()->id)
            //         ->pluck('clinic_branch_id')
            //         ->first();
            //     $appointments = $appointments->where('app_branch', $clinicBranchId);
            // }

            if (!Auth::user()->is_doctor && !Auth::user()->is_admin) {
                // Fetch the clinic_branch_id, which might be a comma-separated string
                $clinicBranchIds = StaffProfile::where('user_id', Auth::user()->id)
                    ->pluck('clinic_branch_id')
                    ->first();
            
                // Convert the string to an array if it exists
                $clinicBranchIdsArray = $clinicBranchIds ? explode(',', $clinicBranchIds) : [];
            
                // Filter appointments based on the clinic branches
                $appointments = $appointments->whereIn('app_branch', $clinicBranchIdsArray);
            }
            
            
            $appointments = $appointments->get();
            $patientContacts = [];
            $clinicDetails = ClinicBasicDetail::first();
            
            foreach ($appointments as $appointment) {
                $patientName = str_replace('<br>', ' ', $appointment->patient->first_name . ' ' . $appointment->patient->last_name);
                
                if ($appointment->patient->phone != null) {
                    $patientContacts[] = [
                        'name' => $patientName,
                        'phone' => $appointment->patient->phone,
                        'message' => "Hello {$patientName},\n\n" .
                        "This is a friendly reminder from {$clinicDetails->clinic_name} about your upcoming appointment on {$date} at {$appointment->app_time}. Please arrive 10 minutes early.\n\n" .
                        "If you need to reschedule or have any questions, contact us at {$appointment->branch->clinic_phone}.\n\n" .
                        "Thank you!\n\n" .
                        "{$clinicDetails->clinic_name}",
                    ];
                }
            }
            
            return redirect()->back()->with('success', 'SMS sent successfully');
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
