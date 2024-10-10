@extends('layouts.dashboard')
@section('title', 'Salary')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">
                </div>
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
                    <h3 class="page-title">Employee Salary</h3>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <!-- Main content -->
                            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                                width="100%">
                                <thead class="bg-primary-light text-center">
                                    <tr>
                                        <th width="10px">No</th>
                                        <th width="100px">Staff ID</th>
                                        <th width="60px">Photo</th>
                                        <th class="text-center">Name</th>
                                        <th width="80px">Role</th>
                                        <th width="100px">Phone Number</th>
                                        <th class="text-center">Branch</th>
                                        <th width="150px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <!-- /.content -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->

    @include('payroll.salary.cancel')

    <script type="text/javascript">
        var table;
        jQuery(function($) {

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Adding 1 to start counting from 1
                        }
                    },
                    {
                        data: 'staff_id',
                        name: 'staff_idid'
                    },
                    {
                        data: 'photo',
                        name: 'photo',
                        render: function(data, type, full, meta) {
                            if (data) {
                                data = "{{ asset('storage/') }}/" + data;
                            } else {
                                data = "{{ asset('images/svg-icon/user.svg') }}";
                            }
                            return '<img src="' + data +
                                '" height="50" style="border-radius:50%;"/>';
                        },
                        orderable: false,
                        searchable: false

                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'text-start'
                    },
                    {
                        data: 'role',
                        name: 'role'
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: true
                    },
                ]
            });
        });

        $(document).ready(function() {

            $("#modal-cancel-lab-bill").on("hidden.bs.modal", function() {
                // Reset the form
                $("#form-cancel-purchase")[0].reset();

                // Clear specific fields
                $("#user_id").val("");
                $("#bill_cancel_reason").val("");

                // Optionally, remove any validation messages
                $("#reasonError").text("");
            });

            $(document).on("click", ".btn-del", function() {
                var userId = $(this).data("id");
                $("#user_id").val(userId);
                $("#modal-cancel-lab-bill").modal("show");
            });

            $("#btn-cancel-bill").click(function() {
                var userId = $("#user_id").val();
                var reason = $("#bill_cancel_reason").val();

                if (reason.length === 0) {
                    $("#bill_cancel_reason").addClass("is-invalid");
                    $("#reasonError").text("Reason is required.").show();
                    return; // Stop further execution
                }

                var url = "/employee_salary/cancel/{id}";
                url = url.replace("{id}", userId);

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    type: "POST",
                    url: url,
                    data: {
                        // _token: "{{ csrf_token() }}",
                        reason: reason,
                    },
                    success: function(response) {
                        $("#modal-cancel-lab-bill").modal("hide"); // Close modal after success
                        table.draw(); // Refresh DataTable
                        $("#successMessage").text("Salary cancelled successfully");
                        $("#successMessage").fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                    },
                    error: function(xhr) {
                        $("#modal-cancel-lab-bill").modal(
                            "hide"); // Close modal in case of error
                        console.log("Error!", xhr.responseJSON.message, "error");
                    },
                });
            });

            $(document).on('click', '.btn-salaryslip-pdf-generate', function() {
                var userId = $(this).data('id');
                const url = '{{ route('download.salaryslip') }}';

                // Make the AJAX request
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        user_id: userId,
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    xhrFields: {
                        responseType: 'blob' // Important for handling binary data like PDFs
                    },
                    success: function(response) {
                        var blob = new Blob([response], {
                            type: 'application/pdf'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = 'patientidcard.pdf';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        // For printing, open the PDF in a new window or iframe and call print
                        var printWindow = window.open(link.href, '_blank');
                        printWindow.onload = function() {
                            printWindow.print();
                        };
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
@endsection
