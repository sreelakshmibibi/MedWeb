var table; // Define table variable in the global scope

jQuery(function ($) {
    table = $(".data-table").DataTable({
        processing: true,
        serverSide: true,
        // ajax: "{{ route('settings.department') }}",
        ajax: {
            url: insuranceUrl,
            type: "GET",
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                    // Return the row index (starts from 0)
                    return meta.row + 1; // Adding 1 to start counting from 1
                },
            },
            {
                data: "company_name",
                name: "company_name",
            },
            {
                data: "claim_type",
                name: "claim_type",
            },
            {
                data: "status",
                name: "status",
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: true,
            },
        ],
    });
});
