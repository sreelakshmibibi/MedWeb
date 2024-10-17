$(document).ready(function () {
    // Clear previous error messages
    $(".invalid-feedback").text("").hide();
    $("input[name='lossOfPay']").change();
    calculateSalary();

    $(".type_select").select2({
        width: "100%",
    });

    $(".collapsible-header tr").click(function () {
        var target = $(this).data("target");
        $(target).collapse("toggle");
    });

    // Function to calculate the total earnings
    function calculateTotalEarnings() {
        let total = 0;
        $("input[name='earningsamount[]']").each(function () {
            let amount = parseFloat($(this).val()) || 0; // Parse and handle NaN
            total += amount;
        });
        $("input[name='incentive']").each(function () {
            let amount = parseFloat($(this).val()) || 0; // Parse and handle NaN
            total += amount;
        });
        $("#earningstotal").val(total.toFixed(2)); // Update total in the total input
        calculateSalary();
    }

    // Function to calculate the total additions
    function calculateTotalAdditions() {
        let total = 0;
        $("input[name='additionsamount[]']").each(function () {
            let amount = parseFloat($(this).val()) || 0; // Parse and handle NaN
            total += amount;
        });
        $("#additionstotal").val(total.toFixed(2)); // Update total in the total input
        calculateSalary();
    }

    // Function to calculate the monthly total deductions
    function calculateTotalMonthlyDeductions() {
        let total = 0;
        $("input[name='lossOfPay']").each(function () {
            let amount = parseFloat($(this).val()) || 0; // Parse and handle NaN
            total += amount;
        });
        $("input[name='monthlyDeduction']").each(function () {
            let amount = parseFloat($(this).val()) || 0; // Parse and handle NaN
            total += amount;
        });
        $("#monthlyDeductionsTotal").val(total.toFixed(2)); // Update total in the total input
        calculateSalary();
    }

    // Function to calculate the total deductions
    function calculateTotalDeductions() {
        let total = 0;
        $("input[name='deductionsamount[]']").each(function () {
            let amount = parseFloat($(this).val()) || 0; // Parse and handle NaN
            total += amount;
        });
        $("#deductionstotal").val(total.toFixed(2)); // Update total in the total input
        calculateSalary();
    }

    function calculateSalary() {
        const salary = parseFloat($("#earningstotal").val()) || 0;
        const statDeductionsTotal =
            parseFloat($("#deductionstotal").val()) || 0;
        const totalAdditions = parseFloat($("#additionstotal").val()) || 0;
        const monthlyDeductionsTotal =
            parseFloat($("#monthlyDeductionsTotal").val()) || 0;
        const totalDeductions = statDeductionsTotal + monthlyDeductionsTotal;

        const netsalary = salary - totalDeductions;
        const ctc = salary + totalAdditions;

        // update salary
        $("#salary").val(salary.toFixed(2));
        $("#netsalary").val(netsalary.toFixed(2));
        $("#ctc").val(ctc.toFixed(2));

        const previousDue = parseFloat($("#previousDue").val()) || 0;
        const advance = parseFloat($("#advance").val()) || 0;
        const monthlySalary = netsalary + previousDue - advance;
        $("#monthlySalary").val(monthlySalary.toFixed(2));
        updateBalance();
    }

    // Attach event listener to the earnings amount inputs
    $("input[name='incentive']").on("input change", calculateTotalEarnings);
    // Attach event listener to the additions amount inputs
    $("input[name='additionsamount[]']").on(
        "input change",
        calculateTotalAdditions
    );
    // Attach event listener to the deductions amount inputs
    $("input[name='deductionsamount[]']").on(
        "input change",
        calculateTotalDeductions
    );

    $("input[name='lossOfPay']").on(
        "input change",
        calculateTotalMonthlyDeductions
    );
    $("input[name='monthlyDeduction']").on(
        "input change",
        calculateTotalMonthlyDeductions
    );
    // Function to validate inputs in a section
    function validateSection(sectionId) {
        const section = document.querySelectorAll(`#${sectionId} .amount`);
        const dateInputs = document.querySelectorAll(
            `#${sectionId} input[type="date"]`
        );
        let sectionValid = true;

        section.forEach(function (input) {
            const errorDiv = input.nextElementSibling; // Assuming the error div is right after input

            // Clear previous error message
            errorDiv.innerHTML = "";
            errorDiv.style.display = "none"; // Hide the error message

            if (input.value.trim() === "") {
                sectionValid = false;
                input.classList.add("is-invalid");
                errorDiv.innerHTML = "This field is required.";
                errorDiv.style.display = "block"; // Show the error message
            } else {
                input.classList.remove("is-invalid");
            }
        });

        // Validate date inputs
        dateInputs.forEach(function (dateInput) {
            if (dateInput.value.trim() === "") {
                sectionValid = false;
                dateInput.classList.add("is-invalid");
                const errorDiv = dateInput.nextElementSibling; // Assuming the error div is right after input
                errorDiv.innerHTML = "This field is required.";
                errorDiv.style.display = "block"; // Show error message
            } else {
                dateInput.classList.remove("is-invalid");
            }
        });

        return sectionValid;
    }

    $(".select2").on("change", function () {
        $("#emp_typeError").text("").hide(); // Hide the error message
        $(this).removeClass("is-invalid"); // Remove invalid class if applicable
    });

    // Add input event listener for all amount fields to clear errors
    document.querySelectorAll(".amount").forEach(function (input) {
        input.addEventListener("input", function () {
            const errorDiv = input.nextElementSibling; // Assuming the error div is right after input
            if (input.value.trim() !== "") {
                input.classList.remove("is-invalid");
                errorDiv.innerHTML = "";
                errorDiv.style.display = "none"; // Hide the error message
            }
        });
    });

    document.querySelectorAll(`input[type="date"]`).forEach(function (input) {
        input.addEventListener("input", function () {
            const errorDiv = input.nextElementSibling; // Assuming the error div is right after input
            if (input.value.trim() !== "") {
                input.classList.remove("is-invalid");
                errorDiv.innerHTML = "";
                errorDiv.style.display = "none"; // Hide the error message
            }
        });
    });

    document
        .getElementById("saveMonthlySalaryButton")
        .addEventListener("click", function (event) {
            event.preventDefault();
            let isValid = true;

            // Clear previous error messages
            document
                .querySelectorAll(".invalid-feedback")
                .forEach(function (error) {
                    error.innerHTML = "";
                });

            // Validate each section
            const earningsValid = validateSection("earningsSection");
            // const additionsValid = validateSection("additionsSection");
            // const deductionsValid = validateSection("deductionsSection");

            // Combine the section validations
            // isValid = earningsValid && additionsValid && deductionsValid;

            // Check total fields
            const totalEarnings = document
                .getElementById("earningstotal")
                .value.trim();
            const totalAdditions = document
                .getElementById("additionstotal")
                .value.trim();
            const totalDeductions = document
                .getElementById("deductionstotal")
                .value.trim();
            const totalMonthlyDeductions = document
                .getElementById("monthlyDeductionsTotal")
                .value.trim();
            const monthlySalary =
                parseFloat(
                    document.getElementById("monthlySalary").value.trim()
                ) || 0;

            if (totalEarnings === "") {
                isValid = false;
                document
                    .getElementById("earningstotal")
                    .classList.add("is-invalid");
                document.querySelector(
                    "#earningstotal + .invalid-feedback"
                ).innerHTML = "Total earnings is required.";
            } else {
                document
                    .getElementById("earningstotal")
                    .classList.remove("is-invalid");
            }

            if (totalMonthlyDeductions === "") {
                isValid = false;
                document
                    .getElementById("monthlyDeductionsTotal")
                    .classList.add("is-invalid");
                document.querySelector(
                    "#monthlyDeductionsTotal + .invalid-feedback"
                ).innerHTML = "Total deductions is required.";
            } else {
                if (
                    document.getElementById("deductionReason").value == "" &&
                    document.querySelector("input[name='monthlyDeduction']")
                        .value > 0
                ) {
                    isValid = false;
                    document
                        .getElementById("deductionReason")
                        .classList.add("is-invalid");
                    $("#deductionReason + .invalid-feedback")
                        .html("Deduction reason is required.")
                        .show();
                } else {
                    document
                        .getElementById("deductionReason")
                        .classList.remove("is-invalid");
                    document
                        .getElementById("deductionstotal")
                        .classList.remove("is-invalid");
                }
            }

            if (totalAdditions === "") {
                isValid = false;
                document
                    .getElementById("additionstotal")
                    .classList.add("is-invalid");
                document.querySelector(
                    "#additionstotal + .invalid-feedback"
                ).innerHTML = "Total additions is required.";
            } else {
                document
                    .getElementById("additionstotal")
                    .classList.remove("is-invalid");
            }

            if (totalDeductions === "") {
                isValid = false;
                document
                    .getElementById("deductionstotal")
                    .classList.add("is-invalid");
                document.querySelector(
                    "#deductionstotal + .invalid-feedback"
                ).innerHTML = "Total deductions is required.";
            } else {
                document
                    .getElementById("deductionstotal")
                    .classList.remove("is-invalid");
            }

            // Validate payment method
            const paymentMethods = document.querySelectorAll(
                'input[name="medmode_of_payment[]"]:checked'
            );
            const bankAmount =
                parseFloat(document.getElementById("medbank").value.trim()) ||
                0;
            const cashAmount =
                parseFloat(document.getElementById("medcash").value.trim()) ||
                0;
            const totalPaidAmount = bankAmount + cashAmount;

            if (paymentMethods.length === 0) {
                isValid = false;
                document.getElementById("modePaymentError").innerHTML =
                    "At least one mode of payment is required.";
            } else {
                document.getElementById("modePaymentError").innerHTML = "";
            }

            // Validate bank amount if bank is selected
            if (
                document.getElementById("medmode_of_payment_bank").checked &&
                bankAmount === 0
            ) {
                isValid = false;
                document.getElementById("medbank").classList.add("is-invalid");
                // document.querySelector(
                //     "#medbank + .invalid-feedback"
                // ).innerHTML =
                //     "Bank amount is required if bank payment is selected.";
                $("#modePaymentError")
                    .html(
                        "Bank amount is required if bank payment is selected."
                    )
                    .show();
            } else {
                document
                    .getElementById("medbank")
                    .classList.remove("is-invalid");
            }

            // Validate cash amount if cash is selected
            if (
                document.getElementById("medmode_of_payment_cash").checked &&
                cashAmount === 0
            ) {
                isValid = false;
                document.getElementById("medcash").classList.add("is-invalid");
                // document.querySelector(
                //     "#medcash + .invalid-feedback"
                // ).innerHTML =
                //     "Cash amount is required if cash payment is selected.";
                $("#modePaymentError")
                    .html(
                        "Cash amount is required if cash payment is selected."
                    )
                    .show();
            } else {
                document
                    .getElementById("medcash")
                    .classList.remove("is-invalid");
            }

            if (totalPaidAmount > monthlySalary) {
                isValid = false;
                document.getElementById("amountPaidError").innerHTML =
                    "Total paid amount cannot exceed monthly salary.";
            } else {
                document.getElementById("amountPaidError").innerHTML = "";
            }

            if (!isValid) return;

            const form = $("#salaryForm");
            // Submit the form via AJAX
            const formData = new FormData($("#salaryForm")[0]);
            console.log(formData);
            console.log($(this).closest("form").attr("action"));
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
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
                            $("#saveMonthlySalaryButton").hide();
                        } else {
                            $("#saveMonthlySalaryButton").show();
                            // $("#saveSalaryButton").attr("disabled", false);
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
                            .text(
                                "An unexpected error occurred. Please try again."
                            )
                            .fadeIn()
                            .delay(3000)
                            .fadeOut();
                    }
                },
            });
        });

    const bankInput = document.getElementById("medbank");
    const cashInput = document.getElementById("medcash");

    // Handle checkbox changes for bank and cash
    document
        .getElementById("medmode_of_payment_bank")
        .addEventListener("change", function () {
            handleCheckboxChange();
            calculateTotalPaid();
        });
    document
        .getElementById("medmode_of_payment_cash")
        .addEventListener("change", function () {
            handleCheckboxChange();
            calculateTotalPaid();
        });

    // Trigger total calculation when bank or cash input changes
    bankInput.addEventListener("input", function () {
        calculateTotalPaid();
    });

    cashInput.addEventListener("input", function () {
        calculateTotalPaid();
    });

    // Show or hide payment fields based on checkbox state
    function handleCheckboxChange() {
        const bankChecked = document.getElementById(
            "medmode_of_payment_bank"
        ).checked;
        const cashChecked = document.getElementById(
            "medmode_of_payment_cash"
        ).checked;

        // Toggle bank input field
        bankInput.style.display = bankChecked ? "inline-block" : "none";
        if (!bankChecked) {
            bankInput.value = ""; // Clear the value if the checkbox is unchecked
        }

        // Toggle cash input field
        cashInput.style.display = cashChecked ? "inline-block" : "none";
        if (!cashChecked) {
            cashInput.value = ""; // Clear the value if the checkbox is unchecked
        }
    }

    // Calculate the total paid and update the balance
    function calculateTotalPaid() {
        let totalPaid = 0;
        let isValid = true;
        const errorElement = document.getElementById("modePaymentError");
        if (errorElement) {
            errorElement.textContent = ""; // Clear previous errors
        }

        // Calculate bank amount if it's visible
        if (bankInput && bankInput.style.display !== "none") {
            const bankValue = parseFloat(bankInput.value);
            if (isNaN(bankValue) || bankValue < 0) {
                isValid = false;
                if (errorElement) {
                    errorElement.textContent +=
                        "Bank amount should be a valid number. ";
                }
            } else {
                totalPaid += bankValue;
            }
        }

        // Calculate cash amount if it's visible
        if (cashInput && cashInput.style.display !== "none") {
            const cashValue = parseFloat(cashInput.value);
            if (isNaN(cashValue) || cashValue < 0) {
                isValid = false;
                if (errorElement) {
                    errorElement.textContent +=
                        "Cash amount should be a valid number. ";
                }
            } else {
                totalPaid += cashValue;
            }
        }

        if (isValid) {
            document.getElementById("medamountPaid").value =
                totalPaid.toFixed(2);
            document.getElementById("total_paid").value = totalPaid.toFixed(2);
            updateBalance();
        }
    }

    // Update balance due
    function updateBalance() {
        const amountPaid =
            parseFloat(document.getElementById("medamountPaid").value) || 0;
        const monthlySalary =
            parseFloat(document.getElementById("monthlySalary").value) || 0;

        const balanceDue = (monthlySalary - amountPaid).toFixed(2);
        document.getElementById("balance_due").value = balanceDue;
        // amountPaid <= monthlySalary ? balanceDue : "0.00";
    }
});

