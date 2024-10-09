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
                data: "amount",
                name: "amount",
                className: "text-center",
            },
            {
                data: "paid_on",
                name: "paid_on",
                className: "text-center",
                render: function (data, type, row) {
                    return moment(data).format("DD-MM-YYYY");
                },
            },
            {
                data: "remarks",
                name: "remarks",
                render: function (data, type, row) {
                    return data ? data : "-";
                },
            },
            {
                data: "status",
                name: "status",
                className: "text-center",
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: true,
                className: "text-center",
            },
        ],
    });
});

$(document).ready(function () {
    $("#doctorPaymentForm").on("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Clear previous error messages
        $(".invalid-feedback").empty();
        $(".form-control").removeClass("is-invalid");

        // Get form data
        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr("action"), // Use the action attribute from the form
            type: "POST",
            data: formData,
            success: function (response) {
                // Handle success response
                if (response.success) {
                    alert(response.message); // Show success message
                    table.ajax.reload(); // Reload the DataTable to fetch the latest data
                    $("#doctorPaymentForm")[0].reset();
                    // Optionally, reset the form or refresh data here
                }
            },
            error: function (xhr) {
                // Handle validation errors
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var field in errors) {
                        $("#" + field).addClass("is-invalid"); // Highlight invalid fields
                        $("#" + field + "Error").text(errors[field][0]); // Show error message
                    }
                } else {
                    alert("An error occurred. Please try again."); // Handle other errors
                }
            },
        });
    });
});

$(document).on("click", ".btn-del", function () {
    // alert("hi");
    var doctorPaymentId = $(this).data("id");
    $("#delete_doctorPayment_id").val(doctorPaymentId); // Set department ID in the hidden input
    $("#modal-doctorPayment-delete").modal("show");
});

$("#btn-confirm-delete").click(function () {
    var doctorPaymentId = $("#delete_doctorPayment_id").val();
    var deleteReason = $("#deleteReason").val();
    var url = deleteurl;
    url = url.replace(":historyId", doctorPaymentId);
    $.ajax({
        type: "POST",
        url: url,
        data: {
            _token: window.csrf,
            deleteReason: deleteReason,
        },
        success: function (response) {
            table.draw(); // Refresh DataTable
            $("#successMessage").text(
                "Doctor payment record cancelled successfully"
            );
            $("#successMessage").fadeIn().delay(3000).fadeOut(); // Show for 3 seconds
        },
        error: function (xhr) {
            $("#modal-doctorPayment-delete").modal("hide");
            swal("Error!", xhr.responseJSON.message, "error");
        },
    });
});
