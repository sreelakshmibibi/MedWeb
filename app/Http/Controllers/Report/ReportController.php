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
use App\Models\PatientPrescriptionBilling;
use App\Models\PatientRegistrationFee;
use App\Models\PatientDueBill;
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
        $treatmentQuery = PatientTreatmentBilling::with([
            'appointment.branch',
            'patient',
            'billedBy:id,name',
            'updatedBy:id,name',
            'createdBy:id,name',
        ]);

        if ($request->filled('fromdate')) {
            $treatmentQuery->whereDate('bill_paid_date', '>=', $request->fromdate);
        }

        if ($request->filled('todate')) {
            $treatmentQuery->whereDate('bill_paid_date', '<=', $request->todate);
        }

        if ($request->filled('branch')) {
            $treatmentQuery->whereHas('appointment', function ($q) use ($request) {
                $q->where('app_branch', $request->branch);
            });
        }

        if ($request->filled('billedby')) {
            $treatmentQuery->where('billed_by', $request->billedby);
        }

        if ($request->filled('generatedby')) {
            $treatmentQuery->where('created_by', $request->generatedby);
        }

        if ($request->filled('combooffer')) {
            $treatmentQuery->whereHas('appointment', function ($q) use ($request) {
                $q->where('combo_offer_id', $request->combooffer);
            });
        }
        $treatmentBillings = $treatmentQuery->get();
        $prescriptionQuery = PatientPrescriptionBilling::with([
            'appointment.branch',
            'patient',
            'createdBy:id,name',
            'updatedBy:id,name',
        ]);

        $registrationFeeQuery = PatientRegistrationFee::with([
            'appointment.branch',
            'patient',
            'createdBy:id,name',
            'updatedBy:id,name',
        ]);

        if ($request->filled('fromdate')) {
            $prescriptionQuery->whereDate('bill_paid_date', '>=', $request->fromdate);
            $registrationFeeQuery->whereDate('bill_paid_date', '>=', $request->fromdate);
        }

        if ($request->filled('todate')) {
            $prescriptionQuery->whereDate('bill_paid_date', '<=', $request->todate);
            $registrationFeeQuery->whereDate('bill_paid_date', '<=', $request->todate);
        }

        if ($request->filled('branch')) {
            $prescriptionQuery->whereHas('appointment', function ($q) use ($request) {
                $q->where('app_branch', $request->branch);
            });
            $registrationFeeQuery->whereHas('appointment', function ($q) use ($request) {
                $q->where('app_branch', $request->branch);
            });
        }

        if ($request->filled('generatedby')) {
            $prescriptionQuery->where('created_by', $request->generatedby);
            $registrationFeeQuery->where('created_by', $request->generatedby);
        }
        if ($request->filled('registration')) {
            $registrationFeeQuery->where('created_by', $request->registration);
        }
        $prescriptionBillings = $prescriptionQuery->get();
        $registrationBillings = $registrationFeeQuery->get();

        $dueBillQuery = PatientDueBill::with([
            'patientProfile',
            'appointment.branch',
            'creator:id,name',
            'updater:id,name',
        ]);

        // Apply date filters if provided
        if ($request->filled('fromdate')) {
            $dueBillQuery->whereDate('bill_paid_date', '>=', $request->fromdate);
        }

        if ($request->filled('todate')) {
            $dueBillQuery->whereDate('bill_paid_date', '<=', $request->todate);
        }

        // Apply branch filter if provided
        if ($request->filled('branch')) {
            $dueBillQuery->whereHas('appointment', function ($q) use ($request) {
                $q->where('app_branch', $request->branch);
            });
        }

        if ($request->filled('generatedby')) {
            $dueBillQuery->where('created_by', $request->generatedby);
        }

        if ($request->filled('outstanding')) {
            $dueBillQuery->where('created_by', $request->outstanding);
        }
        $dueBillngs = $dueBillQuery->get();



        $allBillings = collect(); // Initialize an empty collection

        // Add treatment billings if 'combooffer' is filled
        if ($request->filled('combooffer')) {
            $allBillings = $allBillings->concat($treatmentBillings);
        }

        // Add due billings if 'outstanding' is filled
        if ($request->filled('outstanding')) {
            $allBillings = $allBillings->concat($dueBillngs);
        }

        // Add registration billings if 'registration' is filled
        if ($request->filled('registration')) {
            $allBillings = $allBillings->concat($registrationBillings);
        }

        // If none of the specific filters are filled, concatenate all types of billings
        if (!$request->filled('combooffer') && !$request->filled('outstanding') && !$request->filled('registration')) {
            $allBillings = $treatmentBillings
                ->concat($prescriptionBillings)
                ->concat($registrationBillings)
                ->concat($dueBillngs);
        }

        return DataTables::of($allBillings)
            ->addIndexColumn()
            ->addColumn('billDate', function ($row) {

                if (isset($row->bill_paid_date)) {
                    return $row->bill_paid_date;
                } else {
                    return '';
                }
            })
            ->addColumn('patientId', function ($row) {
                return $row->patient_id;
            })
            ->addColumn('patientName', function ($row) {
                if (isset($row->patient)) {
                    return str_replace('<br>', ' ', $row->patient->first_name) . ' ' . $row->patient->last_name;
                } elseif (isset($row->patientProfile)) {
                    return str_replace('<br>', ' ', $row->patientProfile->first_name) . ' ' . $row->patientProfile->last_name;
                } else {
                    return '';
                }

            })
            ->addColumn('branch', function ($row) {
                if (isset($row->appointment->branch)) {
                    return $row->appointment->branch ? str_replace('<br>', ', ', $row->appointment->branch->clinic_address) : '';
                } else {
                    return '';
                }
            })
            ->addColumn('visitCount', function ($row) {
                return $row->patient->visit_count ?? 0;
            })
            ->addColumn('billType', function ($row) {
                if (isset($row->treatment_total_amount)) {
                    return 'Treatment Bill';
                } elseif (isset($row->prescription_total_amount)) {
                    return 'Medicine Bill';
                } elseif (isset($row->payment_method)) {
                    return 'Registration Bill';
                } elseif (isset($row->treatment_bill_id)) {
                    return 'Outstanding Bill';
                } else {
                    return '';
                }
            })
            ->addColumn('total', function ($row) {
                //return number_format($row->treatment_total_amount, 2);
                if (isset($row->treatment_total_amount)) {
                    // return number_format($row->treatment_total_amount, 2);
                    return $row->treatment_total_amount;
                } elseif (isset($row->prescription_total_amount)) {
                    // return number_format($row->prescription_total_amount, 2);
                    return $row->prescription_total_amount;
                } elseif (isset($row->amount)) {
                    // return number_format($row->amount, 2);
                    return $row->amount;
                } elseif (isset($row->total_amount)) {
                    // return number_format($row->total_amount, 2);
                    return $row->total_amount;
                } else {
                    return 0;
                }
            })
            ->addColumn('discount', function ($row) {
                //return number_format($row->doctor_discount, 2);
                if (isset($row->doctor_discount) || isset($row->combo_offer_deduction)) {
                    //return number_format($row->doctor_discount, 2);
                    return $row->doctor_discount + $row->combo_offer_deduction;
                } elseif (isset($row->discount)) {
                    // return number_format($row->discount, 2);
                    return $row->discount;
                } else {
                    return 0;
                }
            })
            ->addColumn('tax', function ($row) {

                if (isset($row->tax)) {
                    // return number_format($row->tax, 2);
                    return $row->tax;
                } else {
                    return 0;
                }
            })
            ->addColumn('netAmount', function ($row) {
                if (isset($row->amount_to_be_paid)) {
                    // return number_format($row->amount_to_be_paid, 2);
                    return $row->amount_to_be_paid;
                } elseif (isset($row->total_amount)) {
                    // return number_format($row->total_amount, 2);
                    return $row->total_amount;
                } else {
                    return 0;
                }
            })
            ->addColumn('cash', function ($row) {
                if (isset($row->cash)) {
                    //return number_format($row->cash, 2);
                    return $row->cash;
                } else {
                    return 0;
                }
            })
            ->addColumn('gpay', function ($row) {
                if (isset($row->gpay)) {
                    //return number_format($row->gpay, 2);
                    return $row->gpay;
                } else {
                    return 0;
                }
            })
            ->addColumn('card', function ($row) {
                if (isset($row->card)) {
                    //return number_format($row->card, 2);
                    return $row->card;
                } else {
                    return 0;
                }
            })
            ->addColumn('totalPaid', function ($row) {
                //return number_format($row->amount_paid, 2);
                if (isset($row->amount_paid)) {
                    //return number_format($row->amount_paid, 2);
                    return $row->amount_paid;
                } elseif (isset($row->paid_amount)) {
                    //return number_format($row->paid_amount, 2);
                    return $row->paid_amount;
                } else {
                    return 0;
                }
            })
            ->addColumn('balanceGiven', function ($row) {
                // return number_format($row->balance_to_give_back, 2);
                if (isset($row->balance_to_give_back)) {
                    // return number_format($row->balance_to_give_back, 2);
                    return $row->balance_to_give_back;
                } elseif (isset($row->balance_given)) {
                    //return number_format($row->balance_given, 2);
                    return $row->balance_given;
                } else {
                    return 0;
                }
            })
            ->addColumn('outstanding', function ($row) {
                if (isset($row->balance_due)) {
                    // return number_format($row->balance_due, 2);
                    return $row->balance_due;
                } else {
                    return 0;
                }
            })
            ->addColumn('createdBy', function ($row) {
                //return $row->createdBy->name ?? 'N/A';
                if (isset($row->createdBy->name)) {
                    // return $row->createdBy->name;
                    return str_replace('<br>', ' ', $row->createdBy->name);
                } elseif (isset($row->creator->name)) {
                    // return $row->creator->name;
                    return str_replace('<br>', ' ', $row->creator->name);
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('updatedBy', function ($row) {
                //return $row->updatedBy->name ?? 'N/A';
                if (isset($row->updatedBy->name)) {
                    // return $row->updatedBy->name;
                    return str_replace('<br>', ' ', $row->updatedBy->name);
                } elseif (isset($row->updater->name)) {
                    // return $row->updater->name;
                    return str_replace('<br>', ' ', $row->updater->name);
                } else {
                    return 'N/A';
                }
            })
            ->make(true);
    }


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
