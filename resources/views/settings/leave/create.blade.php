<form id="createLeaveForm" method="post" action="{{ route('leave.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Leave Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessagecreate" style="display:none;" class="alert alert-danger">
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="leave_type">Leave Type <span class="text-danger">
                                    *</span></label>
                            <select class="form-control" id="leave_type" name="leave_type" required>
                                <option value="">Select type</option>
                                <option value="Casual Leave">Casual Leave</option>
                                <option value="Medical Leave">Medical Leave</option>
                                <option value="Loss of Pay">Loss of Pay</option>
                                <option value="Other">Other</option>
                            </select>
                            <div id="leaveTypeError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_from" class="form-label">From <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="leave_from" name="leave_from"
                                        placeholder="Leave From Date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>">
                                    <div id="leaveFromError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_to" class="form-label">To <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="leave_to" name="leave_to"
                                        placeholder="Leave To Date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>">
                                    <div id="leaveToError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="reason">Reason</label>
                            <textarea class="form-control" id="reason" name="reason" placeholder="Leave Reason" required></textarea>
                            <div id="reasonError" class="invalid-feedback"></div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="saveLeaveBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Save button click
        $('#saveLeaveBtn').click(function() {
            // Reset previous error messages
            $('#leaveTypeError').text('');
            $('#reasonError').text('');
            $('#leaveFromError').text('');
            $('#leaveToError').text('');

            // Validate form inputs
            var leaveType = $('#leave_type').val();
            var reason = $('#reason').val();
            var leaveFrom = $('#leave_from').val();
            var leaveTo = $('#leave_to').val();

            // Basic client-side validation (you can add more as needed)
            if (leaveType.length == 0) {
                $('#leave_type').addClass('is-invalid');
                $('#leaveTypeError').text('Leave Type is required.');
                return; // Prevent further execution
            } else {
                $('#leave_type').removeClass('is-invalid');
                $('#leaveTypeError').text('');
            }

            if (reason.length == 0) {
                $('#reason').addClass('is-invalid');
                $('#reasonError').text('Reason is required.');
                return; // Prevent further execution
            } else {
                $('#reason').removeClass('is-invalid');
                $('#reasonError').text('');
            }
            var currentDate = new Date().toISOString().split('T')[0];

            if (leaveFrom.length == 0) {
                $('#leave_from').addClass('is-invalid');
                $('#leaveFromError').text('From Date is required.');
                return; // Prevent further execution
            }  else if (leaveFrom < currentDate) { // Compare leaveFrom with currentDate
                $('#leave_from').addClass('is-invalid');
                $('#leaveFromError').text('From Date cannot be in the past.');
                return; // Prevent further execution
            } else {
                $('#leave_from').removeClass('is-invalid');
                $('#leaveFromError').text('');
            }

            if (leaveTo.length == 0) {
                $('#leave_to').addClass('is-invalid');
                $('#leaveToError').text('To date is required.');
                return; // Prevent further execution
            } else if (leaveTo < currentDate) { // Compare leaveFrom with currentDate
                $('#leave_to').addClass('is-invalid');
                $('#leaveToError').text('To Date cannot be in the past.');
                return; // Prevent further execution
            } else if ( leaveTo < leaveFrom ){
                $('#leave_to').addClass('is-invalid');
                $('#leaveToError').text('To Date cannot be less than from date.');
                return; // Prevent further execution
            }else {
                $('#leave_to').removeClass('is-invalid');
                $('#leaveToError').text('');
            }
            

            // If validation passed, submit the form via AJAX
            var form = $('#createLeaveForm');
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

                    if (errors.hasOwnProperty('leave_type')) {
                        $('#leave_type').addClass('is-invalid');
                        $('#leaveTypeError').text(errors.leave_type[0]);
                    }

                    if (errors.hasOwnProperty('leave_from')) {
                        $('#leave_from').addClass('is-invalid');
                        $('#leaveFromError').text(errors.leave_from[0]);
                    }
                    
                    if (errors.hasOwnProperty('leave_to')) {
                        $('#leave_to').addClass('is-invalid');
                        $('#leaveToError').text(errors.leave_to[0]);
                    }
                    
                    if (errors.hasOwnProperty('leave_reason')) {
                        $('#reason').addClass('is-invalid');
                        $('#reasonError').text(errors.leave_reason[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createLeaveForm').trigger('reset');
            $('#leave_type').removeClass('is-invalid');
            $('#leave_from').removeClass('is-invalid');
            $('#leave_to').removeClass('is-invalid');
            $('#reason').removeClass('is-invalid');
            $('#leaveTypeError').text('');
            $('#reasonError').text('');
            $('#leaveFromError').text('');
            $('#leaveToError').text('');
        });
    });
</script>
