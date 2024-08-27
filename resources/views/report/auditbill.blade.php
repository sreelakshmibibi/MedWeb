<form method="post" action="{{ route('report.auditBill') }}">
    @csrf
    <div class="container-fluid">
        <div class="box no-border mb-2">
            <div class="box-header p-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="box-title ">
                        Audit Bill
                    </h4>
                </div>
            </div>
            <div class="box-body px-0 ">
                <div class="row">
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="auditBillFromDate">From <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="auditBillFromDate" name="auditBillFromDate"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="auditBillToDate">To <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="auditBillToDate" name="auditBillToDate"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="auditBillPatientId">Patient Id</label>
                            <input type="text" class="form-control " id="auditBillPatientId"
                                name="auditBillPatientId">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="auditBillNo">Bill No.</label>
                            <input type="text" class="form-control " id="auditBillNo" name="auditBillNo">
                        </div>
                    </div>
                </div>

            </div>
            <div class="box-footer p-3 px-0 text-end " style="border-radius: 0px;">
                <button type="submit" class="btn btn-success" id="searchAuditBillBtn">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </div>
    </div>
    <div class="auditbilldiv container" style="display: none;">
        <div class="table-responsive" style=" width: 100%; overflow-x: auto;">
            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                id="auditBillTable" width="100%">
                <thead class="bg-primary-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Bill ID</th>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
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

        $('#searchAuditBillBtn').click(function(e) {
            e.preventDefault();

            var auditBillFromDate = $('#auditBillFromDate').val();
            var auditBillToDate = $('#auditBillToDate').val();
            var auditBillPatientId = $('#auditBillPatientId').val();
            var auditBillNo = $('#auditBillNo').val();
            $("#errorDiv").text("");

            if (auditBillFromDate !== '' && auditBillToDate === '') {
                $("#errorDiv").text("Please select a 'To Date' when a 'From Date' is selected.");
            } else if (auditBillFromDate === '' && auditBillToDate !== '') {
                $("#errorDiv").text("Please select a 'From Date' when a 'To Date' is selected.");
            } else if (new Date(auditBillFromDate) > new Date(auditBillToDate)) {
                $("#errorDiv").text("'From Date' cannot be after 'To Date'.");
            } else {

                if ($.fn.DataTable.isDataTable("#auditBillTable")) {
                    $('#auditBillTable').DataTable().destroy();
                }

                // Initialize DataTable
                table = $('#auditBillTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('report.auditBill') }}",
                        type: 'POST',
                        data: function(d) {
                            d._token = $('input[name="_token"]').val();
                            d.auditBillFromDate = $('#auditBillFromDate').val();
                            d.auditBillToDate = $('#auditBillToDate').val();
                            d.auditBillPatientId = $('#auditBillPatientId').val();
                            d.auditBillNo = $('#auditBillNo').val();
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
                            data: 'billId',
                            name: 'billId',
                            className: 'align-top',
                        },
                        {
                            data: 'patientId',
                            name: 'patientId',
                            className: 'align-top',
                        },
                        {
                            data: 'patientName',
                            name: 'patientName',
                            className: 'min-w-120 align-top',
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
                            className: 'text-start align-top',
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
                            messageTop: 'Audit Bill Report',
                            orientation: 'landscape',
                            pageSize: 'A3',
                            footer: true,
                            filename: 'Audit Bill Report',
                            exportOptions: {
                                columns: ':visible'
                            },
                            customize: function(win) {
                                $(win.document.body).css('font-size', '10pt');
                                $(win.document.body).find('table').addClass('compact')
                                    .css('font-size', 'inherit');
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: 'Excel',
                            title: clinicBasicDetails.clinic_name,
                            messageTop: 'Audit Bill Report',
                            footer: true,
                            filename: 'Audit Bill Report',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: 'PDF',
                            title: clinicBasicDetails.clinic_name,
                            messageTop: 'Audit Bill Report',
                            orientation: 'landscape',
                            pageSize: 'A3',
                            exportOptions: {
                                columns: ':visible'
                            },
                            footer: true,
                            filename: 'Audit Bill Report',
                            customize: function(doc) {
                                doc.defaultStyle.fontSize = 10;
                                doc.styles.tableHeader.fontSize = 10;
                            }
                        }
                    ],
                });


                $('.auditbilldiv').show();
            }
        });

    });
</script>
