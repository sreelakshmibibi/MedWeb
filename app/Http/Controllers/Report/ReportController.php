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
                $q->where('combo_offer_id', $request->branch);
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

        if ($request->filled('combooffer')) {
            $allBillings = $treatmentBillings;
        }
        else if ($request->filled('outstanding')) {
            $allBillings = $dueBillngs;
        }
        else if ($request->filled('registration')) {
            $allBillings = $registrationBillings;
        }else{
            $allBillings = $treatmentBillings->concat($prescriptionBillings);
            $allBillings = $allBillings->concat($registrationBillings);
            $allBillings = $allBillings->concat($dueBillngs);
        }
        
        
        
        Log::info('$prescriptionBillings: '.$prescriptionBillings);
        Log::info('$treatmentBillings: '.$treatmentBillings);
        Log::info('$registrationBillings: '.$registrationBillings);
        Log::info('$dueBillngs: '.$dueBillngs);
        Log::info('$allBillings: '.$allBillings);
    
        return DataTables::of($allBillings)
            ->addIndexColumn()
            ->addColumn('billDate', function ($row) {
                
                if (isset($row->bill_paid_date)) {
                    return $row->bill_paid_date;
                }else {
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
                }else {
                    return ''; 
                }
                
            })
            ->addColumn('branch', function ($row) {
                if (isset($row->appointment->branch )) {
                return $row->appointment->branch ? str_replace('<br>', ', ', $row->appointment->branch->clinic_address) : '';
                }else{
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
                }elseif (isset($row->payment_method)) {
                    return 'Registration Bill';
                }elseif (isset($row->treatment_bill_id)) {
                    return 'Outstanding Bill';
                }else {
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
                }elseif (isset($row->amount)) {
                    // return number_format($row->amount, 2);
                    return $row->amount;
                }elseif (isset($row->total_amount)) {
                    // return number_format($row->total_amount, 2);
                    return $row->total_amount;
                }else {
                    return 0; 
                }
            })
            ->addColumn('discount', function ($row) {
                //return number_format($row->doctor_discount, 2);
                if (isset($row->doctor_discount) || isset($row->previous_outstanding) || isset($row->combo_offer_deduction) || isset($row->insurance_paid)) {
                    //return number_format($row->doctor_discount, 2);
                    return $row->doctor_discount+$row->previous_outstanding+$row->combo_offer_deduction+$row->insurance_paid;
                } elseif (isset($row->discount)) {
                    // return number_format($row->discount, 2);
                    return $row->discount;
                }else {
                    return 0; 
                }
            })
            ->addColumn('tax', function ($row) {
                
                if (isset($row->tax)) {
                    // return number_format($row->tax, 2);
                    return $row->tax;
                }else {
                    return 0; 
                }
            })
            ->addColumn('netAmount', function ($row) {
                if (isset($row->amount_to_be_paid)) {
                    // return number_format($row->amount_to_be_paid, 2);
                    return $row->amount_to_be_paid;
                }elseif (isset($row->total_amount)) {
                    // return number_format($row->total_amount, 2);
                    return $row->total_amount;
                }else {
                    return 0; 
                }
            })
            ->addColumn('cash', function ($row) {
                if (isset($row->cash)) {
                    //return number_format($row->cash, 2);
                    return $row->cash;
                }else {
                    return 0; 
                }
            })
            ->addColumn('gpay', function ($row) {                
                if (isset($row->gpay)) {
                    //return number_format($row->gpay, 2);
                    return $row->gpay;
                }else {
                    return 0; 
                }
            })
            ->addColumn('card', function ($row) {
                if (isset($row->card)) {
                    //return number_format($row->card, 2);
                    return $row->card;
                }else {
                    return 0; 
                }
            })
            ->addColumn('totalPaid', function ($row) {
                //return number_format($row->amount_paid, 2);
                if (isset($row->amount_paid)) {
                    //return number_format($row->amount_paid, 2);
                    return $row->amount_paid;
                }elseif (isset($row->paid_amount)) {
                    //return number_format($row->paid_amount, 2);
                    return $row->paid_amount;
                }else {
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
                }else {
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
                    return $row->createdBy->name;
                } elseif (isset($row->creator->name)) {
                    return $row->creator->name;
                }else {
                    return 'N/A'; 
                }
            })
            ->addColumn('updatedBy', function ($row) {
                //return $row->updatedBy->name ?? 'N/A';
                if (isset($row->updatedBy->name)) {
                    return $row->updatedBy->name;
                } elseif (isset($row->updater->name)) {
                    return $row->updater->name;
                }else {
                    return 'N/A'; 
                }
            })
            ->make(true);
    }

    // public function collection(Request $request)
    // {
    //     $query = PatientTreatmentBilling::with([
    //         'appointment.branch',
    //         'patient',
    //         'billedBy:id,name',
    //         'updatedBy:id,name',
    //         'createdBy:id,name',
    //     ]);
    
    //     // Apply filters
    //     if ($request->filled('fromdate')) {
    //         $query->whereDate('bill_paid_date', '>=', $request->fromdate);
    //     }
    
    //     if ($request->filled('todate')) {
    //         $query->whereDate('bill_paid_date', '<=', $request->todate);
    //     }
    
    //     if ($request->filled('branch')) {
    //         $query->whereHas('appointment', function ($q) use ($request) {
    //             $q->where('branch_id', $request->branch);
    //         });
    //     }
    
    //     if ($request->filled('billedby')) {
    //         $query->where('billed_by', $request->billedby);
    //     }
    
    //     if ($request->filled('generatedby')) {
    //         $query->where('created_by', $request->generatedby);
    //     }
    
    //     if ($request->filled('outstanding')) {
    //         $query->where('balance_due', '>', 0);
    //     }
    
    //     if ($request->filled('combooffer')) {
    //         $query->whereNotNull('combo_offer_deduction');
    //     }
    
    //     if ($request->filled('registration')) {
    //         $query->whereHas('patient', function ($q) use ($request) {
    //             $q->where('registration_by', $request->registration);
    //         });
    //     }
    
    //     if ($request->filled('bill_status')) {
    //         $query->where('bill_status', $request->bill_status);
    //     }
    //     $bill = $query->get();
        
    //     if ($request->ajax()) {
    //         $data = DataTables::of($bill)
    //             ->addIndexColumn()
    //             ->addColumn('billDate', function ($row) {
    //                 return $row->bill_paid_date;
    //             })
    //             ->addColumn('patientId', function ($row) {
    //                 return $row->patient_id;
    //             })
    //             ->addColumn('patientName', function ($row) {
    //                 return str_replace('<br>', ' ', $row->patient->first_name) . ' ' . $row->patient->last_name;
    //             })
    //             ->addColumn('branch', function ($row) {
    //                 return $row->appointment->branch ? str_replace('<br>', ' ', $row->appointment->branch->clinic_address) : '';
    //             })
    //             ->addColumn('visitCount', function ($row) {
    //                 return $row->patient->visit_count ?? 0;
    //             })
    //             ->addColumn('total', function ($row) {
    //                 return number_format($row->treatment_total_amount, 2);
    //             })
    //             ->addColumn('discount', function ($row) {
    //                 return number_format($row->doctor_discount, 2);
    //             })
    //             ->addColumn('tax', function ($row) {
    //                 return number_format($row->tax, 2);
    //             })
    //             ->addColumn('netAmount', function ($row) {
    //                 return number_format($row->amount_to_be_paid, 2);
    //             })
    //             ->addColumn('cash', function ($row) {
    //                 return number_format($row->cash, 2);
    //             })
    //             ->addColumn('gpay', function ($row) {
    //                 return number_format($row->gpay, 2);
    //             })
    //             ->addColumn('card', function ($row) {
    //                 return number_format($row->card, 2);
    //             })
    //             ->addColumn('totalPaid', function ($row) {
    //                 return number_format($row->amount_paid, 2);
    //             })
    //             ->addColumn('balanceGiven', function ($row) {
    //                 return number_format($row->balance_to_give_back, 2);
    //             })
    //             ->addColumn('outstanding', function ($row) {
    //                 return number_format($row->balance_due, 2);
    //             })
    //             ->addColumn('createdBy', function ($row) {
    //                 return $row->billedBy->name ?? 'N/A';
    //             })
    //             ->addColumn('updatedBy', function ($row) {
    //                 return $row->updatedBy->name ?? 'N/A';
    //             })
    //             ->make(true);
    //             Log::info('$appId: '.response()->json($data));
    
    //         return response()->json($data);
    //     }
    // }
    
