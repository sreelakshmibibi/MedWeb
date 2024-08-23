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
use App\Models\CardPay;
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
        $cardPay = CardPay::where('status', 'Y')->get();
        return view('report.index', compact('treatments', 'treatmentPlans', 'diseases', 'doctors', 'branches', 'staffs', 'billStaffs', 'comboOffers', 'years', 'cardPay'));
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


    // public function income(Request $request)
    // {
    //     $year = $request->input('year');
    //     $month = $request->input('month');
    //     $fromDate = $request->input('fromDate');
    //     $toDate = $request->input('toDate');
    //     if ($request->filled('year') && !$request->filled('month')) {
    //         $query = DB::table('patient_treatment_billings')
    //             ->join('card_pays', 'patient_treatment_billings.card_pay_id', '=', 'card_pays.id')
    //             ->select(
    //                 DB::raw('MONTH(patient_treatment_billings.created_at) as month'),
    //                 DB::raw('SUM(patient_treatment_billings.amount_to_be_paid) as total_amount_to_be_paid'),
    //                 DB::raw('SUM(patient_treatment_billings.gpay) as total_gpay'),
    //                 DB::raw('SUM(patient_treatment_billings.cash) as total_cash'),
    //                 DB::raw('SUM(patient_treatment_billings.card) as total_card'),
    //                 DB::raw('SUM(patient_treatment_billings.amount_paid) as total_amount_paid'),
    //                 'card_pays.card_name',
    //                 'card_pays.service_charge_perc as machine_tax'
    //             )
    //             ->whereYear('patient_treatment_billings.created_at', $year)
    //             ->groupBy(DB::raw('MONTH(patient_treatment_billings.created_at)'), 'card_pays.card_name', 'card_pays.service_charge_perc')
    //             ->get();
           
    //         $regquery = DB::table('patient_registration_fees')
    //             ->leftJoin('card_pays', 'patient_registration_fees.card_pay_id', '=', 'card_pays.id')
    //             ->select(
    //                 DB::raw('MONTH(patient_registration_fees.created_at) as month'),
    //                 DB::raw('COALESCE(card_pays.card_name, "No Card") as card_name'),
    //                 DB::raw('SUM(patient_registration_fees.amount_to_be_paid) as total_amount_to_be_paid'),
    //                 DB::raw('SUM(patient_registration_fees.gpay) as total_gpay'),
    //                 DB::raw('SUM(patient_registration_fees.cash) as total_cash'),
    //                 DB::raw('SUM(patient_registration_fees.card) as total_card'),
    //                 DB::raw('SUM(patient_registration_fees.amount_paid) as total_amount_paid'),
    //                 DB::raw('COALESCE(card_pays.service_charge_perc, 0) as machine_tax')
    //             )
    //             ->whereYear('patient_registration_fees.created_at', $year)
    //             ->groupBy(DB::raw('MONTH(patient_registration_fees.created_at)'), 'card_pays.card_name', 'card_pays.service_charge_perc')
    //             ->get();

    //         // Aggregate totals per month including card-wise totals
    //         $monthlyTotals = $regquery->groupBy('month')->map(function ($items, $month) {
    //             $result = [
    //                 'month' => $month,
    //                 'total_amount_to_be_paid' => 0,
    //                 'total_gpay' => 0,
    //                 'total_cash' => 0,
    //                 'total_card' => 0,
    //                 'total_amount_paid' => 0,
    //                 'cards' => [],
    //             ];

    //             foreach ($items as $item) {
    //                 $result['total_amount_to_be_paid'] += $item->total_amount_to_be_paid;
    //                 $result['total_gpay'] += $item->total_gpay;
    //                 $result['total_cash'] += $item->total_cash;
    //                 $result['total_card'] += $item->total_card;
    //                 $result['total_amount_paid'] += $item->total_amount_paid;

    //                 if (!isset($result['cards'][$item->card_name])) {
    //                     $result['cards'][$item->card_name] = [
    //                         'total_amount_to_be_paid' => 0,
    //                         'total_gpay' => 0,
    //                         'total_cash' => 0,
    //                         'total_card' => 0,
    //                         'total_amount_paid' => 0,
    //                         'machine_tax' => $item->machine_tax,
    //                     ];
    //                 }

    //                 $result['cards'][$item->card_name]['total_amount_to_be_paid'] += $item->total_amount_to_be_paid;
    //                 $result['cards'][$item->card_name]['total_gpay'] += $item->total_gpay;
    //                 $result['cards'][$item->card_name]['total_cash'] += $item->total_cash;
    //                 $result['cards'][$item->card_name]['total_card'] += $item->total_card;
    //                 $result['cards'][$item->card_name]['total_amount_paid'] += $item->total_amount_paid;
    //             }

    //             return $result;
    //         })->values();

    //         // Output the aggregated results
    //         Log::info('Monthly Totals: ', $monthlyTotals->toArray());

    //         Log::info('$treatment' . $query);

    //         return DataTables::of($monthlyTotals)
    //         ->addIndexColumn()
    //         ->addColumn('month', function ($row) {
    //             return $row['month'];
    //         })
    //         ->addColumn('netPaid', function ($row) {
    //             return $row['total_amount_paid'];
    //         })
    //         ->addColumn('cash', function ($row) {
    //             return $row['total_cash'];
    //         })
    //         ->addColumn('gpay', function ($row) {
    //             return $row['total_gpay'];
    //         })
    //         ->addColumn('totalPaid', function ($row) {
    //             return $row['total_amount_paid'];
    //         })
    //         ->addColumn('pureTotal', function ($row) {
    //            return $row['total_amount_to_be_paid'];
    //         })
    //         ->addColumn('totalCustomer', function ($row) {
    //             return 10;
    //         })
    //         ->addColumn('totalService', function ($row) {
    //             return 12;
    //         })
    //         ->addColumn('avgIncome', function ($row) {
    //             return 12;
    //         })
    //         ->addColumn('dayCount', function ($row) {
    //             return 30;
    //         })
    //         ->addColumn('avgCustomer', function ($row) {
    //             return 12;
    //         })
    //         ->addColumn('avgService', function ($row) {
    //             return 12;
    //         })
    //         ->make(true);

    //     }

    // }

    public function income(Request $request)
{
    $year = $request->input('year');
    $month = $request->input('month');
    $fromDate = $request->input('fromDate');
    $toDate = $request->input('toDate');

    if ($request->filled('year') && !$request->filled('month')) {
        $treatmentQuery = DB::table('patient_treatment_billings')
            ->join('card_pays', 'patient_treatment_billings.card_pay_id', '=', 'card_pays.id')
            ->select(
                DB::raw('MONTH(patient_treatment_billings.created_at) as month'),
                DB::raw('SUM(patient_treatment_billings.amount_to_be_paid) as total_amount_to_be_paid'),
                DB::raw('SUM(patient_treatment_billings.gpay) as total_gpay'),
                DB::raw('SUM(patient_treatment_billings.cash) as total_cash'),
                DB::raw('SUM(patient_treatment_billings.card) as total_card'),
                DB::raw('SUM(patient_treatment_billings.amount_paid) as total_amount_paid'),
                'card_pays.card_name',
                'card_pays.service_charge_perc as machine_tax'
            )
            ->whereYear('patient_treatment_billings.created_at', $year)
            ->groupBy(DB::raw('MONTH(patient_treatment_billings.created_at)'), 'card_pays.card_name', 'card_pays.service_charge_perc')
            ->get();

        $regQuery = DB::table('patient_registration_fees')
            ->leftJoin('card_pays', 'patient_registration_fees.card_pay_id', '=', 'card_pays.id')
            ->select(
                DB::raw('MONTH(patient_registration_fees.created_at) as month'),
                DB::raw('COALESCE(card_pays.card_name, "No Card") as card_name'),
                DB::raw('SUM(patient_registration_fees.amount_to_be_paid) as total_amount_to_be_paid'),
                DB::raw('SUM(patient_registration_fees.gpay) as total_gpay'),
                DB::raw('SUM(patient_registration_fees.cash) as total_cash'),
                DB::raw('SUM(patient_registration_fees.card) as total_card'),
                DB::raw('SUM(patient_registration_fees.amount_paid) as total_amount_paid'),
                DB::raw('COALESCE(card_pays.service_charge_perc, 0) as machine_tax')
            )
            ->whereYear('patient_registration_fees.created_at', $year)
            ->groupBy(DB::raw('MONTH(patient_registration_fees.created_at)'), 'card_pays.card_name', 'card_pays.service_charge_perc')
            ->get();

        $monthlyTotals = $regQuery->groupBy('month')->map(function ($items, $month) {
            $result = [
                'month' => $month,
                'total_amount_to_be_paid' => 0,
                'total_gpay' => 0,
                'total_cash' => 0,
                'total_card' => 0,
                'total_amount_paid' => 0,
                'cards' => [],
            ];

            foreach ($items as $item) {
                $result['total_amount_to_be_paid'] += $item->total_amount_to_be_paid;
                $result['total_gpay'] += $item->total_gpay;
                $result['total_cash'] += $item->total_cash;
                $result['total_card'] += $item->total_card;
                $result['total_amount_paid'] += $item->total_amount_paid;

                if (!isset($result['cards'][$item->card_name])) {
                    $result['cards'][$item->card_name] = [
                        'total_card' => 0,
                        'machine_tax' => $item->machine_tax,
                    ];
                }

                $result['cards'][$item->card_name]['total_card'] += $item->total_card;
            }

            return $result;
        })->values();

        // Prepare the data for DataTable
        $dataTableData = [];
        foreach ($monthlyTotals as $row) {
            $rowData = [
                'month' => $row['month'],
                'netPaid' => $row['total_amount_paid'],
                'cash' => $row['total_cash'],
                'gpay' => $row['total_gpay'],
                'totalPaid' => $row['total_amount_paid'],
                'pureTotal' => $row['total_amount_to_be_paid'],
                'totalCustomer' => 10, 
                'totalService' => 10,  
                'avgIncome' => 10,  
                'dayCount' => 10,  
                'avgCustomer' => 10,  
                'avgService' => 10,  
            ];

            // Add card-specific data
            foreach ($row['cards'] as $cardName => $cardData) {
                $rowData["cards.{$cardName}.total"] = $cardData['total_card'];
                $rowData["cards.{$cardName}.machine_tax"] = $cardData['machine_tax'];
            }

            $dataTableData[] = $rowData;
        }
        Log::info('Monthly Totals: ', $dataTableData);
        // Return the data to DataTables
        return DataTables::of($dataTableData)
            ->addIndexColumn()
            ->make(true);
            
    }

    // Handle cases where no data is provided
    return response()->json(['data' => []]);
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
