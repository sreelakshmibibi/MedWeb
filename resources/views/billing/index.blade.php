@extends('layouts.dashboard')
@section('title', 'Billing')
@section('content')

    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">

                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Billing List</h3>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div id="billing_paginator"></div>
                        <br />
                        <div class="table-responsive">

                            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                                id="billing_table" width="100%">
                                <thead class="bg-primary-light">
                                    <tr>
                                        <th width="10px">Token No</th>
                                        <th width="60px">Patient ID</th>
                                        <th class="text-center">Patient Name</th>
                                        <th width="60px">Phone number</th>
                                        <th class="text-center" width="180px">Branch</th>
                                        <th class="text-center">Consulted Doctor</th>
                                        <!-- <th>Bill Amount</th> -->
                                        <th>Status</th>
                                        <th width="144px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @include('billing.cancel')

    <script type="text/javascript">
        var selectedDate;
        var table;

        $(document).ready(function() {

            $("#billing_paginator").datepaginator({
                onSelectedDateChanged: function(a, t) {
                    selectedDate = moment(t).format("YYYY-MM-DD");
                    table.ajax.reload();
                },
            });
            // var initialDate = $("#billing_paginator").datepaginator("getDate");
            // selectedDate = moment(initialDate).format("YYYY-MM-DD")
            selectedDate = moment().format("YYYY-MM-DD");
        });

        jQuery(function($) {
            table = $('#billing_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('billing') }}",
                    type: 'GET',
                    data: function(d) {
                        d.selectedDate = selectedDate;
                    }
                },
                columns: [{
                        data: 'token_no',
                        name: 'token_no'
                    },
                    {
                        data: 'patient_id',
                        name: 'patient_id'
                    },

                    {
                        data: 'name',
                        name: 'name',
                        className: 'text-start'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'branch',
                        name: 'branch',
                        className: 'text-start'
                    },

                    {
                        data: 'doctor',
                        name: 'doctor',
                        className: 'text-start'
                    },
                    // {
                    //     data: 'amount',
                    //     name: 'amount'
                    // },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: true
                    },
                ]
            });
        });

        $(document).on('click', '.btn-pdf-generate', function() {
            var appId = $(this).data('app-id');
            var parentId = $(this).data('parent-id');
            var patientId = $(this).data('patient-id');

            $('#pdf_appointment_id').val(appId);
            $('#pdf_patient_id').val(patientId);
            $('#pdf_app_parent_id').val(parentId);
            $('#pdfType').val('appointment'); // Default to 'appointment'
            $('#toothSelection').addClass('d-none'); // Hide tooth selection by default
            $('#modal-download').modal('show'); // Show the modal
        });

        $(document).on('click', '#btn-cancel-bill', function() {
            var billId = $(this).data('id');
            $('#delete_bill_id').val(billId);
            $('#modal-cancel-bill').modal('show');
        });

        $('#btn-confirm-cancel').click(function() {
            var billId = $('#delete_bill_id').val();
            var reason = $('#reason').val();

            if (reason.length === 0) {
                $('#reason').addClass('is-invalid');
                $('#reasonError').text('Reason is required.');
                return; // Stop further execution
            }

            var url = "{{ route('billing.destroy', ':billId') }}";
            url = url.replace(':billId', billId);

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "reason": reason
                },
                success: function(response) {
                    $('#modal-cancel-bill').modal('hide'); // Close modal after success
                    table.draw(); // Refresh DataTable
                    $('#successMessage').text('Bill cancelled successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds

                },
                error: function(xhr) {
                    $('#modal-cancel-bill').modal(
                        'hide'); // Close modal in case of error
                    console.log("Error!", xhr.responseJSON.message, "error");
                }
            });
        });

        $(document).on('click', '.printTreatmentBillbtn', function() {
            // $('.printTreatmentBillbtn').click(function() {
            var billId = $(this).data('id');
            var appointmentId = $(this).data('appid');
            var receiptRoute = "{{ route('billing.paymentReceipt') }}";
            // AJAX request to generate the PDF
            fetch('/billing/paymentReceipt', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({
                        billId: billId,
                        appointmentId: appointmentId,
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.pdfUrl) {
                        // Open the PDF in a new window and trigger print dialog
                        var printWindow = window.open(data.pdfUrl, "_blank");
                        printWindow.addEventListener("load", function() {
                            printWindow.print();
                        });

                        // Redirect after printing
                        printWindow.addEventListener("afterprint", function() {
                            window.location.href = "{{ route('billing') }}";
                        });
                    } else {
                        alert("Failed to generate PDF.");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        });

        $(document).on('click', '.printMedicineBillbtn', function() {});
    </script>
@endsection
