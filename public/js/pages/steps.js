let availabilityStepAdded = false;

$("#staffform").steps({
    headerTag: "h6.tabHeading",
    bodyTag: "section.tabSection",
    transitionEffect: "none",
    titleTemplate: "#title#",
    labels: {
        finish: '<span><i class="fa fa-save"></i> Save</span>',
    },
    onStepChanging: function (event, currentIndex) {
        let role = $("select[name='role[]']").val();

        if (currentIndex === 0 && (role === "3" || role.includes("3"))) {
            var doctorContent = $(".doctordiv").html();
            // Add the Availability step if it hasn't been added
            if (!availabilityStepAdded) {
                // Add a new step dynamically as the final step
                $(".tab-wizard").steps("add", {
                    title: "Availability", // Title of the new step
                    content: doctorContent,
                    enableCancelButton: false, // Optional: Disable cancel button for this step
                    enableFinishButton: true, // Optional: Enable finish button for this step
                });
                availabilityStepAdded = true; // Update the flag
            }
        }

        if (availabilityStepAdded && !(role === "3" || role.includes("3"))) {
            $(".wizard-content .wizard > .steps > ul > li.last").attr(
                "style",
                "display:none;"
            );
            $(".tab-wizard").steps("remove", "Availability");
            availabilityStepAdded = false; // Reset the flag

            // Remove the tab title and its content from DOM
            $(".tab-wizard > .content > .body")
                .find(".content > .body")
                .remove();
            $(".tab-wizard > .content").find(".content").last().remove();
        }

        return true;
    },
    onFinished: function (event, currentIndex) {
        var formDataStaff = $(".tab-wizard").serialize(); // Correct serialization of form data
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
        var storeRoute = $("#storeRoute").data("url");

        console.log(formDataStaff);
        $.ajax({
            url: storeRoute,
            type: "POST",
            data: formDataStaff,
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": CSRF_TOKEN, // Pass CSRF token via headers
            },
            success: function (response) {
                // If successful, hide modal and show success message
                $("#modal-right").modal("hide");
                $("#successMessage").text("Clinic created successfully");
                $("#successMessage").fadeIn().delay(3000).fadeOut(); // Show for 3 seconds
                location.reload(); // Optionally, you can reload or update the table here
            },
            error: function (xhr) {
                $("#modal-right .modal-body").scrollTop(0);
                // }
            },
        });
    },
});
var form = $(".validation-wizard").show();

$(".validation-wizard").steps({
    headerTag: "h6",
    bodyTag: "section",
    transitionEffect: "none",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: "Submit",
    },
    onStepChanging: function (event, currentIndex, newIndex) {
        return (
            currentIndex > newIndex ||
            (!(3 === newIndex && Number($("#age-2").val()) < 18) &&
                (currentIndex < newIndex &&
                    (form
                        .find(".body:eq(" + newIndex + ") label.error")
                        .remove(),
                    form
                        .find(".body:eq(" + newIndex + ") .error")
                        .removeClass("error")),
                (form.validate().settings.ignore = ":disabled,:hidden"),
                form.valid()))
        );
    },
    onFinishing: function (event, currentIndex) {
        return (form.validate().settings.ignore = ":disabled"), form.valid();
    },
    onFinished: function (event, currentIndex) {
        swal(
            "Your Form Submitted!",
            "Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor."
        );
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
        },
    });
