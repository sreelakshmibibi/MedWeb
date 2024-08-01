<?php

use App\Services\CommonService;
$commonService = new CommonService();

?>
<!DOCTYPE html>
<html>

<head>
    <title>Treatment Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }

        .header,
        .footer {
            position: fixed;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        .header {
            top: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .footer {
            bottom: 10px;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }

        .header img {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .clinic-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .clinic-address {
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .details-table th {
            background-color: #f4f4f4;
        }

        .inline-details {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .inline-details .detail {
            flex: 1;
            min-width: 200px;
            padding: 5px;
            box-sizing: border-box;
        }

        .details-label {
            font-weight: bold;
        }

        .text-wrap {
            white-space: pre-wrap;
            /* Preserve whitespace formatting */
        }

        .header,
        .footer {
            position: fixed;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ $clinicDetails->clinic_logo ?? '' }}" alt="Clinic Logo"> <!-- Clinic Logo -->
        <div class="clinic-name">{{ $clinicDetails->clinic_name ?? 'Clinic Name' }}</div> <!-- Clinic Name -->
        <div class="clinic-address">
            {{ str_replace('<br>', ' ', $appointment->branch->clinic_address) ?? 'N/A' }},
            {{ $appointment->branch->city->city ?? 'N/A' }},
            {{ $appointment->branch->state->state ?? 'N/A' }},
            {{ $appointment->branch->country->country ?? 'N/A' }},
            {{ $appointment->branch->pincode ?? 'N/A' }},
            phone:{{ $appointment->branch->clinic_phone ?? 'N/A' }}
        </div>
    </div>

    <div style="margin-top: 80px;">

        <!-- Treatment Details Title -->
        <h1>Treatment Details</h1>

        <!-- Patient and Appointment Details -->
        <div class="section">
            <div></div>
            <div class="inline-details">
                <div class="detail">
                    <span class="details-label">Patient ID:</span> {{ $appointment->patient->patient_id ?? 'N/A' }}
                </div>
                <div class="detail">
                    <span class="details-label">Patient Name:</span>
                    {{ str_replace('<br>', ' ', $appointment->patient->first_name) }}
                    {{ $appointment->patient->last_name ?? 'N/A' }}
                </div>
                <div class="detail">
                    <span class="details-label">Age:</span><?php $age = $commonService->calculateAge($appointment->patient->date_of_birth);
                    echo $age; ?>


                </div>
            </div>
            <div class="inline-details">

                <div class="detail">
                    <span class="details-label">Address:</span> {{ $appointment->patient->address1 ?? 'N/A' }},
                    {{ $appointment->patient->address2 ?? 'N/A' }},
                    {{ $appointment->patient->city->city ?? 'N/A' }},
                    {{ $appointment->patient->state->state ?? 'N/A' }},
                    {{ $appointment->patient->country->country ?? 'N/A' }},
                    {{ $appointment->patient->pincode ?? 'N/A' }}

                </div>
                <div class="detail">
                    <span class="details-label">Contact Number:</span> {{ $appointment->patient->phone ?? 'N/A' }}
                </div>

            </div>
            <div class="inline-details">
                <div class="detail">
                    <span class="details-label">Appointment ID:</span> {{ $appointment->app_id ?? 'N/A' }}
                </div>
                <div class="detail">
                    <span class="details-label">Appointment Date:</span> {{ $appointment->app_date ?? 'N/A' }}
                    {{ $appointment->app_time ?? 'N/A' }}
                </div>
                <div class="detail">
                    <span class="details-label">Doctor:</span>
                    {{ str_replace('<br>', ' ', $appointment->doctor->name) ?? 'N/A' }}
                </div>

            </div>

        </div>

        <!-- Tooth Examination Details -->
        <div class="section">
            <div></div>
            @if (!empty($toothExamDetails) && count($toothExamDetails) > 0)
                <table class="details-table">
                    <thead>
                        <tr>
                            <th>Tooth Name</th>
                            <th>Chief Complaint</th>
                            <th>Dental Examination</th>
                            <th>Diagnosis</th>
                            <th>Disease</th>
                            <th>Treatment</th>
                            <th>Surface Conditions</th>
                            <th>Treatment Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($toothExamDetails as $examination)
                            <tr>
                                <td>{{ $examination->teeth->teeth_name ?? '' }}</td>
                                <td>{{ $examination->chief_complaint ?? '' }}</td>
                                <td class="text-wrap">{{ $examination->dental_examination ?? '' }}</td>
                                <td>{{ $examination->diagnosis ?? '' }}</td>
                                <td>{{ $examination->disease->name ?? '' }}</td>
                                <td>{{ $examination->treatment->treat_name ?? '' }}</td>
                                <td>
                                    @if ($examination->lingualCondition)
                                        <div><strong>Lingual:</strong>
                                            {{ $examination->lingualCondition->condition ?? 'N/A' }}</div>
                                    @endif
                                    @if ($examination->labialCondition)
                                        <div><strong>Labial:</strong>
                                            {{ $examination->labialCondition->condition ?? 'N/A' }}</div>
                                    @endif
                                    @if ($examination->occlusalCondition)
                                        <div><strong>Occlusal:</strong>
                                            {{ $examination->occlusalCondition->condition ?? 'N/A' }}</div>
                                    @endif
                                    @if ($examination->distalCondition)
                                        <div><strong>Distal:</strong>
                                            {{ $examination->distalCondition->condition ?? 'N/A' }}</div>
                                    @endif
                                    @if ($examination->mesialCondition)
                                        <div><strong>Mesial:</strong>
                                            {{ $examination->mesialCondition->condition ?? 'N/A' }}</div>
                                    @endif
                                    @if ($examination->palatalCondition)
                                        <div><strong>Palatal:</strong>
                                            {{ $examination->palatalCondition->condition ?? 'N/A' }}</div>
                                    @endif
                                    @if ($examination->buccalCondition)
                                        <div><strong>Buccal:</strong>
                                            {{ $examination->buccalCondition->condition ?? 'N/A' }}</div>
                                    @endif
                                </td>
                                <td>{{ $examination->treatmentStatus->status ?? '' }}</td>
                                <td class="text-wrap">{{ $examination->remarks ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No tooth examination details available.</p>
            @endif
        </div>
        <!-- Tooth Examination Details -->
        {{-- <div class="section">
            <div></div>
            @if (!empty($toothExamDetails) && count($toothExamDetails) > 0)
                <table class="details-table">
                    <thead>
                        <tr>
                            <th>Tooth Name</th>
                            <th>Chief Complaint</th>
                            <th>Dental Examination</th>
                            <th>Diagnosis</th>
                            <th>Disease</th>
                            <th>Treatment</th>
                            <th>Treatment Status</th>
                            <th>Surface Conditions</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($toothExamDetails as $examination)
                            <tr>
                                <td>{{ $examination->teeth->teeth_name ?? '' }}</td>
                                <td>{{ $examination->chief_complaint ?? '' }}</td>
                                <td class="text-wrap">{{ $examination->dental_examination ?? '' }}</td>
                                <td>{{ $examination->diagnosis ?? '' }}</td>
                                <td>{{ $examination->disease->name ?? '' }}</td>
                                <td>{{ $examination->treatment->treat_name ?? '' }}</td>
                                <td>{{ $examination->treatmentStatus->status ?? '' }}</td>
                                <td>
                                    <!-- Display surface conditions in a single column -->
                                    <div><strong>Lingual:</strong>
                                        {{ $examination->lingualCondition->condition ?? 'N/A' }}</div>
                                    <div><strong>Labial:</strong>
                                        {{ $examination->labialCondition->condition ?? 'N/A' }}</div>
                                    <div><strong>Occlusal:</strong>
                                        {{ $examination->occlusalCondition->condition ?? 'N/A' }}</div>
                                    <div><strong>Distal:</strong>
                                        {{ $examination->distalCondition->condition ?? 'N/A' }}</div>
                                    <div><strong>Mesial:</strong>
                                        {{ $examination->mesialCondition->condition ?? 'N/A' }}</div>
                                    <div><strong>Palatal:</strong>
                                        {{ $examination->palatalCondition->condition ?? 'N/A' }}</div>
                                    <div><strong>Buccal:</strong>
                                        {{ $examination->buccalCondition->condition ?? 'N/A' }}</div>
                                </td>
                                <td class="text-wrap">{{ $examination->remarks ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No tooth examination details available.</p>
            @endif
        </div> --}}


    </div>

    <!-- Footer -->
    <div class="footer">
        <span>Download Date and Time: {{ date('Y-m-d H:i:s') }}</span>
        <span>Developed by Serieux</span>
    </div>
</body>

</html>
