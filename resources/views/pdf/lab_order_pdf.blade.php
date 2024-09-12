<?php

use App\Services\CommonService;
$commonService = new CommonService();
use App\Models\TeethRow;
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Lab Order Details</title>
    <style>
        /* @font-face {
            font-family: 'DejaVuSans';
            src: url('DejaVuSans.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        } */

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
        {{-- <img src="{{ $clinicDetails->clinic_logo ?? '' }}" alt="Clinic Logo"> <!-- Clinic Logo --> --}}
        <div class="clinic-name">{{ $clinicDetails->clinic_name ?? 'Clinic Name' }}</div> <!-- Clinic Name -->
        <div class="clinic-address">
            {{ str_replace('<br>', ' ', $branch->clinic_address) ?? 'N/A' }},
            {{ $branch->city->city ?? 'N/A' }},
            {{ $branch->state->state ?? 'N/A' }},
            {{ $branch->country->country ?? 'N/A' }}-
            {{ $branch->pincode ?? 'N/A' }}<br />
            Phone: {{ $branch->clinic_phone ?? 'N/A' }}
            Email: {{ $branch->clinic_email ?? 'N/A' }}
        </div>
    </div>

    <div class="pdfbody">
        <h3 class="heading">
            <center>Lab Order Details</center>
            <table class="info-table">
            <tr>
                <td style="width: 15%;"><strong>Lab name: </strong></td>
                <td style="width: 35%;">{{ $lab->lab_name ?? 'N/A' }}</td>

                <td style="width: 50%; text-align:right;"><strong>Technician
                        </strong>{{ $lab->name }}
                </td>
            </tr>

            <tr>
                <td style="width: 15%;"><strong>Order Date: </strong></td>
                <td style="width: 35%;">
                    {{-- {{ isset($patient->date_of_birth) ? $commonService->calculateAge($patient->date_of_birth) : 'N/A' }} --}}
                    {{ $orderDate }}
                </td>
                <td style="width: 50%; text-align:right;"><strong>Expected Delivery: </strong>
                    {{ $expectedDelivery }}
                </td>
            </tr>
            <tr>
                <td style="width: 15%;"><strong>Lab Address: </strong></td>
                <td colspan="2" >
                    {{-- {{ isset($patient->date_of_birth) ? $commonService->calculateAge($patient->date_of_birth) : 'N/A' }} --}}
                    {{ $lab->lab_address }}
                </td>
                
            </tr>
        </table>
            <table class="treatmentbill-table" style="border:1;">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Patient</th>
                    <th class="text-center">Age - Gender</th>
                    <th class="text-center">Tooth</th>
                    <th class="text-center">Plan</th>
                    <th class="text-center">Shade</th>
                    <th class="text-center">Instructions</th>
                    <th class="text-center">Status</th>

                </tr></thead>
                <tbody>
                    <?php $i = 0;?>
                    @foreach($orders as $order)
                    <?php $i++; ?>
                        <tr>
                            <td class="text-center">
                                {{ $i }}
                            </td>
                            <td class="text-center">
                                <?php  
                                    $patient = $order->toothExamination->patient;
                                    $name = str_replace('<br>', ' ', $patient->first_name) . ' ' . $patient->last_name;
                                    echo $order->patient_id . " - " .$name;
                                ?>
                            </td>
                            
                            <td class="text-center">
                               <?php $commonService = new CommonService();
                               
                                $age = $commonService->calculateAge($order->toothExamination->patient->date_of_birth);
                               
                                $gender = match ($order->toothExamination->patient->gender) {
                                            'M' => 'Male',
                                            'F' => 'Female',
                                            'O' => 'Other',
                                            default => 'Unknown',
                                        };
                                echo $age ."<br>" . $gender;
                                ?>
                            </td>
                            <td class="text-center">
                                {{ $order->toothExamination->tooth_id }}
                            </td>
                            <td class="text-center">
                                {{ $order->toothExamination->treatmentPlan->plan }}
                            </td>
                            <td class="text-center">
                                {{ $order->toothExamination->shade_id != null ? $order->toothExamination->shade->shade_name : null }}
                            </td>
                            <td class="text-center">
                                {{ $order->toothExamination->instructions }}
                            </td>
                            <td class="text-center">
                                {{ App\Models\OrderPlaced::statusToWords( $order->order_status) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </h3>
        
    </div>
    <div class="details">
        <span class="details-label">Order Placed by<br>{{ str_replace('<br>', ' ',Auth::user()->name ?? 'Unknown') ?? 'N/A' }}</span>
    </div>

    <div class="footer">
        <div class="footer_text1">Developed by Serieux</div>
        <div class="footer_text2">{{ date('d-m-Y h:i:s A') }}</div>
    </div>
</body>

</html>
