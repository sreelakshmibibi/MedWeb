<form id="editClinicForm" method="post" enctype="multipart/form-data" action="{{ route('settings.clinic.update') }}">
    @csrf
    <input type="hidden" id="edit_clinic_id" name="edit_clinic_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-edit-clinic" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-clinic-medical"> </i> Edit Clinic Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_email">E-mail <span class="text-danger">
                                            *</span></label>
                                    <input type="email" class="form-control" id="edit_clinic_email"
                                        name="clinic_email" placeholder="E-mail">
                                    <div id="edit_clinicEmailError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_phone">Contact
                                        Number <span class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" id="edit_clinic_phone"
                                        name="clinic_phone" placeholder="Phone">
                                    <div id="edit_clinicPhoneError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6" for="branch">Is main branch?</label>
                            <input name="edit_branch_active" type="radio" class="form-control with-gap" id="edit_yes"
                                value="Y">
                            <label for="edit_yes">Yes</label>
                            <input name="edit_branch_active" type="radio" class="form-control with-gap" id="edit_no"
                                value="N">
                            <label for="edit_no">No</label>
                            <div id="edit_clinicBranchError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6" for="branch">Is medicine provided?</label>
                            <input name="edit_is_medicine_provided" type="radio" class="form-control with-gap"
                                id="edit_medicine_yes" value="Y">
                            <label for="edit_medicine_yes">Yes</label>
                            <input name="edit_is_medicine_provided" type="radio" class="form-control with-gap"
                                id="edit_medicine_no" value="N">
                            <label for="edit_medicine_no">No</label>
                            <div id="edit_cliniMedicinecError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_address1">Address Line
                                        1 <span class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" id="edit_clinic_address1"
                                        name="edit_clinic_address1" placeholder="adress line 1">
                                    <div id="edit_clinicAddress1Error" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_address2">Address Line
                                        2 <span class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" id="edit_clinic_address2"
                                        name="edit_clinic_address2" placeholder="adress line 2">
                                    <div id="edit_clinicAddress2Error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_country">Country <span
                                            class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="edit_clinic_country" name="clinic_country">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" <?php if ($country->id == 101) {
                                                echo 'selected';
                                            } ?>>
                                                {{ $country->country }}</option>
                                        @endforeach
                                    </select>
                                    <div id="edit_clinicCountryError" class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_state">State <span
                                            class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="edit_clinic_state" name="clinic_state">

                                    </select>
                                    <div id="edit_clinicStateError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_city">City <span class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="edit_clinic_city" name="clinic_city">

                                    </select>
                                    <div id="edit_clinicCityError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_clinic_pincode">Pin Code <span
                                            class="text-danger">
                                            *</span></label>
                                    <input class="form-control" type="text" id="edit_clinic_pincode"
                                        name="clinic_pincode" placeholder="XXX XXX">
                                    <div id="edit_clinicPincodeError" class="invalid-feedback"></div>
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
                $('#edit_clinic_state').append('<option value="">Select State</option>');
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
                $('#edit_clinic_city').append('<option value="">Select City</option>');
            }
        }

        // Handle Save button click
        $('#updateClinicBtn').click(function(event) {
            // Reset previous error messages
            resetErrors();
            event.preventDefault(); // Prevent default form submission

            // Basic client-side validation
            var isValid = true;

            // Gather form data
            var formData = {
                clinic_email: $('#edit_clinic_email').val(),
                clinic_phone: $('#edit_clinic_phone').val(),
                branch_active: $('input[name="edit_branch_active"]:checked').val(),
                is_medicine_provided: $('input[name="edit_is_medicine_provided"]:checked').val(),
                clinic_address1: $('#edit_clinic_address1').val(),
                clinic_address2: $('#edit_clinic_address2').val(),
                clinic_country: $('#edit_clinic_country').val(),
                clinic_state: $('#edit_clinic_state').val(),
                clinic_city: $('#edit_clinic_city').val(),
                clinic_pincode: $('#edit_clinic_pincode').val(),
                "_token": "{{ csrf_token() }}"
            };
            // if (formData.clinic_email.trim() && !isValidEmail(formData.clinic_email.trim())) {
            if (!isValidEmail(formData.clinic_email.trim())) {
                isValid = false;
                $('#edit_clinic_email').addClass('is-invalid');
                $('#edit_clinicEmailError').text('Invalid email address.');
            }

            if (!formData.clinic_phone.trim()) {
                isValid = false;
                $('#edit_clinic_phone').addClass('is-invalid');
                $('#edit_clinicPhoneError').text('Clinic contact number is required.');
            }

            if (!formData.clinic_address1.trim()) {
                isValid = false;
                $('#edit_clinic_address1').addClass('is-invalid');
                $('#edit_clinicAddress1Error').text('Address Line 1 is required.');
            }
            if (!formData.clinic_address2.trim()) {
                isValid = false;
                $('#edit_clinic_address2').addClass('is-invalid');
                $('#edit_clinicAddress2Error').text('Address Line 2 is required.');
            }

            if (!formData.clinic_country) {
                isValid = false;
                $('#edit_clinic_country').addClass('is-invalid');
                $('#edit_clinicCountryError').text('Select a country.');
            }

            if (!formData.clinic_state) {
                isValid = false;
                $('#edit_clinic_state').addClass('is-invalid');
                $('#edit_clinicStateError').text('Select a state.');
            }

            if (!formData.clinic_city) {
                isValid = false;
                $('#edit_clinic_city').addClass('is-invalid');
                $('#edit_clinicCityError').text('Select a city.');
            }

            if (!formData.clinic_pincode.trim()) {
                isValid = false;
                $('#edit_clinic_pincode').addClass('is-invalid');
                $('#edit_clinicPincodeError').text('Pin code is required.');
            } else if (!isValidPincode(formData.clinic_pincode.trim())) {
                isValid = false;
                $('#edit_clinic_pincode').addClass('is-invalid');
                $('#edit_clinicPincodeError').text('Invalid pin code format.');
            }

            // Perform AJAX submit if form is valid
            if (isValid) {
                var form = $('#editClinicForm');
                var url = form.attr('action');
                var formDataClinic = form.serialize();
                $.ajax({
                    type: 'POST',
                    url: url,
                    // data: {
                    //     formDataClinic,
                    //     "_token": "{{ csrf_token() }}"
                    // },
                    data: formDataClinic,
                    dataType: 'json',
                    success: function(response) {
                        // If successful, hide modal and show success message
                        $('#modal-edit-clinic').modal('hide');
                        $('#successMessage').text('Clinic updated successfully');
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds

                        // location
                        //     .reload(); // Optionally, you can reload or update the table here
                        table.ajax.reload();
                        reloadbranch();
                    },
                    error: function(xhr) {
                        // Reset previous errors
                        resetErrors();
                        console.log(xhr);

                        // Check if there are validation errors in the response
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;

                            // Handle clinic_phone error
                            if (errors.hasOwnProperty('clinic_phone')) {
                                $('#edit_clinic_phone').addClass('is-invalid');
                                $('#edit_clinicPhoneError').text(errors.clinic_phone[0]);
                            }

                            // Handle clinic_email error
                            if (errors.hasOwnProperty('clinic_email')) {
                                $('#edit_clinic_email').addClass('is-invalid');
                                $('#edit_clinicEmailError').text(errors.clinic_email[0]);
                            }

                            // Handle clinic_address1 error
                            if (errors.hasOwnProperty('clinic_address1')) {
                                $('#edit_clinic_address1').addClass('is-invalid');
                                $('#edit_clinicAddress1Error').text(errors.clinic_address1[
                                    0]);
                            }

                            // Handle clinic_country error
                            if (errors.hasOwnProperty('clinic_country')) {
                                $('#edit_clinicCountryError').text(errors.clinic_country[
                                    0]);
                            }

                            // Handle clinic_state error
                            if (errors.hasOwnProperty('clinic_state')) {
                                $('#edit_clinicStateError').text(errors.clinic_state[0]);
                            }

                            // Handle clinic_city error
                            if (errors.hasOwnProperty('clinic_city')) {
                                $('#clinicCityError').text(errors.clinic_city[0]);
                            }

                            // Handle clinic_pincode error
                            if (errors.hasOwnProperty('clinic_pincode')) {
                                $('#edit_clinic_pincode').addClass('is-invalid');
                                $('#edit_clinicPincodeError').text(errors.clinic_pincode[
                                    0]);
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
            $('#edit_clinicPhoneError').text('');
            $('#edit_clinicEmailError').text('');
            $('#edit_clinicWebsiteError').text('');
            $('#edit_clinicBranchError').text('');
            $('#edit_clinicAddress1Error').text('');
            $('#edit_clinicCountryError').text('');
            $('#edit_clinicStateError').text('');
            $('#edit_clinicCityError').text('');
            $('#edit_clinicPincodeError').text('');
        }



        // Reset form and errors on modal close
        $('#modal-edit-clinic').on('hidden.bs.modal', function() {
            $('#editClinicForm').trigger('reset');
            resetErrors();
        });


    });
</script>
