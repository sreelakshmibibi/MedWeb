

// document.addEventListener("DOMContentLoaded", (event) => {
//     const gpaycash = document.getElementById("medgpay");
//     const cash = document.getElementById("medcash");
//     const cardcash = document.getElementById("medcard");
//     const machine = document.getElementById("medmachine");

//     // Function to handle checkbox changes
//     function handleCheckboxChange() {
//         // Get the current checkbox states
//         const gpayChecked = document.getElementById("medmode_of_payment_gpay").checked;
//         const cashChecked = document.getElementById("medmode_of_payment_cash").checked;
//         const cardChecked = document.getElementById("medmode_of_payment_card").checked;

//         // Show/Hide input fields based on checkbox state
//         document.getElementById("medgpay").style.display = gpayChecked ? "inline-block" : "none";
//         document.getElementById("medcash").style.display = cashChecked ? "inline-block" : "none";
//         document.getElementById("medcard").style.display = cardChecked ? "inline-block" : "none";
//         document.getElementById("medmachine").style.display = cardChecked ? "inline-block" : "none"; // Example for machine select box
//     }

//     // Attach change event listeners to checkboxes
//     document.getElementById("medmode_of_payment_gpay").addEventListener("change", function () {
//         handleCheckboxChange();
//         calculateTotalPaid();
//     });
//     document.getElementById("medmode_of_payment_cash").addEventListener("change", function () {
//         handleCheckboxChange();
//         calculateTotalPaid();
//     });
//     document.getElementById("medmode_of_payment_card").addEventListener("change", function () {
//         handleCheckboxChange();
//         calculateTotalPaid();
//     });

//     // Function to calculate total paid
//     function calculateTotalPaid() {
//         let totalPaid = 0;

//         const gpayInput = document.getElementById('medgpay');
//         const cashInput = document.getElementById('medcash');
//         const cardInput = document.getElementById('medcard');

//         if (gpayInput && gpayInput.style.display !== 'none') {
//             totalPaid += parseFloat(gpayInput.value) || 0;
//         }
//         if (cashInput && cashInput.style.display !== 'none') {
//             totalPaid += parseFloat(cashInput.value) || 0;
//         }
//         if (cardInput && cardInput.style.display !== 'none') {
//             totalPaid += parseFloat(cardInput.value) || 0;
//         }

//         document.getElementById('medamountPaid').value = totalPaid.toFixed(2);
//     }

//     // Attach input event listeners to input fields for calculating total
//     document.getElementById("medgpay").addEventListener("input", calculateTotalPaid);
//     document.getElementById("medcash").addEventListener("input", calculateTotalPaid);
//     document.getElementById("medcard").addEventListener("input", calculateTotalPaid);

//     // Initial visibility update and total calculation
//     handleCheckboxChange();
//     calculateTotalPaid();
// });

// document.addEventListener("DOMContentLoaded", function () {
//     // Helper function to update the stock message
//     function updateStockMessage(row, message) {
//         const stockMessageElement = row.querySelector(
//             'span[id^="stock_message"]'
//         );
//         if (stockMessageElement) {
//             stockMessageElement.textContent = message;
//         }
//     }

//     // Function to update rates and total
//     function updateRateAndTotal() {
//         let total = 0;

//         document
//             .querySelectorAll('#tablebody input[type="checkbox"]')
//             .forEach(function (checkbox) {
//                 const row = checkbox.closest("tr");
//                 const price =
//                     parseFloat(checkbox.getAttribute("data-price")) || 0;
//                 const quantityInput = row.querySelector(
//                     'input[name="quantity[]"]'
//                 );
//                 const rateInput = row.querySelector('input[name="rate[]"]');
//                 const isChecked = checkbox.checked;
//                 const totalQuantity =
//                     parseFloat(
//                         quantityInput.getAttribute("data-total-quantity")
//                     ) || 0;

//                 if (!quantityInput || !rateInput) return; // Skip if elements are not found

//                 let quantity = parseFloat(quantityInput.value) || 0;

