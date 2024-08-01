<form id="createClinicForm" method="post" enctype="multipart/form-data" action="{{ route('settings.clinic.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-clinic-medical"></i> Clinic Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- <div class="form-group">
                            <label class="form-label" for="name">Clinic Name</label>
                            <input class="form-control" type="text" id="clinic_name" name="clinic_name"
                                placeholder="Clinic Name">
                            <div id="clinicNameError" class="invalid-feedback"></div>
                        </div> -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="email">E-mail <span class="text-danger">
                                            *</span></label>
                                    <input type="email" class="form-control" id="clinic_email" name="clinic_email"
                                        placeholder="E-mail">
                                    <div id="clinicEmailError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="phone">Contact
                                        Number <span class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" id="clinic_phone" name="clinic_phone"
                                        placeholder="Phone">
                                    <div id="clinicPhoneError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="logo">Logo</label>
                                    <input class="form-control" type="file" id="clinic_logo" name="clinic_logo"
                                        placeholder="logo">
                                    <div id="clinicLogoError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="website">Website</label>
                                    <input class="form-control" type="url" id="clinic_website" name="clinic_website"
                                        placeholder="http://">
                                    <div id="clinicWebsiteError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div> -->

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6" for="branch">Is main branch?</label>
                            <input name="branch_active" type="radio" class="form-control with-gap" id="yes"
                                value="Y" checked>
                            <label for="yes">Yes</label>
                            <input name="branch_active" type="radio" class="form-control with-gap" id="no"
                                value="N">
                            <label for="no">No</label>
                            <div id="clinicBranchError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6" for="medicine">Is medicine provided?</label>
                            <input name="is_medicine_provided" type="radio" class="form-control with-gap"
                                id="medicine_yes" value="Y" checked>
                            <label for="medicine_yes">Yes</label>
                            <input name="is_medicine_provided" type="radio" class="form-control with-gap"
                                id="medicine_no" value="N">
                            <label for="medicine_no">No</label>
                            <div id="clinicBranchError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="address">Address Line
                                        1 <span class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" id="clinic_address1"
                                        name="clinic_address1" placeholder="adress line 1">
                                    <div id="clinicAddress1Error" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="address">Address Line
                                        2</label>
                                    <input type="text" class="form-control" id="clinic_address2"
                                        name="clinic_address2" placeholder="adress line 2">
                                    <div id="clinicAddress2Error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="clinic_country">Country <span class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="clinic_country" name="clinic_country">
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
                                    <label class="form-label" for="clinic_state">State <span class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="clinic_state" name="clinic_state">

                                    </select>
                                    <div id="clinicStateError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="clinic_city">City <span class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="clinic_city" name="clinic_city">
                                    </select>
                                    <div id="clinicCityError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="clinic_pincode">Pin Code <span
                                            class="text-danger">
                                            *</span></label>
                                    <input class="form-control" type="text" id="clinic_pincode"
                                        name="clinic_pincode" placeholder="XXX XXX">
                                    <div id="clinicPincodeError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success float-end" id="saveClinicBtn">Save</button>
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
        $('#clinic_country').change(function() {
            var countryId = $(this).val();
            loadStates(countryId);
        });

        // Handle change event for state dropdown
        $('#clinic_state').change(function() {
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
                        $('#clinic_state').empty();
                        $('#clinic_state').append('<option value="">Select State</option>');
                        $.each(data, function(key, value) {
                            $('#clinic_state').append('<option value="' + key + '">' +
                                value + '</option>');
                        });
                        var initialStateId = $('#clinic_state').val();
                        loadCities(initialStateId);
                    }
                });
            } else {
                $('#clinic_state').empty();
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
                        $('#clinic_city').empty();
                        $('#clinic_city').append('<option value="">Select City</option>');
                        $.each(data, function(key, value) {
                            $('#clinic_city').append('<option value="' + key + '">' +
                                value + '</option>');
                        });
                    }
                });
            } else {
                $('#clinic_city').empty();
                $('#clinic_city').append('<option value="">Select City</option>');
            }
        }

        // Function to handle form submission
        $('#saveClinicBtn').click(function(event) {
            // Reset previous error messages
            resetErrors();
            event.preventDefault(); // Prevent default form submission

            // Validate form fields
            var isValid = true;

            // Gather form data
            var formData = {
                clinic_phone: $('#clinic_phone').val(),
                clinic_email: $('#clinic_email').val(),
                branch_active: $('input[name="branch_active"]:checked').val(),
                is_medicine_provided: $('input[name="is_medicine_provided"]:checked').val(),
                clinic_address1: $('#clinic_address1').val(),
                clinic_address2: $('#clinic_address2').val(),
                clinic_country: $('#clinic_country').val(),
                clinic_state: $('#clinic_state').val(),
                clinic_city: $('#clinic_city').val(),
                clinic_pincode: $('#clinic_pincode').val(),
                "_token": "{{ csrf_token() }}" // CSRF token for Laravel
            };

            isValid = validate_form();

            // Perform AJAX submit if form is valid
            if (isValid) {
                var form = $('#createClinicForm');
                var url = form.attr('action');
                var formDataClinic = form.serialize(); // Correct serialization of form data
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formDataClinic,
                    dataType: 'json',
                    success: function(response) {
                        // If successful, hide modal and show success message
                        $('#modal-right').modal('hide');
                        $('#successMessage').text('Clinic created successfully');
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

                        // Check if there are validation errors in the response
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;

                            // Handle clinic_email error
                            if (errors.hasOwnProperty('clinic_email')) {
                                $('#clinic_email').addClass('is-invalid');
                                $('#clinicEmailError').text(errors.clinic_email[0]);
                            }

                            // Handle clinic_phone error
                            if (errors.hasOwnProperty('clinic_phone')) {
                                $('#clinic_phone').addClass('is-invalid');
                                $('#clinicPhoneError').text(errors.clinic_phone[0]);
                            }

                            // Handle clinic_address1 error
                            if (errors.hasOwnProperty('clinic_address1')) {
                                $('#clinic_address1').addClass('is-invalid');
                                $('#clinicAddress1Error').text(errors
                                    .clinic_address1[0]);
                            }

                            // Handle clinic_address2 error
                            if (errors.hasOwnProperty('clinic_address2')) {
                                $('#clinic_address2').addClass('is-invalid');
                                $('#clinicAddress2Error').text(errors
                                    .clinic_address2[0]);
                            }

                            // Handle clinic_country error
                            if (errors.hasOwnProperty('clinic_country')) {
                                $('#clinicCountryError').text(errors.clinic_country[
                                    0]);
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
                                $('#clinic_pincode').addClass('is-invalid');
                                $('#clinicPincodeError').text(errors.clinic_pincode[
                                    0]);
                            }

                            // Scroll to the top of the modal to show the first error
                            $('#modal-right .modal-body').scrollTop(0);
                        }
                    }
                });
            }
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createClinicForm').trigger('reset');
            resetErrors();
        });

    });
</script>
