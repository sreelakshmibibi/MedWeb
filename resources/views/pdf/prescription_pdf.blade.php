<?php

use App\Services\CommonService;
$commonService = new CommonService();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Prescription</title>
    <style>
        /* Add any styling you need for the PDF */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1,
        h2 {
            color: #333;
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
            padding: 8px;
            text-align: left;
        }

        .info-table td {
            border: none;
            /* No border for patient info table */
        }

        .prescription-table {
            border: 1px solid #ddd;
            /* Add border to prescription table */
        }

        .prescription-table td,
        .prescription-table th {
            border: 1px solid #ddd;
            /* Add border to cells */
        }

        .prescription-table th {
            border-bottom: 2px solid #333;
            /* Darker border for header cells */
        }
    </style>
</head>

<body>
    <h1>Prescription Details</h1>

    <h2>Patient Information</h2>
    <table class="info-table">
        <tr>
            <td><strong>Patient ID:</strong></td>
            <td colspan="2">{{ $patient->patient_id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Name:</strong></td>
            <td colspan="2">{{ str_replace('<br>', ' ', $patient->first_name) }} {{ $patient->last_name ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <td><strong>Age:</strong></td>
            <td colspan="2">
                {{ isset($patient->date_of_birth) ? $commonService->calculateAge($patient->date_of_birth) : 'N/A' }}
            </td>
        </tr>
        <tr>
            <td><strong>Gender:</strong></td>
            <td colspan="2">
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
            <td colspan="2">{{ $patient->address1 ?? 'N/A' }}, {{ $patient->city->city ?? 'N/A' }},
                {{ $patient->state->state ?? 'N/A' }}, {{ $patient->country->country ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Appointment Date:</strong></td>
            <td colspan="2">
                {{ isset($appointment->app_date) ? \Carbon\Carbon::parse($appointment->app_date)->format('d-m-Y') : 'N/A' }}
            </td>
        </tr>
        <tr>
            <td><strong>Doctor:</strong></td>
            <td colspan="2">{{ str_replace('<br>', ' ', $appointment->doctor->name) ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Branch:</strong></td>
            <td colspan="2">{{ str_replace('<br>', ' ', $appointment->branch->clinic_address) ?? 'N/A' }},
                {{ $appointment->branch->city->city ?? 'N/A' }},
                {{ $appointment->branch->state->state ?? 'N/A' }},
                {{ $appointment->branch->country->country ?? 'N/A' }},
                {{ $appointment->branch->pincode ?? 'N/A' }},
                phone: {{ $appointment->branch->clinic_phone ?? 'N/A' }}</td>
        </tr>
    </table>

    <h2>Prescriptions</h2>
    @if ($prescriptions->isEmpty())
        <p>No prescriptions available.</p>
    @else
        <table class="prescription-table">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Dose</th>
                    <th>Frequency</th>
                    <th>Days</th>
                    <th>Advice</th>
                    <th>Route</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prescriptions as $prescription)
                    <tr>
                        <td>{{ $prescription->medicine->med_name ?? '' }}</td>
                        <td>{{ $prescription->dose ?? '' }}</td>
                        <td>{{ $prescription->dosage->dos_name ?? '' }}</td>
                        <td>{{ $prescription->duration ?? '' }}</td>
                        <td>{{ $prescription->advice ?? '' }}</td>
                        <td>{{ $prescription->route->route_name ?? '' }}</td>
                        <td>{{ $prescription->remark ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>

</html>