//                 if (quantity < 0) {
//                     updateStockMessage(row, "Invalid Quantity");
//                     quantity = 0;
//                     quantityInput.value = quantity;
//                     rateInput.value = "0.00";
//                 } else if (quantity > totalQuantity) {
//                     updateStockMessage(row, "Quantity exceeds stock");
//                     quantityInput.value = "";
//                     rateInput.value = "0.00";
//                 } else {
//                     updateStockMessage(row, "");
//                     const rate = price * quantity;
//                     rateInput.value = rate.toFixed(2);

//                     if (isChecked) {
//                         total += rate;
//                     }
//                 }
//             });

//         // Update total field
//         const totalRounded = total.toFixed(2);
//         document.getElementById("total").value = totalRounded;

//         const taxRate =
//             parseFloat(document.getElementById("medtax").value) || 0;
//         const taxAmount = ((total * taxRate) / 100).toFixed(2);
//         document.getElementById("grandTotal").value = (
//             parseFloat(totalRounded) + parseFloat(taxAmount)
//         ).toFixed(2);

//         // Update balance after recalculating total
//         updateBalance();
//     }

//     // Function to update balance
//     function updateBalance() {
//         const amountPaid =
//             parseFloat(document.getElementById("medamountPaid").value) || 0;
//         const total =
//             parseFloat(document.getElementById("grandTotal").value) || 0;

//         // Calculate balance without rounding
//         const balanceToGiveBack = (amountPaid - total).toFixed(2);
//         document.getElementById("medbalanceToGiveBack").value =
//             amountPaid >= total ? balanceToGiveBack : "0.00";
//     }

//     // Function to validate balance given
//     function validateBalanceGiven() {
//         const balanceGivenCheckbox =
//             document.getElementById("medbalance_given");
//         const balanceToGiveBackInput = document.getElementById(
//             "medbalanceToGiveBack"
//         );
//         const checkError = document.getElementById("prescCheckError");

//         if (
//             balanceGivenCheckbox.checked &&
//             parseFloat(balanceToGiveBackInput.value) <= 0
//         ) {
//             checkError.textContent = "Please enter a valid balance amount.";
//         } else {
//             checkError.textContent = "";
//         }
//     }

//     // Add event listeners to checkboxes and quantity inputs
//     document
//         .querySelectorAll('#tablebody input[type="checkbox"]')
//         .forEach(function (checkbox) {
//             checkbox.addEventListener("change", function () {
//                 updateRateAndTotal();
//                 validateBalanceGiven(); // Validate balance whenever a checkbox changes
//             });
//         });

//     document
//         .querySelectorAll('#tablebody input[name="quantity[]"]')
//         .forEach(function (input) {
//             input.addEventListener("input", function () {
//                 updateRateAndTotal();
//             });
//         });

//     // Add event listeners to balance-related inputs
//     const amountPaidInput = document.getElementById("medamountPaid");
//     const balanceGivenCheckbox = document.getElementById("medbalance_given");

//     amountPaidInput.addEventListener("input", function () {
//         updateBalance();
//         validateBalanceGiven();
//     });

//     balanceGivenCheckbox.addEventListener("change", function () {
//         updateBalance();
//         validateBalanceGiven();
//     });

//     // Initial calculations
//     updateRateAndTotal();

//     // const form = document.getElementById('prescriptionBillingForm');

//     // form.addEventListener('submit', function (event) {
//     //     alert('in');
//     //     event.preventDefault();

//     //     let isValid = true;
//     //     const modeOfPayment = document.querySelector('input[name="mode_of_payment"]:checked');
//     //     const amountPaid = parseFloat(document.getElementById('amountPaid').value);
//     //     const total = parseFloat(document.getElementById('total').value);
//     //     const grandTotal = parseFloat(document.getElementById('grandTotal').value);

//     //     // Clear previous error messages
//     //     document.getElementById('modeError').textContent = '';
//     //     document.getElementById('paidAmountError').textContent = '';
//     //     document.getElementById('checkError').textContent = '';

