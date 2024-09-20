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
                    <h3 class="page-title">Update Orders</h3>
                </div>
            </div>

            <section class="content">
                <form method="get" action="{{ route('order.track_order') }}" id="orderForm">
                    @csrf
                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label" for="serviceFromDate">From <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="serviceFromDate"
                                            name="serviceFromDate" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label" for="serviceToDate">To <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="serviceToDate" name="serviceToDate"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="serviceBranch">Branch <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="serviceBranch" name="serviceBranch" required>
                                            <option value="">All</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch['id'] }}"> {{ $branch['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label" for="technician_id">Select Technician <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="technician_id" name="technician_id" required>
                                            <option value="">Select Technician</option>
                                            @foreach ($technicians as $technician)
                                                <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                                            @endforeach
                                        </select>
                                        <div id="technicianError" class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label" for="order_status">Select Order Status</label>
                                        <select class="form-control" id="order_status" name="order_status">
                                            <option value="">All</option>
                                            <option value="{{ App\Models\OrderPlaced::CANCELLED }}">
                                                {{ App\Models\OrderPlaced::CANCELLED_DESC }}</option>
                                            <option value="{{ App\Models\OrderPlaced::DELIVERED }}">
                                                {{ App\Models\OrderPlaced::DELIVERED_DESC }}</option>
                                            <option value="{{ App\Models\OrderPlaced::PLACED }}">
                                                {{ App\Models\OrderPlaced::PLACED_DESC }}</option>
                                            <option value="{{ App\Models\OrderPlaced::PENDING }}">
                                                {{ App\Models\OrderPlaced::PENDING_DESC }}</option>
                                            <option value="{{ App\Models\OrderPlaced::REPEAT }}">
                                                {{ App\Models\OrderPlaced::REPEAT_DESC }}</option>
                                        </select>
                                        <div id="statusError" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer text-end p-3">
                            <button type="submit" class="btn btn-info" id="search">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>

                        <!-- Technicians Dropdown and Table Container -->
                        <div class="box-body" id="ordersTableDiv" style="display:none;">
                            <div id="orderResults">
                                <ul class="list-style-none ps-0" id="info" style="display:none;">
                                    <li><span class="badge badge-dot badge-info"></span>&nbsp; P: Order Placed On</li>
                                    <li><span class="badge badge-dot badge-warning"></span>&nbsp; E: Expected Delivery Date
                                    </li>
                                    <li><span class="badge badge-dot badge-success"></span>&nbsp; D : Delivered On
                                    </li>
                                </ul>
                                <div class="table-responsive" style="width: 100%; overflow-x: auto;">
                                    <table
                                        class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                                        id="ordersTable" width="100%">
                                        <thead class="bg-primary-light">
                                            <tr>
                                                <th width="20px">No</th>
                                                <!-- <th>Technician</th> -->
                                                <th>Patient Id - Name</th>
                                                <th>Tooth Id</th>
                                                <th>Treatment Plan</th>
                                                <th>Shade</th>
                                                <th>Instructions</th>
                                                <th width="100px">Dates</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>

    @include('orders.cancel')
    @include('orders.reorder')
    @include('orders.delivered')
    <script type="text/javascript">
        var table; // Define table variable in the global scope

        jQuery(function($) {
            var clinicBasicDetails = @json($clinicBasicDetails);
            $("#ordersTableDiv").hide();
            $('#orderForm').on('submit', function(e) {
                $("#ordersTableDiv").show();
                e.preventDefault(); // Prevent the default form submission

                if ($.fn.DataTable.isDataTable("#ordersTable")) {
                    // Destroy existing DataTable instance if it exists
                    $("#ordersTable").DataTable().destroy();
                }

                table = $("#ordersTable").DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: $(this).attr('action'), // Use form's action URL
                        type: "GET",
                        data: function(d) {
                            // Send the form data as AJAX parameters
                            d.serviceFromDate = $('#serviceFromDate').val();
                            d.serviceToDate = $('#serviceToDate').val();
                            d.serviceBranch = $('#serviceBranch').val();
                            d.technician_id = $('#technician_id').val();
                            d.order_status = $('#order_status').val();
                        }
                    },
                    columns: [{
                            data: "DT_RowIndex",
                            name: "DT_RowIndex",
                            className: "text-center",
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            },
                        },
                        // {
                        //     data: "technician",
                        //     name: "technician",
                        //     className: "text-left",
                        // },
                        {
                            data: "patient",
                            name: "patient",
                            className: "text-left",
                        },
                        {
                            data: "tooth_id",
                            name: "tooth_id",
                            className: "text-left",
                        },
                        {
                            data: "treatment_plan",
                            name: "treatment_plan",
                            className: "text-left",
                        },
                        {
                            data: "shade",
                            name: "shade",
                            className: "text-left",
                        },
                        {
                            data: "instructions",
                            name: "instructions",
                            className: "text-left",
                        },
                        {
                            data: "dates",
                            name: "dates",
                            className: "text-left",
                            render: function(data, type, row) {
                                if (type === 'display') {
                                    return "P: " + row.order_placed_on + "<br>" +
                                        "E: " + row.delivery_expected_on + "<br>" +
                                        "D: " + (row.delivered_on ? row.delivered_on :
                                            'N/A');
                                } else if (type === 'export') {
                                    // For export, return an array of strings (separate lines)
                                    return ["P: " + row.order_placed_on,
                                        "E: " + row.delivery_expected_on,
                                        "D: " + (row.delivered_on ? row.delivered_on :
                                            'N/A')
                                    ];
                                } else {
                                    // Default export case
                                    return "P: " + row.order_placed_on + "\n" +
                                        "E: " + row.delivery_expected_on + "\n" +
                                        "D: " + (row.delivered_on ? row.delivered_on :
                                            'N/A');
                                }
                            }
                        },

                        {
                            data: "status",
                            name: "status",
                            className: "text-center",
                        },
                        {
                            data: "action",
                            name: "action",
                            className: "text-center",
                            orderable: false,
                            searchable: true,
                        },
                    ],
                    dom: 'Bfrtlp',
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, 'All']
                    ],
                    // select: true,
                    buttons: [{
                            extend: 'print',
                            text: 'Print',
                            title: clinicBasicDetails.clinic_name,
                            messageTop: 'Legend\nP: Order Placed On\nE: Expected Delivery Date\nD: Delivered On\n\nTrack Order Report',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            footer: true,
                            filename: 'Track Order Report',
                            exportOptions: {
                                columns: ':visible:not(:last-child)',
                                format: {
                                    body: function(data, row, column, node) {
                                        return data.replace(/<br\s*\/?>/ig,
                                            '\n'); // Replace <br> with newline for print
                                    }
                                }
                            },
                            customize: function(win) {
                                $(win.document.body).css('font-size', '10pt');
                                $(win.document.body).find('table').addClass('compact').css(
                                    'font-size', 'inherit');
                            }
                        },

                        {
                            extend: 'excelHtml5',
                            text: 'Excel',
                            title: clinicBasicDetails.clinic_name,
                            messageTop: 'Legend\nP: Order Placed On\nE: Expected Delivery Date\nD: Delivered On\n\nTrack Order Report',
                            footer: true,
                            filename: 'Track Order Report',
                            exportOptions: {
                                columns: ':visible:not(:last-child)',
                                format: {
                                    body: function(data, row, column, node) {
                                        // Replace <br> or similar with actual newline
                                        return data.replace(/<br\s*\/?>/ig, '\n');
                                    }
                                }
                            }
                        },

                        {
                            extend: 'pdfHtml5',
                            text: 'PDF',
                            title: clinicBasicDetails.clinic_name,
                            messageTop: 'Track Order Report',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            footer: true,
                            filename: 'Track Order Report',
                            exportOptions: {
                                columns: ':visible:not(:last-child)' // Exclude the action column if needed
                            },
                            customize: function(doc) {
                                doc.defaultStyle.fontSize = 10;
                                doc.styles.tableHeader.fontSize = 10;

                                // Ensure each line in the dates column is treated as a separate line in PDF
                                doc.content.forEach(function(contentItem) {
                                    if (contentItem.table) {
                                        contentItem.table.body.forEach(function(
                                            row) {
                                            row.forEach(function(cell) {
                                                if (typeof cell ===
                                                    'string' ||
                                                    Array.isArray(
                                                        cell)) {
                                                    // Replace <br> with actual newlines and handle arrays
                                                    if (Array
                                                        .isArray(
                                                            cell)) {
                                                        cell = cell
                                                            .join(
                                                                '\n'
                                                            );
                                                    }
                                                    cell = cell
                                                        .replace(
                                                            /<br\s*\/?>/ig,
                                                            '\n');
                                                }
                                            });
                                        });
                                    }
                                });

                                // Add the legend to the PDF body
                                doc.content.splice(1, 0, {
                                    text: 'Legend\nP: Order Placed On\nE: Expected Delivery Date\nD: Delivered On',
                                    margin: [0, 0, 0, 12],
                                    fontSize: 9
                                });
                            }
                        }
                    ],

                    drawCallback: function() {
                        // Check if there are any records
                        if (table.data().count() > 0) {
                            $('#info').show(); // Show the order results container
                        } else {
                            $('#info').hide(); // Hide the order results container
                        }

                    }
                });

                // Show the order results container after initializing DataTable
                $('#orderResults').show();
            });
        });


        $(document).on('click', '#btn-cancell', function() {
            var orderId = $(this).data('id');
            $('#cancel_order_id').val(orderId); // Set staff ID in the hidden input
            $('#modal-cancell').modal('show');
        });
        $('#btn-cancel-order').click(function() {
            var orderId = $('#cancel_order_id').val();
            var reason = $('#order_cancel_reason').val();

            if (reason.length === 0) {
                $('#order_cancel_reason').addClass('is-invalid');
                $('#reasonError').text('Reason is required.');
                return; // Stop further execution
            }

            var url = "{{ route('order.destroy', ':orderId') }}";
            url = url.replace(':orderId', orderId);

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "order_cancel_reason": reason
                },
                success: function(response) {
                    $('#modal-cancell').modal('hide'); // Close modal after success
                    table.draw(); // Refresh DataTable
                    $('#successMessage').text('Order cancelled successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds

                },
                error: function(xhr) {
                    $('#modal-cancell').modal(
                        'hide'); // Close modal in case of error
                    console.log("Error!", xhr.responseJSON.message, "error");
                }
            });
        });
        $(document).on('click', '#btn-deliver', function() {
            var orderId = $(this).data('id');
            $('#order_id').val(orderId); // Set staff ID in the hidden input
            $('#modal-deliver').modal('show');
        });
        $('#btn-confirm-deliver').click(function() {
            var orderId = $('#order_id').val();
            var url = "{{ route('order.update', ':orderId') }}";
            url = url.replace(':orderId', orderId);

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    $('#modal-deliver').modal('hide'); // Close modal after success
                    table.draw(); // Refresh DataTable
                    $('#successMessage').text('Order delivered successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds

                },
                error: function(xhr) {
                    $('#modal-deliver').modal(
                        'hide'); // Close modal in case of error
                    console.log("Error!", xhr.responseJSON.message, "error");
                }
            });
        });

        $(document).on('click', '#btn-reorder', function() {
            var orderId = $(this).data('id');
            var patientName = $(this).data('patient-name');
            var shade = $(this).data('shade');
            $('#repeat_order_id').val(orderId); // Set order ID in the hidden input

            $.ajax({
                url: '{{ url('track_order') }}/' + orderId + '/edit', // Fixed URL concatenation
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    $('#patient_id').val(response.patient_id);
                    $('#patient_name').val(patientName);
                    $('#treatment_plan_id').val(response.treatment_plan_id); // Corrected ID
                    $('#shade_id').val(response.tooth_examination.shade_id); // Corrected shade ID
                    $('#instructions').val(response.tooth_examination
                        .instructions); // Corrected shade ID
                    $('#modal-reorder').modal('show');
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        $('#btn-confirm-reorder').click(function() {
            var orderId = $('#repeat_order_id').val();
            var reason = $('#repeat_reason').val();
            var billable = $('#billable').val();
            var orderDate = $('#order_date').val();
            var deliveryExpected = $('#delivery_expected').val();
            isValid = 1;
            if (reason.length === 0) {
                $('#repeat_reason').addClass('is-invalid');
                $('#repeatReasonError').text('Reason is required.');
                isValid = 0; // Stop further execution
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

            var url = "{{ route('order.repeat', ':orderId') }}";
            url = url.replace(':orderId', orderId);
            if (isValid) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "reason": reason,
                        "billable": billable,
                        "order_placed_on": orderDate,
                        "delivery_expected_on": deliveryExpected,
                    },
                    success: function(response) {
                        $('#modal-reorder').modal('hide'); // Close modal after success
                        table.draw(); // Refresh DataTable
                        $('#successMessage').text('Repeat Order placed successfully');
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds

                    },
                    error: function(xhr) {
                        $('#modal-reorder').modal(
                            'hide'); // Close modal in case of error
                        console.log("Error!", xhr.responseJSON.message, "error");
                    }
                });

            }
        });
    </script>

@endsection
