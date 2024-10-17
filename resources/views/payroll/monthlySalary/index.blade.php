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
                    <h3 class="page-title">Employee Monthly Salary</h3>

                    {{-- <a type="button" class="waves-effect waves-light btn btn-primary" title="Back"
                        href="">
                        <span class="hidden-sm-up"><i class="fa-solid fa-angles-left"></i></span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-angles-left"></i> Back</span>
                    </a> --}}
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="salaryMonth">Month<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control " type="text" id="salaryMonth" name="salaryMonth">

                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="salaryYear">Year<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control " type="text" id="salaryYear" name="salaryYear">

                                        @for ($i = 0; $i < sizeof($years); $i++)
                                            <option value="{{ $years[$i] }}">{{ $years[$i] }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="salaryBranch">Branch </label>
                                    <select class="form-control" id="salaryBranch" name="salaryBranch" required>
                                        <option value="">All</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch['id'] }}"> {{ $branch['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="salaryEmployee">Employee</label>
                                    <select class="form-control" id="salaryEmployee" name="salaryEmployee">
                                        <option value="">All</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">
                                                {{ str_replace('<br>', ' ', $employee->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div id="errorDiv" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="box-footer text-end p-3">
                        <button type="submit" class="btn btn-info" id="searchAttdBtn">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>

                    {{-- Report --}}
                    <div class="box-body no-border mb-0" id="monthlyReportDiv" style="display:none;">
                        <div class="table-responsive">
                            <table width="100%" id="monthlySalaryTable"
                                class="table table-bordered table-hover table-striped mb-0 data-table text-center">
                                <thead class="bg-primary-light text-center">
                                    <tr>
                                        <th width="10px">No</th>
                                        <th width="100px">Staff ID</th>
                                        <th width="60px">Photo</th>
                                        <th class="text-center">Name</th>
                                        <th width="80px">Role</th>
                                        <th width="100px">Phone Number</th>
                                        <th class="text-center">Branch</th>
                                        <th class="text-center">Salary Month</th>
                                        <th width="150px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box no-border mb-0" id="employeeReportDiv" style="display:none;">
                        <div class="table-responsive">
                            <table width="100%" id="employeeSalaryTable"
                                class="table table-bordered table-hover table-striped mb-0 data-table text-center">
                                <thead class="bg-primary-light text-center">
                                    <tr>
                                        <th width="10px">No</th>
                                        <th width="100px">Name</th>
                                        <th width="60px">Designation</th>
                                        <th class="text-center">Branch</th>
                                        <th width="80px">Salary Month</th>
                                        <th width="100px">Working Days</th>
                                        <th class="text-center">Paid Days</th>
                                        <th class="text-center">Unpaid Days</th>
                                        <th class="text-center">Basic Pay</th>
                                        <th class="text-center">Loss of Pay</th>
                                        <th class="text-center">Deductions</th>
                                        <th class="text-center">incentives</th>
                                        <th class="text-center">Net Salary</th>
                                        <th class="text-center">Previous Due Given</th>
                                        <th class="text-center">Advance</th>
                                        <th class="text-center">Advance Recovered</th>
                                        <th class="text-center">Paid Salary</th>
                                        <th class="text-center">Balance Due</th>
                                        <th class="text-center">Salary Paid Date</th>
                                        <th width="150px">Action</th>
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
    <!-- /.content-wrapper -->

    @include('payroll.monthlySalary.cancel')

    <script>
        var clinicBasicDetails = @json($clinicBasicDetails);
        var getReportUrl = "{{ route('employeeMonthlySalary') }}";

        var table;
        let currentDate = new Date();
        let currentMonth = String(currentDate.getMonth() + 1).padStart(2, '0');
        let currentYear = currentDate.getFullYear();

        $('#salaryMonth').val(currentMonth);
        $('#salaryYear').val(currentYear);


        jQuery(function($) {

            if ($.fn.DataTable.isDataTable("#monthlySalaryTable")) {
                $("#monthlySalaryTable").DataTable().destroy();
            }

            table = $("#monthlySalaryTable").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: getReportUrl,
                    type: "GET",
                    data: function(d) {
                        d.month = $('#salaryMonth').val();
                        d.year = $('#salaryYear').val();
                        d.branch = $('#salaryBranch').val();
                    },
                },
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
                        data: 'month',
                        name: 'month',
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
            $('#monthlyReportDiv').show();

            $('#salaryBranch').change(function() {
                var branchId = $(this).val();
                var employeeSelect = $('#salaryEmployee');
                if (!branchId) {
                    branchId = 'ALL';
                }
                employeeSelect.empty().append('<option value="">All</option>');

                if (branchId) {
                    $.ajax({
                        url: '/get-staff/' + branchId,
                        type: 'GET',
                        success: function(data) {

                            $.each(data, function(index, employee) {
                                employeeSelect.append('<option value="' + employee.id +
                                    '">' + employee.name + '</option>');
                            });
                        },
                        error: function() {
                            console.error('Unable to fetch employees');
                        }
                    });
                }
            });

            $('#searchAttdBtn').on('click', function(e) {
                e.preventDefault();

                var employeeId = $('#salaryEmployee').val();

                // If an employee is selected, show the employee attendance table
                if (employeeId) {

                    $('#monthlyReportDiv').hide();
                    $('#employeeReportDiv').show();

                    if ($.fn.DataTable.isDataTable("#employeeSalaryTable")) {
                        $("#employeeSalaryTable").DataTable().destroy();
                    }

                    // Initialize the employee attendance DataTable
                    $("#employeeSalaryTable").DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: getReportUrl,
                            type: "GET",
                            data: function(d) {
                                d.employee = employeeId;
                                d.month = $('#salaryMonth').val();
                                d.year = $('#salaryYear').val();
                                d.branch = $('#salaryBranch').val();
                            },
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false,
                                render: function(data, type, row, meta) {
                                    return meta.row +
                                        1; // Adding 1 to start counting from 1
                                }
                            },
                            {
                                data: 'name',
                                name: 'name',
                                className: 'text-start'
                            },
                            {
                                data: 'designation',
                                name: 'designation'
                            },
                            {
                                data: 'branch',
                                name: 'branch',
                                className: 'text-start'
                            },
                            {
                                data: 'month',
                                name: 'month',
                                className: 'text-start'
                            },
                            {
                                data: 'working_days',
                                name: 'working_days',
                                className: 'text-start'
                            },
                            {
                                data: 'paid_days',
                                name: 'paid_days',
                                className: 'text-start'
                            },
                            {
                                data: 'unpaid_days',
                                name: 'unpaid_days',
                                className: 'text-start'
                            },
                            {
                                data: 'net_salary',
                                name: 'net_salary',
                                className: 'text-start'
                            },
                            {
                                data: 'absence_deduction',
                                name: 'absence_deduction',
                                className: 'text-start'
                            },
                            {
                                data: 'monthly_deduction',
                                name: 'monthly_deduction',
                                className: 'text-start'
                            },
                            {
                                data: 'incentives',
                                name: 'incentives',
                                className: 'text-start'
                            },
                            {
                                data: 'total_salary',
                                name: 'total_salary',
                                className: 'text-start'
                            },
                            {
                                data: 'previous_due',
                                name: 'previous_due',
                                className: 'text-start'
                            },
                            {
                                data: 'advance_given',
                                name: 'advance_given',
                                className: 'text-start'
                            },
                            {
                                data: 'monthly_salary',
                                name: 'monthly_salary',
                                className: 'text-start'
                            },
                            {
                                data: 'salary_paid',
                                name: 'salary_paid',
                                className: 'text-start'
                            },
                            {
                                data: 'balance_due',
                                name: 'balance_due',
                                className: 'text-start'
                            },
                            {
                                data: 'paid_on',
                                name: 'paid_on',
                                className: 'text-start'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: true
                            },
                        ],
                        dom: "Bfrtlp",
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        buttons: [{
                                extend: "print",
                                text: "Print",
                                title: clinicBasicDetails.clinic_name,
                                messageTop: "Monthly Salary Report",
                                orientation: "landscape",
                                pageSize: "A3",
                                footer: true,
                                filename: "Monthly Salary Report",
                                exportOptions: {
                                    columns: ":visible",
                                },
                                customize: function(win) {
                                    $(win.document.body).css("font-size", "10pt");
                                    $(win.document.body)
                                        .find("table")
                                        .addClass("compact")
                                        .css("font-size", "inherit");
                                },
                            },
                            {
                                extend: "excelHtml5",
                                text: "Excel",
                                title: clinicBasicDetails.clinic_name,
                                messageTop: "Monthly Salary Report",
                                footer: true,
                                filename: "Monthly Salary Report",
                                exportOptions: {
                                    columns: ":visible",
                                },
                            },
                            {
                                extend: "pdfHtml5",
                                text: "PDF",
                                title: clinicBasicDetails.clinic_name,
                                messageTop: "Monthly Salary Report",
                                orientation: "landscape",
                                pageSize: "A3",
                                exportOptions: {
                                    columns: ":visible",
                                },
                                footer: true,
                                filename: "Monthly Salary Report",
                                customize: function(doc) {
                                    doc.defaultStyle.fontSize = 10;
                                    doc.styles.tableHeader.fontSize = 10;
                                },
                            },
                        ],
                    });

                } else {
                    // If no employee is selected, show the monthly attendance table
                    $('#employeeReportDiv').hide();
                    $('#monthlyReportDiv').show();

                    if ($.fn.DataTable.isDataTable("#monthlySalaryTable")) {
                        $("#monthlySalaryTable").DataTable().destroy();
                    }

                    // Initialize the monthly attendance DataTable
                    $("#monthlySalaryTable").DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: getReportUrl,
                            type: "GET",
                            data: function(d) {
                                d.month = $('#salaryMonth').val();
                                d.year = $('#salaryYear').val();
                                d.branch = $('#salaryBranch').val();
                            },
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false,
                                render: function(data, type, row, meta) {
                                    return meta.row +
                                        1; // Adding 1 to start counting from 1
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
                                data: 'month',
                                name: 'month',
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
                }
            });

        });

        $(document).ready(function() {

            $("#modal-cancel-salary-bill").on("hidden.bs.modal", function() {
                // Reset the form
                $("#form-cancel-salary")[0].reset();

                // Clear specific fields
                $("#salary_id").val("");
                $("#bill_cancel_reason").val("");

                // Optionally, remove any validation messages
                $("#reasonError").text("");
            });

            $(document).on("click", ".btn-del", function() {
                var userId = $(this).data("id");
                var salaryId = $(this).data("salary-id");
                $("#salary_id").val(salaryId);
                $("#user_id").val(userId);
                $("#modal-cancel-salary-bill").modal("show");
            });

            $("#btn-cancel-bill").click(function() {
                var userId = $("#user_id").val();
                var salaryId = $("#salary_id").val();
                var reason = $("#bill_cancel_reason").val();

                if (reason.length === 0) {
                    $("#bill_cancel_reason").addClass("is-invalid");
                    $("#reasonError").text("Reason is required.").show();
                    return; // Stop further execution
                }

                var url = "/employee_salary/monthly/cancel/{id}";
                url = url.replace("{id}", salaryId);

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    type: "POST",
                    url: url,
                    data: {
                        // _token: "{{ csrf_token() }}",
                        reason: reason,
                        employee: userId,
                    },
                    success: function(response) {
                        $("#modal-cancel-salary-bill").modal(
                            "hide"); // Close modal after success
                        table.draw(); // Refresh DataTable
                        $("#successMessage").text("Salary cancelled successfully");
                        $("#successMessage").fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                        const currentMonth = new Date().getMonth() + 1;
                        const formattedMonth = ("0" + currentMonth).slice(-2);
                        const currentYear = new Date().getFullYear(); // Get the current year
                        $('#salaryYear').val(currentYear);
                        $('#salaryMonth').val(formattedMonth);

                    },
                    error: function(xhr) {
                        $("#modal-cancel-salary-bill").modal(
                            "hide"); // Close modal in case of error
                        console.log("Error!", xhr.responseJSON.message, "error");
                    },
                });
            });

            $(document).on('click', '.btn-salaryslip-pdf-generate', function() {
                var userId = $(this).data('id');
                var month = $(this).data('month');
                var year = $(this).data('year');
                var url = "/employee_salary/monthly/{month}/year/{year}/download-salaryslip";
                url = url.replace("{month}", month);
                url = url.replace("{year}", year);


                // Make the AJAX request
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        userId: userId,
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
                        link.download = 'salaryslip.pdf';
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
