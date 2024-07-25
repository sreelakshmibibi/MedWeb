var form = $("#treatmentform").show();

let historyStepAdded = false;
let prescriptionStepAdded = false;
let chargeStepAdded = false;

function getSessionData() {
    return $.ajax({
        url: "/session-data", // URL of the PHP script
        type: "GET",
        dataType: "json",
    });
}

function getDentalTable(stepIndex) {
    if (
        (stepIndex == 2 && historyStepAdded == false) ||
        (stepIndex == 3 && historyStepAdded == true)
    ) {
        getSessionData()
            .done(function (data) {
                var appId = data.appId;
                var patientId = data.patientId;
                //alert('appId: ' + appId + ', patientId: ' + patientId);

                $.ajax({
                    url: treatmentShowRoute.replace(":appId", appId),
                    type: "GET",
                    data: {
                        patient_id: patientId,
                        app_id: appId,
                    },
                    dataType: "json",
                    success: function (response) {
                        var tableBody = $("#dentalTable tbody");
                        tableBody.empty(); // Clear any existing rows

                        var toothExaminations = response.toothExaminations;

                        if (toothExaminations && toothExaminations.length > 0) {
                            toothExaminations.forEach(function (exam, index) {
                                var viewDocumentsButton = "";
                                teethName = exam.tooth_id ? exam.teeth.teeth_name : exam.row_id;
                                teethNameDisplay = exam.tooth_id ? exam.teeth.teeth_name : 'Row ' +exam.row_id;
                                console.log(
                                    appId,
                                    teethName,
                                    patientId
                                );
                                // Check if there are x-ray images
                                if (
                                    exam.x_ray_images &&
                                    exam.x_ray_images.length > 0
                                ) {
                                    console.log(
                                        appId,
                                        teethName,
                                        patientId
                                    );
                                    viewDocumentsButton = `
                <button type="button" id="xraybtn" class="waves-effect waves-light btn btn-circle btn-info btn-xs"
                    data-bs-toggle="modal" data-bs-target="#modal-documents" data-id="${exam.id}" data-appointment-id="${appId}"
                    data-teeth-name="${teethName}" data-patient-id="${patientId}" title="View documents">
                    <i class="fa-solid fa-file-archive"></i>
                </button>
            `;
                                }

                                var row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${teethNameDisplay}</td>
                                    <td>${exam.chief_complaint}</td>
                                    <td>${exam.disease ? exam.disease.name : ""
                                    }</td>
                                    <td>${exam.hpi}</td>
                                    <td>${exam.dental_examination}</td>
                                    <td>${exam.diagnosis}</td>
                                    <td>${viewDocumentsButton}</td>
                                    <td>${exam.treatment.treat_name}</td>
                                    <td><button type='button' class='waves-effect waves-light btn btn-circle btn-success btn-treat-view btn-xs me-1' title='View' data-bs-toggle='modal' data-id='${teethName
                                    }' data-bs-target='#modal-teeth'><i class='fa fa-eye'></i></button>
                                    <button type='button' class='waves-effect waves-light btn btn-circle btn-warning btn-treat-edit btn-xs me-1' title='Edit' data-bs-toggle='modal' data-id='${teethName
                                    }' data-bs-target='#modal-teeth'><i class='fa fa-pencil'></i></button>
                                    <button type='button' class='waves-effect waves-light btn btn-circle btn-danger btn-treat-delete btn-xs me-1' title='Delete' data-bs-toggle='modal' data-id='${exam.id
                                    }' data-bs-target='#modal-delete'><i class='fa-solid fa-trash'></i></button>
                                    
                                    </td>
                                </tr>
                            `;
                                tableBody.append(row);
                            });
                        } else {
                            var noDataRow = `
                            <tr>
                                <td colspan="10">No data available</td>
                            </tr>
                        `;
                            tableBody.append(noDataRow);
                        }
                    },
                    error: function (xhr) {
                        console.error("Error fetching dental table data:", xhr);
                    },
                });
            })
            .fail(function (xhr) {
                console.error("Error fetching session data:", xhr);
            });
    }
}

function handlePrescription(isChecked) {
    if (isChecked) {
        var prescContent = $(".prescdiv").html();
        var currentStep = $("#treatmentform").steps("getCurrentIndex");

        $("#treatmentform").steps("insert", currentStep + 1, {
            title: "Prescription",
            content: prescContent,
            enableCancelButton: false,
            enableNextButton: true,
        });
        // Add a class to the newly inserted step tab
        $("#treatmentform")
            .find(".steps > ul > li")
            .eq(currentStep + 1)
            .addClass("presc_class");

        // Add a class to the content of the prescription tab
        $("#treatmentform")
            .find(".content > .body")
            .eq(currentStep + 1)
            .addClass("presc_content_class");

        $("#medicine_id1").select2({
            width: "100%",
            placeholder: "Select a Medicine",
            tags: true, // Allow user to add new tags (medicines)
            tokenSeparators: [",", " "], // Define how tags are separated
            createTag: function (params) {
                var term = $.trim(params.term);

                if (term === "") {
                    return null;
                }

                // Check if the term already exists as an option
                var found = false;
                $(this)
                    .find("option")
                    .each(function () {
                        if ($.trim($(this).text()) === term) {
                            found = true;
                            return false; // Exit the loop early
                        }
                    });

                if (!found) {
                    // Return object for new tag
                    return {
                        id: term,
                        text: term,
                        newTag: true, // Add a custom property to indicate it's a new tag
                    };
                }

                return null; // If term already exists, return null
            },
        });
        $("#dosage1").select2({
            width: "100%",
            placeholder: "Select a Dosage",
        });
        $("#advice1").select2({
            width: "100%",
        });

        // }
    } else {
        $(".wizard-content .wizard > .steps > ul > li.presc_class").attr(
            "style",
            "display:none;"
        );
        $(".wizard-content .wizard > .content > .presc_content_class").remove();
        $("#treatmentform").steps("remove", "Prescription");
    }
}

function handleHistoryStep(visitcount) {
    if (visitcount != "0") {
        var currentStep = $("#treatmentform").steps("getCurrentIndex");
        var apphistoryContent = $(".apphistorydiv").html();
        // Add the history step if it hasn't been added
        if (!historyStepAdded) {
            $("#treatmentform").steps("insert", currentStep + 1, {
                title: "Appointment History",
                content: apphistoryContent,
                enableCancelButton: false,
                // enablePreviousButton: true,
                enableNextButton: true,
            });
            historyStepAdded = true;
        }
    }
}

// function handlePrescriptionStep(presc) {
//     if (presc) {
//         var currentStep = $("#treatmentform").steps("getCurrentIndex");
//         var prescContent = $(".prescdiv").html();
//         if (!prescriptionStepAdded) {
//             $("#treatmentform").steps("insert", currentStep + 1, {
//                 title: "Prescription",
//                 content: prescContent,
//                 enableCancelButton: false,
//                 enableNextButton: true,
//             });

//             // Add a class to the newly inserted step tab
//             $("#treatmentform")
//                 .find(".steps > ul > li")
//                 .eq(currentStep + 1)
//                 .addClass("presc_class");

//             // Add a class to the content of the prescription tab
//             $("#treatmentform")
//                 .find(".content > .body")
//                 .eq(currentStep + 1)
//                 .addClass("presc_content_class");
//             prescriptionStepAdded = true;

//             $("#medicine_id1").select2({
//                 width: "100%",
//                 placeholder: "Select a Medicine",
//                 tags: true, // Allow user to add new tags (medicines)
//                 tokenSeparators: [",", " "], // Define how tags are separated
//                 createTag: function (params) {
//                     var term = $.trim(params.term);

//                     if (term === "") {
//                         return null;
//                     }

//                     // Check if the term already exists as an option
//                     var found = false;
//                     $(this)
//                         .find("option")
//                         .each(function () {
//                             if ($.trim($(this).text()) === term) {
//                                 found = true;
//                                 return false; // Exit the loop early
//                             }
//                         });

//                     if (!found) {
//                         // Return object for new tag
//                         return {
//                             id: term,
//                             text: term,
//                             newTag: true, // Add a custom property to indicate it's a new tag
//                         };
//                     }

//                     return null; // If term already exists, return null
//                 },
//             });
//             $("#dosage1").select2({
//                 width: "100%",
//                 placeholder: "Select a Dosage",
//             });
//             $("#advice1").select2({
//                 width: "100%",
//             });
//         }
//     } else {
//         if (prescriptionStepAdded) {
//             $(".wizard-content .wizard > .steps > ul > li.presc_class").attr(
//                 "style",
//                 "display:none;"
//             );
//             $(
//                 ".wizard-content .wizard > .content > .presc_content_class"
//             ).remove();
//             $("#treatmentform").steps("remove", "Prescription");
//             prescriptionStepAdded = false;
//         }
//     }
// }

function getChargeSection(isAdmin) {
    if (isAdmin) {
        var currentStep = $("#treatmentform").steps("getCurrentIndex");
        var chargeContent = $(".chargediv").html();
        if (!chargeStepAdded) {
            $("#treatmentform").steps("add", {
                title: "Charge",
                content: chargeContent,
                enableCancelButton: false,
                enableNextButton: true,
            });
            chargeStepAdded = true;
        }
    }
}

function getTreatmentTable(stepIndex) {
    getSessionData()
        .done(function (data) {
            var isAdmin = data.loginedUserAdmin;
            var appId = data.appId;
            var patientId = data.patientId;

            $.ajax({
                url: treatmentShowChargeRoute.replace(":appId", appId),
                type: "GET",
                data: {
                    patient_id: patientId,
                    app_id: appId,
                },
                dataType: "json",
                success: function (response) {
                    console.log("Success response:", response);

                    var tableBody = $("#chargetablebody");
                    tableBody.empty(); // Clear any existing rows

                    var treatments = response.toothExaminations;
                    var comboOffers = response.comboOffer;
                    var totalCost = 0;
                    if (treatments && treatments.length > 0) {
                        treatments.forEach(function (exam, index) {
                            var treatCost = parseFloat(
                                exam.treatment.treat_cost
                            );
                            var discountCost = parseFloat(
                                exam.treatment.discount_cost
                            );
                            totalCost += discountCost;
                            var treatDiscount =
                                exam.treatment.discount_percentage;

                            var row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${exam.treatment.treat_name
                                } (${treatCost.toFixed(2)})</td>
                                    <td>${treatDiscount != null
                                    ? treatDiscount
                                    : 0
                                } %</td>
                                    <td>${discountCost.toFixed(2)}</td>
                                </tr>
                            `;
                            tableBody.append(row);
                        });

                        // Update total charge table initially
                        updateTotalCharge(totalCost);
                    } else {
                        var noDataRow = `
                            <tr>
                                <td colspan="4">No data available</td>
                            </tr>
                        `;
                        tableBody.append(noDataRow);
                    }
                },
                error: function (xhr) {
                    console.error("Error fetching treatment table data:", xhr);
                },
            });
        })
        .fail(function (xhr) {
            console.error("Error fetching session data:", xhr);
        });
}
// Function to update total charge based on discount
function updateTotalCharge(totalCost, discountPercentage = 0) {
    // If discount percentage is null or undefined, default to 0
    if (isNaN(discountPercentage)) {
        discountPercentage = 0;
    }

    var discountedAmount = totalCost * (1 - discountPercentage / 100);
    var totalAmount = discountedAmount.toFixed(2);

    var tableChargeBody = $("#totalChargeBody");
    tableChargeBody.empty(); // Clear any existing rows

    var row = `
        <tr class="bt-3 border-primary">
            <th colspan="3" class="text-end">Total Rate</th>
            <td colspan="1">${totalCost.toFixed(2)}</td>
        </tr>
        <tr>
            <th colspan="3" class="text-end">Doctor Discount (if any)</th>
            <td colspan="1">
                <div class="input-group">
                    <input type="text" class="form-control" id="discount1" name="discount1" aria-describedby="basic-addon2" value="${discountPercentage}">
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">%</span>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="bg-primary">
            <th colspan="3" class="text-end fs-18 fw-600">Total Amount</th>
            <td colspan="1" class="fs-18 fw-600">${totalAmount}</td>
        </tr>
    `;
    tableChargeBody.append(row);

    // Set focus back to the discount input field after updating
    var discountInput = $("#discount1");
    var discountValue = discountInput.val();
    discountInput.focus().val("").val(discountValue);
}

