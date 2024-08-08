<?php
use App\Services\CommonService;
$commonService = new CommonService();
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Patient ID Card</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #000;
            padding: 0;
            margin: 0;
            /* font-size: 14px; */
            font-size: 10px;
            border: 1px solid #666;
            border-radius: 10px;
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
            padding-top: 1px;
        }

        .header img {
            vertical-align: middle;
            /* width: 50px; */
            margin-bottom: 0;
        }

        .headingdiv {
            margin-top: 0;
        }

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
            /* margin-top: 65px; */
            position: relative;
            /* height: 75%; */
            margin-top: 100px;
        }

        h1,
        h2 {
            color: #333;
        }

        .heading {
            font-size: 13px;
            margin-bottom: 2px;
        }

        .subheading {
            margin-bottom: 2px;
            /* text-decoration: underline; */
            /* font-size: 15px; */
            font-size: 12px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            /* margin-bottom: 20px; */
            padding: 0 8px;
        }

        .info-table td {
            text-align: left;
            vertical-align: top;
            border: none;
            padding-bottom: 8px;
        }

        .details {
            position: absolute;
            right: 0;
            left: auto;
            text-align: right;
            width: 25%;
        }

        .footer {
            bottom: 12px;
            width: 100%;
            border-top: 1px solid #666;
            padding-top: 4px;
            padding-bottom: 2px;
            color: #666;
            font-size: 8px;
        }

        .footer_text1 {
            text-align: left;
            float: left;
            padding-left: 4px;
        }

        .footer_text2 {
            text-align: right;
            float: right;
            padding-right: 4px;
        }

        @media print {
            @page {
                size: A6;
                /* Set paper size to A5 */
                margin: 20mm;
                /* margin: 1rem; */
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
        <!-- Clinic Logo -->
        <img src="{{ $clinicLogo }}" alt="Clinic Logo" height="40px">

        <div class="headingdiv">
            <!-- Clinic Name -->
            <div class="clinic-name">{{ $clinicDetails->clinic_name ?? 'Clinic Name' }}</div>
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
            <center>Patient ID Card</center>
        </h3>

        <table class="info-table">
            <tr>
                <td style="width: 15%;">
                    <h4 class="subheading">Patient ID:</h4>
                </td>
                <td style="width: 45%;">
                    <h4 class="subheading">{{ $patient->patient_id ?? 'N/A' }}</h4>
                </td>
                <td style="width:40%; text-align:right;">
                    <h4 class="subheading">Reg. Date:
                        {{ \Carbon\Carbon::parse($patient->created_at)->format('d-m-Y') ?? 'N/A' }}
                    </h4>
                </td>
            </tr>

            <tr>
                <td><strong>Name:</strong></td>
                <td>{{ str_replace('<br>', ' ', $patient->first_name) }}
                    {{ $patient->last_name ?? 'N/A' }}</td>
                <td style="text-align:right;"><strong>Age:</strong>
                    {{ isset($appointment->patient->date_of_birth) ? (preg_match('/(\d+) years/', $commonService->calculateAge($appointment->patient->date_of_birth), $matches) ? $matches[1] : 'N/A') : 'N/A' }}
                </td>
            </tr>

            <tr>
                <td><strong>Phone No.:</strong></td>
                <td>{{ $patient->phone }}</td>
                <td style="text-align:right;"><strong>Gender:</strong>
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
                <td><strong>Address:</strong></td>
                <td> {{ $patient->address1 ?? 'N/A' }}<br />
                    {{ $patient->address2 ?? 'N/A' }},
                    {{ $patient->city->city ?? 'N/A' }},</br />
                    {{ $patient->state->state ?? 'N/A' }},<br />
                    {{ $patient->country->country ?? 'N/A' }}-
                    {{ $patient->pincode ?? 'N/A' }}</td>
                <td style=" text-align:right;"><strong>Blood
                        Group:</strong> {{ $patient->blood_group ?? '-' }}</td>
            </tr>
        </table>
    </div>

    {{-- <div class="details">
        <span class="details-label">{{ str_replace('<br>', ' ', $appointment->doctor->name) ?? 'N/A' }}</span>
    </div> --}}

    <div class="footer">
        <div class="footer_text1">Developed by Serieux</div>
        <div class="footer_text2">{{ date('d-m-Y h:i:s A') }}</div>
    </div>
</body>

</html>
