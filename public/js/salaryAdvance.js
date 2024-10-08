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
            url: salaryAdvanceUrl,
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
                data: 'staff_id',
                name: 'staff_idid'
            },
            {
                data: 'photo',
                name: 'photo',
                render: function(data, type, full, meta) {
                    if (data) {
                        data = "{{ asset('storage/') }}/" + data;
                    } else {
                        data = "{{ asset('images/svg-icon/user.svg') }}";
                    }
                    return '<img src="' + data +
                        '" height="50" style="border-radius:50%;"/>';
                },
                orderable: false,
                searchable: false

            },
            {
                data: 'name',
                name: 'name',
                className: 'text-start'
            },
            {
                data: 'role',
                name: 'role'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'branch',
                name: 'branch',
                className: 'text-start'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: true
            },
        ],
    });
});
