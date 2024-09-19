@extends('layouts.dashboard')
@section('title', 'Lab Bill Payments')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success"></div>

                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Lab Bill Payments</h3>
                    <a type="button" class="waves-effect waves-light btn btn-primary"
                            href="{{ route('labPayment.show') }}">Previous Payments</a>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <form method="post" action="{{ route('labPayment.store') }}" id="labBillForm">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-md-3 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="serviceFromDate">From <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="serviceFromDate" name="serviceFromDate"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-3 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="serviceToDate">To <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="serviceToDate" name="serviceToDate"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-3 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="serviceBranch">Branch <span class="text-danger">*</span></label>
                                        <select class="form-control" id="serviceBranch" name="serviceBranch" required>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch['id'] }}"> {{ $branch['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="technician_id">Select Technician <span class="text-danger">*</span></label>
                                        <select class="form-control" id="technician_id" name="technician_id">
                                            <option value="">Select Technician</option>
                                            @foreach ($technicians as $technician)
                                                <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                                            @endforeach
                                        </select>
                                        <div id="technicianError" class="invalid-feedback"></div>

                                    </div>
                                </div>

                                <div class="col-md-3 col-lg-2">
                                    <button type="submit" class="btn btn-success" id="generateBillOrderBtn">
                                    <i class="fa fa-credit-card"></i> Generate Bill
                                    </button>
                                </div>
                            </div>

                            <div id="labBillResults" style="display:none; margin-top: 20px;">
                                <div class="box-footer p-3" style="border-radius: 0px;">
                                    <h4 class="form-label text-center">Payment</h4>
                                    <div class="row" id="bill_place_section1">
                                        <div class="col-md-2 col-lg-2">
                                            <div class="form-group">
                                                <label class="form-label" for="bill_amount">Bill Amount <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="bill_amount" name="bill_amount"
                                                        required disabled>                                                     
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2">
                                            <div class="form-group">
                                                <label class="form-label" for="previous_due">Previous Due <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="previous_due" name="previous_due"
                                                        required disabled>                                                     
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2">
                                            <div class="form-group">
                                                <label class="form-label" for="order_date">Net Amount to be paid <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="amount_to_be_paid" name="amount_to_be_paid"
                                                        required disabled>                                                     
                                            </div>
                                        </div>
                                       
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="mode_of_payment">Mode of Payment<span class="text-danger">*</span></label>
                                                <br>

                                                        <!-- Checkbox for Gpay -->
                                                        <input type="checkbox" class="filled-in chk-col-success"
                                                            id="mode_of_payment_gpay" name="mode_of_payment[]"
                                                            value="gpay">
                                                        <label class="form-check-label me-2"
                                                            for="mode_of_payment_gpay">Gpay</label>
                                                        <input type="text" name="gpaycash" id="gpaycash"
                                                            class="form-control  w-100 " style="display: none;"
                                                            >
                                                        &nbsp;
                                                        <!-- Checkbox for Cash -->
                                                        <input type="checkbox" class="filled-in chk-col-success"
                                                            id="mode_of_payment_cash" name="mode_of_payment[]"
                                                            value="cash" >
                                                        <label class="form-check-label me-2"
                                                            for="mode_of_payment_cash">Cash</label>
                                                        <input type="text" name="cash" id="cash"
                                                            class="form-control  w-100" style="display: none;"
                                                            >
                                                        &nbsp;
                                                        <!-- Checkbox for Card -->
                                                        <input type="checkbox" class="filled-in chk-col-success"
                                                            id="mode_of_payment_card" name="mode_of_payment[]"
                                                            value="card">
                                                        <label class="form-check-label me-2"
                                                            for="mode_of_payment_card">Card</label>
                                                        <input type="text" name="cardcash" id="cardcash"
                                                            class="form-control  w-100 " style="display: none;"
                                                            >
                                                        <span class="error-message text-danger" id="modeError"></span>
                                                </div>    
                                        </div>
                                            
                                        <div class="col-md-2 col-lg-2">
                                            <div class="form-group">
                                                <label class="form-check-label me-2"
                                                                for="payment_through">Payment through<span class="text-danger">*</span></label>
                                                <select class="ms-2 form-select w-150" id="payment_through"
                                                name="payment_through">
                                                    <option value="Technician">Technician</option>
                                                    <option value="Lab Account">Lab Account</option>
                                                </select>
                                                <span class="error-message text-danger" id="payment_throughError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-1">
                                            <div class="form-group">
                                                <label class="form-label" for="amount_paid">Amount paid <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="amount_paid" name="amount_paid"
                                                     required readonly> 
                                                    
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-1">
                                        <div class="form-group">
                                                <label class="form-label" for="balance_due">Balance Due<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="balance_due" name="balance_due"
                                                     required disabled> 
                                                    
                                            </div>
                                        </div>
                                        
                                       
                                    </div>
                                    <div>
                                    <div class="col-md-3 col-lg-2">
                                            <br>
                                            <button type="submit" class="btn btn-success" id="payBillOrderBtn">
                                                <i class="fa fa-credit-card"></i> Pay Bill
                                            </button>
                                        </div>

                                    </div>
                                    <div class="box-footer p-3" style="border-radius: 0px;">
                                        <h4 class="form-label text-center">Detailed Bill</h4>

                                        <div id="tableContainer"></div> <!-- Table will be loaded here -->
                                    </div>
                                    
                                </div>  
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
    // Define the function to handle checkbox changes
    function handleCheckboxChange() {
        // Get the current checkbox states
        const gpayChecked = document.getElementById("mode_of_payment_gpay").checked;
        const cashChecked = document.getElementById("mode_of_payment_cash").checked;
        const cardChecked = document.getElementById("mode_of_payment_card").checked;

        // Show/Hide input fields based on checkbox state
        document.getElementById("gpaycash").style.display = gpayChecked ? "inline" : "none";
        document.getElementById("cash").style.display = cashChecked ? "inline" : "none";
        document.getElementById("cardcash").style.display = cardChecked ? "inline" : "none";
    }

    // Function to update payment details
    function updatePaymentDetails() {
        // Get values of the payment inputs
        const gpayAmount = parseFloat(document.getElementById("gpaycash").value) || 0;
        const cashAmount = parseFloat(document.getElementById("cash").value) || 0;
        const cardAmount = parseFloat(document.getElementById("cardcash").value) || 0;

        // Get total amount to be paid
        const totalAmount = parseFloat(document.getElementById("amount_to_be_paid").value) || 0;

        // Calculate the amount paid and balance due
        const totalPaid = gpayAmount + cashAmount + cardAmount;
        const balanceDue = totalAmount - totalPaid;

        // Update fields
        document.getElementById("amount_paid").value = totalPaid.toFixed(2);
        document.getElementById("balance_due").value = balanceDue.toFixed(2);
    }

    // Initialize payment textboxes with default value of 0.00
    document.getElementById("gpaycash").value = "0.00";
    document.getElementById("cash").value = "0.00";
    document.getElementById("cardcash").value = "0.00";

    // Attach change event listeners to checkboxes
    document.getElementById("mode_of_payment_gpay").addEventListener("change", handleCheckboxChange);
    document.getElementById("mode_of_payment_cash").addEventListener("change", handleCheckboxChange);
    document.getElementById("mode_of_payment_card").addEventListener("change", handleCheckboxChange);

    // Attach input event listeners to payment fields
    document.getElementById("gpaycash").addEventListener("input", updatePaymentDetails);
    document.getElementById("cash").addEventListener("input", updatePaymentDetails);
    document.getElementById("cardcash").addEventListener("input", updatePaymentDetails);

    // Initial call to set the correct state on page load
    handleCheckboxChange();
    updatePaymentDetails();        // jQuery document ready function
        $(document).ready(function() {
            $('#generateBillOrderBtn').click(function(e) {
                e.preventDefault();

                // Get the form data
                let fromDate = $('#serviceFromDate').val();
                let toDate = $('#serviceToDate').val();
                let branch = $('#serviceBranch').val();
                let technicianId = $('#technician_id').val();

                // Clear previous results
                $('#tableContainer').empty();
                $('#labBillResults').hide();
                if (technicianId.length == 0) {
                    $('#technician_id').addClass('is-invalid');
                    $('#technicianError').text('Technician is required.');
                    return; // Prevent further execution
                } else {
                    $('#technician_id').removeClass('is-invalid');
                    $('#technicianError').text('');
                }

                // Send the data via AJAX
                $.ajax({
                    url: '{{ route("labPayment.create") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        serviceFromDate: fromDate,
                        serviceToDate: toDate,
                        serviceBranch: branch,
                        serviceTechnicianId: technicianId
                    },
                    success: function(response) {
                        let data = response.data;

                        if (data.length === 0) {
                            // Show no data message
                            $('#tableContainer').html('<p class="text-center">No Bills pending to pay.</p>');
                            $('#labBillResults').show();
                            
                        } else {
                            // Build the table with the response data
                            let tableHtml = '<table class="table table-striped table-bordered">';
                            tableHtml += '<thead><tr><th class="text-center">No.</th><th class="text-center">Patient ID</th><th class="text-center">Patient Name</th><th class="text-center">Tooth</th><th class="text-center">Plan</th><th class="text-center">Shade</th><th class="text-center">Instructions</th><th class="text-center">Amount</th></tr></thead><tbody>';

                            $.each(data, function(index, item) {
                                tableHtml += '<tr>';
                                tableHtml += '<td class="text-center">';
                                tableHtml += `${index + 1}`;
                                tableHtml += '</td>';
                                tableHtml += `<td class="text-center">${item.patient_id}</td>`;
                                tableHtml += `<td class="text-center">${item.name}</td>`;
                                tableHtml += `<td class="text-center">${item.tooth_id}</td>`;
                                tableHtml += `<td class="text-center">${item.plan}</td>`;
                                tableHtml += `<td class="text-center">${item.shade}</td>`;
                                tableHtml += `<td>${item.instructions}</td>`;
                                tableHtml += `<td class="text-center">${item.amount}</td>`;
                                tableHtml += '</tr>';
                            });

                            tableHtml += '</tbody></table>';

                            // Display the table in the form
                            $('#tableContainer').html(tableHtml);
                            $('#previous_due').val(response.previous_due);
                            $('#bill_amount').val(response.bill_amount);
                           
                            $('#amount_to_be_paid').val(response.totalAmount);
                            $('#labBillResults').show();
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        alert('An error occurred while fetching data.');
                    }
                });
            });

            $('#payBillOrderBtn').click(function(e) {
                e.preventDefault();

                // Gather selected checkboxes
                let selectedRows = [];
                
                // Get other form data
                let technicianId = $('#technician_id').val();
                let fromDate = $('#serviceFromDate').val();
                let toDate = $('#serviceToDate').val();
                let bill_amount = $('#bill_amount').val();
                let previous_due = $('#previous_due').val();
                let amountPaid = $('#amount_paid').val();
                let balance_due = $('#balance_due').val();
                let gpaycash = $('#gpaycash').val();
                let cash = $('#cash').val();
                let cardcash = $('#cardcash').val();
                let branch = $('#serviceBranch').val();
                let amount_to_be_paid = $('#amount_to_be_paid').val();
                let payment_through = $('#payment_through').val();
                // Validate
                isValid = 1;
                if (technicianId.length == 0) {
                    $('#technician_id').addClass('is-invalid');
                    $('#technicianError').text('Technician is required.');
                    isValid = 0; // Prevent further execution
                } else {
                    $('#technician_id').removeClass('is-invalid');
                    $('#technicianError').text('');
                }
                

                if (payment_through.length == 0) {
                    $('#payment_through').addClass('is-invalid');
                    $('#payment_throughError').text('Please select.');
                    isValid = 0; // Prevent further execution
                } else {
                    $('#payment_through').removeClass('is-invalid');
                    $('#payment_throughError').text('');
                }

                const paymentModes = [];
                if ($('#mode_of_payment_gpay').is(':checked')) {
                    const gpayAmount = parseFloat($('#gpaycash').val()) || 0;
                    if (gpayAmount <= 0) {
                        $('#modeError').text('Gpay amount must be greater than zero if selected.');
                        isValid = false;
                    } else {
                        paymentModes.push('gpay');
                    }
                }

                if ($('#mode_of_payment_cash').is(':checked')) {
                    const cashAmount = parseFloat($('#cash').val()) || 0;
                    if (cashAmount <= 0) {
                        $('#modeError').text('Cash amount must be greater than zero if selected.');
                        isValid = false;
                    } else {
                        paymentModes.push('cash');
                    }
                }

                if ($('#mode_of_payment_card').is(':checked')) {
                    const cardAmount = parseFloat($('#cardcash').val()) || 0;
                    if (cardAmount <= 0) {
                        $('#modeError').text('Card amount must be greater than zero if selected.');
                        isValid = false;
                    } else {
                        paymentModes.push('card');
                    }
                }
                 if (paymentModes.length == 0) {
                    $('#modeError').text('Select payment mode and amount');
                    isValid = false;
                 } else{
                      $('#modeError').text('');
                 }

                // Send the data via AJAX
                if (isValid) {
                    $.ajax({
                        url: '{{ route("labPayment.store") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            technician_id: technicianId,
                            paymentModes: paymentModes,
                            from_date: fromDate,
                            to_date: toDate,
                            branch_id: branch,
                            gpay:gpaycash,
                            cash:cash,
                            card:cardcash,
                            payment_through:payment_through,
                            amount_to_be_paid : amount_to_be_paid,
                            bill_amount :bill_amount,
                            previous_due:previous_due,
                            amount_paid:amountPaid,
                            balance_due:balance_due,
                            // include other necessary data
                        },
                        success: function(response) {
                            $('#labBillForm')[0].reset(); // Reset the form
                            $('#labBillResults').hide(); // Hide results section if needed

                            // If you have any specific fields that need to be reset to a particular value:
                            $('#serviceFromDate').val(new Date().toISOString().split('T')[0]); // Reset to today
                            $('#serviceToDate').val(new Date().toISOString().split('T')[0]); // Reset to today

                            // Optionally, you can clear the detailed bill table if it exists
                            $('#tableContainer').empty();
                            $('#successMessage').text("Bill paid orders updated Successfully").show();
                            
                            
                        },
                        error: function(error) {
                            console.log(error);
                            alert('An error occurred while saving the order.');
                        }
                    });
                }
            });
        });
    });
</script>

@endsection
