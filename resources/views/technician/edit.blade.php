<form id="editTechForm" method="post" action="{{ route('technicians.update') }}">
    @csrf
    <input type="hidden" id="edit_tech_id" name="edit_tech_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Edit Technician Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessage" style="display:none;" class="alert alert-danger">
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                    <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_tech_name" class="form-label">Technician Name<span
                                                        class="text-danger">*</span></label>                    
                                    <input class="form-control" type="text" id="edit_tech_name" name="edit_tech_name"
                                    placeholder="Technician Name">
                                    <div id="editTechNameError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_tech_name" class="form-label">Technician Phone<span
                                                        class="text-danger">*</span></label>                    
                                    <input class="form-control" type="text" id="edit_tech_phone" name="edit_tech_phone"
                                    placeholder="Technician Phone">
                                    <div id="editTechPhoneError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_lab_name" class="form-label">Lab Name<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="edit_lab_name" name="edit_lab_name"
                                            placeholder="Lab Name">
                                    <div id="editLabNameError" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lab_phone" class="form-label">Lab Phone<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="edit_lab_phone" name="edit_lab_phone"
                                            placeholder="Lab Phone">
                                    <div id="editLabPhoneError" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        <div>
                            
                        <div class="row">
                            <div class="form-group">
                                <label for="edit_lab_address" class="form-label">Lab Address<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" type="text" id="edit_lab_address" name="edit_lab_address"
                                        placeholder="Lab Address"></textarea>
                                <div id="editLabAddressError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        </div>
                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <input name="edit_status" type="radio" checked class="form-control with-gap" id="yes"
                                value="Y">
                            <label for="yes">Yes</label>
                            <input name="edit_status" type="radio" class="form-control with-gap" id="no"
                                value="N">
                            <label for="no">No</label>
                            <div id="editStatusError" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="updateTechBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Update button click
        $('#updateTechBtn').click(function() {
            // Reset previous error messages
            $('#edit_tech_name').removeClass('is-invalid');
            $('#edit_tech_phone').removeClass('is-invalid');
            $('#edit_lab_name').removeClass('is-invalid');
            $('#edit_lab_phone').removeClass('is-invalid');
            $('#edit_lab_address').removeClass('is-invalid');
            $('#editTechNameError').text('');
            $('#editTechPhoneError').text('');
            $('#editLabNameError').text('');
            $('#editLabPhoneError').text('');
            $('#editLabAddressError').text('');
            // Validate form inputs
            var techName = $('#edit_tech_name').val();
            var techPhone = $('#edit_tech_phone').val();
            var labName = $('#edit_lab_name').val();
            var labPhone = $('#edit_lab_phone').val();
            var labAddress = $('#edit_lab_address').val();


            // Basic client-side validation (you can add more as needed)
            if (techName.length == 0) {
                $('#edit_tech_name').addClass('is-invalid');
                $('#editTechNameError').text('Technician name is required.');
                return; // Prevent further execution
            } else {
                $('#edit_tech_name').removeClass('is-invalid');
                $('#editTechNameError').text('');
            }

            if (techPhone.length == 0) {
                $('#edit_tech_phone').addClass('is-invalid');
                $('#editTechPhoneError').text('Phone number is required.');
                return; // Prevent further execution
            } else {
                $('#edit_tech_phone').removeClass('is-invalid');
                $('#editTechPhoneError').text('');
            }
            
            if (labName.length == 0) {
                $('#edit_lab_name').addClass('is-invalid');
                $('#editLabNameError').text('Lab name is required.');
                return; // Prevent further execution
            } else {
                $('#edit_lab_name').removeClass('is-invalid');
                $('#editLabNameError').text('');
            }

            if (labPhone.length == 0) {
                $('#edit_lab_phone').addClass('is-invalid');
                $('#editLabPhoneError').text('Lab phone number is required.');
                return; // Prevent further execution
            } else {
                $('#edit_lab_phone').removeClass('is-invalid');
                $('#editLabPhoneError').text('');
            }

            if (labAddress.length == 0) {
                $('#edit_lab_address').addClass('is-invalid');
                $('#editLabAddressError').text('Lab address is required.');
                return; // Prevent further execution
            } else {
                $('#edit_lab_address').removeClass('is-invalid');
                $('#editLabAddressError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editTechForm');
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // If successful, hide modal and show success message
                    $('#modal-edit').modal('hide');
                    if (response.success) {
                        $('#successMessage').text(response.success);
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                    }

                    // location.reload(); // Refresh the page or update the table as needed
                    table.ajax.reload();
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON.error);
                    if (xhr.responseJSON.error) {
                        $('#errorMessage').text(xhr.responseJSON.error);
                        $('#errorMessage').fadeIn().delay(3000)
                            .fadeOut();
                    }
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('name')) {
                        $('#edit_tech_name').addClass('is-invalid');
                        $('#editTechNameError').text(errors.name[0]);
                    }

                    if (errors.hasOwnProperty('phone_number')) {
                        $('#edit_tech_phone').addClass('is-invalid');
                        $('#editTechPhoneError').text(errors.phone_number[0]);
                    }
                    
                    if (errors.hasOwnProperty('lab_name')) {
                        $('#edit_lab_name').addClass('is-invalid');
                        $('#editLabNameError').text(errors.lab_name[0]);
                    }
                    
                    if (errors.hasOwnProperty('lab_address')) {
                        $('#edit_lab_address').addClass('is-invalid');
                        $('#editLabAddressError').text(errors.lab_address[0]);
                    }
                    if (errors.hasOwnProperty('lab_contact')) {
                        $('#edit_lab_phone').addClass('is-invalid');
                        $('#editLabPhoneError').text(errors.lab_contact[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editDepartmentForm').trigger('reset');
            $('#editleave_type').removeClass('is-invalid');
            $('#editleave_from').removeClass('is-invalid');
            $('#editleave_to').removeClass('is-invalid');
            $('#editreason').removeClass('is-invalid');
            $('#editleaveTypeError').text('');
            $('#editreasonError').text('');
            $('#editleaveFromError').text('');
            $('#editleaveToError').text('');
        });
    });
</script>
