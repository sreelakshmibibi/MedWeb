<?php
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
date_default_timezone_set('Asia/Kolkata');
?>
@extends('layouts.dashboard')
@section('title', 'Billing')
@section('content')

    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Billing :<span class="fs-20 text-info">
                            {{ $appointment->patient_id }}-
                            {{ str_replace('<br>', ' ', $appointment->patient->first_name . ' ' . $appointment->patient->last_name) }}
                        </span>
                    </h3>
                    <?php $base64Id = base64_encode($appointment->id);
                    $idEncrypted = Crypt::encrypt($base64Id);
                    $base64BillId = base64_encode($billExists->id);
                    $idEncryptedBill = Crypt::encrypt($base64BillId); ?>

                    <div>
                        <?php if ($billExists->amount_paid != null) { ?>
                        <button type='button' id="printPayment1"
                            class='waves-effect waves-light btn btn-circle btn-secondary btn-xs me-1'
                            title='Download & Print Treatment Bill'><i class='fa fa-download'></i></button>
                        <?php } ?>
                        <?php if ($hasPrescriptionBill) { ?>
                        <button type='button' id="prescPrintPayment1"
                            class='waves-effect waves-light btn btn-circle btn-warning btn-xs me-1'
                            title='Print Medicine Bill'><i class='fa fa-print'></i></button>
                        <?php } ?>
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

            <!-- Nav tabs -->
            <ul class="nav nav-tabs " role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#treatgbilltabcontent" role="tab"
                        id="treatgbilltabtitle">
                        <span class="hidden-sm-up"><i class="fa-solid fa-file-medical"></i></span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-file-medical me-10"></i>Treatment Bill</span>
                    </a>
                </li>

                <?php if (sizeof($prescriptions) > 0 && $isMedicineProvided == 'Y') { ?>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#medgbilltabcontent" role="tab"
                        id="medgbilltabtitle">
                        <span class="hidden-sm-up"><i class="fa-solid fa-capsules"></i> </span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-capsules me-10"></i>Medicine Bill</span>
                    </a>
                </li>
                <?php } ?>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="treatgbilltabcontent" role="tabpanel">
                    <div class="py-15">
                        <form method="post" id="billingForm" action="{{ route('billing.payment') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <section class="content">
                                <div class=" row invoice-info">
                                    <div class=" col-sm-12 invoice-col mb-15">
                                        <div class="flexbox invoice-details px-1   no-margin">
                                            <div>
                                                <p class="mb-1"><b>Bill No:</b> {{ $billExists->bill_id }}</p>
                                                <input type="hidden" name="appointmentId" id="appointmentId"
                                                    value="{{ $idEncrypted }}">
                                                <input type="hidden" name="billId" id="billId"
                                                    value="{{ $idEncryptedBill }}">
                                                <p class="mb-0"><b>Created at: </b>
                                                    {{-- {{ $billExists->updated_at }} --}}
                                                    {{ date('d-m-Y h:i A', strtotime($billExists->updated_at)) }}
                                                </p>
                                            </div>
                                            <div><b>Appointment ID: </b> {{ $appointment->app_id }}</div>
                                            <div><b>Mobile No.: </b> {{ $appointment->patient->phone }}</div>
                                            <div class="text-end">
                                                <p class="mb-1"><b>Payment Due: </b> {{ date('d/m/Y') }}</p>
                                                <p class="mb-0"><b>Generated by: </b> {{ Auth::user()->name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 table-responsive-sm lh-1">
                                        <table class="table table-bordered caption-top text-center">
                                            <caption class="pt-0">Treatment Details</caption>
                                            <thead class="bg-dark">
                                                <tr>
                                                    <th style="width: 5%;">#</th>
                                                    <th style="width: 45%;">Treatment</th>
                                                    <th style="width: 10%;">Quantity</th>
                                                    <th style="width: 15%;">Unit Cost ( {{ session::get('currency') }} )
                                                    </th>

                                                    <th style="width: 10%;">Discount ( % )</th>

                                                    <th style="width: 15%;">Subtotal ( {{ session::get('currency') }} )
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0;
                                                $treatmentTotal = 0; ?>
                                                @foreach ($detailBills as $detailBill)
                                                    <?php
                                                    $i++;
                                                    $cost = is_numeric($detailBill->cost) ? floatval($detailBill->cost) : 0;
                                                    // $subtotal = is_numeric($detailBill->amount) ? floatval($detailBill->amount) : 0;
                                                    ?>
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td class="text-start">
                                                            {{ $detailBill->treatment_id ? $detailBill->treatment->treat_name : $detailBill->consultation_registration }}
                                                            <input type="hidden" name="treatmentId{{ $i }}"
                                                                value="{{ $detailBill->treatment_id }}">
                                                        </td>
                                                        <td><input type="text" readonly
                                                                name="quantity{{ $i }}"
                                                                class="form-control text-center"
                                                                value="{{ $detailBill->quantity }}">
                                                        </td> <!-- Add quantity if available -->
                                                        <td><input type="text" readonly name="cost{{ $i }}"
                                                                class="form-control text-center"
                                                                value="{{ number_format($detailBill->cost, 3) }}"></td>
                                                        <td> <input type="text" readonly
                                                                name="discount_percentage{{ $i }}"
                                                                class="form-control text-center"
                                                                value="{{ $detailBill->discount }}">
                                                        </td>

                                                        <td> <input type="text" readonly
                                                                name="subtotal{{ $i }}"
                                                                class="form-control text-center"
                                                                value="{{ $detailBill->amount }}">
                                                        </td> <!-- Format the cost -->
                                                        <?php
                                                        
                                                        // $treatmentTotal += number_format($individualTreatmentAmount['subtotal'], 3)
                                                        ?>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                            <tbody>
                                                <tr>
                                                    <th colspan="5" class="text-end">Sub - Total amount</th>
                                                    <td>
                                                        <input type="text" readonly name="treatmenttotal"
                                                            id="treatmenttotal" class="form-control text-center"
                                                            value="{{ number_format($billExists->treatment_total_amount, 3) }}">
                                                    </td>
                                                </tr>
                                                <?php if ($billExists->combo_offer_deduction != 0) { ?>
                                                <tr>
                                                    <td colspan="5" class="text-end">
                                                        <label for="combo_checkbox">Combo offer Discount</label>
                                                    </td>

                                                    <td>
                                                        <input type="text" readonly name="comboOfferAmount"
                                                            id ="comboOfferAmount" class="form-control text-center"
                                                            value="{{ number_format($billExists->combo_offer_deduction, 3) }}">
                                                    </td>
                                                </tr>
                                                <?php  } ?>

                                                @if ($billExists->insurance_paid != 0)
                                                    <tr>
                                                    <tr>
                                                        <td colspan="5" class="text-end">Insurance paid</td>
                                                        <td>
                                                            <input type="text" readonly name="insurance"
                                                                id ="insurance" class="form-control text-center"
                                                                value="{{ number_format($billExists->insurance_paid, 3) }}">
                                                        </td>
                                                    </tr>
                                                    </tr>
                                                @endif
                                                @if ($billExists->doctor_discount != 0)
                                                    <tr>
                                                        <td colspan="5" class="text-end">Doctor Discount
                                                            ({{ $appointment->doctor_discount }} %)</td>

                                                        <td>
                                                            <input type="text" readonly name="doctorDisc"
                                                                id="doctorDisc" class="form-control text-center"
                                                                value="{{ number_format($billExists->doctor_discount, 3) }}">
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($billExists->tax_percentile != 0 && $billExists->tax != 0)
                                                    <tr>
                                                        <td colspan="5" class="text-end">Tax
                                                            ({{ $billExists->tax_percentile }}%)
                                                        </td>

                                                        <td>
                                                            <input type="text" readonly name="tax"
                                                                class="form-control text-center"
                                                                value="{{ number_format($billExists->tax, 3) }}">
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr class="bt-3 border-primary">
                                                    <td colspan="5" class="text-end ">
                                                        <h3><b>Total</b></h3>
                                                    </td>
                                                    <td>
                                                        <h3>
                                                            {{ session('currency') }}{{ number_format($billExists->amount_to_be_paid, 2) }}
                                                            <input type="hidden" name="totaltoPay" id="totalToPay"
                                                                class="form-control text-center"
                                                                value="{{ $billExists->amount_to_be_paid }}">
                                                        </h3>
                                                    </td>
                                                </tr>
                                                {{-- <tr>
                                                    <td colspan="3" class="text-start">
                                                        <span class="text-bold">Mode of Payment : </span>
                                                        <input type="radio" class="form-control with-gap"
                                                            name="mode_of_payment" id="mode_of_payment_gpay"
                                                            value="gpay" required <?php if ($billExists->mode_of_payment == "gpay") { ?> checked
                                                            <?php } ?>>
                                                        <label class="form-check-label me-2"
                                                            for="mode_of_payment_gpay">Gpay</label>
                                                        <input type="radio" class="form-control with-gap"
                                                            name="mode_of_payment" id="mode_of_payment_card"
                                                            value="card" required <?php if ($billExists->mode_of_payment == "card") { ?> checked
                                                            <?php } ?>>
                                                        <label class="form-check-label me-2"
                                                            for="mode_of_payment_card">Card</label>
                                                        <input type="radio" class="form-control with-gap"
                                                            name="mode_of_payment" id="mode_of_payment_cash"
                                                            value="cash" required <?php if ($billExists->mode_of_payment == "cash") { ?> checked
                                                            <?php } ?>>
                                                        <label class="form-check-label me-2"
                                                            for="mode_of_payment_cash">Cash</label>
                                                        <span class="error-message text-danger" id="modeError"></span>
                                                    </td>
                                                    <td><input type="text" readonly name="tax"
                                                            class="form-control text-center"
                                                            value="{{ number_format($billExists->tax, 3) }}"></td>
                                                </tr> --}}


                                                @if ($previousOutStanding != 0)
                                                    <tr>
                                                        <td colspan="5" class="text-end">Previous Outstanding
                                                        </td>

                                                        <td><input type="text" readonly name="previousOutStanding"
                                                                class="form-control text-center"
                                                                value="{{ number_format($previousOutStanding, 3) }}"></td>
                                                    </tr>
                                                @endif
                                                {{-- <tr class="bt-3 border-primary">
                                                    <td colspan="5" class="text-end ">
                                                        <h3><b>Total</b></h3>
                                                    </td>
                                                    <td>
                                                        <h3>{{ session('currency') }}{{ number_format($billExists->amount_to_be_paid, 2) }}
                                                            <input type="hidden" name="totaltoPay" id="totalToPay"
                                                                class="form-control text-center"
                                                                value="{{ $billExists->amount_to_be_paid }}">
                                                        </h3>
                                                    </td>
                                                </tr> --}}
                                                <tr>
                                                    {{-- <td colspan="3">
                                                        <span class="text-bold">Mode of Payment : </span>
                                                        <input type="radio" class="form-check-input"
                                                            name="mode_of_payment" id="mode_of_payment_gpay"
                                                            value="gpay" required <?php if ($billExists->mode_of_payment == "gpay") { ?> checked
                                                            <?php } ?>>
                                                        <label class="form-check-label"
                                                            for="mode_of_payment_gpay">Gpay</label>
                                                        <input type="radio" class="form-check-input"
                                                            name="mode_of_payment" id="mode_of_payment_card"
                                                            value="card" required <?php if ($billExists->mode_of_payment == "card") { ?> checked
                                                            <?php } ?>>
                                                        <label class="form-check-label"
                                                            for="mode_of_payment_card">Card</label>
                                                        <input type="radio" class="form-check-input"
                                                            name="mode_of_payment" id="mode_of_payment_cash"
                                                            value="cash" required <?php if ($billExists->mode_of_payment == "cash") { ?> checked
                                                            <?php } ?>>
                                                        <label class="form-check-label"
                                                            for="mode_of_payment_cash">Cash</label>
                                                        <span class="error-message text-danger" id="modeError"></span>
                                                    </td> --}}
                                                    {{-- <td colspan="3" class="text-start">
                                                        <span class="text-bold">Mode of Payment : </span>
                                                        <input type="radio" class="form-control with-gap"
                                                            name="mode_of_payment" id="mode_of_payment_gpay"
                                                            value="gpay" required <?php// if ($billExists->mode_of_payment == "gpay") { ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> checked
                                                            <?php// } ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?>>
                                                        <label class="form-check-label me-2"
                                                            for="mode_of_payment_gpay">Gpay</label>
                                                        <input type="text" name="gpaycash" style="display: none;"
                                                            class="form-control d-inline w-50">

                                                        <input type="radio" class="form-control with-gap"
                                                            name="mode_of_payment" id="mode_of_payment_cash"
                                                            value="cash" required <?php// if ($billExists->mode_of_payment == "cash") { ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> checked
                                                            <?php// } ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?>>
                                                        <label class="form-check-label me-2"
                                                            for="mode_of_payment_cash">Cash</label>
                                                        <input type="text" name="cash" style="display: none;"
                                                            class="form-control d-inline w-50">

                                                        <input type="radio" class="form-control with-gap"
                                                            name="mode_of_payment" id="mode_of_payment_card"
                                                            value="card" required <?php// if ($billExists->mode_of_payment == "card") { ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> checked
                                                            <?php// } ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?>>
                                                        <label class="form-check-label me-2"
                                                            for="mode_of_payment_card">Card</label>
                                                        <input type="text" name="cardcash" style="display: none;"
                                                            class="form-control d-inline w-50 me-2">
                                                        <select class="ms-2 form-select d-inline w-150" id="machine"
                                                            name="machine" style="display: none;">
                                                            <option value="">Select Machine</option>
                                                            <option value="1">Machine 1</option>
                                                            <option value="2">Machine 2</option>
                                                        </select>
                                                        <span class="error-message text-danger" id="modeError"></span>
                                                    </td> --}}
                                                    <td colspan="3" class="text-start">
                                                        <span class="text-bold me-2">Mode of Payment:</span>

                                                        <!-- Checkbox for Gpay -->
                                                        <input type="checkbox" class="filled-in chk-col-success"
                                                            id="mode_of_payment_gpay" name="mode_of_payment[]"
                                                            value="gpay" <?php if ($billExists->mode_of_payment == 'gpay') { ?> checked
                                                            <?php } ?>>
                                                        <label class="form-check-label me-2"
                                                            for="mode_of_payment_gpay">Gpay</label>
                                                        <input type="text" name="gpaycash" id="gpaycash"
                                                            class="form-control  w-50 " style="display: none;">
                                                        &nbsp;
                                                        <!-- Checkbox for Cash -->
                                                        <input type="checkbox" class="filled-in chk-col-success"
                                                            id="mode_of_payment_cash" name="mode_of_payment[]"
                                                            value="cash" <?php if ($billExists->mode_of_payment == 'cash') { ?> checked
                                                            <?php } ?>>
                                                        <label class="form-check-label me-2"
                                                            for="mode_of_payment_cash">Cash</label>
                                                        <input type="text" name="cash" id="cash"
                                                            class="form-control  w-50" style="display: none;">
                                                        &nbsp;
                                                        <!-- Checkbox for Card -->
                                                        <input type="checkbox" class="filled-in chk-col-success"
                                                            id="mode_of_payment_card" name="mode_of_payment[]"
                                                            value="card" <?php if ($billExists->mode_of_payment =='card') { ?> checked
                                                            <?php } ?>>
                                                        <label class="form-check-label me-2"
                                                            for="mode_of_payment_card">Card</label>
                                                        <input type="text" name="cardcash" id="cardcash"
                                                            class="form-control  w-50 " style="display: none;">
                                                        <select class="ms-2 form-select w-150" id="machine"
                                                            name="machine" style="display: none;">
                                                            <option value="">Select Machine</option>
                                                            <option value="1">Machine 1</option>
                                                            <option value="2">Machine 2</option>
                                                        </select>
                                                        <span class="error-message text-danger" id="modeError"></span>
                                                    </td>

                                                    <td><input type="text" readonly name="tax"
                                                            class="form-control text-center"
                                                            value="{{ number_format($billExists->tax, 3) }}"></td>

                                                    <td class="text-end ">Paid Amount</td>
                                                    <td><input type="text" name="amountPaid" id="amountPaid"
                                                            class="form-control text-center"
                                                            value="<?php if ($billExists->amount_paid != null) {
                                                                echo $billExists->amount_paid;
                                                            } ?>"required><span
                                                            class="error-message text-danger" id="paidAmountError"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-start">
                                                        <input type="checkbox" name="consider_for_next_payment"
                                                            id="consider_for_next_payment"
                                                            class="filled-in chk-col-success" <?php if ($billExists->consider_for_next_payment == 1) { ?> checked
                                                            <?php } ?>>
                                                        <label class="form-check-label"
                                                            for="consider_for_next_payment">Consider for
                                                            Next Payment</label>
                                                    </td>

                                                    <td colspan="2" class="text-end ">Balance Due</td>
                                                    <td><input type="text" name="balance" id="balance"
                                                            class="form-control text-center" value="<?php if ($billExists->balance_due != null) {
                                                                echo $billExists->balance_due;
                                                            } ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-start">
                                                        <input type="checkbox" name="balance_given" id="balance_given"
                                                            class="filled-in chk-col-success" <?php if ($billExists->balance_given == 1) { ?> checked
                                                            <?php } ?>>
                                                        <label class="form-check-label" for="balance_given">Balance
                                                            Given</label>
                                                        <span class="error-message text-danger" id="checkError"></span>
                                                    </td>
                                                    <td colspan="2" class="text-end">Balance to Give Back</td>
                                                    <td><input type="text" name="balanceToGiveBack"
                                                            id="balanceToGiveBack" class="form-control text-center"
                                                            value="<?php if ($billExists->balance_to_give_back != null) {
                                                                echo $billExists->balance_to_give_back;
                                                            } ?>" readonly>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row text-end py-3">
                                    <div class="col-12">
                                        <?php if ($billExists->amount_paid == null) { ?>
                                        <button type="button" class="btn btn-success pull-right" name="submitPayment"
                                            id="submitPayment"><i class="fa fa-credit-card"></i>
                                            Submit Payment
                                        </button>
                                        <?php  } else { ?>
                                        <button type="button" class="btn btn-warning pull-right" name="printPayment"
                                            id="printPayment"><i class="fa fa-print"></i>
                                            Print Receipt
                                        </button>
                                        <?php } ?>
                                    </div>
                                </div>

                            </section>
                        </form>
                    </div>
                </div>

                <div class="tab-pane" id="medgbilltabcontent" role="tabpanel">
                    <div class="py-15">
                        @include('billing.medicine')
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        var receiptRoute = "{{ route('billing.paymentReceipt') }}";
        var billingRoute = "{{ route('billing') }}";
    </script>
    <script src="{{ asset('js/billing.js') }}"></script>

@endsection
