$(document).ready(function () {
    // Clear previous error messages
    $(".invalid-feedback").text("").hide();
    $("#itemModePaymentError, #itemAmountPaidError, #itemCheckError").text("");
    $(".name_select")
        .select2({
            tags: true, // Allow user to add new options
            tokenSeparators: [","], // Define how tags are separated
            createTag: function (params) {
                var term = $.trim(params.term);
                if (term === "") {
                    return null; // Don't create a tag for empty input
                }

                // Return a new tag object
                return {
                    id: term,
                    text: term,
                    newTag: true, // Indicate this is a new tag
                };
            },
        })
        .on("select2:select", function (e) {
            var selected = e.params.data;
            if (selected.newTag || selected.id == "") {
                // If it's a new tag, clear the phone and address fields
                $("#phone").val("");
                $("#address").val("");
                $("#gst_no").val("");
                $("#previousOutStanding").val("");
                $(".sup_details").attr("readonly", false);
                calculateTotal();
                // console.log("New Supplier Added:", selected.text);
            } else {
                // Fetch existing supplier details via AJAX (if needed)
                $.ajax({
                    url: "/getSupplierDetails/" + selected.id, // Adjust this URL
                    method: "GET",
                    success: function (data) {
                        if (data) {
                            $("#phone").val(data.phone).trigger("change");
                            $("#address").val(data.address).trigger("change");
                            $("#gst_no").val(data.gst).trigger("change");
                            $("#previousOutStanding")
                                .val(data.balancedue)
                                .trigger("change");
                            $(".sup_details").attr("readonly", true);
                            calculateTotal();
                        }
                    },
                    error: function (xhr) {
                        console.error("Error fetching supplier details:", xhr);
                    },
                });
            }
        });

    $("#phone").on("input", function () {
        this.value = this.value.replace(/[^0-9.]/g, "");
    });

    $("#category").change(function () {
        const selectedValue = $(this).val();
        if (selectedValue === "D") {
            $("#paymentbody").show();
        } else if (selectedValue === "C") {
            $("#paymentbody").hide();
        }
    });
});

let rowIndex = 1;

