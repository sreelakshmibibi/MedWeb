<form id="createTechForm" method="post" action="{{ route('technicians.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Technician Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessagecreate" style="display:none;" class="alert alert-danger">
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tech_name" class="form-label">Technician Name<span
                                                        class="text-danger">*</span></label>                    
                                    <input class="form-control" type="text" id="tech_name" name="tech_name"
                                    placeholder="Technician Name">
                                    <div id="techNameError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tech_name" class="form-label">Technician Phone<span
                                                        class="text-danger">*</span></label>                    
                                    <input class="form-control" type="text" id="tech_phone" name="tech_phone"
                                    placeholder="Technician Phone">
                                    <div id="techPhoneError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lab_name" class="form-label">Lab Name<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="lab_name" name="lab_name"
                                            placeholder="Lab Name">
                                    <div id="labNameError" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lab_phone" class="form-label">Lab Phone<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="lab_phone" name="lab_phone"
                                            placeholder="Lab Phone">
                                    <div id="labPhoneError" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        <div>
                            
                        <div class="row">
                            <div class="form-group">
                                <label for="lab_address" class="form-label">Lab Address<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" type="text" id="lab_address" name="lab_address"
                                        placeholder="Lab Address"></textarea>
                                <div id="labAddressError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        </div>
                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <input name="status" type="radio" checked class="form-control with-gap" id="yes"
                                value="Y">
                            <label for="yes">Yes</label>
                            <input name="status" type="radio" class="form-control with-gap" id="no"
                                value="N">
                            <label for="no">No</label>
                            <div id="statusError" class="invalid-feedback"></div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="saveTechBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Save button click
        $('#saveTechBtn').click(function() {
            // Reset previous error messages
            $('#techNameError').text('');
            $('#techPhoneError').text('');
            $('#labNameError').text('');
            $('#labPhoneError').text('');
            $('#labAddressError').text('');

            // Validate form inputs
            var techName = $('#tech_name').val();
            var techPhone = $('#tech_phone').val();
            var labName = $('#lab_name').val();
            var labPhone = $('#lab_phone').val();
            var labAddress = $('#lab_address').val();

            // Basic client-side validation (you can add more as needed)
            if (techName.length == 0) {
                $('#tech_name').addClass('is-invalid');
                $('#techNameError').text('Technician name is required.');
                return; // Prevent further execution
            } else {
                $('#tech_name').removeClass('is-invalid');
                $('#techNameError').text('');
            }

            if (techPhone.length == 0) {
                $('#tech_phone').addClass('is-invalid');
                $('#techPhoneError').text('Phone number is required.');
                return; // Prevent further execution
            } else {
                $('#tech_phone').removeClass('is-invalid');
                $('#techPhoneError').text('');
            }
            
            if (labName.length == 0) {
                $('#lab_name').addClass('is-invalid');
                $('#labNameError').text('Lab name is required.');
                return; // Prevent further execution
            } else {
                $('#lab_name').removeClass('is-invalid');
                $('#labNameError').text('');
            }

            if (labPhone.length == 0) {
                $('#lab_phone').addClass('is-invalid');
                $('#labPhoneError').text('Lab phone number is required.');
                return; // Prevent further execution
            } else {
                $('#lab_phone').removeClass('is-invalid');
                $('#labPhoneError').text('');
            }

            if (labAddress.length == 0) {
                $('#lab_address').addClass('is-invalid');
                $('#labAddressError').text('Lab address is required.');
                return; // Prevent further execution
            } else {
                $('#lab_address').removeClass('is-invalid');
                $('#labAddressError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#createTechForm');
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // If successful, hide modal and show success message
                    if (response.success) {
                        $('#successMessage').text(response.success);
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                            $('#modal-right').modal('hide');
                            table.ajax.reload();
                    
                    }
                    if (response.error) {
                        $('#errorMessagecreate').text(response.error);
                        $('#errorMessagecreate').fadeIn().delay(3000)
                        .fadeOut(); 
                    }
                    
                    // location.reload();
                  
                },
                error: function(xhr) {
                    
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('name')) {
                        $('#tech_name').addClass('is-invalid');
                        $('#techNameError').text(errors.name[0]);
                    }

                    if (errors.hasOwnProperty('phone_number')) {
                        $('#tech_phone').addClass('is-invalid');
                        $('#techPhoneError').text(errors.phone_number[0]);
                    }
                    
                    if (errors.hasOwnProperty('lab_name')) {
                        $('#lab_name').addClass('is-invalid');
                        $('#labNameError').text(errors.lab_name[0]);
                    }
                    
                    if (errors.hasOwnProperty('lab_address')) {
                        $('#lab_address').addClass('is-invalid');
                        $('#labAddressError').text(errors.lab_address[0]);
                    }
                    if (errors.hasOwnProperty('lab_contact')) {
                        $('#lab_phone').addClass('is-invalid');
                        $('#labPhoneError').text(errors.lab_contact[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createTechForm').trigger('reset');
            $('#tech_name').removeClass('is-invalid');
            $('#tech_phone').removeClass('is-invalid');
            $('#lab_name').removeClass('is-invalid');
            $('#lab_phone').removeClass('is-invalid');
            $('#lab_address').removeClass('is-invalid');
            $('#techNameError').text('');
            $('#techPhoneError').text('');
            $('#labNameError').text('');
            $('#labPhoneError').text('');
            $('#labAddressError').text('');
        });
    });
</script>
