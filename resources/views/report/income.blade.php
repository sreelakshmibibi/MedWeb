<form method="post" action="{{ route('report.income') }}">
    @csrf
    <div class="box no-border mb-2">
        <div class="box-header p-0">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="box-title ">
                    Income Report
                </h4>

                <button type='button'
                    class='waves-effect waves-light btn btn-circle btn-secondary btn-treatment-pdf-generate btn-xs mt-0 mb-2'
                    title='Download & Print Treatment Summary'><i class='fa fa-download'></i></button>
            </div>
        </div>
        <div class="box-body px-0 ">
            <div class="row">
                <p class="text-warning">
                    * Select <b class="text-decoration-underline">From & To</b> or <b
                        class="text-decoration-underline">Month & Year</b> or
                    <b class="text-decoration-underline">Year Only</b> for getting
                    corresponding reports
                </p>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="incomeFromDate">From </label>
                        <input type="date" class="form-control" id="incomeFromDate" name="incomeFromDate"
                            value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="incomeToDate">To </label>
                        <input type="date" class="form-control" id="incomeToDate" name="incomeToDate"
                            value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="month">Month</label>
                        <select class="form-control " type="text" id="month" name="month">
                            <option value="">All</option>
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

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="year">Year</label>
                        <select class="form-control " type="text" id="year" name="year">
                            <option value="">All</option>
                            @for ($i = 0; $i < sizeof($years); $i++)
                                <option value="{{ $years[$i] }}">{{ $years[$i] }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div id="errorDiv" class="text-danger"></div>

            </div>
        </div>
        <div class="box-footer p-3 px-0 text-end bb-1" style="border-radius: 0px;">
            <button type="submit" class="btn btn-success" id="searchIncomeDateWiseBtn">
                <i class="fa fa-search"></i> Search
            </button>

            <button type="submit" class="btn btn-success" id="searchIncomeMonthWiseBtn">
                <i class="fa fa-search"></i> Search
            </button>
        </div>
    </div>
    <div class="incomedivdatewise container" style="display: none">
        <div class="table-responsive" style=" width: 100%; overflow-x: auto;">
            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                id="datewiseIncomeTable" width="100%">
                <thead class="bg-primary-light">
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Net Paid</th>
                        <th>Cash</th>
                        <th>Gpay</th>
                        <th>card</th>
                        <th>Bank POS Machine Fee</th>
                        <th>Balance Given</th>
                        <th>Pure Total</th>
                        <th>Total Customers</th>
                        <th>Total Services</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total:</th>
                        <th id="total-net"></th>
                        <th id="total-cash"></th>
                        <th id="total-gpay"></th>
                        <th id="total-card"></th>
                        <th id="total-machinetax"></th>
                        <th id="total-balance_given"></th>
                        <th id="total-income"></th>
                        <th id="total-customer"></th>
                        <th id="total-service"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="incomedivmonthwise container" style="display: none">
        <div class="table-responsive" style=" width: 100%; overflow-x: auto;">

            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                id="monthwiseIncomeTable" width="100%">
                <thead class="bg-primary-light">
                    <tr>
                        <th>No</th>
                        <th>Date/Month</th>
                        <th>Net Paid</th>
                        <th>Cash</th>
                        <th>Gpay</th>
                        <th>card</th>
                        <th>Bank POS Machine Fee</th>
                        <th>Balance Given</th>
                        <th>Pure Total</th>
                        <th>Month Average</th>
                        <th>Days</th>
                        <th>Total Customers</th>
                        <th>Average Customers</th>
                        <th>Total Services</th>
                        <th>Average Services</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total:</th>
                        <th id="total-net"></th>
                        <th id="total-cash"></th>
                        <th id="total-gpay"></th>
                        <th id="total-card"></th>
                        <th id="total-machinetax"></th>
                        <th id="total-balance_given"></th>
                        <th id="total-income"></th>
                        <th id="total-avg-income"></th>
                        <th id="total-days"></th>
                        <th id="total-customer"></th>
                        <th id="total-avg-customer"></th>
                        <th id="total-service"></th>
                        <th id="total-avg-service"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</form>

<script type="text/javascript">
    var table;
    jQuery(function($) {

        $('#searchIncomeMonthWiseBtn').click(function(e) {
            e.preventDefault();
            var year = $('#year').val();
            var month = $('#month').val();
            var fromDate = $('#incomeFromDate').val();
            var toDate = $('#incomeToDate').val();
            $("#errorDiv").text("");

            if (month !== '' && year === '') {
                // Case 1: Month is selected, but year is not
                $("#errorDiv").text("Please select a year when a month is selected.");
            } else if (fromDate !== '' && toDate === '') {
                // Case 2: From date is selected, but to date is not
                $("#errorDiv").text("Please select a 'To Date' when a 'From Date' is selected.");
            } else if (fromDate === '' && toDate !== '') {
                // Case 3: To date is selected, but from date is not
                $("#errorDiv").text("Please select a 'From Date' when a 'To Date' is selected.");
            } else if (new Date(fromDate) > new Date(toDate)) {
                // Case 4: From date is after To date
                $("#errorDiv").text("'From Date' cannot be after 'To Date'.");
            } else if (year === '' && month === '' && fromDate === '' && toDate === '') {
                // Case 5: All fields are empty
                $("#errorDiv").text(
                    "Please select at least one filter (Year, Month, From Date, or To Date).");
            } else {
                if (month === '' && year !== '') {
                    if ($.fn.DataTable.isDataTable("#monthwiseIncomeTable")) {
                        // Destroy existing DataTable instance
                        table.destroy();
                    }
                    table = $('#monthwiseIncomeTable').DataTable({
                        processing: true,
                        serverSide: true,
                        // responsive: true,
                        ajax: {
                            url: "{{ route('report.income') }}",
                            type: 'POST',
                            data: function(d) {
                                d._token = $('input[name="_token"]').val();
                                d.fromdate = $('#incomeFromDate').val();
                                d.todate = $('#incomeToDate').val();
                                d.month = $('#month').val();
                                d.year = $('#year').val();
                            },
                            dataSrc: function(json) {
                                return json.data;
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'month',
                                name: 'month'
                            },
                            {
                                data: 'netPaid',
                                name: 'netPaid'
                            },
                            {
                                data: 'cash',
                                name: 'cash'
                            },
                            {
                                data: 'gpay',
                                name: 'gpay'
                            },
                            {
                                data: 'card',
                                name: 'card'
                            },
                            {
                                data: 'machine_tax',
                                name: 'machine_tax'
                            },
                            {
                                data: 'balance_given',
                                name: 'balance_given'
                            },
                            {
                                data: 'pureTotal',
                                name: 'pureTotal'
                            },
                            {
                                data: 'avgIncome',
                                name: 'avgIncome'
                            },
                            {
                                data: 'dayCount',
                                name: 'dayCount'
                            },
                            {
                                data: 'totalCustomer',
                                name: 'totalCustomer'
                            },
                            {
                                data: 'avgCustomer',
                                name: 'avgCustomer'
                            },
                            {
                                data: 'totalService',
                                name: 'totalService'
                            },
                            {
                                data: 'avgService',
                                name: 'avgService'
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
                                // className: 'btn btn-primary',
                                title: 'Dental Clinic',
                                messageTop: 'Collection Report',
                                orientation: 'landscape',
                                pageSize: 'A4',
                                footer: true,
                                filename: 'Collection Report',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                customize: function(win) {
                                    $(win.document.body).css('font-size', '10pt');
                                    $(win.document.body).find('table').addClass(
                                        'compact').css(
                                        'font-size',
                                        'inherit');
                                }
                            },

                            {
                                extend: 'excelHtml5',
                                text: 'Excel',
                                // className: 'btn btn-success',
                                title: 'Dental Clinic',
                                messageTop: 'Collection Report',
                                footer: true,
                                filename: 'Collection Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'PDF',
                                // className: 'btn btn-danger',
                                title: 'Dental Clinic',
                                messageTop: 'Collection Report',
                                orientation: 'landscape',
                                pageSize: 'A3',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                footer: true,
                                headerRows: 1,
                                filename: 'Collection Report',
                                customize: function(doc) {
                                    doc.defaultStyle.fontSize = 10;
                                    doc.styles.tableHeader.fontSize = 10;

                                }
                            }
                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();

                            for (let i = 2; i < 15; i++) {
                                var total = api.column(i).data().reduce(function(a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0);
                                $(api.column(i).footer()).html(total.toFixed(2));
                            }
                        }
                    });
                    $('.incomedivmonthwise').show();
                    $('.incomedivdatewise').hide();

                } else {
                    
                    if ($.fn.DataTable.isDataTable("#datewiseIncomeTable")) {
                        // Destroy existing DataTable instance
                        table.destroy();
                    }
                    table = $('#datewiseIncomeTable').DataTable({
                        processing: true,
                        serverSide: true,
                        // responsive: true,
                        ajax: {
                            url: "{{ route('report.income') }}",
                            type: 'POST',
                            data: function(d) {
                                d._token = $('input[name="_token"]').val();
                                d.fromdate = $('#incomeFromDate').val();
                                d.todate = $('#incomeToDate').val();
                                d.month = $('#month').val();
                                d.year = $('#year').val();
                            },
                            dataSrc: function(json) {
                                return json.data;
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'date',
                                name: 'date'
                            },
                            {
                                data: 'day',
                                name: 'day'
                            },
                            {
                                data: 'netPaid',
                                name: 'netPaid'
                            },
                            {
                                data: 'cash',
                                name: 'cash'
                            },
                            {
                                data: 'gpay',
                                name: 'gpay'
                            },
                            {
                                data: 'card',
                                name: 'card'
                            },
                            {
                                data: 'machine_tax',
                                name: 'machine_tax'
                            },
                            {
                                data: 'balance_given',
                                name: 'balance_given'
                            },
                            {
                                data: 'pureTotal',
                                name: 'pureTotal'
                            },
                            {
                                data: 'totalCustomer',
                                name: 'totalCustomer'
                            },
                            {
                                data: 'totalService',
                                name: 'totalService'
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
                                // className: 'btn btn-primary',
                                title: 'Dental Clinic',
                                messageTop: 'Collection Report',
                                orientation: 'landscape',
                                pageSize: 'A4',
                                footer: true,
                                filename: 'Collection Report',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                customize: function(win) {
                                    $(win.document.body).css('font-size', '10pt');
                                    $(win.document.body).find('table').addClass(
                                        'compact').css(
                                        'font-size',
                                        'inherit');
                                }
                            },

                            {
                                extend: 'excelHtml5',
                                text: 'Excel',
                                // className: 'btn btn-success',
                                title: 'Dental Clinic',
                                messageTop: 'Collection Report',
                                footer: true,
                                filename: 'Collection Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'PDF',
                                // className: 'btn btn-danger',
                                title: 'Dental Clinic',
                                messageTop: 'Collection Report',
                                orientation: 'landscape',
                                pageSize: 'A3',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                footer: true,
                                headerRows: 1,
                                filename: 'Collection Report',
                                customize: function(doc) {
                                    doc.defaultStyle.fontSize = 10;
                                    doc.styles.tableHeader.fontSize = 10;

                                }
                            }
                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();

                            for (let i = 3; i < 13; i++) {
                                var total = api.column(i).data().reduce(function(a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0);
                                $(api.column(i).footer()).html(total.toFixed(2));
                            }
                        }
                    });
                    $('.incomedivmonthwise').hide();
                    $('.incomedivdatewise').show();

                }
            }


        });

    });
</script>
