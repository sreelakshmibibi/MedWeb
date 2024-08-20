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
    <title>Outstanding Payment Bill</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Helvetica;
            color: #000;
            padding: 0;
            margin: 0;
            font-size: 10px;
        }

        .header,
        .footer {
            position: fixed;
            left: 0;
            right: 0;
        }

        .header {
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
            font-weight: bold;
            font-size: 14px;
        }

        .clinic-address {
            font-size: 12px;
        }

        .pdfbody {
            margin-top: 75px;
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
            font-size: 12px;
            margin-top: 2px;
        }

        .total,
        .total h4 {
            font-size: 11px;
            margin-top: 2px;
            margin-bottom: 2px;
        }

        .info-table,
        .regbill-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .info-table td,
        .regbill-table td,
        .regbill-table th {
            text-align: left;
            vertical-align: top;
        }

        .info-table td {
            border: none;
        }

        .regbill-table {
            /* border: 1px solid #ddd;
            border-collapse: collapse; */
            border: none;
        }

        .regbill-table thead,
        .tbodypart {
            border-bottom: 1px solid #ddd;
        }

        .regbill-table td,
        .regbill-table th {
            text-align: center;
            /* padding: 4px; */
        }

        .linestyle {
            border: none;
            border-bottom: 1px solid #666;
            margin-bottom: 4px;
        }

        .details {
            position: absolute;
            right: 0;
            left: auto;
            text-align: right;
            width: 25%;
        }

        .footer {
            bottom: 0;
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

        @media print {
            @page {
                size: A5;
                margin: 1rem;
            }

            body {
                margin: 0;
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
        <!-- Clinic Logo -->
        <div class="headingdiv">
            <div class="clinic-name">{{ $clinicDetails->clinic_name ?? 'Clinic Name' }}</div>
            <div class="clinic-address">
                {{ str_replace('<br>', ' ', $appointment->branch->clinic_address) ?? 'N/A' }},
                {{ $appointment->branch->city->city ?? 'N/A' }},
                {{ $appointment->branch->state->state ?? 'N/A' }},
                {{ $appointment->branch->country->country ?? 'N/A' }}-
                {{ $appointment->branch->pincode ?? 'N/A' }}<br>
                Phone: {{ $appointment->branch->clinic_phone ?? 'N/A' }}
                Email: {{ $appointment->branch->clinic_email ?? 'N/A' }}
            </div> <!-- Clinic Name -->
        </div>
    </div>
    <div class="pdfbody">
        <h3 class="heading">
            <center>Outstanding Payment Bill</center>
        </h3>
        <table class="info-table">
            <tr>
                <td style="width: 15%;"><strong>Patient ID: </strong></td>
                <td style="width: 35%;">{{ $patient->patient_id ?? 'N/A' }}</td>

                <td style="width: 50%; text-align:right;"><strong>Bill No: </strong>{{ $billDetails->bill_id ?? 'N/A' }}
                </td>
            </tr>

            <tr>
                <td style="width: 15%;"><strong>Name: </strong></td>
                <td style="width: 35%;">{{ str_replace('<br>', ' ', $patient->first_name) }}
                    {{ $patient->last_name ?? 'N/A' }}</td>

                <td style="width: 50%; text-align:right;"><strong>Bill Date: </strong>
                    {{ \Carbon\Carbon::parse($billDetails->created_at ?? now())->format('d-m-Y') }}
                </td>
            </tr>

            <tr>
                <td style="width: 15%;"><strong>Age: </strong></td>
                <td style="width: 35%;">
                    {{ isset($patient->date_of_birth) ? (preg_match('/(\d+) years/', $commonService->calculateAge($patient->date_of_birth), $matches) ? $matches[1] : 'N/A') : 'N/A' }}
                </td>
                <td style="width: 50%; text-align:right;"><strong>Gender: </strong>
                    @if ($patient->gender === 'M')
                        Male
                    @elseif($patient->gender === 'F')
                        Female
                    @elseif($patient->gender === 'O')
                        Others
                    @else
                        N/A
                    @endif
                </td>
            </tr>
        </table>

        <hr class="linestyle" />

        <h4 class="subheading">Bill Details</h4>
        @if (empty($billDetails))
            <p>Details not available.</p>
        @else
            <table class="regbill-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th colspan="2">Category</th>
                        <th>Amount ({{ $currency }})</th>
                    </tr>
                </thead>
                <tbody class="tbodypart">
                    <tr>
                        <td>1.</td>
                        <td colspan="2" style="text-align:left;">Outstanding Amount</td>
                        <td>{{ $billDetails->total_amount ?? '' }}</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr class="total">
                        <th colspan="3" style="text-align: right;">
                            <h4>Total</h4>
                        </th>
                        <th>
                            <h4>{{ $currency }}{{ number_format($billDetails->total_amount, 2) }}</h4>
                        </th>
                    </tr>
                    <tr>
                        <td rowspan="4" colspan="2" style="text-align: left;">
                            Mode of Payment :<br />
                            @if ($billDetails->gpay > 0)
                                <span class="text-bold">Gpay : {{ $billDetails->gpay }}</span>
                            @endif
                            <br>
                            @if ($billDetails->cash > 0)
                                <span class="text-bold">Cash : {{ $billDetails->cash }}</span>
                            @endif
                            <br>
                            @if ($billDetails->card > 0)
                                <span class="text-bold">Card : {{ $billDetails->card }}</span>
                            @endif
                        </td>

                        <td style="text-align: right;">Paid Amount</td>
                        <td>{{ $currency }}{{ $billDetails->paid_amount }}</td>
                    </tr>

                    @if ($billDetails->balance_given)
                        <tr>
                            <td style="text-align: right;">Balance Given</td>
                            <td>{{ $currency }}{{ $billDetails->balance_given }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endif
    </div>

    <div class="details">
        <span class="details-label"> Billed
            By<br>{{ str_replace('<br>', ' ', $billDetails->creator->name ?? 'Unknown') }}</span>
    </div>

    <div class="footer">
        <div class="footer_text1">Developed by Serieux</div>
        <div class="footer_text2">{{ date('d-m-Y h:i:s A') }}</div>
    </div>
</body>

</html>