//     public function collection(Request $request)
//     {
//         $query = PatientTreatmentBilling::with([
//             'appointment.branch',
//             'patient',
//             'billedBy:id,name',
//             'updatedBy:id,name',
//             'createdBy:id,name',
//         ]);
//         // ->select(
// //         //     'patient_treatment_billings.*',
// //         //     'patient_profiles.first_name as patientName',
// //         //     'patient_profiles.patient_id as patientId',
// //         //     'billed_by_user.name as billedBy'
// //         // )
// //         // ->join('appointments', 'patient_treatment_billings.appointment_id', '=', 'appointments.id')
// //         // ->join('patient_profiles', 'patient_treatment_billings.patient_id', '=', 'patient_profiles.patient_id')
// //         // ->leftJoin('users as billed_by_user', 'patient_treatment_billings.billed_by', '=', 'billed_by_user.id');

//         // Apply filters based on request parameters
//         if ($request->filled('fromdate')) {
//             $query->whereDate('bill_paid_date', '>=', $request->fromdate);
//         }

//         if ($request->filled('todate')) {
//             $query->whereDate('bill_paid_date', '<=', $request->todate);
//         }

//         if ($request->filled('branch')) {
//             $query->whereHas('appointment', function ($q) use ($request) {
//                 $q->where('branch_id', $request->branch);
//             });
//         }