// Event listener for discount input change
$(document).on("input", "#discount1", function () {
    var discountValue = $(this).val();
    // Remove non-numeric characters from input value
    var numericValue = discountValue.replace(/[^0-9]/g, "");
    $(this).val(numericValue);

    // If the input value is numeric or empty, update the total charge
    var discountPercentage = parseFloat(numericValue);
    if (!isNaN(discountPercentage) || discountValue === "") {
        // Fetch current total cost from the UI
        var currentTotalCost = parseFloat(
            $("#totalChargeBody .bt-3.border-primary td:last-child").text()
        );

        // Check if currentTotalCost is NaN or not a valid number
        if (!isNaN(currentTotalCost)) {
            // Update total charge with new discount
            updateTotalCharge(currentTotalCost, discountPercentage);
        } else {
            // Handle case where currentTotalCost is NaN or not valid
            updateTotalCharge(0, discountPercentage); // Default to 0 if NaN
        }
    } else {
        // If input value is not numeric (NaN) and not empty, default to 0
        updateTotalCharge(0, 0);
    }
});

$("#treatmentform").steps({
    headerTag: "h6",
    bodyTag: "section",
    transitionEffect: "none",
    titleTemplate: "#title#",
    labels: {
        finish: '<span><i class="fa fa-save"></i> Save</span>',
    },
    onStepChanging: function (event, currentIndex, newIndex) {
        // Validate form for current step
        var valid = false;
        form = $("#treatmentform");
        // Validate form for current step
        if (currentIndex < newIndex) {
            var validator = form.validate(); // Initialize validator
            var valid = true;
            // Check validation only if the validator is initialized
            if (validator) {
                // Find inputs in the current step only
                var inputs = form
                    .find("section")
                    .eq(currentIndex)
                    .find("input");
                // Validate only inputs in the current step
                inputs.each(function () {
                    if (!validator.element(this)) {
                        valid = false;
                    }
                });
                // Find selects in the current step only
                var selects = form
                    .find("section")
                    .eq(currentIndex)
                    .find("select");
                // Validate only selects in the current step
                selects.each(function () {
                    if (!validator.element(this)) {
                        valid = false;
                    }
                });
            }
            // If form is not valid, handle error messages
            if (!valid) {
                form.find(".error").removeClass("error"); // Remove error classes
            }
            // Return true or false based on validation result
            // return valid;
        }
        let visitcount = $("#visitcount").val();
        let isAdmin = $("#isAdmin").val();
        if (currentIndex == 0) {
            handleHistoryStep(visitcount);
        }

        getChargeSection(isAdmin);
        getDentalTable(newIndex);
        getTreatmentTable(newIndex);

        // Return true if moving backwards or all validation passed
        return currentIndex > newIndex || valid;
    },
    // onFinishing: function (event, currentIndex) {
    //     return (form.validate().settings.ignore = ":disabled"), form.valid();
    // },
    onFinishing: function (event, currentIndex) {
        var form = $(this);

        // Validate the form
        form.validate();

        // Adjust validation settings if needed
        form.validate().settings.ignore = ":disabled";

        // Check if the form is valid
        var isValid = form.valid();

        // Return true or false based on validation result
        return isValid;
    },
    onFinished: function (event, currentIndex) {
        var formDataPatient = new FormData($("#treatmentform")[0]); // Serialize form data including files
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
        var storeRoute = $("#storeRoute").data("url");
        if (storeRoute == null) {
            storeRoute = $("#updateRoute").data("url");
        }

        console.log("stepjs :" + formDataPatient);
        $.ajax({
            url: storeRoute,
            type: "POST",
            data: formDataPatient,
            dataType: "json",
            processData: false, // Important: To send FormData object, set processData to false
            contentType: false, // Important: To send FormData object, set contentType to falsestafflist-route
            headers: {
                "X-CSRF-TOKEN": CSRF_TOKEN, // Pass CSRF token via headers
            },
            success: function (response) {
                //var successMessage = response.success; // Adjust as per your actual response structure
                var message = "Patient added successfully.";
                // Redirect to stafflist route
                var routeReturn = $("#storeRoute").data("patientlist-route");
                if (routeReturn == null) {
                    routeReturn = $("#updateRoute").data("patientlist-route");
                }

                // Redirect to the stafflist route
                window.location.href =
                    routeReturn +
                    "?success_message=" +
                    encodeURIComponent(message);
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Validation error occurred
                    console.log(xhr.responseJSON.errors); // Log the validation errors to console
                    var errorMessage = "<ul>"; // Start an unordered list for error messages

                    // Loop through the errors and concatenate them into list items
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        errorMessage += "<li>" + value[0] + "</li>"; // Wrap each error message in <li> tags
                    });

                    errorMessage += "</ul>"; // Close the unordered list

                    $("#error-message").html(errorMessage);
                    $("#error-message").show();
                } else if (xhr.responseJSON && xhr.responseJSON.error) {
                    // Other server-side error occurred
                    console.log(xhr.responseJSON.error); // Log the server error message to console

                    // Display error message on the page
                    $("#error-message").text(xhr.responseJSON.error);
                    $("#error-message").show(); // Show the error message element
                } else {
                    console.error(
                        "Error occurred but no specific error message received."
                    );
                    $("#error-message").text("An error occurred.");
                    $("#error-message").show(); // Show a generic error message
                }
            },
        });
    },
}),
    $("#treatmentform").validate({
        ignore: "input[type=hidden]",
        ignore: "select[name=title]",
        errorClass: "text-danger",
        successClass: "text-success",
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        errorPlacement: function (error, element) {
            if (
                $(element).hasClass("select2-hidden-accessible") ||
                $(element).hasClass("select2") ||
                $(element).parent().hasClass("input-group")
            ) {
                error.insertAfter($(element).siblings());
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            title: { required: true },
            firstname: { required: true, minlength: 3, maxlength: 255 },
            lastname: { required: true, maxlength: 255 },
            gender: { required: true },
            date_of_birth: { required: true, date: true },
            aadhaar_no: { minlength: 12, maxlength: 12, digits: true },
            email: { email: true },
            phone: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 15,
            },
            alter_phone: { digits: true, minlength: 10, maxlength: 15 },
            regdate: { required: true, date: true },
            address1: { required: true, minlength: 3, maxlength: 255 },
            address2: { required: true, minlength: 3, maxlength: 255 },
            country_id: { required: true },
            state_id: { required: true },
            city_id: { required: true },
            pincode: { required: true, digits: true, maxlength: 10 },
            blood_group: { minlength: 1 },
            clinic_branch_id0: { required: true },
            doctor2: { required: true },
            appdate: { required: true },
            appstatus: { required: true },
            height: { number: true },
            weight: { number: true },
            bp: { minlength: 3 },
            rdoctor: { minlength: 3 },
            marital_status: { required: false },
            smoking_status: { required: false },
            alcoholic_status: { required: false },
            diet: { required: false },
            allergies: { maxlength: 500 },
            "medical_conditions[]": { required: false, maxlength: 500 },
        },
    });
