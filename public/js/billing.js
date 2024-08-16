document.addEventListener("DOMContentLoaded", (event) => {
    const gpaycash = document.getElementById("gpaycash");
    const cash = document.getElementById("cash");
    const cardcash = document.getElementById("cardcash");
    const machine = document.getElementById("machine");

    // const insuranceBtn = document.getElementById("insuranceBtn");
    // const medicineBtn = document.getElementById("medicineBtn");

    gpaycash.style.display = "none";
    cash.style.display = "none";
    cardcash.style.display = "none";
    machine.style.display = "none";
    // Function to handle checkbox changes
    function handleCheckboxChange() {
        // Get the current checkbox states
        const gpayChecked = document.getElementById(
            "mode_of_payment_gpay"
        ).checked;
        const cashChecked = document.getElementById(
            "mode_of_payment_cash"
        ).checked;
        const cardChecked = document.getElementById(
            "mode_of_payment_card"
        ).checked;

        // Show/Hide input fields based on checkbox state
        document.getElementById("gpaycash").style.display = gpayChecked
            ? "inline"
            : "none";
        document.getElementById("cash").style.display = cashChecked
            ? "inline"
            : "none";
        document.getElementById("cardcash").style.display = cardChecked
            ? "inline"
            : "none";
        document.getElementById("machine").style.display = cardChecked
            ? "inline"
            : "none"; // Example for machine select box
    }

    // Attach change event listeners to checkboxes
    document
        .getElementById("mode_of_payment_gpay")
        .addEventListener("change", handleCheckboxChange);
    document
        .getElementById("mode_of_payment_cash")
        .addEventListener("change", handleCheckboxChange);
    document
        .getElementById("mode_of_payment_card")
        .addEventListener("change", handleCheckboxChange);

    // // Initial call to set the correct state on page load
    handleCheckboxChange();

    // combo_checkbox.addEventListener("change", () => {
    //     if (combo_checkbox.checked) {
    //         $("#modal-combo").modal("show");
    //     } else {
    //         $("#modal-combo").modal("hide");
    //         $("#combotr").hide();
    //     }
    // });

    // insurance_checkbox.addEventListener("change", () => {
    //     if (insurance_checkbox.checked) {
    //         $("#modal-insurance").modal("show");
    //     } else {
    //         $("#modal-insurance").modal("hide");
    //         $("#insurancetr").hide();
    //     }
    // });

    // medicine_checkbox.addEventListener("change", () => {
    //     if (medicine_checkbox.checked) {
    //         $("#modal-medicine").modal("show");
    //     } else {
    //         $("#modal-medicine").modal("hide");
    //         $("#medicinetr").hide();
    //     }
    // });

    // comboBtn.addEventListener("click", () => {
    //     $("#combotr").show();
    // });
    // insuranceBtn.addEventListener("click", () => {
    //     $("#insurancetr").show();
    // });
    // medicineBtn.addEventListener("click", () => {
    //     $("#medicinetr").show();
    // });
});

