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
            url: holidayUrl,
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
                data: "holiday_on",
                name: "holiday_on",
                className: "text-left",
            },
            {
                data: "reason",
                name: "reason",
                className: "text-left",
            },
            {
            
                data: "branches",
                name: "branches",
                className: "text-left",
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
