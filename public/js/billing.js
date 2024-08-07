document.addEventListener("DOMContentLoaded", (event) => {
    const combo_checkbox = document.getElementById("combo_checkbox");
    const insurance_checkbox = document.getElementById("insurance_checkbox");
    const medicine_checkbox = document.getElementById("medicine_checkbox");

    const comboBtn = document.getElementById("comboBtn");
    const insuranceBtn = document.getElementById("insuranceBtn");
    const medicineBtn = document.getElementById("medicineBtn");

    combo_checkbox.addEventListener("change", () => {
        if (combo_checkbox.checked) {
            $("#modal-combo").modal("show");
        } else {
            $("#modal-combo").modal("hide");
            $("#combotr").hide();
        }
    });

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
});document.addEventListener('DOMContentLoaded', function() {
    function updateBalance() {
        // Get the total amount from the hidden input
        const totalAmountElement = document.getElementById('totalToPay');
        const amountPaidElement = document.getElementById('amountPaid');
        const balanceElement = document.getElementById('balance');

        if (!totalAmountElement || !amountPaidElement || !balanceElement) {
            return;
        }

        const totalAmount = parseFloat(totalAmountElement.value) || 0;
        const amountPaid = parseFloat(amountPaidElement.value.replace(/,/g, '')) || 0;

        // Calculate the balance
        const balance = totalAmount - amountPaid;

        // Format the balance and amount paid
        amountPaidElement.value = formatNumber(amountPaid);
        balanceElement.value = formatNumber(balance);
    }

    // Format number with commas and 2 decimal places
    function formatNumber(num) {
        return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Check if the amountPaid element exists before adding the event listener
    const amountPaidElement = document.getElementById('amountPaid');
    if (amountPaidElement) {
        amountPaidElement.addEventListener('input', updateBalance);
    } 

    // Initialize the balance on page load
    updateBalance();
});

document.addEventListener('DOMContentLoaded', function() {
    const totalToPayElement = document.getElementById('totalToPay');
    const insurancePaidElement = document.getElementById('insurance_paid');
    const amountToBePaidElement = document.getElementById('amount_to_be_paid');

    function updateAmounts() {
        // Check if elements exist
        if (!totalToPayElement || !amountToBePaidElement) return;

        // Get values from elements
        const totalToPay = parseFloat(totalToPayElement.value) || 0;
        const insurancePaid = insurancePaidElement ? parseFloat(insurancePaidElement.value) || 0 : 0;
        
        // Calculate balance amount
        const balanceAmount = totalToPay - insurancePaid;

        // Update the DOM elements
        amountToBePaidElement.value = balanceAmount.toFixed(2);

        // Optional: Update hidden totalToPay value
        totalToPayElement.value = totalToPay.toFixed(2);
    }

    // Initial update
    updateAmounts();

    // Check if the insurancePaidElement exists before adding the event listener
    if (insurancePaidElement) {
        insurancePaidElement.addEventListener('input', updateAmounts);
    }
});


