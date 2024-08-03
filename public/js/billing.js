document.addEventListener("DOMContentLoaded", (event) => {
    const combo_checkbox = document.getElementById("combo_checkbox");
    const insurance_checkbox = document.getElementById("insurance_checkbox");
    const medicine_checkbox = document.getElementById("medicine_checkbox");

    combo_checkbox.addEventListener("change", () => {
        if (combo_checkbox.checked) {
            $("#modal-combo").modal("show");
        } else {
            $("#modal-combo").modal("hide");
        }
    });

    insurance_checkbox.addEventListener("change", () => {
        if (insurance_checkbox.checked) {
            $("#modal-insurance").modal("show");
        } else {
            $("#modal-insurance").modal("hide");
        }
    });

    medicine_checkbox.addEventListener("change", () => {
        if (medicine_checkbox.checked) {
            $("#modal-medicine").modal("show");
        } else {
            $("#modal-medicine").modal("hide");
        }
    });
});
