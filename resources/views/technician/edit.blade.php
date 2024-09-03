<form id="editLeaveForm" method="post" action="{{ route('leave.update') }}">
    @csrf
    <input type="hidden" id="edit_leave_id" name="edit_leave_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Edit Leave Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessage" style="display:none;" class="alert alert-danger">
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="editleave_type">Leave Type <span class="text-danger">
                                    *</span></label>
                            <select class="form-control" id="editleave_type" name="editleave_type">
                                <option value="" disabled selected>Select type</option>
                                <option value="Casual Leave" >Casual Leave</option>
                                <option value="Medical Leave">Medical Leave</option>
                                <option value="Loss of Pay">Loss of Pay</option>
                                <option value="Other">Other</option>
                            </select>
                            <div id="editleaveTypeError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editleave_from" class="form-label">From <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="editleave_from" name="editleave_from"
                                        placeholder="Leave From Date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>">
                                    <div id="editleaveFromError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editleave_to" class="form-label">To <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="editleave_to" name="editleave_to"
                                        placeholder="Leave To Date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>">
                                    <div id="editleaveToError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="editreason">Reason</label>
                            <textarea class="form-control" id="editreason" name="editreason" placeholder="Leave Reason"></textarea>
                            <div id="reasonError" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="updateLeaveBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Update button click
        $('#updateLeaveBtn').click(function() {
            // Reset previous error messages
            $('#editleave_type').removeClass('is-invalid');
            $('#editleave_type').next('.invalid-feedback').text('');
            $('#editleave_from').removeClass('is-invalid');
            $('#editleave_to').removeClass('is-invalid');
            $('#editreason').removeClass('is-invalid');
            $('#editleaveTypeError').text('');
            $('#editreasonError').text('');
            $('#editleaveFromError').text('');
            $('#editleaveToError').text('');

            // Validate form inputs
            var leaveType = $('#editleave_type').val();
            var reason = $('#editreason').val();
            var leaveFrom = $('#editleave_from').val();
            var leaveTo = $('#editleave_to').val();

            // Basic client-side validation (you can add more as needed)
            if (leaveType.length === 0) {
                $('#leave_type').addClass('is-invalid');
                $('#leaveTypeError').text('Leave Type is required.');
                return; // Prevent further execution
            } else {
                $('#leave_type').removeClass('is-invalid');
                $('#leaveTypeError').text('');
            }

            if (reason.length === 0) {
                $('#reason').addClass('is-invalid');
                $('#reasonError').text('Reason is required.');
                return; // Prevent further execution
            } else {
                $('#reason').removeClass('is-invalid');
                $('#reasonError').text('');
            }

            if (leaveFrom.length === 0) {
                $('#leave_from').addClass('is-invalid');
                $('#leaveFromError').text('From Date is required.');
                return; // Prevent further execution
            } else {
                $('#leave_from').removeClass('is-invalid');
                $('#leaveFromError').text('');
            }

            if (leaveTo.length === 0) {
                $('#leave_to').addClass('is-invalid');
                $('#leaveToError').text('To date is required.');
                return; // Prevent further execution
            } else {
                $('#leave_to').removeClass('is-invalid');
                $('#leaveToError').text('');
            }
            
            // If validation passed, submit the form via AJAX
            var form = $('#editLeaveForm');
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

                    if (errors.hasOwnProperty('leave_type')) {
                        $('#editleave_type').addClass('is-invalid');
                        $('#editleaveTypeError').text(errors.leave_type[0]);
                    }

                    if (errors.hasOwnProperty('leave_from')) {
                        $('#editleave_from').addClass('is-invalid');
                        $('#editleaveFromError').text(errors.leave_from[0]);
                    }
                    
                    if (errors.hasOwnProperty('leave_to')) {
                        $('#editleave_to').addClass('is-invalid');
                        $('#editleaveToError').text(errors.leave_to[0]);
                    }
                    
                    if (errors.hasOwnProperty('leave_reason')) {
                        $('#editreason').addClass('is-invalid');
                        $('#editreasonError').text(errors.leave_reason[0]);
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
