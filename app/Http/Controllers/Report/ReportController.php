<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\AppointmentType;
use App\Models\ClinicBranch;
use App\Services\CommonService;
use App\Services\DoctorAvaialbilityService;
use App\Services\ReportService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\PatientTreatmentBilling;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reportService = new ReportService();
        $treatments = $reportService->getTreatments();
        $treatmentPlans = $reportService->getTreatmentPlans();
        $diseases = $reportService->getDiseases();
        $doctors = $reportService->getDoctors();
        $branches = $reportService->getBranches();
        $staffs = $reportService->getStaff();
        $billStaffs = $reportService->getBillStaff();
        $comboOffers = $reportService->getComboOffers();
        $years = $reportService->getYears();
        return view('report.index', compact('treatments', 'treatmentPlans', 'diseases', 'doctors', 'branches', 'staffs', 'billStaffs', 'comboOffers', 'years'));
    }


    public function collection(Request $request)
    {
        $query = PatientTreatmentBilling::with([
            'appointment.branch',
            'patient',
            'billedBy:id,name',
            'updatedBy:id,name',
            'createdBy:id,name',
        ]);
        // ->select(
//         //     'patient_treatment_billings.*',
//         //     'patient_profiles.first_name as patientName',
//         //     'patient_profiles.patient_id as patientId',
//         //     'billed_by_user.name as billedBy'
//         // )
//         // ->join('appointments', 'patient_treatment_billings.appointment_id', '=', 'appointments.id')
//         // ->join('patient_profiles', 'patient_treatment_billings.patient_id', '=', 'patient_profiles.patient_id')
//         // ->leftJoin('users as billed_by_user', 'patient_treatment_billings.billed_by', '=', 'billed_by_user.id');
    
        // Apply filters based on request parameters
        if ($request->filled('fromdate')) {
            $query->whereDate('bill_paid_date', '>=', $request->fromdate);
        }
    
        if ($request->filled('todate')) {
            $query->whereDate('bill_paid_date', '<=', $request->todate);
        }
    
        if ($request->filled('branch')) {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->where('branch_id', $request->branch);
            });
        }
    
        if ($request->filled('billedby')) {
            $query->where('billed_by', $request->billedby);
        }
    
        if ($request->filled('generatedby')) {
            $query->where('created_by', $request->generatedby);
        }
    
        if ($request->filled('outstanding')) {
            $query->where('balance_due', '>', 0);
        }
    
        if ($request->filled('combooffer')) {
            $query->whereNotNull('combo_offer_deduction');
        }
    
        if ($request->filled('registration')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('registration_by', $request->registration);
            });
        }
    
        if ($request->filled('bill_status')) {
            $query->where('bill_status', $request->bill_status);
        }
    
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('billDate', function ($row) {
                return $row->bill_paid_date;
            })
            ->addColumn('patientId', function ($row) {
                return $row->patient_id;
            })
            ->addColumn('patientName', function ($row) {
                return str_replace('<br>', ' ', $row->patient->first_name) . ' ' . $row->patient->last_name;
            })
            ->addColumn('branch', function ($row) {
                return $row->appointment->branch ? str_replace('<br>', ' ', $row->appointment->branch->clinic_address) : '';
            })
            ->addColumn('visitCount', function ($row) {
                return $row->patient->visit_count ?? 0;
            })
            ->addColumn('total', function ($row) {
                return number_format($row->treatment_total_amount, 2);
            })
            ->addColumn('discount', function ($row) {
                return number_format($row->doctor_discount, 2);
            })
            ->addColumn('tax', function ($row) {
                return number_format($row->tax, 2);
            })
            ->addColumn('netAmount', function ($row) {
                return number_format($row->amount_to_be_paid, 2);
            })
            ->addColumn('cash', function ($row) {
                return number_format($row->cash, 2);
            })
            ->addColumn('gpay', function ($row) {
                return number_format($row->gpay, 2);
            })
            ->addColumn('card', function ($row) {
                return number_format($row->card, 2);
            })
            ->addColumn('totalPaid', function ($row) {
                return number_format($row->amount_paid, 2);
            })
            ->addColumn('balanceGiven', function ($row) {
                return number_format($row->balance_to_give_back, 2);
            })
            ->addColumn('outstanding', function ($row) {
                return number_format($row->balance_due, 2);
            })
            ->addColumn('createdBy', function ($row) {
                return $row->billedBy->name ?? 'N/A';
            })
            ->addColumn('updatedBy', function ($row) {
                return $row->updatedBy->name ?? 'N/A';
            })
            ->make(true);
    }
    
     /**
     * Report Collection.
     */
    public function income(Request $request)
    {
        echo "income";
        exit;

    }

    /**
     * Report Collection.
     */
    public function service(Request $request)
    {
        echo "service";
        exit;
    }

    /**
     * Report Collection.
     */
    public function patient(Request $request)
    {
        echo "patient";
        exit;
    }

    /**
     * Report Collection.
     */
    public function disease(Request $request)
    {
        echo "disease";
        exit;

    }



    


    

}