document.addEventListener("DOMContentLoaded", function () {
    const totalToPayElement = document.getElementById("totalToPay");
    const insurancePaidElement = document.getElementById("insurance_paid");
    const amountToBePaidElement = document.getElementById(
        "amount_to_be_paid_insurance"
    );

    function updateAmounts() {
        // Check if elements exist
        if (!totalToPayElement || !amountToBePaidElement) return;

        // Get values from elements
        const totalToPay = parseFloat(totalToPayElement.value) || 0;
        const insurancePaid = insurancePaidElement
            ? parseFloat(insurancePaidElement.value) || 0
            : 0;

        // Calculate balance amount
        const balanceAmount = totalToPay - insurancePaid;

        // Update the DOM elements
        amountToBePaidElement.value = balanceAmount.toFixed(3);

        // Optional: Update hidden totalToPay value
        totalToPayElement.value = totalToPay.toFixed(3);
    }

    // Initial update
    updateAmounts();

    // Check if the insurancePaidElement exists before adding the event listener
    if (insurancePaidElement) {
        insurancePaidElement.addEventListener("input", updateAmounts);
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const balanceInput = document.getElementById("balance");
    const balanceToGiveBackInput = document.getElementById("balanceToGiveBack");
    const considerForNextPaymentCheckbox = document.getElementById(
        "consider_for_next_payment"
    );
    const balanceGivenCheckbox = document.getElementById("balance_given");

    // Function to check if input fields have values
    function checkValues() {
        const balance = parseFloat(balanceInput.value) || 0;
        const balanceToGiveBack = parseFloat(balanceToGiveBackInput.value) || 0;

        // Enable checkboxes based on values
        considerForNextPaymentCheckbox.disabled = balance != 0;
        balanceGivenCheckbox.disabled = balanceToGiveBack != 0;
    }

    // Function to handle mutually exclusive selection
    function handleCheckboxChange() {
        if (
            considerForNextPaymentCheckbox.checked &&
            balanceGivenCheckbox.checked
        ) {
            // Uncheck balanceGivenCheckbox if considerForNextPaymentCheckbox is checked
            balanceGivenCheckbox.checked = false;
        } else if (
            balanceGivenCheckbox.checked &&
            considerForNextPaymentCheckbox.checked
        ) {
            // Uncheck considerForNextPaymentCheckbox if balanceGivenCheckbox is checked
            considerForNextPaymentCheckbox.checked = false;
        }
    }

    // Initialize checkbox states
    checkValues();

    // Add event listeners to update states when input values change
    balanceInput.addEventListener("input", checkValues);
    balanceToGiveBackInput.addEventListener("input", checkValues);

    // Add event listeners to handle mutually exclusive checkbox selection
    considerForNextPaymentCheckbox.addEventListener(
        "change",
        handleCheckboxChange
    );
    balanceGivenCheckbox.addEventListener("change", handleCheckboxChange);
});

document.addEventListener("DOMContentLoaded", function () {
    // Add event listener to the submit button
    if (document.querySelector("#submitPayment")) {
        document
            .querySelector("#submitPayment")
            .addEventListener("click", function (event) {
                event.preventDefault(); // Prevent the default form submission

                // Get the form and its inputs
                const form = document.getElementById("billingForm");
                const modeOfPayment = form.querySelector(
                    'input[name="mode_of_payment[]"]:checked'
                );
                const amountPaid = form.querySelector(
                    'input[name="amountPaid"]'
                ).value;
                const balanceToGiveBack =
                    parseFloat(
                        form.querySelector('input[name="balanceToGiveBack"]')
                            .value
                    ) || 0;
                const considerForNextPayment = form.querySelector(
                    'input[name="consider_for_next_payment"]'
                ).checked;
                const balanceGiven = form.querySelector(
                    'input[name="balance_given"]'
                ).checked;
                var isValid = 1;
                // Check if mode of payment is selected
                if (!modeOfPayment) {
                    $("#modeError").text("Please select a mode of payment.");
                    isValid = 0;
                }
                
                const cardCashInput = form.querySelector('input[name="cardcash"]');
                const machineSelect = form.querySelector('select[name="machine"]');
                const cardChecked = form.querySelector('input[id="mode_of_payment_card"]');
            
                const cardCash = parseFloat(cardCashInput.value) || 0;
                const machine = machineSelect.value > 0 ? machineSelect.value : 0;
                const cardCheckedValue = cardChecked.checked;
        
                if (cardCheckedValue  && cardCash > 0 && machine == 0) {
                    $("#modeError").text('Please select a machine when cash is entered.');
                    isValid = 0;
                } 

                // Check if amount paid is not null or empty
                if (
                    !amountPaid ||
                    isNaN(amountPaid) ||
                    parseFloat(amountPaid) <= 0
                ) {
                    $("#paidAmountError").text(
                        "Please enter a valid amount paid."
                    );
                    isValid = 0;
                }

                // Check if balance to give back is greater than zero and at least one checkbox is checked
                if (balanceToGiveBack > 0) {
                    if (!considerForNextPayment && !balanceGiven) {
                        $("#checkError").text(
                            "If balance is to be given back, at least one checkbox (Consider for Next Payment or Balance Given) must be checked."
                        );
                        isValid = 0;
                    }
                }
                if (isValid) {
                    // If all checks pass, submit the form
                    $("#modeError").text("");
                    $("#paidAmountError").text("");
                    $("#checkError").text("");
                    //form.submit();
                    // Submit the form via AJAX
                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.pdfUrl) {
                                // Open the PDF in a new window and trigger print dialog
                                var printWindow = window.open(
                                    data.pdfUrl,
                                    "_blank"
                                );
                                printWindow.addEventListener(
                                    "load",
                                    function () {
                                        printWindow.print();
                                    }
                                );
                                window.location.href = billingRoute;
                                // Redirect after printing
                                printWindow.addEventListener(
                                    "afterprint",
                                    function () {
                                        // window.location.href = "{{ route('billing') }}";
                                        window.location.href = billingRoute;
                                    }
                                );
                            } else {
                                alert("Failed to generate PDF.");
                            }
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                        });
                } else {
                    return;
                }
            });
    }
    document
        .getElementById("printPayment")
        .addEventListener("click", function () {
            var billId = document.getElementById("billId").value;
            var appointmentId = document.getElementById("appointmentId").value;

            // AJAX request to generate the PDF
            fetch(receiptRoute, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    billId: billId,
                    appointmentId: appointmentId,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.pdfUrl) {
                        // Open the PDF in a new window and trigger print dialog
                        var printWindow = window.open(data.pdfUrl, "_blank");
                        printWindow.addEventListener("load", function () {
                            printWindow.print();
                        });

                        // Redirect after printing
                        printWindow.addEventListener("afterprint", function () {
                            window.location.href = "{{ route('billing') }}";
                        });
                    } else {
                        alert("Failed to generate PDF.");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        });
    document
        .getElementById("printPayment1")
        .addEventListener("click", function () {
            var billId = document.getElementById("billId").value;
            var appointmentId = document.getElementById("appointmentId").value;

            // AJAX request to generate the PDF
            fetch(receiptRoute, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    billId: billId,
                    appointmentId: appointmentId,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.pdfUrl) {
                        // Open the PDF in a new window and trigger print dialog
                        var printWindow = window.open(data.pdfUrl, "_blank");
                        printWindow.addEventListener("load", function () {
                            printWindow.print();
                        });

                        // Redirect after printing
                        printWindow.addEventListener("afterprint", function () {
                            window.location.href = "{{ route('billing') }}";
                        });
                    } else {
                        alert("Failed to generate PDF.");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        });
});

