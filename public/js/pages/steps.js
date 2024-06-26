let availabilityStepAdded = false;

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

// var form = $("#staffform").show();
var form = $(".validation-wizard").show();

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
        form = $(".validation-wizard");
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

                // Redirect to stafflist route
                var routeReturn = $("#storeRoute").data("stafflist-route");
                if (routeReturn == null) {
                    routeReturn = $("#updateRoute").data("stafflist-route");
                }

                // Redirect to the stafflist route
                window.location.href =
                    routeReturn +
                    "?success_message=" +
                    encodeURIComponent("Staff added successfully.");
            },
            error: function (xhr) {
                console.log(xhr.responseJSON.message);
                // console.log(response.error);
            },
        });
    },
}),
    $(".validation-wizard").validate({
        ignore: "input[type=hidden]",
        errorClass: "text-danger",
        successClass: "text-success",
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
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
        },
    });
