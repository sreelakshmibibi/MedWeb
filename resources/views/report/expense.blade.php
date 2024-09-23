@extends('layouts.dashboard')
@section('title', 'Expense Report')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">

                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Expense Report</h3>
                    <div>
                        <a type="button" class="waves-effect waves-light btn btn-circle btn-primary btn-xs" title="back"
                            href="{{ route('report') }}">
                            <i class="fa-solid fa-angles-left"></i></a>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="expenseDiv container" >
                        <div class="table-responsive" style=" width: 100%; overflow-x: auto;">
                            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                                id="expenseTable" width="100%">
                                <thead class="bg-primary-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Expense Name</th>
                                        <th>Expense Amount</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr class="bt-3 border-primary">
                                        <th colspan="2"></th>
                                        <th>Total:</th>
                                        <th id="total-expense"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </div>
    <script>
        jQuery(function($) {
            var table;
            var clinicBasicDetails = @json($clinicBasicDetails);


            if ($.fn.DataTable.isDataTable("#consolidatedTable")) {
                $('#expenseTable').DataTable().destroy();
            }

            // Initialize DataTable
            table = $('#expenseTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "",
                    type: 'GET',
                    data: function(d) {
                        
                    },
                    dataSrc: function(json) {
                        return json.data; // Ensure `json.data` is correct
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'billDate', name: 'billDate' },
                    { data: 'expenseName', name: 'expenseName' },
                    { data: 'amount', name: 'amount' }
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
                        messageTop: 'Expense Report',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        footer: true,
                        filename: 'Expense Report',
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
                        messageTop: 'Expense Report',
                        footer: true,
                        filename: 'Expense Report',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        // className: 'btn btn-danger',
                        title: clinicBasicDetails.clinic_name,
                        messageTop: 'Expense Report',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible'
                        },
                        footer: true,
                        headerRows: 1,
                        filename: 'Expense Report',
                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 10;
                            doc.styles.tableHeader.fontSize = 10;

                        }
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    
                        var total = api.column(3).data().reduce(function(a, b) {
                            return parseFloat(a) + parseFloat(b);
                        }, 0);
                        $(api.column(3).footer()).html(total.toFixed(2));
                    
                }
            });

        });
    </script>
@endsection