document.addEventListener("DOMContentLoaded", function() {
    // Function to calculate the total amount paid
    function calculateAmountPaid() {
        // Get values from input fields and parse them as floats
        let gpaycash = parseFloat(document.getElementById('gpaycash').value);
        let cardcash = parseFloat(document.getElementById('cardcash').value);
        let cash = parseFloat(document.getElementById('cash').value);
        
        if (isNaN(gpaycash) || gpaycash < 0) {
            $("#modeError").text('GPay amount should be a valid positive number. ');
        } else if (isNaN(cardcash) || cardcash < 0) {
            $("#modeError").text('Card amount should be a valid positive number. ');
        } else if (isNaN(cash) || cash < 0) {
            $("#modeError").text('Cash amount should be a valid positive number. ');
        } else {
            $("#modeError").text('');
        }
        // Calculate the total
        const totalAmountPaid = gpaycash + cardcash + cash;
        if (isNaN(totalAmountPaid)) {
            $("#paidAmountError").text('Paid amount should be a valid number. ');
        } else {
            $("#paidAmountError").text('');
        }
        // Update the amountPaid field
        document.getElementById('amountPaid').value = totalAmountPaid.toFixed(3);
        
        // Calculate and update balance and balanceToGiveBack
        updateBalances();
    }

    // Function to update balance and balanceToGiveBack
    function updateBalances() {
        const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
        const totalToPay = parseFloat(document.getElementById('totalToPay').value) || 0;

        const balanceDue = totalToPay - amountPaid;
        const balanceToGiveBack = amountPaid > totalToPay ? amountPaid - totalToPay : 0;

        document.getElementById('balance').value = balanceDue.toFixed(2);
        document.getElementById('balanceToGiveBack').value = balanceToGiveBack.toFixed(2);
    }

    // Function to handle checkbox changes
    function handleCheckboxChange() {
        const gpayChecked = document.getElementById("mode_of_payment_gpay").checked;
        const cashChecked = document.getElementById("mode_of_payment_cash").checked;
        const cardChecked = document.getElementById("mode_of_payment_card").checked;

        document.getElementById('gpaycash').style.display = gpayChecked ? "inline" : "none";
        document.getElementById('cash').style.display = cashChecked ? "inline" : "none";
        document.getElementById('cardcash').style.display = cardChecked ? "inline" : "none";
        document.getElementById('machine').style.display = cardChecked ? "inline" : "none"; // Example for machine select box

        // Reset values and recalculate
        if (!gpayChecked) document.getElementById('gpaycash').value = 0;
        if (!cashChecked) document.getElementById('cash').value = 0;
        if (!cardChecked) document.getElementById('cardcash').value = 0;
        
        calculateAmountPaid();
    }

    // Add event listeners to the input fields
    document.getElementById('gpaycash').addEventListener('input', calculateAmountPaid);
    document.getElementById('cardcash').addEventListener('input', calculateAmountPaid);
    document.getElementById('cash').addEventListener('input', calculateAmountPaid);

    // Add event listeners to the checkboxes to update the amounts when they are checked/unchecked
    document.querySelectorAll('input[name="mode_of_payment[]"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', handleCheckboxChange);
    });
    
    // Initial call to set the correct state on page load
    handleCheckboxChange();
});



