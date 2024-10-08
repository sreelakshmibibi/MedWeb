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
        // ajax: "{{ route('settings.department') }}",
        ajax: {
            url: payHeadUrl,
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
                    // Return the row index (starts from 0)
                    return meta.row + 1; // Adding 1 to start counting from 1
                },
            },
            {
                data: "head_type",
                name: "head_type",
                className: "text-left",
            },
            {
                data: "type",
                name: "type",
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
    });
});
