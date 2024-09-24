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
                $(".sup_details").attr("readonly", false);

                console.log("New Supplier Added:", selected.text);
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
                            $(".sup_details").attr("readonly", true);
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
    $("#purchaseItemsForm").on("submit", function (event) {
        event.preventDefault();
        let isValid = true;

        // Clear previous error messages
        $(".invalid-feedback").text("").hide();
        $("#itemModePaymentError, #itemAmountPaidError, #itemCheckError").text(
            ""
        );

        // Check if at least one item row exists
        if ($("#itembody tr").length === 0) {
            alert("Please add at least one item.");
            isValid = false;
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
            // const modeOfPayment = $(
            //     'input[name="itemmode_of_payment[]"]:checked'
            // );
            // if (modeOfPayment.length === 0) {
            //     isValid = false;
            //     $("#itemModePaymentError").text(
            //         "Please select a mode of payment."
            //     );
            // }

            $("input[type='checkbox'][name='itemmode_of_payment[]']").each(
                function () {
                    const inputField = document.getElementById(
                        "item" + $(this).val()
                    );
                    // const inputField = $(this)
                    //     .closest("td")
                    //     .find("input[type='text']");
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

            // Validate amount paid
            // if (amountPaid <= 0) {
            //     $("#itemAmountPaidError").text(
            //         "Please enter a valid amount paid."
            //     );
            //     isValid = false;
            // }
        }

        // Submit the form if all validations are successful
        // if (isValid) {
        //     $("#purchaseItemsForm").submit();

        //     // Reset the form
        //     $("#purchaseItemsForm")[0].reset(); // Reset form fields
        //     $(".select2").val(null).trigger("change"); // Reset Select2 fields
        // }

        if (!isValid) return;

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
                    $("#successMessage")
                        .text(response.success)
                        .fadeIn()
                        .delay(3000)
                        .fadeOut();
                    // $('#modal-right').modal('hide');
                    // table.ajax.reload();
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
                if (errors) {
                    for (const key in errors) {
                        // showError(key, errors[key][0]);
                    }
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

    // $("input[type='tel'], input[type='text'], textarea").on(
    //     "input change",
    //     function () {
    //         const errorId = $(this).attr("id") + "Error"; // Create corresponding error ID
    //         $("#" + errorId)
    //             .text("")
    //             .hide(); // Hide the error message
    //         $(this).removeClass("is-invalid"); // Remove invalid class
    //     }
    // );

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
            // $(this).next(".invalid-feedback").text("").hide();
            // $(this).removeClass("is-invalid");
            // const inputField = document.getElementById("item" + $(this).val());
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
});