//     //     if (!modeOfPayment) {
//     //         document.getElementById('modeError').textContent = 'Payment mode is required.';
//     //         isValid = false;
//     //     }

//     //     if (isNaN(amountPaid) || amountPaid <= 0) {
//     //         document.getElementById('paidAmountError').textContent = 'Valid amount paid is required.';
//     //         isValid = false;
//     //     }

//     //     if (isNaN(total) || total <= 0) {
//     //         document.getElementById('total').classList.add('is-invalid');
//     //         isValid = false;
//     //     } else {
//     //         document.getElementById('total').classList.remove('is-invalid');
//     //     }

//     //     if (isNaN(grandTotal) || grandTotal <= 0) {
//     //         document.getElementById('grandTotal').classList.add('is-invalid');
//     //         isValid = false;
//     //     } else {
//     //         document.getElementById('grandTotal').classList.remove('is-invalid');
//     //     }

//     //     if (isValid) {
//     //         // If all checks pass, submit the form
//     //         $('#modeError').text('');
//     //         $('#paidAmountError').text('');
//     //         $('#checkError').text('');
//     //         alert('success');
//     //         form.submit();

//     //     } else {
//     //         alert('error');
//     //         return;
//     //     }
//     // });
// });

document.addEventListener("DOMContentLoaded", (event) => {
    const gpaycash = document.getElementById("medgpay");
    const cash = document.getElementById("medcash");
    const cardcash = document.getElementById("medcard");
    const machine = document.getElementById("medmachine");

    // Function to handle checkbox changes
    function handleCheckboxChange() {
        // Get the current checkbox states
        const gpayChecked = document.getElementById("medmode_of_payment_gpay").checked;
        const cashChecked = document.getElementById("medmode_of_payment_cash").checked;
        const cardChecked = document.getElementById("medmode_of_payment_card").checked;

        // Show/Hide input fields based on checkbox state
        document.getElementById("medgpay").style.display = gpayChecked ? "inline-block" : "none";
        document.getElementById("medcash").style.display = cashChecked ? "inline-block" : "none";
        document.getElementById("medcard").style.display = cardChecked ? "inline-block" : "none";
        document.getElementById("medmachine").style.display = cardChecked ? "inline-block" : "none"; // Example for machine select box
    }

    // Attach change event listeners to checkboxes
    document.getElementById("medmode_of_payment_gpay").addEventListener("change", function () {
        handleCheckboxChange();
        calculateTotalPaid();
    });
    document.getElementById("medmode_of_payment_cash").addEventListener("change", function () {
        handleCheckboxChange();
        calculateTotalPaid();
    });
    document.getElementById("medmode_of_payment_card").addEventListener("change", function () {
        handleCheckboxChange();
        calculateTotalPaid();
    });

    // Function to calculate total paid
    function calculateTotalPaid() {
        let totalPaid = 0;
        let isValid = true;

        const gpayInput = document.getElementById('medgpay');
        const cashInput = document.getElementById('medcash');
        const cardInput = document.getElementById('medcard');
        const errorElement = document.getElementById('prescModePaymentError');
        if (errorElement) {
            errorElement.textContent = '';
        }


        if (gpayInput && gpayInput.style.display !== 'none') {
            const gpayValue = parseFloat(gpayInput.value);
            if (isNaN(gpayValue)) {
                isValid = false;
                if (errorElement) {
                    errorElement.textContent += 'GPay amount should be a valid number. ';
                }
            } else {
                totalPaid += gpayValue;
            }
        }

        // Check and calculate Cash amount
        if (cashInput && cashInput.style.display !== 'none') {
            const cashValue = parseFloat(cashInput.value);
            if (isNaN(cashValue)) {
                isValid = false;
                if (errorElement) {
                    errorElement.textContent += 'Cash amount should be a valid number. ';
                }
            } else {
                totalPaid += cashValue;
            }
        }

        // Check and calculate Card amount
        if (cardInput && cardInput.style.display !== 'none') {
            const cardValue = parseFloat(cardInput.value);
            if (isNaN(cardValue)) {
                isValid = false;
                if (errorElement) {
                    errorElement.textContent += 'Card amount should be a valid number. ';
                }
            } else {
                totalPaid += cardValue;
            }
        }


        if (isValid) {
            document.getElementById('medamountPaid').value = totalPaid.toFixed(2);
            updateBalance();
        }
    }

    // Attach input event listeners to input fields for calculating total
    document.getElementById("medgpay").addEventListener("input", calculateTotalPaid);
    document.getElementById("medcash").addEventListener("input", calculateTotalPaid);
    document.getElementById("medcard").addEventListener("input", calculateTotalPaid);

    // Function to update rates and total
    function updateRateAndTotal() {
        let total = 0;

        document.querySelectorAll('#tablebody input[type="checkbox"]').forEach(function (checkbox) {
            const row = checkbox.closest("tr");
            const price = parseFloat(checkbox.getAttribute("data-price")) || 0;
            const quantityInput = row.querySelector('input[name="quantity[]"]');
            const rateInput = row.querySelector('input[name="rate[]"]');
            const isChecked = checkbox.checked;
            const totalQuantity = parseFloat(quantityInput.getAttribute("data-total-quantity")) || 0;

            if (!quantityInput || !rateInput) return; // Skip if elements are not found

            let quantity = parseFloat(quantityInput.value) || 0;

            if (quantity < 0) {
                updateStockMessage(row, "Invalid Quantity");
                quantity = 0;
                quantityInput.value = quantity;
                rateInput.value = "0.00";
            } else if (quantity > totalQuantity) {
                updateStockMessage(row, "Quantity exceeds stock");
                quantityInput.value = "";
                rateInput.value = "0.00";
            } else {
                updateStockMessage(row, "");
                const rate = price * quantity;
                rateInput.value = rate.toFixed(2);

                if (isChecked) {
                    total += rate;
                }
            }
        });

        // Update total field
        const totalRounded = total.toFixed(2);
        document.getElementById("total").value = totalRounded;

        const taxRate = parseFloat(document.getElementById("medtax").value) || 0;
        const taxAmount = ((total * taxRate) / 100).toFixed(2);
        document.getElementById("grandTotal").value = (
            parseFloat(totalRounded) + parseFloat(taxAmount)
        ).toFixed(2);

        // Update balance after recalculating total
        updateBalance();
    }

    // Function to update balance
    function updateBalance() {
        const amountPaid = parseFloat(document.getElementById("medamountPaid").value) || 0;
        const grandTotal = parseFloat(document.getElementById("grandTotal").value) || 0;

        // Calculate balance without rounding
        const balanceToGiveBack = (amountPaid - grandTotal).toFixed(2);
        document.getElementById("medbalanceToGiveBack").value =
            amountPaid >= grandTotal ? balanceToGiveBack : "0.00";
    }

    // Helper function to update the stock message
    function updateStockMessage(row, message) {
        const stockMessageElement = row.querySelector('span[id^="stock_message"]');
        if (stockMessageElement) {
            stockMessageElement.textContent = message;
        }
    }

    // Function to validate balance given
    function validateBalanceGiven() {
        const balanceGivenCheckbox = document.getElementById("medbalance_given");
        const balanceToGiveBackInput = document.getElementById("medbalanceToGiveBack");
        const checkError = document.getElementById("prescCheckError");

        if (
            balanceGivenCheckbox.checked &&
            parseFloat(balanceToGiveBackInput.value) <= 0
        ) {
            checkError.textContent = "Please enter a valid balance amount.";
        } else {
            checkError.textContent = "";
        }
    }

    // Add event listeners to checkboxes and quantity inputs
    document.querySelectorAll('#tablebody input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            updateRateAndTotal();
            validateBalanceGiven(); // Validate balance whenever a checkbox changes
        });
    });

    document.querySelectorAll('#tablebody input[name="quantity[]"]').forEach(function (input) {
        input.addEventListener("input", function () {
            updateRateAndTotal();
        });
    });

    // Add event listeners to balance-related inputs
    const amountPaidInput = document.getElementById("medamountPaid");
    const balanceGivenCheckbox = document.getElementById("medbalance_given");

    amountPaidInput.addEventListener("input", function () {
        updateBalance();
        validateBalanceGiven();
    });

    balanceGivenCheckbox.addEventListener("change", function () {
        updateBalance();
        validateBalanceGiven();
    });

    // Initial calculations
    handleCheckboxChange();
    calculateTotalPaid();
    updateRateAndTotal();
});