$(document).on("click", "#itemAddRow", function () {
    rowIndex++;
    const tbody = document.getElementById("itembody");

    const row = document.createElement("tr");
    row.innerHTML = `
        <td>${rowIndex}</td>
        <td class="text-start">
            <input type="text" name="item[]" class="form-control" placeholder="Enter item">
            <div class="invalid-feedback text-start"></div>
        </td>
        <td>
            <input type="number" min="1" name="price[]" class="form-control price-input" placeholder="Price">
            <div class="invalid-feedback text-start"></div>
        </td>
        <td>
            <input type="number" min="1" name="quantity[]" class="form-control quantity-input" placeholder="Quantity">
            <div class="invalid-feedback text-start"></div>
        </td>
        <td>
            <input type="text" class="form-control text-center item-amount" name="itemAmount[]" readonly  placeholder="0.00">
            <div class="invalid-feedback text-start"></div>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
});

// Remove row function
window.removeRow = function (button) {
    const row = button.closest("tr");
    row.remove();
    updateSlno();
    calculateTotal();
};

function updateSlno() {
    const rows = document.querySelectorAll("#itembody tr");
    rowIndex = 1; // Reset rowIndex

    rows.forEach((row, index) => {
        row.cells[0].innerText = rowIndex; // Update Slno
        rowIndex++;
    });
    rowIndex--;
}

function removeAllRowsExceptFirst() {
    const tbody = document.getElementById("itembody");
    const rows = tbody.querySelectorAll("tr");

    // Loop through all rows except the first
    for (let i = 1; i < rows.length; i++) {
        rows[i].remove(); // Remove the row
    }

    // Reset the Slno for the first row
    updateSlno();
}

// Calculate amount
$(document).on("input", ".price-input, .quantity-input", function () {
    const row = $(this).closest("tr");
    const price = parseFloat(row.find(".price-input").val()) || 0;
    const quantity = parseFloat(row.find(".quantity-input").val()) || 0;
    const amount = price * quantity;

    row.find(".item-amount").val(amount.toFixed(2)); // Update the amount field
    calculateTotal();
});

// Calculate total item amount
function calculateTotal() {
    let subtotal = 0;
    $(".item-amount").each(function () {
        const amount = parseFloat($(this).val()) || 0; // Parse amount and default to 0
        subtotal += amount;
    });

    // Update fields
    $("#itemtotal").val(subtotal.toFixed(2)); // Update subtotal field

    // Get delivery charge and GST
    const deliveryCharge = parseFloat($("#deliverycharge").val()) || 0;
    const gstAmount = parseFloat($("#tax").val()) || 0;

    // Calculate total with delivery charge and GST
    const total = subtotal + deliveryCharge + gstAmount;

    // Update fields
    $("#currentbilltotal").val(total.toFixed(2)); // Update current bill total

    // Get previousOutStanding and discount
    const previousOutStanding =
        parseFloat($("#previousOutStanding").val()) || 0;
    const discount = parseFloat($("#discount").val()) || 0;

    const grandtotal = total - discount + previousOutStanding;

    // Update fields
    $("#grandTotal").val(grandtotal.toFixed(2)); // Update current bill total

    const itemAmountPaid = parseFloat($("#itemAmountPaid").val()) || 0;
    const balance = grandtotal - itemAmountPaid;

    $("#balance").val("");
    $("#itemBalanceToGiveBack").val("");
    // Update fields
    if (balance > 0) {
        $("#balance").val(balance.toFixed(2)); // Update balance
    } else {
        $("#itemBalanceToGiveBack").val(Math.abs(balance).toFixed(2));
    }
}

// Delivery charge and GST event listeners
$(document).on(
    "input",
    "#deliverycharge, #tax, #discount, #previousOutStanding",
    function () {
        this.value = this.value.replace(/[^0-9.]/g, "");
        calculateTotal(); // Recalculate total whenever delivery charge or GST is updated
    }
);

// Toggle input fields based on checkbox selection
$("input[type='checkbox'][name='itemmode_of_payment[]']").change(function () {
    let checkbox = $(this);
    let allItemsFilled = true;

    // Check each row in the item table
    $("#itembody tr").each(function () {
        const itemName = $(this).find("input[name='item[]']").val().trim();
        const price =
            parseFloat($(this).find("input[name='price[]']").val()) || 0;
        const quantity =
            parseInt($(this).find("input[name='quantity[]']").val()) || 0;

        if (!itemName || price <= 0 || quantity <= 0) {
            allItemsFilled = false;
        }
    });

    if (allItemsFilled) {
        const inputFieldId = document.getElementById("item" + checkbox.val());
        inputFieldId.style.display = checkbox.is(":checked")
            ? "inline"
            : "none";
        inputFieldId.value = "";
        calculateTotalPaid(); // Recalculate total when toggling checkboxes
    } else {
        alert("Please add items first!");
        checkbox.prop("checked", false);
    }
});

// Recalculate total when input values change
$(".itempay").on("input", function () {
    this.value = this.value.replace(/[^0-9.]/g, "");
    calculateTotalPaid();
});

// Calculate total paid amount
function calculateTotalPaid() {
    let totalPaid = 0;

    // Iterate through each payment mode input field with the class 'itempay'
    $(".itempay").each(function () {
        const value = parseFloat($(this).val()) || 0; // Default to 0 if not a number
        totalPaid += value; // Accumulate total
    });

    // Update the total paid amount field
    $("#itemAmountPaid").val(totalPaid.toFixed(2)); // Format to 2 decimal places

    calculateTotal();
}

$(document).ready(function () {
    // Event listener for save button
    $("#savePurchaseButton").on("click", function (event) {
        event.preventDefault();
        let isValid = true;

        // Clear previous error messages
        $(".invalid-feedback").text("").hide();
        $("#itemModePaymentError, #itemAmountPaidError, #itemCheckError").text(
            ""
        );
        // console.log(this)
        const inputs = document.querySelectorAll(
            "input[required], textarea[required]"
        );

        inputs.forEach((input) => {
            if (input.value.trim() === "") {
                isValid = false;
                input.classList.add("is-invalid"); // Add a class for styling
            } else {
                input.classList.remove("is-invalid"); // Remove class if valid
            }
        });

        if (!isValid) {
            document.getElementById("errorMessagecreate").innerText =
                "Please fill out all required fields.";
            document.getElementById("errorMessagecreate").style.display =
                "block";
        } else {
            document.getElementById("errorMessagecreate").style.display =
                "none"; // Hide error message if valid
        }

        // Check if at least one item row exists
        if ($("#itembody tr").length === 0) {
            isValid = false;
            alert("Please add at least one item.");
            return;
        }

        // Validate form fields
        const validations = [
            {
                selector: "#name",
                errorMessage: "Please select a Supplier Name.",
            },
            {
                selector: "#phone",
                errorMessage: "Please enter a valid 10-digit phone number.",
                condition: (val) => val.length < 10,
            },
            { selector: "#address", errorMessage: "Please enter an address." },
            {
                selector: "#gst_no",
                errorMessage: "Please enter a valid GST No.",
            },
            {
                selector: "#invoice_no",
                errorMessage: "Please enter an Invoice No.",
            },
            {
                selector: "#invoice_date",
                errorMessage: "Please select an Invoice Date.",
            },
            { selector: "#branch", errorMessage: "Please select a Branch." },
        ];

        validations.forEach(({ selector, errorMessage, condition }) => {
            const value = $(selector).val();
            if (!value || (condition && condition(value))) {
                isValid = false;
                $(selector + "Error")
                    .text(errorMessage)
                    .show();
            }
        });

        // Validate item rows
        $("#itembody tr").each(function () {
            const itemName = $(this).find("input[name='item[]']").val().trim();
            const price =
                parseFloat($(this).find("input[name='price[]']").val()) || 0;
            const quantity =
                parseInt($(this).find("input[name='quantity[]']").val()) || 0;

            if (!itemName) {
                isValid = false;
                $(this)
                    .find("input[name='item[]']")
                    .next(".invalid-feedback")
                    .text("Item name is required.")
                    .show();
            }
            if (price <= 0) {
                isValid = false;
                $(this)
                    .find("input[name='price[]']")
                    .next(".invalid-feedback")
                    .text("Price must be a positive number.")
                    .show();
            }
            if (quantity <= 0) {
                isValid = false;
                $(this)
                    .find("input[name='quantity[]']")
                    .next(".invalid-feedback")
                    .text("Quantity must be a positive number.")
                    .show();
            }
        });

        // Validate payment section if category is "D"
        if ($("#category").val() === "D") {
            $("input[type='checkbox'][name='itemmode_of_payment[]']").each(
                function () {
                    const inputField = document.getElementById(
                        "item" + $(this).val()
                    );

                    if ($(this).is(":checked") && inputField.value === "") {
                        isValid = false;
                        inputField.classList.add("is-invalid");
                        // inputField.addClass("is-invalid");
                        $("#itemModePaymentError").text(
                            "Please enter the amount for " +
                                $(this).next("label").text()
                        );
                    } else {
                        // inputField.removeClass("is-invalid");
                        inputField.classList.remove("is-invalid");
                        $("#itemModePaymentError").text("");
                    }
                }
            );

            const modeOfPayment = $(
                'input[name="itemmode_of_payment[]"]:checked'
            );
            if (modeOfPayment.length === 0) {
                isValid = false;
                $("#itemModePaymentError").text(
                    "Please select a mode of payment."
                );
            }

            // Additional validations for payment amounts
            const amountPaid =
                parseFloat($('input[name="itemAmountPaid"]').val()) || 0;
            const balanceToGiveBack =
                parseFloat($('input[name="itemBalanceToGiveBack"]').val()) || 0;

            // Validate balance and conditions
            if (
                balanceToGiveBack > 0 &&
                !(
                    $('input[name="consider_for_next_payment"]').is(
                        ":checked"
                    ) || $('input[name="itemBalance_given"]').is(":checked")
                )
            ) {
                $("#itemCheckError").text(
                    "If balance is to be given back, please mark (Consider for Next Payment or Balance Given)."
                );
                isValid = false;
            }
        }

        if (!isValid) return;
        const form = $("#purchaseItemsForm");
        // Submit the form via AJAX
        const formData = new FormData($("#purchaseItemsForm")[0]);
        console.log(formData);
        console.log($(this).closest("form").attr("action"));
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            url: $(this).closest("form").attr("action"),
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // Show success message
                    $("#successMessage")
                        .text(response.success)
                        .fadeIn()
                        .delay(3000)
                        .fadeOut();
                    console.log(response);
                    if (response.status == 201) {
                        // Reset the form
                        form[0].reset(); // Reset the form
                        // Reset specific fields
                        $("#name").val("").trigger("change"); // Reset Supplier Name
                        $("#branch").val("").trigger("change"); // Reset Branch
                        $("#category").val("D").trigger("change");
                        $(".itempay").hide();
                        removeAllRowsExceptFirst();
                    } else {
                        if (response.purchase.billfile != null) {
                            $("#uploadedBills").attr("disabled", false);
                        }
                    }
                } else if (response.error) {
                    $("#errorMessagecreate")
                        .text(response.error)
                        .fadeIn()
                        .delay(3000)
                        .fadeOut();
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON.errors;
                $("#errorMessagecreate").empty(); // Clear previous error messages
                if (errors) {
                    // Loop through each error and display it
                    for (const key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            // Create a new error message element
                            const errorMessage = $("<div>")
                                .text(errors[key][0])
                                .addClass("alert alert-danger");
                            $("#errorMessagecreate").append(errorMessage);
                        }
                    }
                    $("#errorMessagecreate").fadeIn().delay(3000).fadeOut();
                } else {
                    $("#errorMessagecreate")
                        .text("An unexpected error occurred. Please try again.")
                        .fadeIn()
                        .delay(3000)
                        .fadeOut();
                }
            },
        });
    });

    // Hide error messages when user interacts with input fields
    $("input").on("input change", function () {
        const errorId = $(this).attr("id") + "Error"; // Create corresponding error ID
        $("#" + errorId)
            .text("")
            .hide(); // Hide the error message
        $(this).removeClass("is-invalid"); // Remove invalid class if applicable
    });

    // Hide error messages when user interacts with Select2 fields
    $(".select2").on("change", function () {
        const errorId = $(this).attr("id") + "Error"; // Create corresponding error ID
        $("#" + errorId)
            .text("")
            .hide(); // Hide the error message
        $(this).removeClass("is-invalid"); // Remove invalid class if applicable
    });

    // Hide error messages when user interacts with checkboxes
    $("input[type='checkbox'][name='itemmode_of_payment[]']").on(
        "change",
        function () {
            $("#item" + $(this).val()).removeClass("is-invalid");
            $("#itemModePaymentError").text(""); // Clear mode of payment error
        }
    );

    // Generalized error message hiding for all input types
    $(
        "input[type='text'],input[type='number'], select, input[type='tel'], input[type='text'], textarea"
    ).on("input change", function () {
        const errorId = $(this).attr("id") + "Error"; // Create corresponding error ID
        $("#" + errorId)
            .text("")
            .hide(); // Hide the error message
        $(this).removeClass("is-invalid"); // Remove invalid class if applicable
        $(this).next(".invalid-feedback").text("").hide();
    });

    $("#modal-cancel-lab-bill").on("hidden.bs.modal", function () {
        // Reset the form
        $("#form-cancel-purchase")[0].reset();

        // Clear specific fields
        $("#cancel_bill_id").val("");
        $("#bill_cancel_reason").val("");

        // Optionally, remove any validation messages
        $("#reasonError").text("");
    });
});

$(document).on("click", ".btn-del", function () {
    var billId = $(this).data("id");
    $("#cancel_bill_id").val(billId);
    $("#modal-cancel-lab-bill").modal("show");
});

$("#btn-cancel-bill").click(function () {
    var purchaseId = $("#cancel_bill_id").val();
    var reason = $("#bill_cancel_reason").val();

    if (reason.length === 0) {
        $("#bill_cancel_reason").addClass("is-invalid");
        $("#reasonError").text("Reason is required.").show();
        return; // Stop further execution
    }

    var url = "/purchases/cancel/{id}";
    url = url.replace("{id}", purchaseId);

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "POST",
        url: url,
        data: {
            // _token: "{{ csrf_token() }}",
            reason: reason,
        },
        success: function (response) {
            $("#modal-cancel-lab-bill").modal("hide"); // Close modal after success
            table.draw(); // Refresh DataTable
            $("#successMessage").text("Bill cancelled successfully");
            $("#successMessage").fadeIn().delay(3000).fadeOut(); // Show for 3 seconds
        },
        error: function (xhr) {
            $("#modal-cancel-lab-bill").modal("hide"); // Close modal in case of error
            console.log("Error!", xhr.responseJSON.message, "error");
        },
    });
});
