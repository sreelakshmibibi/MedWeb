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
            url: doctorPaymentUrlCreate,
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
                data: 'month',
                name: 'month',
                className: 'text-center'

            },
            
            {
                data: 'amount',
                name: 'amount',
                className: 'text-center'

            },
            {
                data: 'paid_on',
                name: 'paid_on',
                className: 'text-center'
            },
            {
                data: 'remarks',
                name: 'remarks'
            },
            {
                data: 'status',
                name: 'status',
                className: 'text-center'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: true,
                className: 'text-center'

            },
        ],
    });
});
   
$(document).ready(function() {
    $('#salaryAdvanceForm').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Clear previous error messages
        $('.invalid-feedback').empty();
        $('.form-control').removeClass('is-invalid');


        // Get form data
        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'), // Use the action attribute from the form
            type: 'POST',
            data: formData,
            success: function(response) {
                // Handle success response
                if (response.success) {
                    $('#successMessage').text('Payment record added successfully');
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                    table.ajax.reload(); // Reload the DataTable to fetch the latest data
                    $('#salaryAdvanceForm')[0].reset(); 
                    // Optionally, reset the form or refresh data here
                }
            },
            error: function(xhr) {
                // Handle validation errors
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var message = xhr.responseJSON.message;
                    console.log(xhr.responseJSON.message);
                    for (var field in errors) {
                        $('#' + field).addClass('is-invalid'); // Highlight invalid fields
                        $('#' + field + 'Error').text(errors[field][0]); // Show error message
                    }
                    if (message) {
                         var field = 'amount';
                        // Display server error message
                        $('#' + field).addClass('is-invalid'); // Highlight invalid fields
                        $('#' + field + 'Error').text(message); // Show error message
                       
                    } 
                } 
            }
        });
    });
});

$(document).on('click', '.btn-del', function() {
    var salaryAdvanceId = $(this).data('id');
    $('#delete_salaryAdvance_id').val(salaryAdvanceId); // Set department ID in the hidden input
    $('#modal-salaryAdvance-delete').modal('show');
});

$('#btn-confirm-delete').click(function() {
    var salaryAdvanceId = $('#delete_salaryAdvance_id').val();
    var deleteReason = $('#deleteReason').val();
    var url = deleteurl;
    url = url.replace(':historyId', salaryAdvanceId);
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            "_token": window.csrf,
            "deleteReason" : deleteReason
        },
        success: function(response) {
            table.draw(); // Refresh DataTable
            $('#successMessage').text('Salary Advance payment record cancelled successfully');
            $('#successMessage').fadeIn().delay(3000)
                .fadeOut(); // Show for 3 seconds
        },
        error: function(xhr) {
            $('#modal-salaryAdvance-delete').modal('hide');
            swal("Error!", xhr.responseJSON.message, "error");
        }
    });
});

