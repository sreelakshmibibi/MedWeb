<form method="post" action="{{ route('report.auditPatient') }}">
    @csrf
    <div class="container-fluid">
        <div class="box no-border mb-2">
            <div class="box-header p-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="box-title ">
                        Audit Patient
                    </h4>
                </div>
            </div>
            <div class="box-body px-0 ">

                <div class="row">
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="auditPatientFromDate">From <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="auditPatientFromDate"
                                name="auditPatientFromDate" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="auditPatientToDate">To <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="auditPatientToDate" name="auditPatientToDate"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="auditPatientId">Patient Id</label>
                            <input type="text" class="form-control " id="auditPatientId" name="auditPatientId">
                        </div>
                    </div>

                </div>
                <div id="errorDiv" class="text-danger"></div>
            </div>
            <div class="box-footer p-3 px-0 text-end " style="border-radius: 0px;">
                <button type="submit" class="btn btn-success" id="searchAuditPatientBtn">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </div>
    </div>
    <div class="auditpatientdiv container" style="display: none;">
        <div class="table-responsive" style="width:100%; overflow-x: auto;">
            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                id="auditPatientTable" width="100%">
                <thead class="bg-primary-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Patient ID</th>
                        <th width="200px">Patient Name</th>
                        <th>Table Name</th>
                        <th>Action</th>
                        <th class="text-center">Old Data</th>
                        <th class="text-center">New Data</th>
                        <th>Changed By</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</form>
<script type="text/javascript">
    var table;
    jQuery(function($) {
        var clinicBasicDetails = @json($clinicBasicDetails);

        $('#searchAuditPatientBtn').click(function(e) {
            e.preventDefault();
            var auditPatientId = $('#auditPatientId').val();
            var auditPatientFromDate = $('#auditPatientFromDate').val();
            var auditPatientToDate = $('#auditPatientToDate').val();
            $("#errorDiv").text("");

            if (auditPatientFromDate !== '' && auditPatientToDate === '') {
                $("#errorDiv").text("Please select a 'To Date' when a 'From Date' is selected.");
            } else if (auditPatientFromDate === '' && auditPatientToDate !== '') {
                $("#errorDiv").text("Please select a 'From Date' when a 'To Date' is selected.");
            } else if (new Date(auditPatientFromDate) > new Date(auditPatientToDate)) {
                $("#errorDiv").text("'From Date' cannot be after 'To Date'.");
            } else {

                if ($.fn.DataTable.isDataTable("#auditPatientTable")) {
                    $('#auditPatientTable').DataTable().destroy();
                }

                // Initialize DataTable
                table = $('#auditPatientTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('report.auditPatient') }}",
                        type: 'POST',
                        data: function(d) {
                            d._token = $('input[name="_token"]').val();
                            d.auditPatientFromDate = $('#auditPatientFromDate').val();
                            d.auditPatientToDate = $('#auditPatientToDate').val();
                            d.auditPatientId = $('#auditPatientId').val();
                        },
                        dataSrc: function(json) {
                            return json.data;
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            className: 'align-top',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'date',
                            name: 'date',
                            className: 'min-w-60 align-top',
                            render: function(data, type, row) {
                                if (data) {
                                    var date = new Date(data);

                                    // Format the date as d-m-y
                                    var day = ("0" + date.getDate()).slice(-2);
                                    var month = ("0" + (date.getMonth() + 1)).slice(-2);
                                    var year = date.getFullYear();

                                    return day + '-' + month + '-' + year;
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            data: 'patientId',
                            name: 'patientId',
                            className: 'align-top',
                        },
                        {
                            data: 'patientName',
                            name: 'patientName',
                            className: 'min-w-120 text-start align-top',
                        },
                        {
                            data: 'tableName',
                            name: 'tableName',
                            className: 'align-top',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            className: 'align-top',
                        },
                        {
                            data: 'oldData',
                            name: 'oldData',
                            className: 'min-w-200 text-start align-top',
                            render: function(data, type, row) {
                                // Render the oldData as HTML
                                return data ? $('<div/>').html(data).text() : '-';
                            }
                        },
                        {
                            data: 'newData',
                            name: 'newData',
                            className: 'text-start align-top',
                            render: function(data, type, row) {
                                return data ? $('<div/>').html(data).text() : '-';
                            }
                        },
                        {
                            data: 'changedBy',
                            name: 'changedBy',
                            className: 'align-top',
                        }
                    ],
                    dom: 'Bfrtlp',
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, 'All']
                    ],
                    buttons: [{
                            extend: 'print',
                            text: 'Print',
                            title: clinicBasicDetails.clinic_name,
                            messageTop: 'Audit Patient Report',
                            orientation: 'landscape',
                            pageSize: 'A3',
                            footer: true,
                            filename: 'Audit Patient Report',
                            exportOptions: {
                                columns: ':visible'
                            },
                            customize: function(win) {
                                $(win.document.body).css('font-size', '10pt');
                                $(win.document.body).find('table').addClass('compact')
                                    .css(
                                        'font-size', 'inherit');
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: 'Excel',
                            title: clinicBasicDetails.clinic_name,
                            messageTop: 'Audit Patient Report',
                            footer: true,
                            filename: 'Audit Patient Report',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: 'PDF',
                            title: clinicBasicDetails.clinic_name,
                            messageTop: 'Audit Patient Report',
                            orientation: 'landscape',
                            pageSize: 'A3',
                            exportOptions: {
                                columns: ':visible'
                            },
                            footer: true,
                            filename: 'Audit Patient Report',
                            customize: function(doc) {
                                doc.defaultStyle.fontSize = 10;
                                doc.styles.tableHeader.fontSize = 10;
                            }
                        }
                    ],
                });
                $('.auditpatientdiv').show();

            }
        });

    });
</script>
