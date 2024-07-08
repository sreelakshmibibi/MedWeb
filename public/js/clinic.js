// $(function () {
function validate_form() {
    // alert("from new file");
    isValid = true;

    // Validate clinic_email
    var clinicEmail = $("#clinic_email").val();
    if (!isValidEmail(clinicEmail)) {
        $("#clinic_email").addClass("is-invalid");
        $("#clinicEmailError").text("Please enter a valid email address.");
        isValid = false;
    }

    // Validate clinic_phone
    var clinicPhone = $("#clinic_phone").val();
    if (clinicPhone.trim() === "") {
        $("#clinic_phone").addClass("is-invalid");
        $("#clinicPhoneError").text("Phone number is required.");
        isValid = false;
    }

    // Validate clinic_address1
    var clinicAddress1 = $("#clinic_address1").val();
    if (clinicAddress1.trim() === "") {
        $("#clinic_address1").addClass("is-invalid");
        $("#clinicAddress1Error").text("Address Line 1 is required.");
        isValid = false;
    }

    // Validate clinic_country
    var clinicCountry = $("#clinic_country").val();
    if (clinicCountry === "") {
        $("#clinicCountryError").text("Country is required.");
        isValid = false;
    }

    // Validate clinic_state
    var clinicState = $("#clinic_state").val();
    if (clinicState === "") {
        $("#clinic_state").addClass("is-invalid");
        $("#clinicStateError").text("State is required.");
        isValid = false;
    }

    // Validate clinic_city
    var clinicCity = $("#clinic_city").val();
    if (clinicCity === "") {
        $("#clinic_city").addClass("is-invalid");
        $("#clinicCityError").text("City is required.");
        // $('#clinic_city').addClass('is-invalid');
        isValid = false;
    }

    // Validate clinic_pincode
    var clinicPincode = $("#clinic_pincode").val();
    if (!isValidPincode(clinicPincode)) {
        $("#clinic_pincode").addClass("is-invalid");
        $("#clinicPincodeError").text(
            "Please enter a valid pin code (6 digits)."
        );
        isValid = false;
    }
    return isValid;
}
// });
// Function to validate email format
function isValidEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

// Function to validate pin code format
function isValidPincode(pincode) {
    var re = /^\d{6}$/;
    return re.test(pincode);
}

// Function to validate URL format
function isValidUrl(url) {
    // You can implement your own URL validation logic here
    var re = /^(ftp|http|https):\/\/[^ "]+$/;
    return re.test(url);
}

function reloadbranch() {
    $("#basic").click(function () {
        location.reload();
    });
}

// Function to reset form errors
function resetErrors() {
    $(".form-control").removeClass("is-invalid");
    $(".invalid-feedback").text("");
}
