<form id="editClinicForm" method="post" enctype="multipart/form-data" action="{{ route('settings.clinic.update') }}">
    @csrf
    <input type="hidden" id="edit_clinic_id" name="edit_clinic_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-edit-clinic" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-clinic-medical"></i> Clinic Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="edit_clinic_name">Clinic Name</label>
                            <input class="form-control" type="text" id="edit_clinic_name" name="clinic_name"
                                placeholder="Clinic Name">
                            <div id="editclinicNameError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_email">E-mail</label>
                                    <input type="email" class="form-control" id="edit_clinic_email"
                                        name="clinic_email" placeholder="E-mail">
                                    <div id="clinicEmailError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_phone">Contact
                                        Number</label>
                                    <input type="text" class="form-control" id="edit_clinic_phone"
                                        name="clinic_phone" placeholder="Phone">
                                    <div id="clinicPhoneError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_logo">Logo</label>
                                    <img id="currentClinicLogoImg" src="" alt="Current Logo"
                                        style="max-width: 100px;">
                                    <input class="form-control" type="file" id="edit_clinic_logo" name="clinic_logo"
                                        placeholder="logo">
                                    <div id="clinicLogoError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_website">Website</label>
                                    <input class="form-control" type="url" id="edit_clinic_website"
                                        name="clinic_website" placeholder="http://">
                                    <div id="clinicWebsiteError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6" for="branch">Is main branch?</label>
                            <input name="edit_branch_active" type="radio" class="form-control with-gap" id="edit_yes"
                                value="Y" checked>
                            <label for="yes">Yes</label>
                            <input name="edit_branch_active" type="radio" class="form-control with-gap" id="edit_no"
                                value="N">
                            <label for="no">No</label>
                            <div id="clinicBranchError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">

                            <label class="form-label col-md-6">Is Medicine Provided?</label>
                            <div>
                                <input name="status" type="radio" class="form-control-input"
                                    id="edit_medicine_yes" value="Y">
                                <label class="form-check-label" for="edit_yes">Yes</label>
                                <input name="status" type="radio" class="form-control-input"
                                    id="edit_medicine_no" value="N">
                                <label class="form-check-label" for="edit_no">No</label>
                            </div>
                            <div class="text-danger" id="statusError"></div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_address1">Address Line
                                        1</label>
                                    <input type="text" class="form-control" id="edit_clinic_address1"
                                        name="clinic_address1" placeholder="adress line 1">
                                    <div id="clinicAddress1Error" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_address2">Address Line
                                        2</label>
                                    <input type="text" class="form-control" id="edit_clinic_address2"
                                        name="clinic_address2" placeholder="adress line 2">
                                    <div id="clinicAddress2Error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_country">Country</label>
                                    <select class="form-select" id="edit_clinic_country" name="clinic_country">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" <?php if ($country->id == 101) {
                                                echo 'selected';
                                            } ?>>
                                                {{ $country->country }}</option>
                                        @endforeach
                                    </select>
                                    <div id="clinicCountryError" class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_state">State</label>
                                    <select class="form-select" id="edit_clinic_state" name="clinic_state">

                                    </select>
                                    <div id="clinicStateError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_city">City</label>
                                    <select class="form-select" id="edit_clinic_city" name="clinic_city">

                                    </select>
                                    <div id="clinicCityError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_pincode">Pin Code</label>
                                    <input class="form-control" type="text" id="edit_clinic_pincode"
                                        name="clinic_pincode" placeholder="XXX XXX">
                                    <div id="clinicPincodeError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success float-end" id="updateClinicBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        var initialCountryId = $('#clinic_country').val(); // Assuming India is selected initially
        loadStates(initialCountryId);

        // Handle change event for country dropdown
        $('#edit_clinic_country').change(function() {
            var countryId = $(this).val();
            loadStates(countryId);
        });

        // Handle change event for state dropdown
        $('#edit_clinic_state').change(function() {
            var stateId = $(this).val();
            loadCities(stateId);
        });

        // Function to load states based on country ID
        function loadStates(countryId) {
            if (countryId) {
                $.ajax({
                    url: '{{ route('get.states', '') }}' + '/' + countryId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#edit_clinic_state').empty();
                        $('#edit_clinic_state').append('<option value="">Select State</option>');
                        $.each(data, function(key, value) {
                            $('#edit_clinic_state').append('<option value="' + key + '">' +
                                value + '</option>');
                        });
                        var initialStateId = $('#edit_clinic_state').val();
                        loadCities(initialStateId);
                    }
                });
            } else {
                $('#edit_clinic_state').empty();
            }
        }

        // Function to load cities based on state ID
        function loadCities(stateId) {
            if (stateId) {
                $.ajax({
                    url: '{{ route('get.cities', '') }}' + '/' + stateId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#edit_clinic_city').empty();
                        $('#edit_clinic_city').append('<option value="">Select City</option>');
                        $.each(data, function(key, value) {
                            $('#edit_clinic_city').append('<option value="' + key + '">' +
                                value + '</option>');
                        });
                    }
                });
            } else {
                $('#edit_clinic_city').empty();
            }
        }

        // Handle Save button click
        $('#updateClinicBtn').click(function() {
            // Reset previous error messages
            resetErrors();

            // Gather form data
            var formData = {
                clinic_name: $('#edit_clinic_name').val(),
                clinic_phone: $('#edit_clinic_phone').val(),
                clinic_email: $('#edit_clinic_email').val(),
                clinic_website: $('#edit_clinic_website').val(),
                branch_active: $('input[name="branch_active"]:checked').val(),
                is_medicine_provided: $('input[name="is_medicine_provided"]:checked').val(),
                clinic_logo: $('#edit_clinic_logo').val(),
                clinic_address1: $('#edit_clinic_address1').val(),
                clinic_address2: $('#edit_clinic_address2').val(),
                clinic_country: $('#edit_clinic_country').val(),
                clinic_state: $('#edit_clinic_state').val(),
                clinic_city: $('#edit_clinic_city').val(),
                clinic_pincode: $('#edit_clinic_pincode').val(),
            };

            // Basic client-side validation
            var isValid = true;

            if (!formData.clinic_name.trim()) {
                isValid = false;
                $('#edit_clinic_name').addClass('is-invalid');
                $('#editclinicNameError').text('Clinic name is required.');
            }

            if (!formData.clinic_phone.trim()) {
                isValid = false;
                $('#edit_clinic_phone').addClass('is-invalid');
                $('#clinicPhoneError').text('Clinic contact number is required.');
            }

            if (formData.clinic_email.trim() && !isValidEmail(formData.clinic_email.trim())) {
                isValid = false;
                $('#edit_clinic_email').addClass('is-invalid');
                $('#clinicEmailError').text('Invalid email address.');
            }

            if (formData.clinic_website.trim() && !isValidUrl(formData.clinic_website.trim())) {
                isValid = false;
                $('#edit_clinic_website').addClass('is-invalid');
                $('#clinicWebsiteError').text('Invalid website URL.');
            }

            if (!formData.branch_active) {
                isValid = false;
                $('#edit_clinicBranchError').text('Select whether it is the main branch.');
            }

            if (!formData.clinic_address1.trim()) {
                isValid = false;
                $('#edit_clinic_address1').addClass('is-invalid');
                $('#clinicAddress1Error').text('Address Line 1 is required.');
            }
            if (!formData.clinic_address2.trim()) {
                isValid = false;
                $('#edit_clinic_address2').addClass('is-invalid');
                $('#clinicAddress2Error').text('Address Line 2 is required.');
            }

            if (!formData.clinic_country) {
                isValid = false;
                $('#edit_clinic_country').addClass('is-invalid');
                $('#clinicCountryError').text('Select a country.');
            }

            if (!formData.clinic_state) {
                isValid = false;
                $('#edit_clinic_state').addClass('is-invalid');
                $('#clinicStateError').text('Select a state.');
            }

            if (!formData.clinic_city) {
                isValid = false;
                $('#edit_clinic_city').addClass('is-invalid');
                $('#clinicCityError').text('Select a city.');
            }

            if (!formData.clinic_pincode.trim()) {
                isValid = false;
                $('#edit_clinic_pincode').addClass('is-invalid');
                $('#clinicPincodeError').text('Pin code is required.');
            } else if (!isValidPincode(formData.clinic_pincode.trim())) {
                isValid = false;
                $('#edit_clinic_pincode').addClass('is-invalid');
                $('#clinicPincodeError').text('Invalid pin code format.');
            }

            // Perform AJAX submit if form is valid
            if (isValid) {
                $('#modal-right').attr('data-bs-dismiss', 'modal');
                var form = $('#editClinicForm');
                var url = form.attr('action');
                var formDataClinic = form.serialize();
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        formDataClinic,
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        // If successful, hide modal and show success message
                        $('#modal-edit-clinic').modal('hide');
                        $('#successMessage').text('Clinic updated successfully');
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                        location
                            .reload(); // Optionally, you can reload or update the table here
                    },
                    error: function(xhr) {
                        // Reset previous errors
                        resetErrors();
                        console.log(xhr);

                        // Check if there are validation errors in the response
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;

                            // Handle clinic_name error
                            if (errors.hasOwnProperty('clinic_name')) {
                                $('#edit_clinic_name').addClass('is-invalid');
                                $('#editclinicNameError').text(errors.clinic_name[0]);
                            }

                            // Handle clinic_phone error
                            if (errors.hasOwnProperty('clinic_phone')) {
                                $('#edit_clinic_phone').addClass('is-invalid');
                                $('#clinicPhoneError').text(errors.clinic_phone[0]);
                            }

                            // Handle clinic_email error
                            if (errors.hasOwnProperty('clinic_email')) {
                                $('#edit_clinic_email').addClass('is-invalid');
                                $('#clinicEmailError').text(errors.clinic_email[0]);
                            }

                            // Handle clinic_website error

                            if (errors.hasOwnProperty('clinic_website')) {
                                $('#edit_clinic_website').addClass('is-invalid');
                                $('#clinicWebsiteError').text(errors.clinic_website[0]);
                            }

                            // Handle branch_active error
                            if (errors.hasOwnProperty('branch_active')) {
                                $('#edit_clinicBranchError').text(errors.branch_active[0]);
                            }

                            // Handle clinic_address1 error
                            if (errors.hasOwnProperty('clinic_address1')) {
                                $('#edit_clinic_address1').addClass('is-invalid');
                                $('#clinicAddress1Error').text(errors.clinic_address1[0]);
                            }

                            // Handle clinic_country error
                            if (errors.hasOwnProperty('clinic_country')) {
                                $('#clinicCountryError').text(errors.clinic_country[0]);
                            }

                            // Handle clinic_state error
                            if (errors.hasOwnProperty('clinic_state')) {
                                $('#clinicStateError').text(errors.clinic_state[0]);
                            }

                            // Handle clinic_city error
                            if (errors.hasOwnProperty('clinic_city')) {
                                $('#clinicCityError').text(errors.clinic_city[0]);
                            }

                            // Handle clinic_pincode error
                            if (errors.hasOwnProperty('clinic_pincode')) {
                                $('#edit_clinic_pincode').addClass('is-invalid');
                                $('#clinicPincodeError').text(errors.clinic_pincode[0]);
                            }

                            // Handle clinic_logo error if applicable
                            if (errors.hasOwnProperty('clinic_logo')) {
                                $('#edit_clinic_logo').addClass('is-invalid');
                                $('#clinicLogoError').text(errors.clinic_logo[0]);
                            }

                            // Scroll to the top of the modal to show the first error
                            $('#modal-edit-clinic .modal-body').scrollTop(0);
                        }
                    }

                });
            }
        });

        // Function to reset form errors
        function resetErrors() {
            $('#edit_clinic_name').removeClass('is-invalid');
            $('#clinicPhoneError').text('');
            $('#clinicEmailError').text('');
            $('#clinicWebsiteError').text('');
            $('#clinicBranchError').text('');
            $('#clinicAddress1Error').text('');
            $('#clinicCountryError').text('');
            $('#clinicStateError').text('');
            $('#clinicCityError').text('');
            $('#clinicPincodeError').text('');
        }

        // Function to validate email format

        // Reset form and errors on modal close
        $('#modal-edit-clinic').on('hidden.bs.modal', function() {
            $('#editClinicForm').trigger('reset');
            resetErrors();
        });


    });
</script>
