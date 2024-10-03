<form id="editLeaveTypeForm" method="post" action="{{ route('settings.leaveType.update') }}">
    @csrf
    <input type="hidden" id="edit_leaveType_id" name="edit_leaveType_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Edit Leave Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessage" style="display:none;" class="alert alert-danger"></div>
                
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="edit_type">Leave Type <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="edit_type" name="edit_type" placeholder="Leave Type" required>
                            <div id="editTypeError" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_description">Description</label>
                            <textarea class="form-control" id="edit_description" name="edit_description" placeholder="Description"></textarea>
                            <div id="editDescriptionError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_duration">Duration <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="edit_duration" name="edit_duration" placeholder="Duration" required>
                                    <div id="editDurationError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_duration_type">Duration Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="edit_duration_type" name="edit_duration_type" required>
                                        <option value="day">Day</option>
                                        <option value="month">Month</option>
                                    </select>
                                    <div id="editDurationTypeError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="edit_payment_status">Payment Required? <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_payment_status" name="edit_payment_status" required>
                                <option value="Paid">Paid</option>
                                <option value="Partially Paid">Partially Paid</option>
                                <option value="Not Paid">Not Paid</option>
                            </select>
                            <div id="editPaymentStatusError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label">Active</label>
                            <input name="edit_status" type="radio" checked class="form-check-input" id="edit_yes" value="Y">
                            <label class="form-check-label" for="edit_yes">Yes</label>
                            <input name="edit_status" type="radio" class="form-check-input" id="edit_no" value="N">
                            <label class="form-check-label" for="edit_no">No</label>
                            <div id="editStatusError" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="updateLeaveTypeBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Update button click
        $('#updateLeaveTypeBtn').click(function() {
            // Reset previous error messages
            $('#errorMessage').hide();
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            // Validate form inputs
            var leaveTypeId = $('#edit_leaveType_id').val();
            var status = $('input[name="edit_status"]:checked').val();

            // Basic client-side validation
            if ($('#edit_type').val().length === 0) {
                $('#edit_type').addClass('is-invalid');
                $('#editTypeError').text('Leave Type is required.');
                return;
            }

            if ($('#edit_duration').val().length === 0) {
                $('#edit_duration').addClass('is-invalid');
                $('#editDurationError').text('Duration is required.');
                return;
            }

            if (!status) {
                $('#editStatusError').text('Status is required.');
                return;
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editLeaveTypeForm');
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#modal-edit').modal('hide');
                        $('#successMessage').text(response.success).fadeIn().delay(3000).fadeOut();
                        table.ajax.reload(); // Reload the table
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        if (errors.hasOwnProperty('type')) {
                            $('#edit_type').addClass('is-invalid');
                            $('#editTypeError').text(errors.type[0]);
                        }
                        if (errors.hasOwnProperty('duration')) {
                            $('#edit_duration').addClass('is-invalid');
                            $('#editDurationError').text(errors.duration[0]);
                        }
                        if (errors.hasOwnProperty('status')) {
                            $('#editStatusError').text(errors.status[0]);
                        }
                    } else {
                        $('#errorMessage').text('Failed to update leave type.').fadeIn().delay(3000).fadeOut();
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editLeaveTypeForm').trigger('reset');
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        });
    });
</script>
