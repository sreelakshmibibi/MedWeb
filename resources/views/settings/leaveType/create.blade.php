<form id="createLeaveTypeForm" method="post" action="{{ route('settings.leaveType.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Leave Type Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessagecreate" style="display:none;" class="alert alert-danger"></div>
                
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="type">Leave Type <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="type" name="type" placeholder="Leave Type" required>
                            <div id="typeError" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Description"></textarea>
                            <div id="descriptionError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="duration">Duration <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="duration" name="duration" placeholder="Duration" required>
                                    <div id="durationError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="duration_type">Duration Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="duration_type" name="duration_type" required>
                                        <option value="day">Day</option>
                                        <option value="month">Month</option>
                                    </select>
                                    <div id="durationTypeError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="payment_status">Payment Required? <span class="text-danger">*</span></label>
                            <select class="form-control" id="payment_status" name="payment_status" required>
                                <option value="Paid">Paid</option>
                                <option value="Partially Paid">Partially Paid</option>
                                <option value="Not Paid">Not Paid</option>
                            </select>
                            <div id="paymentStatusError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label">Active</label>
                            <input name="status" type="radio" checked class="form-check-input" id="yes" value="Y">
                            <label class="form-check-label" for="yes">Yes</label>
                            <input name="status" type="radio" class="form-check-input" id="no" value="N">
                            <label class="form-check-label" for="no">No</label>
                            <div id="statusError" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="saveLeaveTypeBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Include custom JavaScript file -->
<script src="{{ asset('js/leaveTypes.js') }}"></script>

<script>
    $(function() {
        // Handle Save button click
        $('#saveLeaveTypeBtn').click(function() {
            // Reset previous error messages
            $('#typeError, #descriptionError, #durationError, #durationTypeError, #paymentStatusError, #statusError').text('');
            $('#errorMessagecreate').hide();

            // Validate form inputs
            var type = $('#type').val();
            var description = $('#description').val();
            var duration = $('#duration').val();
            var durationType = $('#duration_type').val();
            var paymentStatus = $('#payment_status').val();
            var status = $('input[name="status"]:checked').val();
            let valid = true;
            // Basic client-side validation
            if (type.length === 0) {
                $('#type').addClass('is-invalid');
                $('#typeError').text('Leave Type is required.');
                valid = false; 
            } else {
                $('#type').removeClass('is-invalid');
            }

            
            if (duration.length === 0) {
                $('#duration').addClass('is-invalid');
                $('#durationError').text('Duration is required.');
                valid = false;  
            } else {
                $('#duration').removeClass('is-invalid');
            }

            if (!durationType) {
                $('#duration_type').addClass('is-invalid');
                $('#durationTypeError').text('Duration Type is required.');
                valid = false; 
            } else {
                $('#duration_type').removeClass('is-invalid');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#createLeaveTypeForm');
            var url = form.attr('action');
            var formData = form.serialize();
            if (valid) {
                $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // If successful, hide modal and show success message
                    if (response.success) {
                        $('#successMessage').text(response.success);
                        $('#successMessage').fadeIn().delay(3000).fadeOut(); // Show for 3 seconds
                        $('#modal-right').modal('hide');
                        table.ajax.reload();
                    }
                    if (response.error) {
                        $('#errorMessagecreate').text(response.error);
                        $('#errorMessagecreate').fadeIn().delay(3000).fadeOut(); 
                    }
                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('type')) {
                        $('#type').addClass('is-invalid');
                        $('#typeError').text(errors.type[0]);
                    }

                    if (errors.hasOwnProperty('description')) {
                        $('#description').addClass('is-invalid');
                        $('#descriptionError').text(errors.description[0]);
                    }

                    if (errors.hasOwnProperty('duration')) {
                        $('#duration').addClass('is-invalid');
                        $('#durationError').text(errors.duration[0]);
                    }

                    if (errors.hasOwnProperty('duration_type')) {
                        $('#duration_type').addClass('is-invalid');
                        $('#durationTypeError').text(errors.duration_type[0]);
                    }

                    if (errors.hasOwnProperty('payment_status')) {
                        $('#paymentStatusError').text(errors.payment_status[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#statusError').text(errors.status[0]);
                    }
                }
            });
        
            }
           
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createLeaveTypeForm').trigger('reset');
            $('#type, #description, #duration, #duration_type').removeClass('is-invalid');
            $('#typeError, #descriptionError, #durationError, #durationTypeError, #paymentStatusError, #statusError').text('');
            $('#errorMessagecreate').hide();
        });
    });
</script>
