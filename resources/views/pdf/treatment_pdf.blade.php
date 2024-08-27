<?php

use App\Services\CommonService;
$commonService = new CommonService();
use App\Models\TeethRow;
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Treatment Details</title>
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
            margin-top: 65px;
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
        }

        .teethname {
            margin-bottom: 2px;
            font-size: 11px;
        }

        .slno {
            text-decoration: none;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            text-align: left;
            vertical-align: top;
        }

        .info-table td {
            border: none;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            /* margin-bottom: 20px; */
        }

        .details-table th,
        .details-table td {
            border: 1px solid #ddd;
            /* padding: 8px; */
            text-align: center;
            padding: 2px;
        }

        .details-table th {
            background-color: #f4f4f4;
            text-align: center;
        }

        .details-label {
            font-weight: bold;
        }

        .text-wrap {
            white-space: pre-wrap;
            /* Preserve whitespace formatting */
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
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        {{-- <img src="{{ $clinicDetails->clinic_logo ?? '' }}" alt="Clinic Logo"> <!-- Clinic Logo --> --}}
        <div class="clinic-name">{{ $clinicDetails->clinic_name ?? 'Clinic Name' }}</div> <!-- Clinic Name -->
        <div class="clinic-address">
            {{ str_replace('<br>', ' ', $appointment->branch->clinic_address) ?? 'N/A' }},
            {{ $appointment->branch->city->city ?? 'N/A' }},
            {{ $appointment->branch->state->state ?? 'N/A' }},
            {{ $appointment->branch->country->country ?? 'N/A' }}-
            {{ $appointment->branch->pincode ?? 'N/A' }}<br />
            Phone: {{ $appointment->branch->clinic_phone ?? 'N/A' }}
            Email: {{ $appointment->branch->clinic_email ?? 'N/A' }}
        </div>
    </div>

    <div class="pdfbody">
        <h3 class="heading">
            <center>Treatment Report</center>
        </h3>
        <table class="info-table" style="border-bottom: 1px solid #666;">
            <tr>
                <td colspan="2" style="width: 33%; border-right:1px solid #666;">
                    <strong>
                        {{ str_replace('<br>', ' ', $appointment->patient->first_name) }}
                        {{ $appointment->patient->last_name ?? 'N/A' }}
                    </strong>
                </td>
                <td style="width: 10%; padding-left:8px;"><strong>Patient ID:</strong></td>
                <td style="width: 23%; border-right:1px solid #666;">{{ $appointment->patient->patient_id ?? 'N/A' }}
                </td>
                <td style="width: 33%; text-align:right;"><strong>Appoint_Date:</strong></td>
            </tr>
            <tr>
                <td style="width: 8%;">Age:</td>
                <td style="width: 25%; border-right:1px solid #666;">
                    {{ isset($appointment->patient->date_of_birth) ? (preg_match('/(\d+) years/', $commonService->calculateAge($appointment->patient->date_of_birth), $matches) ? $matches[1] : 'N/A') : 'N/A' }}
                </td>
                <td style="width: 10%; padding-left:8px;">Appoint_ID:</td>
                <td style="width: 23%; border-right:1px solid #666;">{{ $appointment->app_id ?? 'N/A' }}</td>
                <td style="width: 33%; text-align:right;">
                    {{ \Carbon\Carbon::parse($appointment->app_date ?? '')->format('d-m-Y') . ' ' . (isset($appointment->app_time) ? \Carbon\Carbon::parse($appointment->app_time)->format('g:i A') : 'N/A') }}
                </td>
            </tr>
            <tr>
                <td style="width: 8%;">Gender:</td>
                <td style="width: 25%; border-right:1px solid #666;">
                    @if ($appointment->patient->gender === 'M')
                        Male
                    @elseif($appointment->patient->gender === 'F')
                        Female
                    @elseif($appointment->patient->gender === 'O')
                        Others
                    @else
                        N/A
                    @endif
                </td>
                <td style="width: 10%; padding-left:8px;"><strong>Doctor:</strong></td>
                <td style="width: 23%; border-right:1px solid #666;">
                    <strong>{{ str_replace('<br>', ' ', $appointment->doctor->name) ?? 'N/A' }}</strong>
                </td>
                <td style="width: 33%; text-align:right;">
                    <strong>Contact No:</strong>{{ $appointment->patient->phone ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
        </table>

        <h4 class="subheading">Treatment Summary</h4>
        @if (!empty($toothExamDetails) && count($toothExamDetails) > 0)
            <?php $i = 0; ?>
            @foreach ($toothExamDetails as $examination)
                <h5 class="teethname">{{ ++$i }}.
                    @if ($examination->teeth)
                        Teeth No. {{ $examination->teeth->teeth_name ?? '' }}
                    @elseif ($examination->row_id)
                        {{-- Here we need to use your existing mapping or description logic --}}
                        @switch($examination->row_id)
                            @case(TeethRow::Row1)
                                Row: {{ TeethRow::Row_1_Desc }}
                            @break

                            @case(TeethRow::Row2)
                                Row: {{ TeethRow::Row_2_Desc }}
                            @break

                            @case(TeethRow::Row3)
                                Row: {{ TeethRow::Row_3_Desc }}
                            @break

                            @case(TeethRow::Row4)
                                Row: {{ TeethRow::Row_4_Desc }}
                            @break
                            @case(TeethRow::RowAll)
                                Row: {{ TeethRow::Row_All_Desc }};
                            @break
                            @default
                                Unknown Row
                        @endswitch
                    @endif
                </h5>
                <table class="info-table">
                    <tr>
                        <td style="width: 15%;"><strong>Chief Complaint:</strong></td>
                        <td style="width: 80%;">{{ $examination->chief_complaint ?? '' }}</td>
                    </tr>
                    @if ($examination->teeth)
                        <tr>
                            <td style="width: 15%;"><strong>Surface Conditions:</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table class="details-table">
                                    <thead>
                                        <tr>
                                            @if ($examination->lingualCondition)
                                                <th>Lingual</th>
                                            @endif
                                            @if ($examination->labialCondition)
                                                <th>Labial</th>
                                            @endif
                                            @if ($examination->occlusalCondition)
                                                <th>Occulusal</th>
                                            @endif
                                            @if ($examination->distalCondition)
                                                <th>Distal</th>
                                            @endif
                                            @if ($examination->mesialCondition)
                                                <th>Mesial</th>
                                            @endif
                                            @if ($examination->palatalCondition)
                                                <th>Palatal</th>
                                            @endif
                                            @if ($examination->buccalCondition)
                                                <th>Buccal</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @if ($examination->lingualCondition)
                                                <td>{{ $examination->lingualCondition->condition ?? 'N/A' }}</td>
                                            @endif

                                            @if ($examination->labialCondition)
                                                <td>{{ $examination->labialCondition->condition ?? 'N/A' }}</td>
                                            @endif

                                            @if ($examination->occlusalCondition)
                                                <td>{{ $examination->occlusalCondition->condition ?? 'N/A' }}</td>
                                            @endif

                                            @if ($examination->distalCondition)
                                                <td>{{ $examination->distalCondition->condition ?? 'N/A' }}</td>
                                            @endif

                                            @if ($examination->mesialCondition)
                                                <td>{{ $examination->mesialCondition->condition ?? 'N/A' }}</td>
                                            @endif

                                            @if ($examination->palatalCondition)
                                                <td>{{ $examination->palatalCondition->condition ?? 'N/A' }}</td>
                                            @endif

                                            @if ($examination->buccalCondition)
                                                <td>{{ $examination->buccalCondition->condition ?? 'N/A' }}</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td style="width: 15%;"><strong>Dental Examination:</strong></td>
                        <td style="width: 80%;">{{ $examination->dental_examination ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 15%;"><strong>Diagnosis:</strong></td>
                        <td style="width: 80%;">{{ $examination->diagnosis ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 15%;"><strong>Disease:</strong></td>
                        <td style="width: 80%;">{{ $examination->disease->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 15%;"><strong>Treatment:</strong></td>
                        <td style="width: 80%;">{{ $examination->treatment->treat_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 15%;"><strong>Treatment Status:</strong></td>
                        <td style="width: 80%;">{{ $examination->treatmentStatus->status ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 15%;"><strong>Remarks:</strong></td>
                        <td style="width: 80%;">{{ $examination->remarks ?? '-' }}</td>
                    </tr>

                </table>
            @endforeach
        @else
            <p>No tooth examination details available.</p>
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
