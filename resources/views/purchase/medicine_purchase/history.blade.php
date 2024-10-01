@extends('layouts.dashboard')
@section('title', 'Purchases')
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
                    <h3 class="page-title">Medicine Purchase Details</h3>

                    <a type="button" class="waves-effect waves-light btn btn-primary" title="Back"
                        href="{{ route('medicine.purchases') }}">
                        <span class="hidden-sm-up"><i class="fa-solid fa-angles-left"></i></span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-angles-left"></i> Back</span>
                    </a>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table width="100%"
                                class="table table-bordered table-hover table-striped mb-0 data-table text-center">
                                <thead class="bg-primary-light text-center">
                                    <tr>
                                        <th width="10px">No</th>
                                        <th width="80px">Date</th>
                                        <th>Branch</th>
                                        <th>Invoice no.</th>
                                        <th width="80px">Invoice date</th>
                                        <th>Supplier</th>
                                        {{-- <th>Items</th> --}}
                                        <th>Bill Amount</th>
                                        <th>Previous Due</th>
                                        <th>Total Amount</th>
                                        <th>Payment Mode</th>
                                        <th>Amount Paid</th>
                                        <th>Balance Due</th>
                                        <th width="10px">Status</th>
                                        <th width="180px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populate table rows with department data -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->

    @include('purchase.purchase.cancel')

    <script>
        var clinicBasicDetails = @json($clinicBasicDetails);
        var getPurchasesUrl = "{{ route('medicine.purchases.history') }}";

        var table; // Define table variable in the global scope

        jQuery(function($) {
            // Check if DataTable is already initialized
            if ($.fn.DataTable.isDataTable(".data-table")) {
                // Destroy existing DataTable instance
                $(".data-table").DataTable().destroy();
            }

            table = $(".data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: getPurchasesUrl,
                    type: "GET",
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Adding 1 to start counting from 1
                        },
                    },
                    {
                        data: "entrydate",
                        name: "entrydate",
                        render: function(data) {
                            return moment(data).format('DD-MM-YYYY'); // Format using Moment.js
                        }
                    },
                    {
                        data: "branch",
                        name: "branch",
                        className: "text-start",
                    },
                    {
                        data: "invoice_no",
                        name: "invoice_no",
                    },
                    {
                        data: "invoice_date",
                        name: "invoice_date",
                        render: function(data) {
                            return moment(data).format('DD-MM-YYYY'); // Format using Moment.js
                        }
                    },
                    {
                        data: "supplier",
                        name: "supplier",
                        className: "text-start",
                    },
                    {
                        data: "bill_amount",
                        name: "bill_amount",
                    },
                    {
                        data: "previous_due",
                        name: "previous_due",
                        render: function(data) {
                            return data != null ? data : '-'; // Format using Moment.js
                        }
                    },
                    {
                        data: "amount_to_be_paid",
                        name: "amount_to_be_paid",
                        render: function(data) {
                            return data != null ? data : '-'; // Format using Moment.js
                        }
                    },
                    {
                        data: "mode",
                        name: "mode",
                        className: "text-start",
                    },
                    {
                        data: "amount_paid",
                        name: "amount_paid",
                        // className: "text-left",
                    },
                    {
                        data: "balance_due",
                        name: "balance_due",
                        render: function(data) {
                            return data != null ? data : '-'; // Format using Moment.js
                        }
                    },

                    {
                        data: "status",
                        name: "status",
                    },
                    {
                        data: "action",
                        name: "action",
                        className: "min-w-120 w-150",
                        orderable: false,
                        searchable: true,
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
                        messageTop: "Purchase Report",
                        orientation: "landscape",
                        pageSize: "A4",
                        footer: true,
                        filename: "Medicine Purchase Report",
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
                        messageTop: "Medicine Purchase Report",
                        footer: true,
                        filename: "Medicine Purchase Report",
                        exportOptions: {
                            columns: ":visible",
                        },
                    },
                    {
                        extend: "pdfHtml5",
                        text: "PDF",
                        title: clinicBasicDetails.clinic_name,
                        messageTop: "Medicine Purchase Report",
                        orientation: "landscape",
                        pageSize: "A4",
                        exportOptions: {
                            columns: ":visible",
                        },
                        footer: true,
                        filename: "Medicine Purchase Report",
                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 10;
                            doc.styles.tableHeader.fontSize = 10;
                        },
                    },
                ],
            });
        });

        // Download bills when button is clicked
        $(document).on('click', '.downloadBills', function() {
            var purchaseId = $(this).data('id');
            window.location.href = '{{ url('purchases') }}' + "/" + purchaseId + "/download-bills";
        });
    </script>
@endsection

@section('scripts')
    <!-- custom JavaScript file -->
    <script src="{{ asset('js/medicine_purchase.js') }}"></script>
@endsection
