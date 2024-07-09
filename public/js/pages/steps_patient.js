var form = $("#patientform").show();

$("#patientform").steps({
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
        form = $("#patientform");
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
        var formDataPatient = new FormData($("#patientform")[0]); // Serialize form data including files
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
    $("#patientform").validate({
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
            // alert($(element).attr("id"));
            if ($(element).hasClass("select2")) {
                error.insertAfter($(element).siblings());
            } else {
                error.insertAfter(element);
            }
            // error.insertAfter(element);
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
        },
    });
