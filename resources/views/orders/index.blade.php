@extends('layouts.dashboard')
@section('title', 'Orders')
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
                    <h3 class="page-title">Place Orders</h3>
                </div>
            </div>

            <section class="content">
                <form method="post" action="{{ route('orders.store') }}" id="orderForm">
                    @csrf
                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="serviceFromDate">From <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="serviceFromDate"
                                            name="serviceFromDate" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="serviceToDate">To <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="serviceToDate" name="serviceToDate"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="serviceBranch">Branch <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="serviceBranch" name="serviceBranch" required>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch['id'] }}"> {{ $branch['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="serviceTreatmentPlan">Treatment Plan</label>
                                        <select class="form-control" id="serviceTreatmentPlan" name="serviceTreatmentPlan">
                                            <option value="">All</option>
                                            @foreach ($treatmentPlans as $treatmentPlan)
                                                <option value="{{ $treatmentPlan->id }}"> {{ $treatmentPlan->plan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer text-end p-3">
                            <button type="submit" class="btn btn-info" id="searchPlaceOrderBtn">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>

                        <!-- Technicians Dropdown and Table Container -->
                        <div class="box no-border mb-0" id="orderResults" style="display:none;">
                            <div class="box-header py-2">
                                <h4 class="box-title">Place Order</h4>
                            </div>

                            <div class="box-body">
                                <div class="row" id="order_place_section1">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="technician_id">Select Technician <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="technician_id" name="technician_id" required>
                                                <option value="">Select Technician</option>
                                                @foreach ($technicians as $technician)
                                                    <option value="{{ $technician->id }}">{{ $technician->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div id="technicianError" class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="order_date">Order Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" id="order_date"
                                                name="order_date" value="{{ date('Y-m-d') }}" required>
                                            <div id="orderDateError" class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="delivery_expected">Delivery Expected on
                                                <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" id="delivery_expected"
                                                name="delivery_expected" value="{{ date('Y-m-d') }}" required>
                                            <div id="deliveryExpectedError" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="order_place_section2">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="selectRow">Select Patients <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div id="selectPatientError" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tableContainer"></div> <!-- Table will be loaded here -->
                            </div>

                            @if (Auth::user()->can('order place store'))
                                <div class="box-footer p-3 text-end" id="order_place_section3">
                                    <button type="button" class="btn btn-success" id="savePlaceOrderBtn">
                                        <i class="fa fa-save"> </i> Save
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            var now = new Date().toISOString().slice(0, 16);
            document.getElementById('order_date').setAttribute('min', now);
            document.getElementById('delivery_expected').setAttribute('min', now);

            $('#searchPlaceOrderBtn').click(function(e) {
                e.preventDefault();

                // Get the form data
                let fromDate = $('#serviceFromDate').val();
                let toDate = $('#serviceToDate').val();
                let branch = $('#serviceBranch').val();
                let treatmentPlan = $('#serviceTreatmentPlan').val();

                // Clear previous results
                $('#tableContainer').empty();
                $('#orderResults').hide();
                $('#order_place_section1').show();
                $('#order_place_section2').show();
                $('#order_place_section3').show();

                // Send the data via AJAX
                $.ajax({
                    url: '{{ route('orders.create') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        serviceFromDate: fromDate,
                        serviceToDate: toDate,
                        serviceBranch: branch,
                        serviceTreatmentPlan: treatmentPlan
                    },
                    success: function(response) {
                        let data = response.data;

                        if (data.length === 0) {
                            // Show no data message
                            $('#tableContainer').html(
                                '<p class="text-center">No treatment plans pending to order.</p>'
                            );
                            $('#orderResults').show();
                            $('#order_place_section1').hide();
                            $('#order_place_section2').hide();
                            $('#order_place_section3').hide();
                        } else {
                            // Build the table with the response data
                            let tableHtml =
                                '<table class="table table-striped table-bordered">';
                            tableHtml +=
                                '<thead><tr><th class="text-center">Select</th><th class="text-center">Patient ID</th><th class="text-center">Patient Name</th><th class="text-center">Tooth</th><th class="text-center">Plan</th><th class="text-center">Shade</th><th class="text-center">Instructions</th></tr></thead><tbody>';

                            $.each(data, function(index, item) {
                                tableHtml += '<tr>';
                                tableHtml += '<td class="text-center">';
                                tableHtml +=
                                    `<input type="checkbox" id="checkbox${index}" name="selectRow[]" value="${item.id}" class="filled-in chk-col-success" />`;
                                tableHtml += `<label for="checkbox${index}"></label>`;
                                tableHtml += '</td>';
                                tableHtml +=
                                    `<td class="text-center">${item.patient_id}</td>`;
                                tableHtml +=
                                    `<td class="text-center">${item.name}</td>`;
                                tableHtml +=
                                    `<td class="text-center">${item.tooth_id}</td>`;
                                tableHtml +=
                                    `<td class="text-center">${item.plan}</td>`;
                                tableHtml +=
                                    `<td class="text-center">${item.shade}</td>`;
                                tableHtml += `<td>${item.instructions}</td>`;
                                tableHtml += '</tr>';
                            });

                            tableHtml += '</tbody></table>';

                            // Display the table in the form
                            $('#tableContainer').html(tableHtml);
                            $('#orderResults').show();
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        alert('An error occurred while fetching data.');
                    }
                });
            });

            $('#savePlaceOrderBtn').click(function(e) {
                e.preventDefault();

                // Gather selected checkboxes
                let selectedRows = [];
                $('input[name="selectRow[]"]:checked').each(function() {
                    selectedRows.push($(this).val());
                });

                // Get other form data
                let technicianId = $('#technician_id').val();
                let orderDate = $('#order_date').val();
                let deliveryExpected = $('#delivery_expected').val();
                let branch = $('#serviceBranch').val();
                // Validate
                isValid = 1;
                if (selectedRows.length === 0) {
                    $('#selectPatientError').text('Atleast one rowneed to be selected.');
                    isValid = 0; // Prevent further execution
                }
                if (technicianId.length == 0) {
                    $('#technician_id').addClass('is-invalid');
                    $('#technicianError').text('Technician is required.');
                    isValid = 0; // Prevent further execution
                } else {
                    $('#technician_id').removeClass('is-invalid');
                    $('#technicianError').text('');
                }

                if (orderDate.length == 0) {
                    $('#order_date').addClass('is-invalid');
                    $('#orderDateError').text('Order date is required.');
                    isValid = 0; // Prevent further execution
                } else {
                    $('#order_date').removeClass('is-invalid');
                    $('#orderDateError').text('');
                }
                if (deliveryExpected.length == 0) {
                    $('#delivery_expected').addClass('is-invalid');
                    $('#deliveryExpectedError').text('Delivery Expected Date is required.');
                    isValid = 0; // Prevent further execution
                } else {
                    $('#delivery_expected').removeClass('is-invalid');
                    $('#deliveryExpectedError').text('');
                }


                // Send the data via AJAX
                if (isValid) {
                    $.ajax({
                        url: '{{ route('orders.store') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            selectedRows: selectedRows,
                            technician_id: technicianId,
                            order_date: orderDate,
                            delivery_expected: deliveryExpected,
                            branch: branch
                        },
                        success: function(response) {
                            console.log(response, 'response');
                            $('#successMessage').text("Order Placed Successfully").show();
                            $('#orderForm')[0].reset();
                            $('#tableContainer').empty();
                            $('#orderResults').hide();
                            if (response.pdfUrl) {
                                // Open the PDF in a new window and trigger print dialog
                                var printWindow = window.open(response.pdfUrl, "_blank");
                                printWindow.addEventListener("load", function() {
                                    printWindow.print();
                                });

                                // Redirect after printing
                                printWindow.addEventListener("afterprint", function() {
                                    window.location.href =
                                        "{{ route('order.place_order') }}";
                                });
                            } else {
                                alert("Failed to generate PDF.");
                            }

                        },
                        error: function(error) {
                            console.log(error);
                            alert('An error occurred while saving the order.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
