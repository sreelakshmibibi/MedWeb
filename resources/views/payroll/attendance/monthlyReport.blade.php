@extends('layouts.dashboard')
@section('title', 'Attendance Report')
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
                    <h3 class="page-title">Attendance Report</h3>

                    <a type="button" class="waves-effect waves-light btn btn-primary" title="Back"
                        href="{{ route('attendance') }}">
                        <span class="hidden-sm-up"><i class="fa-solid fa-angles-left"></i></span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-angles-left"></i> Back</span>
                    </a>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="attendnceMonth">Month<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control " type="text" id="attendnceMonth" name="attendnceMonth">

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
                                    <label class="form-label" for="attendnceYear">Year<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control " type="text" id="attendnceYear" name="attendnceYear">

                                        @for ($i = 0; $i < sizeof($years); $i++)
                                            <option value="{{ $years[$i] }}">{{ $years[$i] }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="attendnceBranch">Branch </label>
                                    <select class="form-control" id="attendnceBranch" name="attendnceBranch" required>
                                        <option value="">All</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch['id'] }}"> {{ $branch['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="attendnceEmployee">Employee</label>
                                    <select class="form-control" id="attendnceEmployee" name="attendnceEmployee">
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
                    <div class="box no-border mb-0" id="monthlyReportDiv" style="display:none;">
                        <div class="table-responsive">
                            <table width="100%" id="monthlyAttendanceTable"
                                class="table table-bordered table-hover table-striped mb-0 data-table text-center">
                                <thead class="bg-primary-light text-center">
                                    <tr>
                                        <th width="10px">No</th>
                                        <th>Name</th>
                                        <th>Designation</th>
                                        <th>Branch</th>
                                        <th>Total Working Days</th>
                                        <th width="80px">Present Days</th>
                                        <th>Absent Days</th>
                                        <th>Casual Leave</th>
                                        <th>Sick Leave</th>
                                        <th>Compensatory Leave</th>
                                        <th>Total Leave Days</th>
                                        <th>Total Absent Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box no-border mb-0" id="employeeReportDiv" style="display:none;">
                        <div class="table-responsive">
                            <table width="100%" id="employeeAttendanceTable"
                                class="table table-bordered table-hover table-striped mb-0 data-table text-center">
                                <thead class="bg-primary-light text-center">
                                    <tr>
                                        <th width="10px">No</th>
                                        <th>Name</th>
                                        <th>Designation</th>
                                        <th>Branch</th>
                                        <th width="80px">Date</th>
                                        <th>Attendance Status</th>
                                        <th>Leave Status</th>
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



    <script>
        var clinicBasicDetails = @json($clinicBasicDetails);
        var getReportUrl = "{{ route('report.attendance.month') }}";

        var table;
        let currentDate = new Date();
        let currentMonth = String(currentDate.getMonth() + 1).padStart(2, '0'); 
        let currentYear = currentDate.getFullYear();

        $('#attendnceMonth').val(currentMonth);
        $('#attendnceYear').val(currentYear);


        jQuery(function($) {

            if ($.fn.DataTable.isDataTable("#monthlyAttendanceTable")) {
                $("#monthlyAttendanceTable").DataTable().destroy();
            }

            table = $("#monthlyAttendanceTable").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: getReportUrl,
                    type: "GET",
                    data: function(d) {
                        d.month = $('#attendnceMonth').val();
                        d.year = $('#attendnceYear').val();
                        d.branch = $('#attendnceBranch').val();
                    },
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; 
                        },
                    },
                    {
                        data: "name",
                        name: "name",
                        className: "text-start",
                    },
                    {
                        data: "designation",
                        name: "designation",
                        className: "text-start",
                    },
                    {
                        data: "branch",
                        name: "branch",
                        className: "text-start",
                    },
                    {
                        data: "totalWorkingDays",
                        name: "totalWorkingDays",
                    },
                    {
                        data: "presentDays",
                        name: "presentDays",
                    },
                    {
                        data: "AbsentDays",
                        name: "AbsentDays",
                        render: function(data) {
                            return data != null ? data : 0;
                        }
                    },
                    {
                        data: "casualLeave",
                        name: "casualLeave",
                        render: function(data) {
                            return data != null ? data : 0;
                        }
                    },
                    {
                        data: "sickLeave",
                        name: "sickLeave",
                        render: function(data) {
                            return data != null ? data : 0;
                        }
                    },
                    {
                        data: "compensatoryLeave",
                        name: "compensatoryLeave",
                        render: function(data) {
                            return data != null ? data : 0;
                        }
                    },
                    {
                        data: "totalLeave",
                        name: "totalLeave",
                        render: function(data) {
                            return data != null ? data : 0;
                        }
                    },
                    {
                        data: "totalAbsent",
                        name: "totalAbsent",
                        render: function(data) {
                            return data != null ? data : 0;
                        }
                    },

                ],
                dom: "Bfrtlp",
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"],
                ],
                buttons: [{
                        extend: "print",
                        text: "Print",
                        title: clinicBasicDetails.clinic_name,
                        messageTop: "Attendance Report",
                        orientation: "landscape",
                        pageSize: "A4",
                        footer: true,
                        filename: "Attendance Report",
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
                        messageTop: "Attendance Report",
                        footer: true,
                        filename: "Attendance Report",
                        exportOptions: {
                            columns: ":visible",
                        },
                    },
                    {
                        extend: "pdfHtml5",
                        text: "PDF",
                        title: clinicBasicDetails.clinic_name,
                        messageTop: "Attendance Report",
                        orientation: "landscape",
                        pageSize: "A4",
                        exportOptions: {
                            columns: ":visible",
                        },
                        footer: true,
                        filename: "Attendance Report",
                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 10;
                            doc.styles.tableHeader.fontSize = 10;
                        },
                    },
                ],
            });
            $('#monthlyReportDiv').show();

            $('#attendnceBranch').change(function() {
                var branchId = $(this).val(); 
                var employeeSelect = $('#attendnceEmployee');
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

                var employeeId = $('#attendnceEmployee').val();

                // If an employee is selected, show the employee attendance table
                if (employeeId) {

                    $('#monthlyReportDiv').hide();
                    $('#employeeReportDiv').show();
                    
                    if ($.fn.DataTable.isDataTable("#employeeAttendanceTable")) {
                        $("#employeeAttendanceTable").DataTable().destroy();
                    }

                    // Initialize the employee attendance DataTable
                    $("#employeeAttendanceTable").DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: getReportUrl,
                            type: "GET",
                            data: function(d) {
                                d.employee = employeeId;
                                d.month = $('#attendnceMonth').val();
                                d.year = $('#attendnceYear').val();
                                d.branch = $('#attendnceBranch').val();
                            },
                        },
                        columns: [{
                                data: "DT_RowIndex",
                                name: "DT_RowIndex",
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: "name",
                                name: "name",
                                className: "text-start"
                            },
                            {
                                data: "designation",
                                name: "designation",
                                className: "text-start"
                            },
                            {
                                data: "branch",
                                name: "branch",
                                className: "text-start",
                            },
                            {
                                data: "date",
                                name: "date",
                            },
                            {
                                data: "attendanceStatus",
                                name: "attendanceStatus"
                            },
                            {
                                data: "leaveStatus",
                                name: "leaveStatus"
                            }
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
                                messageTop: "Attendance Report",
                                orientation: "landscape",
                                pageSize: "A4",
                                footer: true,
                                filename: "Attendance Report",
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
                                messageTop: "Attendance Report",
                                footer: true,
                                filename: "Attendance Report",
                                exportOptions: {
                                    columns: ":visible",
                                },
                            },
                            {
                                extend: "pdfHtml5",
                                text: "PDF",
                                title: clinicBasicDetails.clinic_name,
                                messageTop: "Attendance Report",
                                orientation: "landscape",
                                pageSize: "A4",
                                exportOptions: {
                                    columns: ":visible",
                                },
                                footer: true,
                                filename: "Attendance Report",
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

                    if ($.fn.DataTable.isDataTable("#monthlyAttendanceTable")) {
                        $("#monthlyAttendanceTable").DataTable().destroy();
                    }

                    // Initialize the monthly attendance DataTable
                    $("#monthlyAttendanceTable").DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: getReportUrl,
                            type: "GET",
                            data: function(d) {
                                d.month = $('#attendnceMonth').val();
                                d.year = $('#attendnceYear').val();
                                d.branch = $('#attendnceBranch').val();
                            },
                        },
                        columns: [{
                                data: "DT_RowIndex",
                                name: "DT_RowIndex",
                                orderable: false,
                                searchable: false,
                                render: function(data, type, row, meta) {
                                    return meta.row +
                                        1; 
                                },
                            },
                            {
                                data: "name",
                                name: "name",
                                className: "text-start",
                            },
                            {
                                data: "designation",
                                name: "designation",
                                className: "text-start",
                            },
                            {
                                data: "branch",
                                name: "branch",
                                className: "text-start",
                            },
                            {
                                data: "totalWorkingDays",
                                name: "totalWorkingDays",
                            },
                            {
                                data: "presentDays",
                                name: "presentDays",
                            },
                            {
                                data: "AbsentDays",
                                name: "AbsentDays",
                                render: function(data) {
                                    return data != null ? data : 0;
                                }
                            },
                            {
                                data: "casualLeave",
                                name: "casualLeave",
                                render: function(data) {
                                    return data != null ? data : 0;
                                }
                            },
                            {
                                data: "sickLeave",
                                name: "sickLeave",
                                render: function(data) {
                                    return data != null ? data : 0;
                                }
                            },
                            {
                                data: "compensatoryLeave",
                                name: "compensatoryLeave",
                                render: function(data) {
                                    return data != null ? data : 0;
                                }
                            },
                            {
                                data: "totalLeave",
                                name: "totalLeave",
                                render: function(data) {
                                    return data != null ? data : 0;
                                }
                            },
                            {
                                data: "totalAbsent",
                                name: "totalAbsent",
                                render: function(data) {
                                    return data != null ? data : 0;
                                }
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
                                messageTop: "Attendance Report",
                                orientation: "landscape",
                                pageSize: "A4",
                                footer: true,
                                filename: "Attendance Report",
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
                                messageTop: "Attendance Report",
                                footer: true,
                                filename: "Attendance Report",
                                exportOptions: {
                                    columns: ":visible",
                                },
                            },
                            {
                                extend: "pdfHtml5",
                                text: "PDF",
                                title: clinicBasicDetails.clinic_name,
                                messageTop: "Attendance Report",
                                orientation: "landscape",
                                pageSize: "A4",
                                exportOptions: {
                                    columns: ":visible",
                                },
                                footer: true,
                                filename: "Attendance Report",
                                customize: function(doc) {
                                    doc.defaultStyle.fontSize = 10;
                                    doc.styles.tableHeader.fontSize = 10;
                                },
                            },
                        ],
                    });
                }
            });

        });
    </script>
@endsection
