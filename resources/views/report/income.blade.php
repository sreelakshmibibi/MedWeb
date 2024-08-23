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
                        <label class="form-label" for="fromdate">From </label>
                        <input type="date" class="form-control" id="fromdate" name="fromdate"
                            value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="todate">To </label>
                        <input type="date" class="form-control" id="todate" name="todate"
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

            <button type="submit" class="btn btn-success" id="searchincomebtn">
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
                        @foreach ($cardPay as $machine)
                            <th>{{ $machine->card_name }} card</th>
                            <th>{{ $machine->card_name }} Bank POS Machine Fee</th>
                        @endforeach
                        <th>Total Paid</th>
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
                        <th id="total-paid"></th>
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
                        @foreach ($cardPay as $machine)
                            <th>{{ $machine->card_name }} card</th>
                            <th>{{ $machine->card_name }} Bank POS Machine Fee</th>
                        @endforeach
                        <th>Total Paid</th>
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
                        <th id="total-paid"></th>
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

        $('#searchincomebtn').click(function(e) {
            e.preventDefault(); // Prevent form submission


            $("#errorDiv").text("");
            $('.incomedivmonthwise').hide();
            $('.incomedivdatewise').hide();
            // Initialize DataTable
            var year = $('#year').val();
            var month = $('#month').val();
            var fromDate = $('#fromdate').val();
            var toDate = $('#todate').val();
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

                    let table;
                    if ($.fn.DataTable.isDataTable("#monthwiseIncomeTable")) {
                        table = $('#monthwiseIncomeTable').DataTable();
                        table.clear().destroy();
                        $('#monthwiseIncomeTable').empty();
                    }

                    $.ajax({
                        url: "{{ route('report.income') }}",
                        type: 'POST',
                        data: {
                            _token: $('input[name="_token"]').val(),
                            fromdate: $('#fromdate').val(),
                            todate: $('#todate').val(),
                            month: $('#month').val(),
                            year: $('#year').val()
                        },
                        success: function(response) {
                            console.log("AJAX response:", response);

                            if (response.data && Array.isArray(response.data) && response
                                .data.length > 0) {
                                let dynamicColumns = [{
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
                                        data: 'totalPaid',
                                        name: 'totalPaid'
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
                                ];

                                // Determine dynamic columns
                                let firstRow = response.data[0];
                                if (firstRow) {
                                    Object.keys(firstRow).forEach(function(key) {
                                        if (key.startsWith('cards.')) {
                                            dynamicColumns.push({
                                                data: key,
                                                name: key
                                            });
                                        }
                                    });
                                }

                                console.log(dynamicColumns);

                                try {
                                    // Initialize DataTable with dynamic columns
                                    table = $('#monthwiseIncomeTable').DataTable({
                                        processing: true,
                                        serverSide: true,
                                        responsive: true,
                                        data: response.data,
                                        columns: dynamicColumns,
                                        dom: 'lfrtBp',
                                        buttons: [{
                                                extend: 'print',
                                                text: 'Print',
                                                className: 'btn btn-primary',
                                                title: 'Patient Report',
                                                messageTop: 'This is a printed report.',
                                                exportOptions: {
                                                    columns: ':visible'
                                                },
                                                customize: function(win) {
                                                    $(win.document.body)
                                                        .css('font-size',
                                                            '10pt');
                                                    $(win.document.body)
                                                        .find('table')
                                                        .addClass('compact')
                                                        .css('font-size',
                                                            'inherit');
                                                }
                                            },
                                            {
                                                extend: 'excelHtml5',
                                                text: 'Excel',
                                                className: 'btn btn-success',
                                                title: 'Patient Report'
                                            },
                                            {
                                                extend: 'pdfHtml5',
                                                text: 'PDF',
                                                className: 'btn btn-danger',
                                                title: 'Patient Report',
                                                exportOptions: {
                                                    columns: ':visible'
                                                },
                                                customize: function(doc) {
                                                    doc.defaultStyle
                                                        .fontSize = 10;
                                                    doc.styles.tableHeader
                                                        .fontSize = 10;
                                                }
                                            }
                                        ],
                                        footerCallback: function(row, data, start,
                                            end, display) {
                                            var api = this.api();
                                            dynamicColumns.forEach(function(col,
                                                i) {
                                                if (i >
                                                    2) { // Skip non-numeric columns (e.g., month)
                                                    var total = api
                                                        .column(i)
                                                        .data().reduce(
                                                            function(a,
                                                                b) {
                                                                return parseFloat(
                                                                        a
                                                                        ) +
                                                                    parseFloat(
                                                                        b
                                                                        );
                                                            }, 0);
                                                    $(api.column(i)
                                                            .footer())
                                                        .html(total
                                                            .toFixed(2)
                                                            );
                                                }
                                            });
                                        }
                                    });

                                    // Show the appropriate divs
                                    $('.incomedivmonthwise').show();
                                    $('.incomedivdatewise').hide();
                                } catch (error) {
                                    console.error("DataTable initialization error:", error);
                                }
                            } else {
                                console.error("No data received or data is empty.");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX error:", status, error);
                        }
                    });

                } else {
                    if ($.fn.DataTable.isDataTable("#datewiseIncomeTable")) {
                        // Destroy existing DataTable instance
                        table.destroy();
                    }
                    table = $('#datewiseIncomeTable').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        ajax: {
                            url: "{{ route('report.income') }}",
                            type: 'POST',
                            data: function(d) {
                                d._token = $('input[name="_token"]').val();
                                d.fromdate = $('#fromdate').val();
                                d.todate = $('#todate').val();
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
                                data: 'machineTax',
                                name: 'machineTax'
                            },
                            {
                                data: 'totalPaid',
                                name: 'totalPaid'
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
                        dom: 'lfrtBp', // Specify layout including Buttons
                        buttons: [{
                                extend: 'print',
                                text: 'Print',
                                className: 'btn btn-primary',
                                title: 'Patient Report',
                                messageTop: 'This is a printed report.',
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
                            // Optional: Add more buttons if needed
                            {
                                extend: 'excelHtml5',
                                text: 'Excel',
                                className: 'btn btn-success',
                                title: 'Patient Report'
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'PDF',
                                className: 'btn btn-danger',
                                title: 'Patient Report',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                customize: function(doc) {
                                    doc.defaultStyle.fontSize = 10;
                                    doc.styles.tableHeader.fontSize = 10;
                                }
                            }
                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();
                            for (let i = 4; i < 13; i++) {
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