// document.addEventListener("DOMContentLoaded", (event) => {
//     const bankInput = document.getElementById("medbank");
//     const cashInput = document.getElementById("medcash");

//     // Handle checkbox changes for bank and cash
//     document
//         .getElementById("medmode_of_payment_bank")
//         .addEventListener("change", function () {
//             handleCheckboxChange();
//             calculateTotalPaid();
//         });
//     document
//         .getElementById("medmode_of_payment_cash")
//         .addEventListener("change", function () {
//             handleCheckboxChange();
//             calculateTotalPaid();
//         });

//     // Trigger total calculation when bank or cash input changes
//     bankInput.addEventListener("input", function () {
//         calculateTotalPaid();
//     });

//     cashInput.addEventListener("input", function () {
//         calculateTotalPaid();
//     });

//     // Show or hide payment fields based on checkbox state
//     function handleCheckboxChange() {
//         const bankChecked = document.getElementById(
//             "medmode_of_payment_bank"
//         ).checked;
//         const cashChecked = document.getElementById(
//             "medmode_of_payment_cash"
//         ).checked;

//         // Toggle bank input field
//         bankInput.style.display = bankChecked ? "inline-block" : "none";
//         if (!bankChecked) {
//             bankInput.value = ""; // Clear the value if the checkbox is unchecked
//         }

