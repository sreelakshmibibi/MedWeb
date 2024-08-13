document.addEventListener('DOMContentLoaded', function () {
    // Helper function to update the stock message
    function updateStockMessage(row, message) {
        const stockMessageElement = row.querySelector('span[id^="stock_message"]');
        if (stockMessageElement) {
            stockMessageElement.textContent = message;
        }
    }

    // Function to update rates and total
    function updateRateAndTotal() {
        let total = 0;

        document.querySelectorAll('#tablebody input[type="checkbox"]').forEach(function (checkbox) {
            const row = checkbox.closest('tr');
            const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
            const quantityInput = row.querySelector('input[name="quantity[]"]');
            const rateInput = row.querySelector('input[name="rate[]"]');
            const isChecked = checkbox.checked;
            const totalQuantity = parseFloat(quantityInput.getAttribute('data-total-quantity')) || 0;

            if (!quantityInput || !rateInput) return; // Skip if elements are not found

            let quantity = parseFloat(quantityInput.value) || 0;

            if (quantity < 0) {
                updateStockMessage(row, 'Invalid Quantity');
                quantity = 0;
                quantityInput.value = quantity;
                rateInput.value = '0.00';
            } else if (quantity > totalQuantity) {
                updateStockMessage(row, 'Quantity exceeds stock');
                quantityInput.value = '';
                rateInput.value = '0.00';
            } else {
                updateStockMessage(row, '');
                const rate = price * quantity;
                rateInput.value = rate.toFixed(2);

                if (isChecked) {
                    total += rate;
                }
            }
        });

        // Update total field
        const totalRounded = total.toFixed(2);
        document.getElementById('total').value = totalRounded;

        const taxRate = parseFloat(document.getElementById('tax').value) || 0;
        const taxAmount = (total * taxRate / 100).toFixed(2);
        document.getElementById('grandTotal').value = (parseFloat(totalRounded) + parseFloat(taxAmount)).toFixed(2);

        // Update balance after recalculating total
        updateBalance();
    }

    // Function to update balance
    function updateBalance() {

        const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
        const total = parseFloat(document.getElementById('grandTotal').value) || 0;

        // Calculate balance without rounding
        const balanceToGiveBack = (amountPaid - total).toFixed(2);
        document.getElementById('balanceToGiveBack').value = (amountPaid >= total) ? balanceToGiveBack : '0.00';
    }

    // Function to validate balance given
    function validateBalanceGiven() {
        const balanceGivenCheckbox = document.getElementById('balance_given');
        const balanceToGiveBackInput = document.getElementById('balanceToGiveBack');
        const checkError = document.getElementById('prescCheckError');

        if (balanceGivenCheckbox.checked && parseFloat(balanceToGiveBackInput.value) <= 0) {
            checkError.textContent = 'Please enter a valid balance amount.';
        } else {
            checkError.textContent = '';
        }
    }

    // Add event listeners to checkboxes and quantity inputs
    document.querySelectorAll('#tablebody input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            updateRateAndTotal();
            validateBalanceGiven(); // Validate balance whenever a checkbox changes
        });
    });

    document.querySelectorAll('#tablebody input[name="quantity[]"]').forEach(function (input) {
        input.addEventListener('input', function () {
            updateRateAndTotal();
        });
    });

    // Add event listeners to balance-related inputs
    const amountPaidInput = document.getElementById('amountPaid');
    const balanceGivenCheckbox = document.getElementById('balance_given');

    amountPaidInput.addEventListener('input', function () {
        updateBalance();
        validateBalanceGiven();
    });

    balanceGivenCheckbox.addEventListener('change', function () {
        updateBalance();
        validateBalanceGiven();
    });

    // Initial calculations
    updateRateAndTotal();

    // const form = document.getElementById('prescriptionBillingForm');

    // form.addEventListener('submit', function (event) {
    //     alert('in');
    //     event.preventDefault();

    //     let isValid = true;
    //     const modeOfPayment = document.querySelector('input[name="mode_of_payment"]:checked');
    //     const amountPaid = parseFloat(document.getElementById('amountPaid').value);
    //     const total = parseFloat(document.getElementById('total').value);
    //     const grandTotal = parseFloat(document.getElementById('grandTotal').value);

    //     // Clear previous error messages
    //     document.getElementById('modeError').textContent = '';
    //     document.getElementById('paidAmountError').textContent = '';
    //     document.getElementById('checkError').textContent = '';

    //     if (!modeOfPayment) {
    //         document.getElementById('modeError').textContent = 'Payment mode is required.';
    //         isValid = false;
    //     }

    //     if (isNaN(amountPaid) || amountPaid <= 0) {
    //         document.getElementById('paidAmountError').textContent = 'Valid amount paid is required.';
    //         isValid = false;
    //     }

    //     if (isNaN(total) || total <= 0) {
    //         document.getElementById('total').classList.add('is-invalid');
    //         isValid = false;
    //     } else {
    //         document.getElementById('total').classList.remove('is-invalid');
    //     }

    //     if (isNaN(grandTotal) || grandTotal <= 0) {
    //         document.getElementById('grandTotal').classList.add('is-invalid');
    //         isValid = false;
    //     } else {
    //         document.getElementById('grandTotal').classList.remove('is-invalid');
    //     }

    //     if (isValid) {
    //         // If all checks pass, submit the form
    //         $('#modeError').text('');
    //         $('#paidAmountError').text('');
    //         $('#checkError').text('');
    //         alert('success');
    //         form.submit();

    //     } else {
    //         alert('error');
    //         return;
    //     }
    // });
});


