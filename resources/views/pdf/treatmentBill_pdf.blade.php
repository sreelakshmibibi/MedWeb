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

        <h4 class="subheading">Bill</h4>
        @if ($billDetails->isEmpty())
            <p>No Treatment available.</p>
        @else
            <table class="prescription-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Treatment</th>
                        <th>Quantity</th>
                        <th>Unit Cost ({{$clinicDetails->currency}})</th>
                        <th>Discount(%)</th>
                        <th>SubTotal ({{$clinicDetails->currency}})</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($billDetails as $billDetail)
                    <?php $treatment  = null ?>
                        <tr>
                            <td>{{ ++$i }}</td>
                            <?php if ($billDetail->treatment_id != null ) { 
                                $treatment = $billDetail->treatment->treat_name;
                                } else {
                                    $treatment = $billDetail->consultation_registration; 
                                } ?> 
                            <td style="text-align:left;">{{ $treatment ?? '' }}</td>
                            <td>{{ $billDetail->quantity ?? '' }}</td>
                            <td>{{ $billDetail->cost ?? '' }}</td>
                            <td>{{ $billDetail->discount ?? '' }}</td>
                            <td>{{ $billDetail->amount ?? '' }}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
                
                <tbody>
                                <tr>
                                    <td colspan="5" class="text-end">Sub - Total amount</td>
                                    <td><input type="text" readonly name="treatmenttotal" id="treatmenttotal" class="form-control text-center" value="{{ number_format($patientTreatmentBilling->treatment_total_amount, 3) }}"> </td>
                                </tr>
                                <?php if ($patientTreatmentBilling->combo_offer_deduction != 0) { ?>
                                    <tr>
                                        <td colspan="5" class="text-end">
                                        <label for="combo_checkbox">Combo offer Discount</label></td>
                                        
                                        <td>{{ number_format($patientTreatmentBilling->combo_offer_deduction, 3) }}</td>
                                    </tr> 
                                <?php  } ?>
                                <!-- <tr>
                                    <td colspan="5" class="text-end">Combo Offer</td>
                                    <td>{{ session('currency') }}</td>
                                </tr> -->
                                @if ($patientTreatmentBilling->insurance_paid!= 0) 
                                <tr>
                                <tr>
                                        <td colspan="5" class="text-end">Insurance paid</td>
                                        <td>{{ number_format($patientTreatmentBilling->insurance_paid, 3) }}</td>
                                    </tr> 
                                </tr>
                                @endif
                                @if ($patientTreatmentBilling->doctor_discount != 0)
                                <tr>
                                    <td colspan="5" class="text-end">Doctor Discount ({{$appointment->doctor_discount}} %)</td>
                                    
                                    <td>{{ number_format($patientTreatmentBilling->doctor_discount, 3) }}</td>
                                </tr>
                                @endif
                                @if ($patientTreatmentBilling->tax_percentile != 0 && $patientTreatmentBilling->tax != 0) 
                                <tr>
                                    <td colspan="5" class="text-end">Tax ({{$patientTreatmentBilling->tax_percentile}}%)</td>
                                  
                                    <td>{{ number_format($patientTreatmentBilling->tax, 3) }}</td>
                                </tr>
                                @endif
                                <tr class="bt-3 border-primary">
                                    <td colspan="5" class="text-end ">
                                        <h3><b>Total</b></h3>
                                    </td>
                                    <td>
                                        <h3>{{ session('currency') }}{{ number_format($patientTreatmentBilling->amount_to_be_paid, 2) }}
                                        </h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <span class="text-bold">Mode of Payment : </span>
                                        <span class="text-bold">{{$patientTreatmentBilling->mode_of_payment}}</span>
                                    </td>

                                    <td colspan="2" class="text-end ">Paid Amount</td>
                                    <td>{{$patientTreatmentBilling->amount_paid}}
                                    </td>
                                </tr>
                                @if($patientTreatmentBilling->consider_for_next_payment)
                                <tr>
                                    <td colspan="5" class="text-end ">Advance Payment</td>
                                    <td>{{$patientTreatmentBilling->balance_due}}</td>
                                </tr>
                                @endif
                                @if($patientTreatmentBilling->balance_given)
                                <tr>
                                    <td colspan="5" class="text-end">Balance Given</td>
                                    <td>{{$patientTreatmentBilling->balance_to_give_back}}</td>
                                </tr>
                                @endif
                            </tbody>
            </table>
        @endif
    </div>

   

    <div class="footer">
        <div class="footer_text1">Developed by Serieux</div>
        <div class="footer_text2">{{ date('d-m-Y h:i:s A') }}</div>
    </div>
</body>

</html>
