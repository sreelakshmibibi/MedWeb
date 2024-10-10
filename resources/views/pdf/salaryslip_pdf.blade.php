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
                <td style="width: 10%;"><strong>Staff ID: </strong></td>
                <td style="width: 40%;">{{ $staff->staff_id ?? 'N/A' }}</td>

                <td style="width: 50%; text-align:right;"><strong>Designation: </strong>{{ $staff->designation }}
                </td>
            </tr>

            <tr>
                <td style="width: 10%;"><strong>Name: </strong></td>
                <td style="width: 40%;">{{ str_replace('<br>', ' ', $staff->user->name) }}</td>

                <td style="width: 50%; text-align:right;"><strong>Department: </strong>{{ $department->department }}

            </tr>

            <tr>
                <td style="width: 10%;"><strong>Age: </strong></td>
                <td style="width: 40%;">
                    {{ isset($staff->date_of_birth) ? (preg_match('/(\d+) years/', $commonService->calculateAge($staff->date_of_birth), $matches) ? $matches[1] : 'N/A') : 'N/A' }}
                </td>
                <td style="width: 50%; text-align:right;"><strong>Date of Joining: </strong>
                    {{ \Carbon\Carbon::parse($staff->date_of_joining)->format('d-m-Y') }}
                </td>

            </tr>

            <tr>
                <td style="width: 10%;"><strong>Gender: </strong></td>
                <td style="width: 40%;">
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
                <td style="width: 50%; text-align:right;"><strong>Created on: </strong>
                    {{ \Carbon\Carbon::parse($salary->created_at ?? now())->format('d-m-Y') }}
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
            <tbody class="tbodypart">

                @if (!$EPayHeads->isEmpty())
                    <?php $i = 0; ?>
                    <tr>
                        <th class="subhead" colspan="3" style="text-align:left;">Earnings</th>
                        <th class="subhead">{{ $currency }}{{ number_format($salary->etotal, 2) }}</th>
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
                        <th class="subhead" colspan="3" style="text-align:left;">Statutory Additions</th>
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
                        <th class="subhead" colspan="3" style="text-align:left;">Statutory Deductions</th>
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
                <tr class="total">
                    <th colspan="3" style="text-align: right;">
                        <h4>Salary</h4>
                    </th>
                    <th>
                        <h4>{{ $currency }}{{ number_format($salary->salary, 2) }}
                        </h4>
                    </th>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Net Salary</td>
                    <td>{{ $currency }}{{ number_format($salary->netsalary, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">CTC</td>
                    <td>{{ $currency }}{{ number_format($salary->ctc, 2) }}</td>
                </tr>

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
