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
});
document.addEventListener('DOMContentLoaded', function() {
    function updateBalance() {
        // Get the total amount from the hidden input
        let totalAmountElement = document.getElementById('totalToPay');
        let totalAmount = parseFloat(totalAmountElement.value) || 0;

        // Get the amount paid
        let amountPaidElement = document.getElementById('amountPaid');
        let amountPaid = parseFloat(amountPaidElement.value.replace(/,/g, '')) || 0;

        // Calculate the balance
        let balance = totalAmount - amountPaid;

        // Format the balance and amount paid
        amountPaidElement.value = formatNumber(amountPaid);
        document.getElementById('balance').value = formatNumber(balance);
    }

    // Format number with commas and 2 decimal places
    function formatNumber(num) {
        return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Add event listener to the amountPaid input field
    document.getElementById('amountPaid').addEventListener('input', updateBalance);

    // Initialize the balance on page load
    updateBalance();
});

