@extends('layouts.dashboard')
@section('title', 'Attendance')
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
                    <h3 class="page-title">Attendance</h3>
                </div>
            </div>

            <section class="content">
                <form method="post" action="{{ route('attendance.store') }}" id="orderForm">
                    @csrf
                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="todays_date">From <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="todays_date"
                                            name="todays_date" value="{{ date(format: 'Y-m-d') }}" required>
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
                            </div>
                        </div>
                        <div class="box-footer text-end p-3">
                            <button type="submit" class="btn btn-info" id="searchAttendanceBtn">
                                <i class="fa fa-search"></i> Get Attendance
                            </button>
                        </div>

                        <!-- Technicians Dropdown and Table Container -->
                        <div class="box no-border mb-0" id="orderResults" style="display:none;">
                            <div class="box-header py-2">
                                <h4 class="box-title">Staff Attendance</h4>
                            </div>

                            <div class="box-body">
                                
                                <div id="tableContainer"></div> <!-- Table will be loaded here -->
                            </div>

                            @if (Auth::user()->can('order place store'))
                                <div class="box-footer p-3 text-end" id="order_place_section3">
                                    <button type="button" class="btn btn-success" id="savePlaceOrderBtn">
                                        <i class="fa fa-save"> </i> Update
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
            $('#searchAttendanceBtn').click(function(e) {
                e.preventDefault();

                // Get the form data
                let attendance_date = $('#attendance_date').val();
                let branch = $('#serviceBranch').val();
                
                // Clear previous results
                $('#tableContainer').empty();
                $('#orderResults').hide();
                $('#order_place_section3').show();

                // Send the data via AJAX
                $.ajax({
                    url: '{{ route("attendance.create") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        attendance_date: attendance_date,
                        serviceBranch: branch,
                        
                    },
                    success: function(response) {
                        let data = response.data;

                        if (data.length === 0) {
                            // Show no data message
                            $('#tableContainer').html(
                                '<p class="text-center">No treatment plans pending to order.</p>'
                            );
                            $('#orderResults').show();
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
