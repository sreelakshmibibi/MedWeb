let availabilityStepAdded = false;
// Track validation status for each day
var daysValidation = {
    sunday: false,
    monday: false,
    tuesday: false,
    wednesday: false,
    thursday: false,
    friday: false,
    saturday: false,
};

function resetDays() {
    daysValidation = {
        sunday: false,
        monday: false,
        tuesday: false,
        wednesday: false,
        thursday: false,
        friday: false,
        saturday: false,
    };
}

function handleAvailabilityStep(role) {
    if (role === "3" || role.includes("3")) {
        var doctorContent = $(".doctordiv").html();
        // Add the Availability step if it hasn't been added
        if (!availabilityStepAdded) {
            $("#staffform").steps("add", {
                title: "Availability", // Title of the new step
                content: doctorContent,
                enableCancelButton: false, // Optional: Disable cancel button for this step
                enableFinishButton: true, // Optional: Enable finish button for this step
            });
            availabilityStepAdded = true; // Update the flag
        }
    } else {
        // Remove Availability step if role does not require it
        // if (availabilityStepAdded) {
        //     $("#staffform").steps("remove", "Availability");
        //     availabilityStepAdded = false; // Reset the flag
        // }
        if (availabilityStepAdded && !(role === "3" || role.includes("3"))) {
            $(".wizard-content .wizard > .steps > ul > li.last").attr(
                "style",
                "display:none;"
            );
            $("#staffform").steps("remove", "Availability");
            availabilityStepAdded = false; // Reset the flag

            // Remove the tab title and its content from DOM
            $("#staffform > .content > .body")
                .find(".content > .body")
                .remove();
            $("#staffform > .content").find(".content").last().remove();
        }
    }
}

// Validate weekday time inputs
function validateWeekdayTime(day, i) {
    // alert(day);
    var fromValue = $("#" + day + "_from" + i).val();
    var toValue = $("#" + day + "_to" + i).val();

    // Check if fromValue is filled and toValue is empty
    if (fromValue && !toValue) {
        $("#" + day + "_to" + i).addClass("is-invalid"); // Add Bootstrap's is-invalid class
        daysValidation[day] = false; // Mark the day as invalid
        return false;
    } else {
        $("#" + day + "_to" + i).removeClass("is-invalid"); // Remove is-invalid class if valid
        daysValidation[day] = true; // Mark the day as valid
        return true;
    }
}

// Function to check if at least one day is filled
function checkAtLeastOneDayFilled() {
    alert("inside");
    var isValid = Object.values(daysValidation).some(function (valid) {
        return valid;
    });
}

// var form = $("#staffform").show();
var form = $("#staffform").show();

