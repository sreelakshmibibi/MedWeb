var form = $("#patientform").show();

$("#patientform").steps({
    headerTag: "h6",
    bodyTag: "section",
    transitionEffect: "none",
    titleTemplate: "#title#",
    labels: {
        finish: '<span><i class="fa fa-save"></i> Save</span>',
    },
    onFinishing: function (event, currentIndex) {
        return (form.validate().settings.ignore = ":disabled"), form.valid();
    },
    onFinished: function (event, currentIndex) {
        var formDataStaff = new FormData($("#patientform")[0]); // Serialize form data including files
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
                var successMessage = response.success; // Adjust as per your actual response structure

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
            },
        });
    },
}),
    $("#patientform").validate({
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
