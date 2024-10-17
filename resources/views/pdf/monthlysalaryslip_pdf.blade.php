<?php
use App\Services\CommonService;
$commonService = new CommonService();

date_default_timezone_set('Asia/Kolkata');
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Salary Slip</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Helvetica;
            color: #000;
            padding: 0;
            margin: 0;
            /* font-size: 14px; */
            font-size: 10px;
        }

        .header,
        .footer {
            position: fixed;
            left: 0;
            right: 0;
        }

        .header {
            align-items: center;
            text-align: center;
            padding-bottom: 4px;
            border-bottom: 2px solid #666;
        }

        .header img {
            vertical-align: middle;
            width: 50px;
        }

        .headingdiv {}

        .clinic-name {
            /* font-size: 16px; */
            font-weight: bold;
            font-size: 14px;
        }

        .clinic-address {
            /* font-size: 14px; */
            font-size: 12px;
        }

        .pdfbody {
            margin-top: 110px;
            position: relative;
            height: 75%;
        }

        h1,
        h2 {
            color: #333;
        }

        .heading {
            font-size: 13px;
        }

        .subheading {
            margin-bottom: 2px;
            text-decoration: underline;
            /* font-size: 15px; */
            font-size: 12px;
            margin-top: 2px;
        }

        .total,
        .total h4 {
            font-size: 11px;
            margin-top: 2px;
            margin-bottom: 2px;
        }

        h4 {
            margin-top: 0;
            margin-bottom: 0;
        }

        .info-table,
        .treatmentbill-table {
            width: 100%;
            border-collapse: collapse;
            /* margin-bottom: 20px; */
            margin-bottom: 4px;
        }

        .info-table td,
        .treatmentbill-table td,
        .treatmentbill-table th {
            text-align: left;
            vertical-align: top;
        }

        .info-table td {
            border: none;
        }

        .treatmentbill-table {
            border: 1px solid #ddd;
            border: none;
        }

        .treatmentbill-table thead,
        .tbodypart {
            border-bottom: 1px solid #ddd;
        }

        .treatmentbill-table td,
        .treatmentbill-table th {
            /* border: 1px solid #ddd; */
            text-align: center;
        }

        .linestyle {
            border: none;
            border-bottom: 1px solid #666;
            margin-bottom: 4px;
        }

        /* .details {
            position: absolute;
            right: 0;
            left: auto;
            text-align: right;
            width: 25%;
        } */
        .details {
            text-align: center;
            width: 100%;
            margin-top: 20px;
            /* Add spacing */
        }

        .footer {
            bottom: 0;
            /* font-size: 12px; */
            width: 100%;
            border-top: 1px solid #666;
            padding-top: 4px;
            color: #666;
            font-size: 8px;
        }

        .footer_text1 {
            text-align: left;
            width: 50%;
            float: left;
        }

        .footer_text2 {
            text-align: right;
            width: 50%;
            float: right;
        }

        .subhead {
            border-bottom: 1px solid #ddd;
        }



        @media print {
            @page {
                size: A4;
                /* Set paper size to A5 */
                /* margin: 20mm; */
                margin: 1rem;
                /* Adjust margins if necessary */
            }

            body {
                margin: 0;
                /* Remove margins if using @page size settings */
            }

            .no-print {
                display: none;
            }

        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        {{-- <img src="'{{ asset('storage/') }}/' + {{ $clinicDetails->clinic_logo }}" alt="Clinic Logo" width="50px"> --}}
        <!-- Clinic Logo -->
        <img src="{{ $clinicLogo }}" alt="Clinic Logo" height="40px">
        <div class="headingdiv">
            <div class="clinic-name">{{ $clinicDetails->clinic_name ?? 'Clinic Name' }}</div> <!-- Clinic Name -->
            <div class="clinic-address">
                {{ str_replace('<br>', ' ', $clinicBranches->clinic_address) ?? 'N/A' }},
                {{ $clinicBranches->city->city ?? 'N/A' }},
                {{ $clinicBranches->state->state ?? 'N/A' }},
                {{ $clinicBranches->country->country ?? 'N/A' }}-
                {{ $clinicBranches->pincode ?? 'N/A' }}<br>
                Phone: {{ $clinicBranches->clinic_phone ?? 'N/A' }}
                Email: {{ $clinicBranches->clinic_email ?? 'N/A' }}
            </div>
        </div>
    </div>
    <div class="pdfbody">
        <h3 class="heading">
            <center>Salary Slip</center>
        </h3>
        <table class="info-table">
            <tr>
                <td style="width: 15%;"><strong>Staff ID: </strong></td>
                <td style="width: 35%;">{{ $staff->staff_id ?? 'N/A' }}</td>

                <td style="width: 50%; text-align:right;"><strong>Designation: </strong>{{ $staff->designation }}
                </td>
            </tr>

            <tr>
                <td style="width: 15%;"><strong>Name: </strong></td>
                <td style="width: 35%;">{{ str_replace('<br>', ' ', $staff->user->name) }}</td>

                <td style="width: 50%; text-align:right;"><strong>Department: </strong>{{ $department->department }}

            </tr>

            <tr>
                <td style="width: 15%;"><strong>Age: </strong></td>
                <td style="width: 35%;">
                    {{ isset($staff->date_of_birth) ? (preg_match('/(\d+) years/', $commonService->calculateAge($staff->date_of_birth), $matches) ? $matches[1] : 'N/A') : 'N/A' }}
                </td>
                <td style="width: 50%; text-align:right;"><strong>Date of Joining: </strong>
                    {{ \Carbon\Carbon::parse($staff->date_of_joining)->format('d-m-Y') }}
                </td>

            </tr>

            <tr>
                <td style="width: 15%;"><strong>Gender: </strong></td>
                <td style="width: 35%;">
                    @if ($staff->gender === 'M')
                        Male
                    @elseif($staff->gender === 'F')
                        Female
                    @elseif($staff->gender === 'O')
                        Others
                    @else
                        N/A
                    @endif
                </td>
                <td style="width: 50%; text-align:right;"><strong>Pay period: </strong>
                    {{ \Carbon\Carbon::createFromDate($monthlySalary->year ?? now()->year, $monthlySalary->month ?? now()->month, 1)->format('F Y') }}
                </td>
            </tr>
            <tr>
                <td style="width: 15%;"><strong>Working Days: </strong></td>
                <td style="width: 35%;">
                   {{$monthlySalary->working_days}}
                </td>
                <td style="width: 50%; text-align:right;"><strong>Eligible Days: </strong>
                    {{ $monthlySalary->paid_days + round(($monthlySalary->partially_paid_days ?? 0) / 2, 1) }}
                </td>
            </tr>
        </table>

        <hr class="linestyle" />

        <h4 class="subheading">Salary Details</h4>

        <table class="treatmentbill-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pay Head</th>
                    <th>With Effect From</th>
                    <th>Amount ({{ $currency }})</th>
                </tr>
            </thead>
            <?php  
                $earningTotal = 0;
                $monthlyDeduction = 0;
            ?>
            <tbody class="tbodypart">

                @if (!$EPayHeads->isEmpty())
                    <?php $i = 0; 
                    $earningTotal = $salary->etotal + $monthlySalary->incentives;?>
                    <tr>
                        <th class="subhead" colspan="3" style="text-align:left;">Earnings  (A)</th>
                        <th class="subhead">{{ $currency }}{{ number_format($monthlySalary->total_earnings, 2) }}</th>
                    </tr>
                    @foreach ($EPayHeads as $head)
                        <?php $earning = $employeesalary->where('pay_head_id', $head->id)->first(); ?>
                        <tr>
                            <td>{{ ++$i }}.</td>
                            <td style="text-align:left;">{{ $head->head_type ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($earning->with_effect_from)->format('d-m-Y') }}</td>
                            <td>{{ $earning->amount ?? '' }}</td>
                        </tr>
                    @endforeach
                    @if ($monthlySalary->incentives >0)
                    <tr>
                        <td>{{ ++$i }}.</td>
                        <td style="text-align:left;">Incentive</td>
                        <td></td>
                        <td>{{ $monthlySalary->incentives ?? '' }}</td>
                    </tr>
                    @endif
                @else
                    <tr>
                        <td colspan="4">No Earnings Entered</td>
                    </tr>
                @endif
            </tbody>
            <tbody class="tbodypart">

                @if (!$SAPayHeads->isEmpty())
                    <?php $i = 0; ?>
                    <tr>
                        <th class="subhead" colspan="3" style="text-align:left;">Statutory Additions  (B)</th>
                        <th class="subhead">{{ $currency }}{{ number_format($salary->satotal, 2) }}</th>
                    </tr>
                    @foreach ($SAPayHeads as $head)
                        <?php $earning = $employeesalary->where('pay_head_id', $head->id)->first(); ?>
                        <tr>
                            <td>{{ ++$i }}.</td>
                            <td style="text-align:left;">{{ $head->head_type ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($earning->with_effect_from)->format('d-m-Y') }}</td>
                            <td>{{ $earning->amount ?? '' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" style="text-align:left;">No Statutory Additions Entered</td>
                    </tr>
                @endif
            </tbody>
            <tbody class="tbodypart">

                @if (!$SDPayHeads->isEmpty())
                    <?php $i = 0; ?>
                    <tr>
                        <th class="subhead" colspan="3" style="text-align:left;">Statutory Deductions  (C)</th>
                        <th class="subhead">{{ $currency }}{{ number_format($salary->sdtotal, 2) }}</th>
                    </tr>
                    @foreach ($SDPayHeads as $head)
                        <?php $earning = $employeesalary->where('pay_head_id', $head->id)->first(); ?>
                        <tr>
                            <td>{{ ++$i }}.</td>
                            <td style="text-align:left;">{{ $head->head_type ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($earning->with_effect_from)->format('d-m-Y') }}</td>
                            <td>{{ $earning->amount ?? '' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No Statutory Deductions Entered</td>
                    </tr>
                @endif

            </tbody>
            <tbody class="tbodypart">

                @if ($monthlySalary->absence_deduction > 0 || $monthlySalary->monthly_deduction > 0)
                    <?php $i = 0; 
                    $monthlyDeduction = $monthlySalary->absence_deduction + $monthlySalary->monthly_deduction?>
                    <tr>
                        <th class="subhead" colspan="3" style="text-align:left;">Deductions  (D)</th>
                        <th class="subhead">{{ $currency }}{{ number_format($monthlyDeduction, 2) }}</th>
                    </tr>
                    @if ($monthlySalary->absence_deduction >0)
                    <tr>
                        <td>{{ ++$i }}.</td>
                        <td style="text-align:left;">Loss of Pay</td>
                        <td></td>
                        <td>{{ $monthlySalary->absence_deduction ?? '' }}</td>
                    </tr>
                    @endif
                    @if ($monthlySalary->monthly_deduction >0)
                    <tr>
                        <td>{{ ++$i }}.</td>
                        <td style="text-align:left;">{{$monthlySalary->deduction_reason??'Other Deduction'}}</td>
                        <td></td>
                        <td>{{ $monthlySalary->monthly_deduction ?? '' }}</td>
                    </tr>
                    @endif
                @endif
                   

            </tbody>

            <?php  
               
                $ctc = $earningTotal + $salary->satotal;
                $monthlyNetSalary = $ctc - ($monthlyDeduction + $salary->sdtotal);
            ?>
            <tbody class="tbodypart">
                <tr class="total">
                    <th colspan="3" style="text-align: right;">
                        <h4>Gross  (A)</h4>
                    </th>
                    <th>
                        <h4>{{ $currency }}{{ number_format( $monthlySalary->total_earnings, 2) }}
                        </h4>
                    </th>
                </tr>
                
                <tr>
                    <td colspan="3" style="text-align: right;">CTC  (A+B)</td>
                    <td>{{ $currency }}{{ number_format($monthlySalary->ctc, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><h4>CURRENT MONTH NET (A-C-D) </h4></td>
                    <td><h4>{{ $currency }}{{ number_format($monthlySalary->total_salary, 2) }}<h4></td>
                </tr>
                {{-- @if ($monthlySalary->previous_due >0)
                <tr>
                    <td colspan="3" style="text-align: right;"> Previous Due Given</td>
                    <td> + {{ $currency }}{{ number_format($monthlySalary->previous_due, 2) }}</td>
                </tr>
                
                @endif
                @if ($monthlySalary->advance_given >0)
                <tr>
                    <td colspan="3" style="text-align: right;"> Advance</td>
                    <td> - {{ $currency }}{{ number_format($monthlySalary->advance_given, 2) }}</td>
                </tr>
                @endif
                @if ($monthlySalary->balance_due >0)
                <tr>
                    <td colspan="3" style="text-align: right;"> Balance Due</td>
                    <td> - {{ $currency }}{{ number_format($monthlySalary->balance_due, 2) }}</td>
                </tr>
                
                @endif
                <tr>
                    <td colspan="3" style="text-align: right;">CURRENT MONTH SALARY</td>
                    <td>{{ $currency }}{{ number_format($monthlySalary->amount_paid, 2) }}</td>
                </tr> --}}
            </tbody>
        </table>

    </div>

    <div class="details">
        <div class="footer_text1">Employee Signature</div>
        <div class="footer_text2">Employer Signature</div>
    </div>

    <div class="footer">
        <div class="footer_text1">Developed by Serieux</div>
        <div class="footer_text2">{{ date('d-m-Y h:i:s A') }}</div>
    </div>

</body>

</html>
