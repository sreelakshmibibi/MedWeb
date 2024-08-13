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
            font-family: Arial, sans-serif;
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

        .subheading {
            margin-bottom: 2px;
            text-decoration: underline;
            /* font-size: 15px; */
            font-size: 12px;
        }

        .info-table,
        .prescription-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        }

        .prescription-table td,
        .prescription-table th {
            border: 1px solid #ddd;
            text-align: center;
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
        <h4 class="subheading">Patient Information</h4>
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
        </table>

        <h4 class="subheading">Prescriptions</h4>
        @if ($prescriptions->isEmpty())
            <p>No prescriptions available.</p>
        @else
            <table class="prescription-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine</th>
                        <th>Dose</th>
                        <th>Frequency</th>
                        <th>Days</th>
                        <th>Advice</th>
                        <th>Route</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($prescriptions as $prescription)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td style="text-align:left;">{{ $prescription->medicine->med_name ?? '' }}</td>
                            <td>{{ $prescription->dose ?? '' }} {{ $prescription->dose_unit ?? '' }}</td>
                            <td>{{ $prescription->dosage->dos_name ?? '' }}</td>
                            <td>{{ $prescription->duration ?? '' }}</td>
                            <td>{{ $prescription->advice ?? '' }}</td>
                            <td>{{ $prescription->route->route_name ?? '' }}</td>
                            <td>{{ $prescription->remark ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="details">
        <span class="details-label">{{ str_replace('<br>', ' ', $appointment->doctor->name) ?? 'N/A' }}</span>
    </div>

    <div class="footer">
        <div class="footer_text1">Developed by Serieux</div>
        <div class="footer_text2">{{ date('d-m-Y h:i:s A') }}</div>
    </div>
</body>

</html>
