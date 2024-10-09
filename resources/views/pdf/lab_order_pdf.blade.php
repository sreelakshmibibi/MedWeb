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
        @foreach($orders as $order)
    <?php 
        $patient = $order->toothExamination->patient;
        $name = str_replace('<br>', ' ', $patient->first_name) . ' ' . $patient->last_name;

        $commonService = new CommonService();
        $age = $commonService->calculateAge($patient->date_of_birth);
        $gender = match ($patient->gender) {
            'M' => 'Male',
            'F' => 'Female',
            'O' => 'Other',
            default => 'Unknown',
        };
    ?>
    <div class="patient-section" style="border: 1px solid #ccc; margin-bottom: 20px; padding: 10px;">
        <h2>Patient Information</h2>
        <table>
            <tr>
                <td><strong>No:</strong> {{ $loop->iteration }}</td>
                <td><strong>Name:</strong> {{ $name }}</td>
                <td><strong>Patient ID:</strong> {{ $order->patient_id }}</td>
            </tr>
            <tr>
                <td><strong>Age:</strong> {{ $age }}</td>
                <td><strong>Gender:</strong> {{ $gender }}</td>
            </tr>
        </table>
        
        <h3>Details</h3>
        <table>
            <tr>
                <td><strong>Tooth:</strong> {{ $order->toothExamination->tooth_id }}</td>
                <td colspan="2"><strong>Plan:</strong> {{ $order->toothExamination->treatmentPlan->plan }}</td>
                <td><strong>Status:</strong> {{ App\Models\OrderPlaced::statusToWords($order->order_status) }}</td>
            </tr>
            <tr>
                <td><strong>Shade:</strong> {{ $order->toothExamination->shade_id != null ? $order->toothExamination->shade->shade_name : 'N/A' }}</td>
                <td><strong>Metal Trail:</strong> {{ $order->toothExamination->metal_trial != null ? $order->toothExamination->metal_trial : 'N/A' }}</td>
                <td><strong>Bisq Trial:</strong> {{ $order->toothExamination->bisq_trail != null ? $order->toothExamination->bisq_trail : 'N/A' }}</td>
                <td><strong>Finish:</strong> {{ $order->toothExamination->finish != null ? $order->toothExamination->finish : 'N/A' }}</td>
                
            </tr>
            <?php 
                if (in_array($order->toothExamination->tooth_id, ['11', '12', '13', '21', '22', '23', '31', '32', '33', '41', '42', '43', '51', '52', '53', '61', '62', '63', '71', '72', '73', '81', '82', '83'])) { ?>
                    <tr>
                        <td><strong>Upper Shade:</strong> {{ $order->toothExamination->upper_shade != null ? $order->toothExamination-> upper_shade: 'N/A' }}</td>
                        <td><strong>Middle Shade:</strong> {{ $order->toothExamination->middle_shade != null ? $order->toothExamination-> middle_shade: 'N/A' }}</td>
                        <td><strong>Lower Shade:</strong> {{ $order->toothExamination->lower_shade != null ? $order->toothExamination-> lower_shade: 'N/A' }}</td>
                        
                    </tr>
            <?php } ?>
            
            
           
            <tr>
                <td><strong>Instructions:</strong> {{ $order->toothExamination->instructions }}</td>
            </tr>
        </table>
    </div>
@endforeach

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