//         // Toggle cash input field
//         cashInput.style.display = cashChecked ? "inline-block" : "none";
//         if (!cashChecked) {
//             cashInput.value = ""; // Clear the value if the checkbox is unchecked
//         }
//     }

//     // Calculate the total paid and update the balance
//     function calculateTotalPaid() {
//         let totalPaid = 0;
//         let isValid = true;
//         const errorElement = document.getElementById("modePaymentError");
//         if (errorElement) {
//             errorElement.textContent = ""; // Clear previous errors
//         }

//         // Calculate bank amount if it's visible
//         if (bankInput && bankInput.style.display !== "none") {
//             const bankValue = parseFloat(bankInput.value);
//             if (isNaN(bankValue) || bankValue < 0) {
//                 isValid = false;
//                 if (errorElement) {
//                     errorElement.textContent +=
//                         "Bank amount should be a valid number. ";
//                 }
//             } else {
//                 totalPaid += bankValue;
//             }
//         }

//         // Calculate cash amount if it's visible
//         if (cashInput && cashInput.style.display !== "none") {
//             const cashValue = parseFloat(cashInput.value);
//             if (isNaN(cashValue) || cashValue < 0) {
//                 isValid = false;
//                 if (errorElement) {
//                     errorElement.textContent +=
//                         "Cash amount should be a valid number. ";
//                 }
//             } else {
//                 totalPaid += cashValue;
//             }
//         }

//         if (isValid) {
//             document.getElementById("medamountPaid").value =
//                 totalPaid.toFixed(2);
//             document.getElementById("total_paid").value = totalPaid.toFixed(2);
//             updateBalance();
//         }
//     }

//     // Update balance due
//     function updateBalance() {
//         const amountPaid =
//             parseFloat(document.getElementById("medamountPaid").value) || 0;
//         const monthlySalary =
//             parseFloat(document.getElementById("monthlySalary").value) || 0;

//         const balanceDue = (monthlySalary - amountPaid).toFixed(2);
//         document.getElementById("balance_due").value =
//             amountPaid <= monthlySalary ? balanceDue : "0.00";
//     }
// });
