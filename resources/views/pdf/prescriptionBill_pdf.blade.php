<?php
use App\Services\CommonService;
$commonService = new CommonService();
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Prescription</title>
    <style>
        body {
            /* font-family: Arial, sans-serif; */
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
        .prescription-table {
            width: 100%;
            border-collapse: collapse;
            /* margin-bottom: 20px; */
            margin-bottom: 4px;
        }

        .info-table td,
        .prescription-table td,
        .prescription-table th {
            text-align: left;
            vertical-align: top;
        }

        .info-table td {
            border: none;
        }

        .prescription-table {
            border: 1px solid #ddd;
            border: none;
        }

        .prescription-table thead,
        .tbodypart {
            border-bottom: 1px solid #ddd;
        }

        .prescription-table td,
        .prescription-table th {
            /* border: 1px solid #ddd; */
            text-align: center;
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

        @media print {
            @page {
                size: A5;
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
    <img src="{{ $clinicLogo }}" alt="Clinic Logo" height="40px">
        {{-- <img src="'{{ asset('storage/') }}/' + {{ $clinicDetails->clinic_logo }}" alt="Clinic Logo" width="50px"> --}}
        <!-- Clinic Logo -->
        <div class="headingdiv">
            <div class="clinic-name">{{ $clinicDetails->clinic_name ?? 'Clinic Name' }}</div> <!-- Clinic Name -->
            <div class="clinic-address">
                {{ str_replace('<br>', ' ', $appointment->branch->clinic_address) ?? 'N/A' }},
                {{ $appointment->branch->city->city ?? 'N/A' }},
                {{ $appointment->branch->state->state ?? 'N/A' }},
                {{ $appointment->branch->country->country ?? 'N/A' }}-
                {{ $appointment->branch->pincode ?? 'N/A' }}<br>
                Phone: {{ $appointment->branch->clinic_phone ?? 'N/A' }}
                Email: {{ $appointment->branch->clinic_email ?? 'N/A' }}
            </div>
        </div>
    </div>
    <div class="pdfbody">
        <h3 class="heading">
            <center>Medicine Bill</center>
        </h3>

        <table class="info-table">
            <tr>
                <td style="width: 15%;"><strong>Patient ID: </strong></td>
                <td style="width: 35%;">{{ $patient->patient_id ?? 'N/A' }}</td>

                <td style="width: 50%; text-align:right;"><strong>Bill
                        No: </strong>{{ $patientPrescriptionBilling->bill_id }}
                </td>
            </tr>

            <tr>
                <td style="width: 15%;"><strong>Name: </strong></td>
                <td style="width: 35%;">{{ str_replace('<br>', ' ', $patient->first_name) }}
                    {{ $patient->last_name ?? 'N/A' }}</td>

                <td style="width: 50%; text-align:right;"><strong>Bill Date: </strong>
                    {{ \Carbon\Carbon::parse($patientPrescriptionBilling->created_at)->format('d-m-Y') }}
                </td>
            </tr>

            <tr>
                <td style="width: 15%;"><strong>Age: </strong></td>
                <td style="width: 35%;">
                    {{-- {{ isset($patient->date_of_birth) ? $commonService->calculateAge($patient->date_of_birth) : 'N/A' }} --}}
                    {{ isset($patient->date_of_birth) ? (preg_match('/(\d+) years/', $commonService->calculateAge($patient->date_of_birth), $matches) ? $matches[1] : 'N/A') : 'N/A' }}
                </td>
                <td style="width: 50%; text-align:right;"><strong>App_Date: </strong>
                    {{ isset($appointment->app_date) ? \Carbon\Carbon::parse($appointment->app_date)->format('d-m-Y') : 'N/A' }}
                </td>
            </tr>

            <tr>
                <td style="width: 15%;"><strong>Gender: </strong></td>
                <td style="width: 35%;">
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
                <td style="width: 50%; text-align:right;"><strong>Doctor: </strong>
                    {{ str_replace('<br>', ' ', $appointment->doctor->name) ?? 'N/A' }}
                </td>
            </tr>
        </table>

        <hr class="linestyle" />


        {{-- <h4 class="subheading">Patient Information</h4>
        <table class="info-table">
            <tr>
                <td style="width: 15%;"><strong>Patient ID:</strong></td>
                <td style="width: 35%;">{{ $patient->patient_id ?? 'N/A' }}</td>

                <td style="width: 15%;"><strong>Name:</strong></td>
                <td style="width: 35%;">{{ str_replace('<br>', ' ', $patient->first_name) }}
                    {{ $patient->last_name ?? 'N/A' }}
                </td>
            </tr>

            <tr>
                <td><strong>Age:</strong></td>
                <td>
                    {{ isset($patient->date_of_birth) ? $commonService->calculateAge($patient->date_of_birth) : 'N/A' }}
                </td>

                <td><strong>Gender:</strong></td>
                <td>
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

            <tr>
                <td><strong>Doctor:</strong></td>
                <td>{{ str_replace('<br>', ' ', $appointment->doctor->name) ?? 'N/A' }}</td>

                <td><strong>Date:</strong></td>
                <td style="vertical-align: bottom;">
                    {{ isset($appointment->app_date) ? \Carbon\Carbon::parse($appointment->app_date)->format('d-m-Y') : 'N/A' }}
                </td>
            </tr>
        </table> --}}

        <h4 class="subheading">Bill Details</h4>
        @if ($billDetails->isEmpty())
            <p>No Prescription available.</p>
        @else
            <table class="prescription-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine</th>
                        <th>Quantity</th>
                        <th>Unit Cost ({{ $clinicDetails->currency }})</th>
                        <th>SubTotal ({{ $clinicDetails->currency }})</th>
                    </tr>
                </thead>
                <tbody class="tbodypart">
                    <?php $i = 0; ?>
                    @foreach ($billDetails as $billDetail)
                        <?php $medicine = null; ?>
                        <tr>
                            <td>{{ ++$i }}.</td>
                            <?php if ($billDetail->medicine_id != null) {
                                $medicine = $billDetail->medicine->med_name;
                            } ?>
                            <td style="text-align:left;">{{ $medicine ?? '' }}</td>
                            <td>{{ $billDetail->quantity ?? '' }}</td>
                            <td>{{ $billDetail->cost ?? '' }}</td>
                            <td>{{ $billDetail->amount ?? '' }}</td>

                        </tr>
                    @endforeach
                </tbody>

                <tbody class="tbodypart">
                    <tr>
                        <td colspan="4" style="text-align: right;">Sub_Total amount</td>
                        <td>
                            {{ $currency }}{{ number_format($patientPrescriptionBilling->prescription_total_amount, 3) }}
                            {{-- <input type="text" readonly name="treatmenttotal" id="treatmenttotal"
                                class="form-control text-center"
                                value="{{ number_format($patientPrescriptionBilling->prescription_total_amount, 3) }}"> --}}
                        </td>
                    </tr>

                    @if ($patientPrescriptionBilling->tax_percentile != 0 && $patientPrescriptionBilling->tax != 0)
                        <tr>
                            <td colspan="4" style="text-align: right;">Tax
                                ({{ $patientPrescriptionBilling->tax_percentile }}%)
                            </td>

                            <td>{{ number_format($patientPrescriptionBilling->tax, 3) }}</td>
                        </tr>
                    @endif
                </tbody>

                <tbody>
                    <tr class="total">
                        <th colspan="4" style="text-align: right;">
                            <h4>Total</h4>
                        </th>
                        <th>
                            <h4>{{ $currency }}{{ number_format($patientPrescriptionBilling->amount_to_be_paid, 2) }}
                            </h4>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2" rowspan="4" style="text-align: left;">
                            Mode of Payment :
                            @if ($patientPrescriptionBilling->gpay > 0)
                                <span class="text-bold">Gpay : {{ $patientPrescriptionBilling->gpay }}</span>
                            @endif
                            <br>
                            @if ($patientPrescriptionBilling->cash > 0)
                                <span class="text-bold">Cash : {{ $patientPrescriptionBilling->cash }}</span>
                            @endif
                            <br>
                            @if ($patientPrescriptionBilling->card > 0)
                                <span class="text-bold">Card : {{ $patientPrescriptionBilling->card }}</span>
                            @endif
                        </td>

                        <td colspan="2" style="text-align: right;">Paid Amount</td>
                        <td>{{ $currency }}{{ $patientPrescriptionBilling->amount_paid }}
                        </td>
                    </tr>

                    @if ($patientPrescriptionBilling->balance_given)
                        <tr>
                            <td colspan="2" style="text-align: right;">Balance Given</td>
                            <td>{{ $currency }}{{ $patientPrescriptionBilling->balance_given }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endif
    </div>

    <div class="details">
        <span class="details-label"> Billed
            By<br>{{ str_replace('<br>', ' ', $patientPrescriptionBilling->createdBy->name ?? 'Unknown') ?? 'N/A' }}</span>
    </div>

    <div class="footer">
        <div class="footer_text1">Developed by Serieux</div>
        <div class="footer_text2">{{ date('d-m-Y h:i:s A') }}</div>
    </div>
</body>

</html>