document.addEventListener("DOMContentLoaded", function () {
    // Add event listener to the submit button
    if (document.querySelector("#prescSubmitPayment")) {
        document
            .querySelector("#prescSubmitPayment")
            .addEventListener("click", function (event) {
                event.preventDefault(); // Prevent the default form submission

                $("#prescTotalError").text("");
                $("#prescGrandTotalError").text("");
                $("#prescAmountPaidError").text("");
                $("#prescModePaymentError").text("");
                $("#prescBalanceToGiveBackError").text("");
                $("#prescCheckError").text("");

                // Get the form and its inputs
                const form = document.getElementById("prescriptionBillingForm");
                // const modeOfPayment = form.querySelector(
                //     'input[name="medmode_of_payment"]:checked'
                // );
                const modeOfPaymentCheckboxes = form.querySelectorAll('input[name="medmode_of_payment[]"]:checked');
                const amountPaid = form.querySelector(
                    'input[name="medamountPaid"]'
                ).value;
                const balanceToGiveBack =
                    parseFloat(
                        form.querySelector('input[name="medbalanceToGiveBack"]')
                            .value
                    ) || 0;
                const balanceGiven = form.querySelector(
                    'input[name="medbalance_given"]'
                ).checked;
                const grandTotal = form.querySelector(
                    'input[name="grandTotal"]'
                ).value;
                const tax = form.querySelector('input[name="medtax"]').value;
                const total = form.querySelector('input[name="total"]').value;
                var isValid = 1;
                if (isNaN(total) || parseFloat(total) <= 0) {
                    $("#prescTotalError").text(
                        "Please select atleast a medicine."
                    );
                    isValid = 0;
                }

                if (isNaN(grandTotal) || parseFloat(grandTotal) <= 0) {
                    $("#prescGrandTotalError").text(
                        "Please select atleast a medicine."
                    );
                    isValid = 0;
                }

                if (isNaN(amountPaid) || parseFloat(amountPaid) < 0) {
                    $("#prescAmountPaidError").text(
                        "Please enter a valid amount paid."
                    );
                    isValid = 0;
                }
                if (parseFloat(amountPaid) < parseFloat(grandTotal)) {
                    $("#prescAmountPaidError").text(
                        "Amount paid is less than grand total"
                    );
                    isValid = 0;
                }
                // Check if mode of payment is selected
                // if (!modeOfPayment) {
                //     $("#prescModePaymentError").text(
                //         "Please select a mode of payment."
                //     );
                //     isValid = 0;
                // }
                let paymentModeError = false;
                let modeSelected = false;

                modeOfPaymentCheckboxes.forEach(checkbox => {
                    modeSelected = true;
                    const mode = checkbox.value;
                    const relatedInputField = form.querySelector(`input[name="med${mode}"]`);

                    if (mode === 'gpay' && relatedInputField && relatedInputField.value.trim() === '') {
                        paymentModeError = true;
                        $("#prescModePaymentError").text("Gpay amount is required.");
                    }
                    if (mode === 'cash' && relatedInputField && relatedInputField.value.trim() === '') {
                        paymentModeError = true;
                        $("#prescModePaymentError").text("Cash amount is required.");
                    }
                    if (mode === 'card' && relatedInputField && relatedInputField.value.trim() === '') {
                        paymentModeError = true;
                        $("#prescModePaymentError").text("Card amount is required.");
                    }
                    if (mode === 'card') {
                        const medmachine = form.querySelector('select[name="medmachine"]').value;
                        if (medmachine == '') {
                            paymentModeError = true;
                            $("#prescModePaymentError").text("Please select a machine.");
                        }

                    }
                });

                if (!modeSelected) {
                    $("#prescModePaymentError").text("Please select at least one mode of payment.");
                    isValid = 0;
                }



                if (paymentModeError) {
                    isValid = 0;
                }
                // Check if amount paid is not null or empty
                if (
                    !amountPaid ||
                    isNaN(amountPaid) ||
                    parseFloat(amountPaid) <= 0
                ) {
                    $("#prescAmountPaidError").text(
                        "Please enter a valid amount paid."
                    );
                    isValid = 0;
                }

                // Check if balance to give back is greater than zero and at least one checkbox is checked
                if (balanceToGiveBack > 0) {
                    if (!balanceGiven) {
                        $("#prescCheckError").text(
                            "If balance is to be given back, checkbox must be checked."
                        );
                        isValid = 0;
                    }
                }
                if (isValid) {
                    // If all checks pass, submit the form
                    $("#prescTotalError").text("");
                    $("#prescGrandTotalError").text("");
                    $("#prescAmountPaidError").text("");
                    $("#prescModePaymentError").text("");
                    $("#prescBalanceToGiveBackError").text("");
                    $("#prescCheckError").text("");
                    //form.submit();
                    $.ajax({
                        url: form.action,
                        method: form.method,
                        data: $(form).serialize(),
                        success: function(response) {

                            if (response.success) {
                                // Create a Blob from the base64-encoded PDF data
                                var blob = new Blob([new Uint8Array(atob(response.pdf)
                                    .split("").map(function(c) {
                                        return c.charCodeAt(0)
                                    }))], {
                                    type: "application/pdf"
                                });


                                var url = window.URL.createObjectURL(blob);

                                var link = document.createElement("a");
                                link.href = url;
                                link.download = "Medicine_bill_receipt.pdf";
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);

                                var printWindow = window.open(url, "_blank");
                                printWindow.onload = function() {
                                    printWindow.print();
                                };
                                window.location.href = prescriptionBillingRoute;

                                // window.location.href = prescriptionBillingRoute + "?success_message=" +
                                //     encodeURIComponent(
                                //         "Medicine bill payment successfully recorded.");
                            } else {
                                // Handle any other success messages
                                var message = "Medicine bill payment successfully recorded.";
                                window.location.href = prescriptionBillingRoute + "?success_message=" +
                                    encodeURIComponent(message);
                            }
                        },
                        error: function(xhr) {
                            // Handle the error
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    return;
                }
            });
    }

    
    document
        .getElementById("prescPrintPayment")
        .addEventListener("click", function () {
            var billId = document.getElementById("medbillId").value;
            var appointmentId = document.getElementById("appointmentId").value;

            $.ajax({
                url: prescriptionReceiptRoute,
                type: "POST",
                data: {
                    medbillId: billId,
                    appointmentId: appointmentId,
                    _token: $('meta[name="csrf-token"]').attr("content"), // Include CSRF token for security
                },
                xhrFields: {
                    responseType: "blob", // Important for handling binary data like PDFs
                },
                success: function (response) {
                    var blob = new Blob([response], {
                        type: "application/pdf",
                    });

                    // Create a URL for the Blob and download the PDF
                    var link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "prescription.pdf";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    // For printing, open the PDF in a new window or iframe
                    var printWindow = window.open(link.href, "_blank");
                    printWindow.onload = function () {
                        printWindow.print();
                    };
                    window.location.href = prescriptionBillingRoute;
                    // Redirect after printing
                    // printWindow.addEventListener("afterprint", function () {
                    //     window.location.href = prescriptionBillingRoute;
                    // });
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                },
            });
        });

});