//         if ($request->filled('billedby')) {
//             $query->where('billed_by', $request->billedby);
//         }

//         if ($request->filled('generatedby')) {
//             $query->where('created_by', $request->generatedby);
//         }

//         if ($request->filled('outstanding')) {
//             $query->where('balance_due', '>', 0);
//         }

//         if ($request->filled('combooffer')) {
//             $query->whereNotNull('combo_offer_deduction');
//         }

//         if ($request->filled('registration')) {
//             $query->whereHas('patient', function ($q) use ($request) {
//                 $q->where('registration_by', $request->registration);
//             });
//         }

//         if ($request->filled('bill_status')) {
//             $query->where('bill_status', $request->bill_status);
//         }
//         if ($request->ajax()) {

//             return DataTables::of($query)
//                 ->addIndexColumn()
//                 ->addColumn('billDate', function ($row) {
//                     return $row->bill_paid_date;
//                 })
//                 ->addColumn('patientId', function ($row) {
//                     return $row->patient_id;
//                 })
//                 ->addColumn('patientName', function ($row) {
//                     return str_replace('<br>', ' ', $row->patient->first_name) . ' ' . $row->patient->last_name;
//                 })
//                 ->addColumn('branch', function ($row) {
//                     return $row->appointment->branch ? str_replace('<br>', ' ', $row->appointment->branch->clinic_address) : '';
//                 })
//                 ->addColumn('visitCount', function ($row) {
//                     return $row->patient->visit_count ?? 0;
//                 })
//                 ->addColumn('total', function ($row) {
//                     return number_format($row->treatment_total_amount, 2);
//                 })
//                 ->addColumn('discount', function ($row) {
//                     return number_format($row->doctor_discount, 2);
//                 })
//                 ->addColumn('tax', function ($row) {
//                     return number_format($row->tax, 2);
//                 })
//                 ->addColumn('netAmount', function ($row) {
//                     return number_format($row->amount_to_be_paid, 2);
//                 })
//                 ->addColumn('cash', function ($row) {
//                     return number_format($row->cash, 2);
//                 })
//                 ->addColumn('gpay', function ($row) {
//                     return number_format($row->gpay, 2);
//                 })
//                 ->addColumn('card', function ($row) {
//                     return number_format($row->card, 2);
//                 })
//                 ->addColumn('totalPaid', function ($row) {
//                     return number_format($row->amount_paid, 2);
//                 })
//                 ->addColumn('balanceGiven', function ($row) {
//                     return number_format($row->balance_to_give_back, 2);
//                 })
//                 ->addColumn('outstanding', function ($row) {
//                     return number_format($row->balance_due, 2);
//                 })
//                 ->addColumn('createdBy', function ($row) {
//                     return $row->billedBy->name ?? 'N/A';
//                 })
//                 ->addColumn('updatedBy', function ($row) {
//                     return $row->updatedBy->name ?? 'N/A';
//                 })
//                 ->make(true);
//         }
//     }

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
