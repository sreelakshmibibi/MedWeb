var table; // Define table variable in the global scope

jQuery(function ($) {
    table = $(".data-table").DataTable({
        processing: true,
        serverSide: true,
        // ajax: "{{ route('settings.department') }}",
        ajax: {
            url: planUrl,
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
                data: "plan",
                name: "plan",
            },
            {
                data: "cost",
                name: "cost",
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
