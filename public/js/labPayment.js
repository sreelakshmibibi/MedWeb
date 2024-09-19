var table; // Define table variable in the global scope
jQuery(function ($) {
    // Check if DataTable is already initialized
    if ($.fn.DataTable.isDataTable(".data-table")) {
        // Destroy existing DataTable instance
        $(".data-table").DataTable().destroy();
    }

    table = $(".data-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: labPaymentUrl,
            type: "GET",
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                className: "text-center",
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + 1; // Adding 1 to start counting from 1
                },
            },
            {
                data: "from_date",
                name: "from_date",
                className: "text-center",
            },
            {
                data: "to_date",
                name: "to_date",
                className: "text-center",
            },
            {
                data: "bill_amount",
                name: "bill_amount",
                className: "text-left",
            },
            {
                data: "previous_due",
                name: "previous_due",
                className: "text-left",
            },
            {
                data: "amount_to_be_paid",
                name: "amount_to_be_paid",
                className: "text-left",
            },
            {
                data: "mode",
                name: "mode",
                className: "text-left",
            },
            {
                data: "amount_paid",
                name: "amount_paid",
                className: "text-left",
            },
            {
                data: "balance_due",
                name: "balance_due",
                className: "text-left",
            },
            {
                data: "created_at",
                name: "created_at",
                className: "text-left",
            },
            {
                data: "status",
                name: "status",
                className: "text-center",
            },
            {
                data: "action",
                name: "action",
                className: "text-center",
                orderable: false,
                searchable: true,
            },
        ],
        dom: 'Bfrtlp',
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All']
        ],
        buttons: [
            {
                extend: 'print',
                text: 'Print',
                title: 'Your Clinic Name',
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
                    $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                }
            },
            {
                extend: 'excelHtml5',
                text: 'Excel',
                title: 'Your Clinic Name',
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
                title: 'Your Clinic Name',
                messageTop: 'Collection Report',
                orientation: 'landscape',
                pageSize: 'A3',
                exportOptions: {
                    columns: ':visible'
                },
                footer: true,
                filename: 'Collection Report',
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 10;
                    doc.styles.tableHeader.fontSize = 10;
                }
            }
        ],
    });
});
