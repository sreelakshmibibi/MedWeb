<?php

use App\Services\CommonService;
$commonService = new CommonService();
use Illuminate\Support\Facades\Session;

?>
@extends('layouts.dashboard')
@section('title', 'Due Payment')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success"></div>
                @if (session('success'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-success alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('success') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                @if (session('error'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('error') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Due Payment</h3>

                </div>
            </div>

            <section class="content">
                <div class="row">
                    <div class="col-xl-8 col-12">
                        <div class="box">
                            <div class="box-body bb-1" style="border-radius: 0px;">
                                <form id="searchPatientForm" class="d-flex align-items-center justify-content-between">
                                    <div class="form-group d-flex align-items-center">
                                        <label for="name" class="me-2">Name:</label>
                                        <input type="text" id="name" name="name" class="form-control"
                                            placeholder="Enter patient name">
                                    </div>
                                    <div class="form-group d-flex align-items-center ms-3">
                                        <label for="phone" class="me-2">Phone:</label>
                                        <input type="text" id="phone" name="phone" class="form-control"
                                            placeholder="Enter phone number">
                                    </div>
                                    <div class="form-group d-flex align-items-center ms-3">
                                        <label for="patientId" class="me-2">ID:</label>
                                        <input type="text" id="patientId" name="patientId" class="form-control"
                                            placeholder="Enter patient ID">
                                    </div>
                                    <button type="button" id="searchPatientButton"
                                        class="btn btn-primary ms-3">Search</button>

                                </form>
                            </div>
                            <div>
                                <span class="text-danger text-center" id="duePatientNotfoundError">

                                </span>
                            </div>
                            <div id="treatmentBillPage" style="display:none;">

                            </div>
                            <div class="box-body bb-1" style="border-radius: 0px;">
                                <div id="patientInfo" style="display:none;">
                                    <form id="payDueForm" action="{{ route('duePayment.due') }}" method="POST">
                                        @csrf
                                        <h4>Patient Details</h4>
                                        <table class="table table-bordered" id="dueTable">
                                            <thead>
                                                <tr>
                                                    <th style="width:20%;">Patient ID</th>
                                                    <th style="width:30%;">Patient Name</th>
                                                    <th style="width:20%;">Phone</th>
                                                    <th style="width:30%;" class="text-center">Due Amount
                                                        {{ $clinicDetails->currency }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="patientIdDisplay"></td>
                                                    <td id="patientName"></td>
                                                    <td id="patientPhone"></td>
                                                    <td><input type="text" class="form-control text-center"
                                                            id="dueAmount" name="dueAmount" aria-describedby="basic-addon2"
                                                            readonly></td>
                                                </tr>
                                            </tbody>
                                            <tbody>
                                                <tr>
                                                    <th colspan="3" class="text-end">Total</th>
                                                    <th><input type="text" class="form-control text-center"
                                                            id="dueTotal" name="dueTotal" aria-describedby="basic-addon2"
                                                            readonly>
                                                        <span class="text-danger" id="dueTotalError">
                                                            @error('dueTotal')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </th>
                                                </tr>


                                                <tr>

                                                    <td colspan="2" class="text-start">
                                                        <span class="text-bold me-2">Mode of Payment:</span>

                                                        <!-- Checkbox for Gpay -->
                                                        <input type="checkbox" class="filled-in chk-col-success"
                                                            id="duemode_of_payment_gpay" name="duemode_of_payment[]"
                                                            value="gpay">
                                                        <label class="form-check-label me-2"
                                                            for="duemode_of_payment_gpay">Gpay</label>
                                                        <input type="text" name="duegpay" id="duegpay"
                                                            class="form-control  w-100 " style="display: none;">
                                                        &nbsp;
                                                        <!-- Checkbox for Cash -->
                                                        <input type="checkbox" class="filled-in chk-col-success"
                                                            id="duemode_of_payment_cash" name="duemode_of_payment[]"
                                                            value="cash">
                                                        <label class="form-check-label me-2"
                                                            for="duemode_of_payment_cash">Cash</label>
                                                        <input type="text" name="duecash" id="duecash"
                                                            class="form-control  w-100" style="display: none;">
                                                        &nbsp;
                                                        <!-- Checkbox for Card -->
                                                        <input type="checkbox" class="filled-in chk-col-success"
                                                            id="duemode_of_payment_card" name="duemode_of_payment[]"
                                                            value="card">
                                                        <label class="form-check-label me-2"
                                                            for="duemode_of_payment_card">Card</label>
                                                        <input type="text" name="duecard" id="duecard"
                                                            class="form-control  w-100 " style="display: none;">
                                                        <select class="ms-2 form-select w-150" id="duemachine"
                                                            name="duemachine" style="display: none;">
                                                            <option value="">Select Machine</option>
                                                            @foreach ($cardPay as $machine)
                                                                <option value="{{ $machine->id }}">
                                                                    {{ $machine->card_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger" id="dueModePaymentError">
                                                            @error('duemode_of_payment')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </td>

                                                    <td class="text-end ">Paid Amount</td>
                                                    <td>
                                                        <input type="text" name="dueAmountPaid" id="dueAmountPaid"
                                                            class="form-control text-center" required readonly>
                                                        <span id="dueAmountPaidError" class="error-message text-danger">
                                                            @error('dueAmountPaid')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-start">
                                                        <input type="checkbox" name="dueBalance_given"
                                                            id="dueBalance_given" class="filled-in chk-col-success">
                                                        <label class="form-check-label" for="dueBalance_given">Balance
                                                            Given</label>
                                                        <span class="error-message text-danger" id="dueCheckError"></span>
                                                    </td>
                                                    <td class="text-end">Balance to Give Back</td>
                                                    <td><input type="text" name="dueBalanceToGiveBack"
                                                            id="dueBalanceToGiveBack" class="form-control text-center"
                                                            readonly>
                                                        <span class="text-danger" id="dueBalanceToGiveBackError">
                                                            @error('dueBalanceToGiveBack')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <!-- Pay Button -->

                                        <input type="hidden" id="patientIdInput" name="patientIdInput">
                                        <input type="hidden" id="appInput" name="appInput">
                                        <input type="hidden" id="treatmentBillInput" name="treatmentBillInput">
                                        <input type="hidden" id="dueBillInput" name="dueBillInput">
                                        <div class="row text-end py-3">
                                            <div class="col-12">

                                                <button type="button" class="btn btn-success pull-right"
                                                    name="dueSubmitPayment" id="dueSubmitPayment"><i
                                                        class="fa fa-credit-card"></i>
                                                    Pay Due
                                                </button>

                                                <button type="button" class="btn btn-warning pull-right"
                                                    name="duePrintPayment" id="duePrintPayment"><i
                                                        class="fa fa-print"></i>
                                                    Print Receipt
                                                </button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var dueReceiptRoute = "{{ route('duePayment.paymentReceipt') }}";
        var billingRoute = "{{ route('duePayment') }}";
    </script>
    <script>
        document.getElementById('searchPatientButton').addEventListener('click', function() {
            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            const patientId = document.getElementById('patientId').value;
            document.getElementById('duePatientNotfoundError').innerText = '';

            // Perform AJAX request to search for the patient
            fetch(`/search-patient?name=${name}&phone=${phone}&id=${patientId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.patient.treatmentBillDetails && Object.keys(data.patient.treatmentBillDetails)
                            .length > 0) {
                            // Show patient information
                            document.getElementById('patientInfo').style.display = 'block';
                            document.getElementById('patientName').innerText = data.patient.name;
                            document.getElementById('patientPhone').innerText = data.patient.phone;
                            document.getElementById('patientIdDisplay').innerText = data.patient.id;
                            document.getElementById('dueAmount').value = data.patient.due_amount;
                            document.getElementById('patientIdInput').value = data.patient.id;
                            document.getElementById('dueTotal').value = data.patient.due_amount;
                            document.getElementById('dueBillInput').value = data.patient.dueBillId;
                            document.getElementById('treatmentBillInput').value = data.patient.treatmentBillId;
                            document.getElementById('appInput').value = data.patient.billAppId;
                            if (data.patient.dueBillDetails && Object.keys(data.patient.dueBillDetails).length >
                                0) {
                                const dueBillDetails = data.patient.dueBillDetails;
                                document.getElementById('dueAmountPaid').value = dueBillDetails.paid_amount;
                                // Populate the payment fields
                                if (dueBillDetails.gpay > 0) {
                                    document.getElementById('duemode_of_payment_gpay').checked = true;
                                    document.getElementById('duegpay').value = dueBillDetails.gpay;
                                    document.getElementById('duegpay').style.display = 'block';
                                    document.getElementById('duegpay').readOnly = true;
                                }
                                if (dueBillDetails.cash > 0) {
                                    document.getElementById('duemode_of_payment_cash').checked = true;
                                    document.getElementById('duecash').value = dueBillDetails.cash;
                                    document.getElementById('duecash').style.display = 'block';
                                    document.getElementById('duecash').readOnly = true;
                                }
                                if (dueBillDetails.card > 0) {
                                    document.getElementById('duemode_of_payment_card').checked = true;
                                    document.getElementById('duecard').value = dueBillDetails.card;
                                    document.getElementById('duecard').style.display = 'block';
                                    document.getElementById('duecard').readOnly = true;
                                    document.getElementById('duemachine').value = dueBillDetails.card_pay_id;
                                    document.getElementById('duemachine').style.display = 'block';
                                }
                                if (dueBillDetails.balance_given > 0) {
                                    document.getElementById('dueBalance_given').checked = true;
                                    document.getElementById('dueBalanceToGiveBack').value = dueBillDetails
                                        .balance_given;
                                }
                            }
                            if (data.patient.dueBillDetails && Object.keys(data.patient.dueBillDetails).length >
                                0) {
                                document.getElementById('duePrintPayment').style.display = 'block';
                                document.getElementById('dueSubmitPayment').style.display = 'none';
                            } else if (data.patient.due_amount > 0) {

                                document.getElementById('duePrintPayment').style.display = 'none';
                                document.getElementById('dueSubmitPayment').style.display = 'block';

                            } else {
                                document.getElementById('duePrintPayment').style.display = 'none';
                                document.getElementById('dueSubmitPayment').style.display = 'none';
                            }

                            // Clear search fields
                            document.getElementById('name').value = '';
                            document.getElementById('phone').value = '';
                            document.getElementById('patientId').value = '';
                        } else {
                            document.getElementById('patientInfo').style.display = 'none';
                            document.getElementById('treatmentBillPage').style.display = 'block';
                            var appointmentId = data.patient.billAppId;

                            // Create the button element
                            var button = document.createElement('button');
                            button.type = 'button';
                            button.className = 'btn btn-primary';
                            button.innerText = 'Add Billing';

                            // Set the onclick attribute to navigate to the billing creation route
                            button.onclick = function() {
                                window.location.href = '/billing/add/' + appointmentId;
                            };

                            // Append the button to the treatmentBillPage div
                            document.getElementById('treatmentBillPage').appendChild(button);
                        }
                    } else {
                        document.getElementById('duePatientNotfoundError').innerText = 'Patient not found.';
                        document.getElementById('patientInfo').style.display = 'none';
                        document.getElementById('treatmentBillPage').style.display = 'none';
                    }
                });
        });

        document.addEventListener("DOMContentLoaded", (event) => {
            const gpayField = document.getElementById("duegpay");
            const cashField = document.getElementById("duecash");
            const cardField = document.getElementById("duecard");
            const machineField = document.getElementById("duemachine");

            // Function to handle checkbox changes
            function handleCheckboxChange() {
                const gpayChecked = document.getElementById("duemode_of_payment_gpay").checked;
                const cashChecked = document.getElementById("duemode_of_payment_cash").checked;
                const cardChecked = document.getElementById("duemode_of_payment_card").checked;

                gpayField.style.display = gpayChecked ? "inline-block" : "none";
                cashField.style.display = cashChecked ? "inline-block" : "none";
                cardField.style.display = cardChecked ? "inline-block" : "none";
                machineField.style.display = cardChecked ? "inline-block" :
                    "none"; // For machine select box if needed
            }

            // Attach change event listeners to checkboxes
            document.getElementById("duemode_of_payment_gpay").addEventListener("change", function() {
                handleCheckboxChange();
                calculateTotalPaid();
            });
            document.getElementById("duemode_of_payment_cash").addEventListener("change", function() {
                handleCheckboxChange();
                calculateTotalPaid();
            });
            document.getElementById("duemode_of_payment_card").addEventListener("change", function() {
                handleCheckboxChange();
                calculateTotalPaid();
            });

            // Function to calculate total paid
            function calculateTotalPaid() {
                let totalPaid = 0;
                let isValid = true;

                const gpayInput = document.getElementById('duegpay');
                const cashInput = document.getElementById('duecash');
                const cardInput = document.getElementById('duecard');
                const errorElement = document.getElementById('dueModePaymentError');
                if (errorElement) {
                    errorElement.textContent = '';
                }

                if (gpayInput && gpayInput.style.display !== 'none') {
                    const gpayValue = parseFloat(gpayInput.value);
                    if (isNaN(gpayValue) || gpayInput.value != gpayValue.toString()) {
                        isValid = false;
                        errorElement.textContent += 'GPay amount should be a valid number. ';
                    } else {
                        totalPaid += gpayValue;
                    }
                }

                if (cashInput && cashInput.style.display !== 'none') {
                    const cashValue = parseFloat(cashInput.value);
                    if (isNaN(cashValue) || cashInput.value != cashValue.toString()) {
                        isValid = false;
                        errorElement.textContent += 'Cash amount should be a valid number. ';
                    } else {
                        totalPaid += cashValue;
                    }
                }

                if (cardInput && cardInput.style.display !== 'none') {
                    const cardValue = parseFloat(cardInput.value);
                    if (isNaN(cardValue) || cardInput.value != cardValue.toString()) {
                        isValid = false;
                        errorElement.textContent += 'Card amount should be a valid number. ';
                    } else {
                        totalPaid += cardValue;
                    }
                }

                if (isValid) {
                    document.getElementById('dueAmountPaid').value = totalPaid.toFixed(2);
                    updateBalance();
                }
            }

            // Attach input event listeners to input fields for calculating total
            gpayField.addEventListener("input", calculateTotalPaid);
            cashField.addEventListener("input", calculateTotalPaid);
            cardField.addEventListener("input", calculateTotalPaid);

            // Function to update balance
            function updateBalance() {
                const amountPaid = parseFloat(document.getElementById("dueAmountPaid").value) || 0;
                const totalDue = parseFloat(document.getElementById("dueTotal").value) || 0;

                const balance = (amountPaid - totalDue).toFixed(2);
                document.getElementById("dueBalanceToGiveBack").value =
                    amountPaid >= totalDue ? balance : "0.00";
            }

            // Initial calculations
            handleCheckboxChange();
            calculateTotalPaid();
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Add event listener to the submit button
            if (document.querySelector("#dueSubmitPayment")) {
                document
                    .querySelector("#dueSubmitPayment")
                    .addEventListener("click", function(event) {
                        event.preventDefault(); // Prevent the default form submission

                        // Clear previous error messages
                        $("#dueTotalError").text("");
                        $("#dueAmountPaidError").text("");
                        $("#dueModePaymentError").text("");
                        $("#dueBalanceToGiveBackError").text("");
                        $("#dueCheckError").text("");

                        // Get the form and its inputs
                        const form = document.getElementById("payDueForm");
                        const modeOfPaymentCheckboxes = form.querySelectorAll(
                            'input[name="duemode_of_payment[]"]:checked');
                        const amountPaid = form.querySelector('input[name="dueAmountPaid"]').value;
                        const balanceToGiveBack = parseFloat(form.querySelector(
                            'input[name="dueBalanceToGiveBack"]').value) || 0;
                        const balanceGiven = form.querySelector('input[name="dueBalance_given"]').checked;
                        const dueTotal = form.querySelector('input[name="dueTotal"]').value;
                        var isValid = 1;

                        // Validate dueTotal
                        if (isNaN(dueTotal) || parseFloat(dueTotal) <= 0) {
                            $("#dueTotalError").text("Total due amount must be greater than zero.");
                            isValid = 0;
                        }

                        // Validate amountPaid
                        if (isNaN(amountPaid) || parseFloat(amountPaid) <= 0) {
                            $("#dueAmountPaidError").text("Please enter a valid amount paid.");
                            isValid = 0;
                        }

                        // Validate if amountPaid is less than dueTotal
                        if (parseFloat(amountPaid) < parseFloat(dueTotal)) {
                            $("#dueAmountPaidError").text("Amount paid is less than total due.");
                            isValid = 0;
                        }

                        // Check if at least one mode of payment is selected
                        let paymentModeError = false;
                        let modeSelected = false;

                        modeOfPaymentCheckboxes.forEach(checkbox => {
                            modeSelected = true;
                            const mode = checkbox.value;
                            const relatedInputField = form.querySelector(`input[name="due${mode}"]`);

                            if (mode === 'gpay' && relatedInputField && relatedInputField.value
                                .trim() === '') {
                                paymentModeError = true;
                                $("#dueModePaymentError").text("Gpay amount is required.");
                            }
                            if (mode === 'cash' && relatedInputField && relatedInputField.value
                                .trim() === '') {
                                paymentModeError = true;
                                $("#dueModePaymentError").text("Cash amount is required.");
                            }
                            if (mode === 'card' && relatedInputField && relatedInputField.value
                                .trim() === '') {
                                paymentModeError = true;
                                $("#dueModePaymentError").text("Card amount is required.");
                            }
                            if (mode === 'card') {
                                const machine = form.querySelector('select[name="duemachine"]').value;
                                if (machine === '') {
                                    paymentModeError = true;
                                    $("#dueModePaymentError").text("Please select a machine.");
                                }
                            }
                        });

                        if (!modeSelected) {
                            $("#dueModePaymentError").text("Please select at least one mode of payment.");
                            isValid = 0;
                        }

                        if (paymentModeError) {
                            isValid = 0;
                        }

                        // Validate balance to give back and checkbox
                        if (balanceToGiveBack > 0 && !balanceGiven) {
                            $("#dueCheckError").text(
                                "If balance is to be given back, checkbox must be checked.");
                            isValid = 0;
                        }

                        // If all validations pass, submit the form
                        if (isValid) {
                            //form.submit();
                            $.ajax({
                                url: form.action,
                                method: form.method,
                                data: $(form).serialize(),
                                success: function(response) {

                                    if (response.success) {
                                        // Create a Blob from the base64-encoded PDF data
                                        var blob = new Blob([new Uint8Array(atob(response.pdf)
                                            .split("").map(function(c) {
                                                return c.charCodeAt(0)
                                            }))], {
                                            type: "application/pdf"
                                        });


                                        var url = window.URL.createObjectURL(blob);

                                        var link = document.createElement("a");
                                        link.href = url;
                                        link.download = "outstanding_payment_receipt.pdf";
                                        document.body.appendChild(link);
                                        link.click();
                                        document.body.removeChild(link);

                                        var printWindow = window.open(url, "_blank");
                                        printWindow.onload = function() {
                                            printWindow.print();
                                        };

                                        window.location.href = billingRoute + "?success_message=" +
                                            encodeURIComponent(
                                                "Due payment successfully recorded.");
                                    } else {
                                        // Handle any other success messages
                                        var message = "Due payment successfully recorded.";
                                        window.location.href = billingRoute + "?success_message=" +
                                            encodeURIComponent(message);
                                    }
                                },
                                error: function(xhr) {
                                    // Handle the error
                                    console.log(xhr.responseText);
                                }
                            });
                        }
                    });
            }

            document
                .getElementById("duePrintPayment")
                .addEventListener("click", function() {
                    var bill_id = document.getElementById("dueBillInput").value;

                    $.ajax({
                        url: dueReceiptRoute,
                        type: "POST",
                        data: {
                            bill_id: bill_id,
                            _token: $('meta[name="csrf-token"]').attr(
                                "content"), // Include CSRF token for security
                        },
                        xhrFields: {
                            responseType: "blob", // Important for handling binary data like PDFs
                        },
                        success: function(response) {

                            var blob = new Blob([response], {
                                type: "application/pdf"
                            });
                            var url = window.URL.createObjectURL(blob);

                            var link = document.createElement("a");
                            link.href = url;
                            link.download = "outstanding_payment_receipt.pdf";
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);

                            var printWindow = window.open(url, "_blank");
                            printWindow.onload = function() {
                                printWindow.print();
                            };
                            window.location.href = billingRoute;
                        },
                        error: function(xhr, status, error) {
                            console.error("Error:", error);
                        },
                    });
                });
        });
    </script>
@endsection