$("#staffform").steps({
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
        form = $("#staffform");
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
        // if (currentIndex < newIndex) {
        //     var valid = form.valid();
        //     if (!valid) {
        //         form.find(
        //             ".body:eq(" + currentIndex + ") label.error"
        //         ).remove();
        //         form.find(".body:eq(" + currentIndex + ") .error").removeClass(
        //             "error"
        //         );
        //     }
        // }

        // Handle availability step based on role selection
        let role = $("select[name='role[]']").val();
        handleAvailabilityStep(role);

        // Return true if moving backwards or all validation passed
        return currentIndex > newIndex || valid;
    },
    onFinishing: function (event, currentIndex) {
        var form = $(this);

        // Validate the form
        form.validate();

        // Adjust validation settings if needed
        form.validate().settings.ignore = ":disabled";

        // Check if the form is valid
        var isValid = form.valid();

        // if (availabilityStepAdded) {
        //     $("#error-message").text(""); // Clear error message if validation passes
        //     $("#error-message").hide();
        //     var rowcount = $("#row_count").val();
        //     alert(rowcount);
        //     if (!rowcount) {
        //         rowcount = 1;
        //     }
        //     for (let i = 1; i <= rowcount; i++) {
        //         // isValid = checkAtLeastOneDayFilled();
        //         resetDays();
        //         // Validate all weekdays
        //         [
        //             "sunday",
        //             "monday",
        //             "tuesday",
        //             "wednesday",
        //             "thursday",
        //             "friday",
        //             "saturday",
        //         ].forEach(function (day, i) {
        //             if (!validateWeekdayTime(day, i)) {
        //                 isValid = false;
        //             }
        //         });

        //         if (!isValid) {
        //             event.preventDefault(); // Prevent form submission if validation fails
        //             $("#error-message").show();
        //             $("#error-message").text("");
        //             $("#error-message").text("Please fill all weekday times");
        //         } else {
        //             isValid = checkAtLeastOneDayFilled(); // Check if at least one day is filled
        //             if (!isValid) {
        //                 event.preventDefault(); // Prevent form submission if validation fails
        //                 $("#error-message").show();
        //                 $("#error-message").text("");
        //                 $("#error-message").text(
        //                     "Please enter the availability"
        //                 );
        //             } else {
        //                 $("#error-message").text(""); // Clear error message if validation passes
        //                 $("#error-message").hide();
        //             }
        //         }
        //     }
        // }

        // Return true or false based on validation result
        return isValid;
    },
    onFinished: function (event, currentIndex) {
        var formDataStaff = new FormData($("#staffform")[0]); // Serialize form data including files
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
        var storeRoute = $("#storeRoute").data("url");
        if (storeRoute == null) {
            storeRoute = $("#updateRoute").data("url");
        }

        console.log(formDataStaff);
        $.ajax({
            url: storeRoute,
            type: "POST",
            data: formDataStaff,
            dataType: "json",
            processData: false, // Important: To send FormData object, set processData to false
            contentType: false, // Important: To send FormData object, set contentType to false
            headers: {
                "X-CSRF-TOKEN": CSRF_TOKEN, // Pass CSRF token via headers
            },
            success: function (response) {
                // var successMessage = response.success; // Adjust as per your actual response structure
                var message = "Staff added successfully.";
                // Redirect to stafflist route
                var routeReturn = $("#storeRoute").data("stafflist-route");
                if (routeReturn == null) {
                    message = "Staff details updated successfully.";
                    routeReturn = $("#updateRoute").data("stafflist-route");
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
                // console.log(xhr.responseJSON);
                // if (xhr.responseJSON && xhr.responseJSON.error) {
                //     console.log(xhr.responseJSON.error); // Log the error message to console

                //     // Display error message on the page
                //     $('#error-message').text(xhr.responseJSON.error);
                //     $('#error-message').show(); // Show the error message element
                // } else {
                //     console.error('Error occurred but no error message received.');
                //     $('#error-message').text('An error occurred.');
                //     $('#error-message').show(); // Show a generic error message
                // }
            },
        });
    },
}),
    // ignore: function (element) {
    //     // Check if element is hidden or select[name=title]
    //     if (
    //         $(element).is(":hidden") ||
    //         $(element).is("select[name=title]")
    //     ) {
    //         return true;
    //     }

    //     // Dynamic ignore based on availabilityStepAdded
    //     if (availabilityStepAdded) {
    //         // Ignore clinic_branch_id if availabilityStepAdded is true
    //         return $(element).is("select[name=clinic_branch_id]");
    //     } else {
    //         // Ignore specialization if availabilityStepAdded is false
    //         return (
    //             $(element).is("input[name=specialization]") ||
    //             $(element).is("input[name=subspecialty]") ||
    //             $(element).is("input[name=license_number]")
    //         );
    //     }

    //     // Return false if none of the conditions match
    //     //  return false;
    // },
    // ignore: function (element) {
    //     if (!availabilityStepAdded) {
    //         return (
    //             $(element).is("input[name=specialization]") ||
    //             $(element).is("input[name=subspecialty]") ||
    //             $(element).is("input[name=license_number]")
    //         );
    //     }
    // },
    $("#staffform").validate({
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
            if ($(element).hasClass("select2")) {
                error.insertAfter($(element).siblings());
            } else {
                error.insertAfter(element);
            }
            // error.insertAfter(element);
        },
        rules: {
            email: {
                email: !0,
            },
            firstname: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            lastname: {
                required: true,
                maxlength: 255,
            },
            gender: {
                required: true,
            },
            role: {
                required: true,
            },
            date_of_birth: {
                required: true,
                date: true,
            },

            phone: {
                required: true,
            },
            address1: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            address2: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },

            pincode: {
                required: true,
                maxlength: 10,
            },

            aadhaar_no: {
                required: true,
                minlength: 12,
                maxlength: 12,
            },
            designation: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            qualification: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            years_of_experience: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            date_of_joining: {
                required: true,
                date: true,
            },
            // specialization: {
            //     required: true,
            //     minlength: 3,
            //     maxlength: 255,
            // },
            // subspecialty: {
            //     required: true,
            //     minlength: 3,
            //     maxlength: 255,
            // },
            // license_number: {
            //     required: true,
            // },
            // clinic_branch_id: {
            //     required: true,
            // },
        },
    });

// $("#staffform").validate({
// ignore: "input[type=hidden]",
// ignore: "select[name=title]",
// ignore: function (element) {
//     if (availabilityStepAdded) {
//         // Ignore clinic_branch_id if availabilityStepAdded is true
//         return $(element).is("input[name=clinic_branch_id]");
//     } else {
//         // Ignore specialization if availabilityStepAdded is false
//         // return $(element).is("input[name=specialization]");
//         return (
//             $(element).is("input[name=specialization]") ||
//             $(element).is("input[name=subspecialty]") ||
//             $(element).is("input[name=license_number]")
//         );
//     }
// },
// ignore: "select[name=title]",