document.addEventListener('DOMContentLoaded', function () {
    // Add event listener to the submit button
    if (document.querySelector('#prescSubmitPayment')) {
        document.querySelector('#prescSubmitPayment').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default form submission

            $('#prescTotalError').text('');
            $('#prescGrandTotalError').text('');
            $('#prescAmountPaidError').text('');
            $('#prescAmountPaid').text('');
            $('#prescModePaymentError').text('');
            $('#prescBalanceToGiveBackError').text('');
            $('#prescCheckError').text('');

            // Get the form and its inputs
            const form = document.getElementById('prescriptionBillingForm');
            const modeOfPayment = form.querySelector('input[name="mode_of_payment"]:checked');
            const amountPaid = form.querySelector('input[name="amountPaid"]').value;
            const balanceToGiveBack = parseFloat(form.querySelector('input[name="balanceToGiveBack"]').value) || 0;
            const balanceGiven = form.querySelector('input[name="balance_given"]').checked;
            const grandTotal = form.querySelector('input[name="grandTotal"]').value;
            const tax = form.querySelector('input[name="tax"]').value;
            const total = form.querySelector('input[name="total"]').value;
            var isValid = 1;
            if (isNaN(total) || parseFloat(total) <= 0) {
                $('#prescTotalError').text('Please select atleast a medicine.');
                isValid = 0;
            }

            if (isNaN(grandTotal) || parseFloat(grandTotal) <= 0) {
                $('#prescGrandTotalError').text('Please select atleast a medicine.');
                isValid = 0;
            }

            if (isNaN(amountPaid) || parseFloat(amountPaid) < 0) {
                $('#prescAmountPaidError').text('Please enter a valid amount paid.');
                isValid = 0;
            }
            if (parseFloat(amountPaid) < parseFloat(grandTotal)) {
                $('#prescAmountPaidError').text('Amount paid is less than grand total');
                isValid = 0;
            }
            // Check if mode of payment is selected
            if (!modeOfPayment) {
                $('#prescModePaymentError').text('Please select a mode of payment.');
                isValid = 0;
            }

            // Check if amount paid is not null or empty
            if (!amountPaid || isNaN(amountPaid) || parseFloat(amountPaid) <= 0) {
                $('#prescAmountPaidError').text('Please enter a valid amount paid.');
                isValid = 0;
            }

            // Check if balance to give back is greater than zero and at least one checkbox is checked
            if (balanceToGiveBack > 0) {
                if (!balanceGiven) {
                    $('#prescCheckError').text('If balance is to be given back, checkbox must be checked.');
                    isValid = 0;
                }
            }
            if (isValid) {
                // If all checks pass, submit the form
                $('#prescTotalError').text('');
                $('#prescGrandTotalError').text('');
                $('#prescAmountPaidError').text('');
                $('#prescAmountPaid').text('');
                $('#prescModePaymentError').text('');
                $('#prescBalanceToGiveBackError').text('');
                $('#prescCheckError').text('');

                form.submit();

            } else {
                return;
            }

        });
    }
    document.getElementById('prescPrintPayment').addEventListener('click', function () {

        var billId = document.getElementById('billId').value;
        var appointmentId = document.getElementById('appointmentId').value;


        // AJAX request to generate the PDF
        // fetch(receiptRoute, {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //     },
        //     body: JSON.stringify({ billId: billId, appointmentId: appointmentId })
        // })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.pdfUrl) {
        //             // Open the PDF in a new window and trigger print dialog
        //             var printWindow = window.open(data.pdfUrl, '_blank');
        //             printWindow.addEventListener('load', function () {
        //                 printWindow.print();
        //             });

        //             // Redirect after printing
        //             printWindow.addEventListener('afterprint', function () {
        //                 window.location.href = "{{ route('billing') }}";
        //             });
        //         } else {
        //             alert('Failed to generate PDF.');
        //         }

        //     })
        //     .catch(error => {
        //         console.error('Error:', error);
        //     });

        $.ajax({
            url: receiptRoute,
            type: 'POST',
            data: {
                billId: billId,
                appointmentId: appointmentId,
                _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token for security
            },
            xhrFields: {
                responseType: 'blob' // Important for handling binary data like PDFs
            },
            success: function (response) {
                var blob = new Blob([response], {
                    type: 'application/pdf'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'prescription.pdf';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // For printing, open the PDF in a new window or iframe and call print
                var printWindow = window.open(link.href, '_blank');
                printWindow.onload = function () {
                    printWindow.print();
                };
                printWindow.addEventListener('afterprint', function () {
                    //window.location.href = "{{ route('billing') }}";
                    window.location.href = billingRoute;
                });
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

});
