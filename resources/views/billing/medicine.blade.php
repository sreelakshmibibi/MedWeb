<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session; ?>
@extends('layouts.dashboard')
@section('title', 'Billing')
@section('content')

    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h3 class="page-title">Billing :<span class="fs-20 text-info">
                            {{ $appointment->patient_id }}-
                            {{ str_replace('<br>', ' ', $appointment->patient->first_name . ' ' . $appointment->patient->last_name) }}
                        </span>
                    </h3>
                    <?php
                    $idEncryptedBill = '';
                    $base64Id = base64_encode($appointment->id);
                    $idEncrypted = Crypt::encrypt($base64Id);
                    if ($hasPrescriptionBill) {
                        $base64BillId = base64_encode($hasPrescriptionBill->id);
                        $idEncryptedBill = Crypt::encrypt($base64BillId);
                    }
                    
                    ?>

                    <div>
                        <?php if (sizeof($prescriptions) > 0 && $isMedicineProvided == 'Y') { ?>
                        <a href="{{ route('medicineBilling.create', ['appointmentId' => $idEncrypted]) }}"
                            class="btn btn-success float-end">Medicine Bill</a>
                        <?php } ?>
                        <a href="{{ route('billing.create', ['appointmentId' => $idEncrypted]) }}"
                            class="btn btn-success float-end">Treatment Bill</a>
                    </div>
                    <div>
                        <button type='button'
                            class='waves-effect waves-light btn btn-circle btn-info btn-pdf-generate btn-xs me-1'
                            title='Download' data-bs-toggle='modal' data-app-id='{$row->id}' data-parent-id='{$parent_id}'
                            data-patient-id='{$row->patient->patient_id}' data-bs-target='#modal-download'><i
                                class='fa fa-download'></i></button>
                        <button type='button'
                            class='waves-effect waves-light btn btn-circle btn-warning buttons-print btn-pdf-generate btn-xs me-1'
                            title='Print' data-bs-toggle='modal' data-app-id='{$row->id}' data-parent-id='{$parent_id}'
                            data-patient-id='{$row->patient->patient_id}' data-bs-target='#modal-download'><i
                                class='fa fa-print'></i></button>
                        <a type="button" class="waves-effect waves-light btn btn-primary btn-circle btn-xs me-1"
                            href="{{ route('billing') }}">
                            <i class="fa-solid fa-angles-left"></i></a>
                    </div>
                </div>
                <div id="error-message-container">
                    <p id="error-message"
                        class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fadeOut"
                        style="display: none;"></p>
                </div>
            </div>
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <form method="post" id="prescriptionBillingForm" action="{{ route('medicineBilling.store') }}"
                enctype="multipart/form-data">
                @csrf
                <section class="content">
                    <div class=" row invoice-info">
                        <div class=" col-sm-12 invoice-col mb-15">
                            <div class="flexbox invoice-details px-1   no-margin">
                                <div>
                                    <p class="mb-1"><b>Bill No:</b>
                                        @if ($hasPrescriptionBill)
                                            {{ $hasPrescriptionBill->bill_id }}
                                        @endif
                                    </p>

                                    <p class="mb-0"><b>Generated at:</b>{{ date('d/m/Y H:m:s') }}</p>
                                </div>
                                <div>
                                    <input type="hidden" value="{{ $idEncrypted }}" name="appointmentId"
                                        id="appointmentId">
                                    <input type="hidden" value="{{ $appointment->patient_id }}" name="patientId">
                                    <input type="hidden" name="billId" id="billId" value="{{ $idEncryptedBill }}">
                                    <b>Appointment ID:</b> {{ $appointment->app_id }}
                                </div>
                                <div><b>Mobile No.:</b> {{ $appointment->patient->phone }}</div>
                                <div class="text-end">
                                    <p class="mb-1"><b>Payment Due:</b> {{ date('d/m/Y') }}</p>
                                    <p class="mb-0"><b>Generated by:</b> {{ Auth::user()->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 table-responsive-sm lh-1">
                            <table class="table table-bordered caption-top text-center">
                                <caption class="pt-0">Prescription Details</caption>
                                <thead class="bg-dark">
                                    <tr>
                                        <th style="width:30%;" class="text-start">Medicine</th>
                                        <th style="width:10%;">Dose</th>
                                        <th style="width:10%;">Frequency</th>
                                        <th style="width:10%;">Duration</th>
                                        <th style="width:10%;">Quantity</th>
                                        <th style="width:15%;" class="text-center">Status</th>
                                        <th style="width:20%;" class="text-center">Rate
                                            ({{ $clinicBasicDetails->currency }})
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tablebody">
                                    @foreach ($prescriptions as $prescription)
                                        {{-- @php
                                            $isOutOfStock = $prescription->medicine->stock_status === 'Out of Stock';
                                            $totalQuantity = $prescription->medicine->total_quantity;
                                        @endphp --}}
                                        @php
                                            $isOutOfStock = $prescription->medicine->stock_status === 'Out of Stock';
                                            $totalQuantity = $prescription->medicine->total_quantity;
                                            $isChecked = false;
                                            $quantityValue = '';
                                            $rateValue = '';
                                            $paidAmount = '';
                                            $modeOfPayment = '';

                                            // Check if prescription bill details contain this medicine
                                            $billDetail = $prescriptionBillDetails->firstWhere(
                                                'medicine_id',
                                                $prescription->medicine->id,
                                            );
                                            if ($billDetail) {
                                                $isChecked = true;
                                                $quantityValue = $billDetail->quantity;
                                                $rateValue = $billDetail->rate;
                                            }
                                            if ($hasPrescriptionBill) {
                                                $paidAmount = $hasPrescriptionBill->amount_paid;
                                                $modeOfPayment = $hasPrescriptionBill->mode_of_payment;
                                            }
                                        @endphp
                                        <tr class="{{ $isOutOfStock ? 'bg-light text-muted' : '' }}">
                                            {{-- <td class="text-start">
                                                <input type="checkbox" id="medicine_checkbox{{ $loop->index }}"
                                                    name="medicine_checkbox[]" class="filled-in chk-col-success"
                                                    value="{{ $prescription->medicine->id }}"
                                                    data-price="{{ $prescription->medicine->med_price }}"
                                                    {{ $isOutOfStock ? 'disabled' : '' }} />
                                                <label for="medicine_checkbox{{ $loop->index }}">
                                                    {{ $prescription->medicine->med_name }}
                                                </label>
                                            </td> --}}
                                            <td class="text-start">
                                                <input type="checkbox" id="medicine_checkbox{{ $loop->index }}"
                                                    name="medicine_checkbox[]" class="filled-in chk-col-success"
                                                    value="{{ $prescription->medicine->id }}"
                                                    {{ $isChecked ? 'checked' : '' }}
                                                    data-price="{{ $prescription->medicine->med_price }}"
                                                    {{ $isOutOfStock ? 'disabled' : '' }} />
                                                <label for="medicine_checkbox{{ $loop->index }}">
                                                    {{ $prescription->medicine->med_name }}
                                                </label>
                                            </td>
                                            <td>
                                                <div class="input-group col-12">
                                                    <input type="number" class="form-control text-center"
                                                        id="duration{{ $loop->index }}" name="duration[]"
                                                        aria-describedby="basic-addon2" value="{{ $prescription->dose }}"
                                                        {{ $isOutOfStock ? 'disabled' : '' }} readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"
                                                            id="basic-addon2">{{ $prescription->dose_unit }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <th>{{ $prescription->dosage->dos_name }}</th>
                                            <td>
                                                <div class="input-group col-12">
                                                    <input type="number" class="form-control text-center"
                                                        id="duration{{ $loop->index }}" name="duration[]"
                                                        aria-describedby="basic-addon2"
                                                        value="{{ $prescription->duration }}"
                                                        {{ $isOutOfStock ? 'disabled' : '' }} readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">days</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <th>
                                                <input type="number" name="quantity[]" id="quantity{{ $loop->index }}"
                                                    data-total-quantity="{{ $totalQuantity }}"
                                                    class="form-control text-center" {{ $isOutOfStock ? 'disabled' : '' }}
                                                    value="{{ $quantityValue }}">
                                                <span id="stock_message{{ $loop->index }}" class="text-danger"></span>
                                                <span id="quantity{{ $loop->index }}-error" class="text-danger"></span>
                                            </th>
                                            <td>{{ $prescription->medicine->stock_status }}</td>
                                            <td>
                                                <input type="hidden" id="unitcost{{ $loop->index }}" name="unitcost[]"
                                                    value="{{ $prescription->medicine->med_price }}">
                                                <input type="text" class="form-control text-center"
                                                    id="rate{{ $loop->index }}" name="rate[]"
                                                    value="{{ $rateValue }}" aria-describedby="basic-addon2"
                                                    {{ $isOutOfStock ? 'disabled' : '' }} readonly>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tbody>
                                    <tr>
                                        <th colspan="6" class="text-end">Total</th>
                                        <th><input type="text" class="form-control text-center" id="total"
                                                name="total" aria-describedby="basic-addon2" readonly>
                                            <span class="text-danger" id="prescTotalError">
                                                @error('total')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">Tax</th>
                                        <th><input type="hidden" class="form-control text-center" id="tax"
                                                name="tax" aria-describedby="basic-addon2"
                                                value="{{ $clinicBasicDetails->tax }}" readonly>
                                            <label>{{ $clinicBasicDetails->tax }}%</label>
                                            <span class="text-danger" id="prescTaxError">
                                                @error('tax')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">Grand Total</th>
                                        <th><input type="text" class="form-control text-center" id="grandTotal"
                                                name="grandTotal" aria-describedby="basic-addon2" readonly>
                                            <span class="text-danger" id="prescGrandTotalError">
                                                @error('grandTotal')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </th>

                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <span class="text-bold">Mode of Payment : </span>
                                            <input type="radio" class="form-check-input" name="mode_of_payment"
                                                id="mode_of_payment_gpay" value="gpay" <?php if ($modeOfPayment == "gpay") { ?> checked
                                                <?php } ?>>
                                            <label class="form-check-label" for="mode_of_payment_gpay">Gpay</label>
                                            <input type="radio" class="form-check-input" name="mode_of_payment"
                                                id="mode_of_payment_card" value="card" <?php if ($modeOfPayment == "card") { ?> checked
                                                <?php } ?>>
                                            <label class="form-check-label" for="mode_of_payment_card">Card</label>
                                            <input type="radio" class="form-check-input" name="mode_of_payment"
                                                id="mode_of_payment_cash" value="cash" <?php if ($modeOfPayment == "cash") { ?> checked
                                                <?php } ?>>
                                            <label class="form-check-label" for="mode_of_payment_cash">Cash</label>
                                            <span class="text-danger" id="prescModePaymentError">
                                                @error('mode_of_payment')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </td>

                                        <td colspan="3" class="text-end ">Paid Amount</td>
                                        <td><input type="text" name="amountPaid" id="amountPaid"
                                                class="form-control text-center" value="{{ $paidAmount }}" required>
                                            <span id="prescAmountPaidError" class="error-message text-danger">
                                                @error('amountPaid')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <input type="checkbox" name="balance_given" id="balance_given"
                                                class="form-check-input">
                                            <label class="form-check-label" for="balance_given">Balance Given</label>
                                            <span class="error-message text-danger" id="prescCheckError"></span>
                                        </td>
                                        <td colspan="3" class="text-end">Balance to Give Back</td>
                                        <td><input type="text" name="balanceToGiveBack" id="balanceToGiveBack"
                                                class="form-control text-center" readonly>
                                            <span class="text-danger" id="prescBalanceToGiveBackError">
                                                @error('balanceToGiveBack')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="row text-end py-3">
                        <div class="col-12">
                            <?php if (!$hasPrescriptionBill) { ?>
                            <button type="button" class="btn btn-success pull-right" name="prescSubmitPayment"
                                id="prescSubmitPayment"><i class="fa fa-credit-card"></i>
                                Submit Payment
                            </button>
                            <?php  } else { ?>
                            <button type="button" class="btn btn-success pull-right" name="prescPrintPayment"
                                id="prescPrintPayment"><i class="fa fa-credit-card"></i>
                                Print Receipt
                            </button>
                            <?php } ?>
                        </div>
                    </div>

                </section>
            </form>
        </div>
    </div>
    <script>
        var receiptRoute = "{{ route('medicineBilling.paymentReceipt') }}";
    </script>
    <script src="{{ asset('js/prescription_billing.js') }}"></script>
@endsection
