<form method="post" action="{{ route('report.consolidated') }}">
    @csrf
    <div class="container-fluid">
        <div class="box no-border mb-2">
            <div class="box-header p-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="box-title ">
                        Consolidated Report
                    </h4>
                </div>
            </div>
            <div class="box-body px-0 ">

                <div class="row">
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="consFromDate">From <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="consFromDate" name="consFromDate"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="consToDate">To <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="consToDate" name="consToDate"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="consBranch">Branch</label>
                            <select class="form-control " type="text" id="consBranch" name="consBranch">
                                <option value="">All</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch['id'] }}"> {{ $branch['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="box-footer p-3 px-0 text-end " style="border-radius: 0px;">
                <button type="submit" class="btn btn-success" id="searchConsolidatedBtn">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </div>
    </div>
    <div class="consolidatedDiv container" style="display: none;">
        <div class="table-responsive" style=" width: 100%;
    overflow-x: auto;">
            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                id="consolidatedTable" width="100%">
                <thead class="bg-primary-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Collection</th>
                        <th>Bank</th>
                        <th>Expense</th>
                        <th>Purchase</th>
                        <th>Total Income</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr class="bt-3 border-primary">
                        <th></th>
                        <th>Total:</th>
                        <th id="total-collect"></th>
                        <th id="total-bank"></th>
                        <th id="total-exp"></th>
                        <th id="total-pur"></th>
                        <th id="total-total"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</form>
<script type="text/javascript">
    var table;
    jQuery(function($) {
        var clinicBasicDetails = @json($clinicBasicDetails);

        $('#searchConsolidatedBtn').click(function(e) {
            e.preventDefault(); 

            if ($.fn.DataTable.isDataTable("#consolidatedTable")) {
                $('#consolidatedTable').DataTable().destroy();
            }

            // Initialize DataTable
            table = $('#consolidatedTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('report.consolidated') }}",
                    type: 'POST',
                    data: function(d) {
                        d._token = $('input[name="_token"]').val();
                        d.consFromdate = $('#consFromDate').val();
                        d.consTodate = $('#consToDate').val();
                        d.consBranch = $('#consBranch').val();
                    },
                    dataSrc: function(json) {
                        return json.data; // Ensure `json.data` is correct
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
                        name: 'date',
                        className: 'min-w-60',
                        render: function(data, type, row) {
                            if (data) {
                                // Convert the date string to a JavaScript Date object
                                var date = new Date(data);

                                // Format the date as d-m-y
                                var day = ("0" + date.getDate()).slice(-2);
                                var month = ("0" + (date.getMonth() + 1)).slice(-2);
                                var year = date.getFullYear();

                                return day + '-' + month + '-' +
                                    year; // Return formatted date
                            } else {
                                return '-'; // Return dash if no data is present
                            }
                        }
                    },
                    {
                        data: 'collection',
                        name: 'collection'
                    },
                    {
                        data: 'machine_tax',
                        name: 'machine_tax'
                    },
                    {
                        data: 'expense',
                        name: 'expense'
                    },
                    {
                        data: 'purchase',
                        name: 'purchase'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    }
                    
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
                        // className: 'btn btn-primary',
                        title: clinicBasicDetails.clinic_name,
                        messageTop: 'Consolidated Report',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        footer: true,
                        filename: 'Consolidated Report',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(win) {
                            $(win.document.body).css('font-size', '10pt');
                            $(win.document.body).find('table').addClass('compact').css(
                                'font-size',
                                'inherit');
                        }
                    },

                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        // className: 'btn btn-success',
                        title: clinicBasicDetails.clinic_name,
                        messageTop: 'Consolidated Report',
                        footer: true,
                        filename: 'Consolidated Report',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        // className: 'btn btn-danger',
                        title: clinicBasicDetails.clinic_name,
                        messageTop: 'Consolidated Report',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible'
                        },
                        footer: true,
                        headerRows: 1,
                        filename: 'Consolidated Report',
                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 10;
                            doc.styles.tableHeader.fontSize = 10;

                        }
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    for (let i = 2; i < 7; i++) {
                        var total = api.column(i).data().reduce(function(a, b) {
                            return parseFloat(a) + parseFloat(b);
                        }, 0);
                        $(api.column(i).footer()).html(total.toFixed(2));
                    }
                }
            });

            $('.consolidatedDiv').show();
        });

    });
</script>
