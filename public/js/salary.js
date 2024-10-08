$(document).ready(function () {
    // Clear previous error messages
    $(".invalid-feedback").text("").hide();

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
        const totalDeductions = parseFloat($("#deductionstotal").val()) || 0;
        const totalAdditions = parseFloat($("#additionstotal").val()) || 0;

        const netsalary = salary - totalDeductions;
        const ctc = salary + totalAdditions;

        // update salary
        $("#salary").val(salary.toFixed(2));
        $("#netsalary").val(netsalary.toFixed(2));
        $("#ctc").val(ctc.toFixed(2));
    }

    // Attach event listener to the earnings amount inputs
    $("input[name='earningsamount[]']").on(
        "input change",
        calculateTotalEarnings
    );
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

    // document
    //     .getElementById("casual_leaves")
    //     .addEventListener("input", function () {
    //         const casualLeavesError =
    //             document.getElementById("casual_leavesError");
    //         casualLeavesError.innerHTML = "";
    //         casualLeavesError.style.display = "none"; // Hide error message
    //         this.classList.remove("is-invalid");
    //     });

    // document
    //     .getElementById("sick_leaves")
    //     .addEventListener("input", function () {
    //         const sickLeavesError = document.getElementById("sick_leavesError");
    //         sickLeavesError.innerHTML = "";
    //         sickLeavesError.style.display = "none"; // Hide error message
    //         this.classList.remove("is-invalid");
    //     });

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
        .getElementById("saveSalaryButton")
        .addEventListener("click", function (event) {
            event.preventDefault();
            let isValid = true;

            // Clear previous error messages
            document
                .querySelectorAll(".invalid-feedback")
                .forEach(function (error) {
                    error.innerHTML = "";
                });

            // Validate Employee Type
            const empType = document.getElementById("emp_type");
            if (empType.value === "") {
                isValid = false;
                empType.classList.add("is-invalid");
                document.getElementById("emp_typeError").innerHTML =
                    "Employee Type is required.";
                $("#emp_typeError").show();
            } else {
                empType.classList.remove("is-invalid");
            }

            // Validate Casual Leaves
            // const casualLeaves = document.getElementById("casual_leaves");
            // if (casualLeaves.value === "") {
            //     isValid = false;
            //     casualLeaves.classList.add("is-invalid");
            //     document.getElementById("casual_leavesError").innerHTML =
            //         "Total Casual Leaves are required.";
            //     $("#casual_leavesError").show();
            // } else {
            //     casualLeaves.classList.remove("is-invalid");
            // }

            // Validate Sick Leaves
            // const sickLeaves = document.getElementById("sick_leaves");
            // if (sickLeaves.value === "") {
            //     isValid = false;
            //     sickLeaves.classList.add("is-invalid");
            //     document.getElementById("sick_leavesError").innerHTML =
            //         "Total Sick Leaves are required.";
            //     $("#sick_leavesError").show();
            // } else {
            //     sickLeaves.classList.remove("is-invalid");
            // }

            // Validate each section
            const earningsValid = validateSection("earningsSection");
            const additionsValid = validateSection("additionsSection");
            const deductionsValid = validateSection("deductionsSection");

            // Combine the section validations
            isValid = earningsValid && additionsValid && deductionsValid;

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
                            $("#saveSalaryButton").hide();
                        } else {
                            $("#saveSalaryButton").show();
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
});
