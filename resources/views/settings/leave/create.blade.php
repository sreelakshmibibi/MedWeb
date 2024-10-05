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
                                @foreach ($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->id }}"> {{ $leaveType->type }}</option>
                                @endforeach
                            </select>
                            <div id="leaveTypeError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group" id="compensationDateGroup" style="display: none;">
                            <label for="compensation_date" class="form-label">Compensation Date Worked On <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="compensation_date" name="compensation_date" placeholder="Compensation Date">
                            <div id="compensationDateError" class="invalid-feedback"></div>
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

                        <div class="form-group">
                            <label class="form-label" for="leave_file">Documents</label>
                            <input class="form-control @error('leave_file') is-invalid @enderror" type="file"
                                id="leave_file" name="leave_file" placeholder="logo">
                            @error('leave_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
    $('#leave_type').change(function() {
        var selectedType = $(this).val();
        // if (selectedType) {
            if (selectedType == 19) { // Compensatory Leave
                $('#leave_to').prop('disabled', true); // Disable leave_to
                $('#leave_to').val($('#leave_from').val()); // Set leave_to to leave_from
                $('#compensationDateGroup').show(); // Show compensation date input
            } else {
                $('#leave_to').prop('disabled', false); // Enable leave_to
                $('#compensationDateGroup').hide(); // Hide compensation date input
            }
        // } else {
        //     $('#leave_to').prop('disabled', false); // Enable leave_to
        //     $('#compensationDateGroup').hide(); // Hide compensation date input
        // }
    });

    $('#compensation_date').change(function() {
    var compensationDate = $(this).val();
    var userId = $('#leave_type').val(); // Assuming you have the user ID available

    if (compensationDate.length == 0) {
        return; // No date selected, do nothing
    }

    // AJAX call to validate the compensation date
    $.ajax({
        url: '/check-compensation-date', // Create an endpoint for this
        method: 'POST',
        data: {
            compensation_date: compensationDate,
            user_id: userId // Send the user ID if needed
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success response
            $('#compensation_date').removeClass('is-invalid');
            $('#compensationDateError').text('');
        },
        error: function(xhr) {
            // Handle error response
            const errors = xhr.responseJSON.errors;
            if (errors) {
                if (errors.holiday) {
                    $('#compensation_date').addClass('is-invalid');
                    $('#compensationDateError').text(errors.holiday);
                } else if (errors.attendance) {
                    $('#compensation_date').addClass('is-invalid');
                    $('#compensationDateError').text(errors.attendance);
                } else if (errors.leave) {
                    $('#compensation_date').addClass('is-invalid');
                    $('#compensationDateError').text(errors.leave);
                }
            }
        }
    });
});


    // Handle Save button click
    $('#saveLeaveBtn').click(function() {
        // Reset previous error messages
        $('#leaveTypeError').text('');
        $('#reasonError').text('');
        $('#leaveFromError').text('');
        $('#leaveToError').text('');
        $('#compensationDateError').text('');

        // Validate form inputs
        var leaveType = $('#leave_type').val();
        var reason = $('#reason').val();
        var leaveFrom = $('#leave_from').val();
        var leaveTo = $('#leave_to').val();
        var compensationDate = $('#compensation_date').val();

        // Basic client-side validation
        if (leaveType.length == 0) {
            $('#leave_type').addClass('is-invalid');
            $('#leaveTypeError').text('Leave Type is required.');
            return; 
        } else {
            $('#leave_type').removeClass('is-invalid');
            $('#leaveTypeError').text('');
        }

        if (reason.length == 0) {
            $('#reason').addClass('is-invalid');
            $('#reasonError').text('Reason is required.');
            return; 
        } else {
            $('#reason').removeClass('is-invalid');
            $('#reasonError').text('');
        }

        if (leaveType == 19) { // Compensatory Leave
            if (compensationDate.length == 0) {
                $('#compensation_date').addClass('is-invalid');
                $('#compensationDateError').text('Compensation Date is required.');
                return; 
            } else {
                $('#compensation_date').removeClass('is-invalid');
                $('#compensationDateError').text('');

                // Check if compensationDate is before leaveFrom
                if (new Date(compensationDate) >= new Date(leaveFrom)) {
                    $('#compensation_date').addClass('is-invalid');
                    $('#compensationDateError').text('Compensation Date must be before From Date.');
                    return; 
                }
            }
            $('#leave_to').val(leaveFrom); // Ensure leave_to is set to leave_from
        } else {
            if (leaveTo.length == 0) {
                $('#leave_to').addClass('is-invalid');
                $('#leaveToError').text('To date is required.');
                return; 
            } else if (leaveTo < new Date().toISOString().split('T')[0]) {
                $('#leave_to').addClass('is-invalid');
                $('#leaveToError').text('To Date cannot be in the past.');
                return; 
            } else if (leaveTo < leaveFrom) {
                $('#leave_to').addClass('is-invalid');
                $('#leaveToError').text('To Date cannot be less than From Date.');
                return; 
            } else {
                $('#leave_to').removeClass('is-invalid');
                $('#leaveToError').text('');
            }
        }

        var currentDate = new Date().toISOString().split('T')[0];

        if (leaveFrom.length == 0) {
            $('#leave_from').addClass('is-invalid');
            $('#leaveFromError').text('From Date is required.');
            return; 
        } else if (leaveFrom < currentDate) {
            $('#leave_from').addClass('is-invalid');
            $('#leaveFromError').text('From Date cannot be in the past.');
            return; 
        } else {
            $('#leave_from').removeClass('is-invalid');
            $('#leaveFromError').text('');
        }

        // Temporarily enable leave_to to include it in the payload
        $('#leave_to').prop('disabled', false); 

        // If validation passed, submit the form via AJAX
        var form = $('#createLeaveForm');
        var url = form.attr('action');
        var formData = new FormData($('#createLeaveForm')[0]);

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            contentType: false, // Important: Prevent jQuery from overriding content type
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#successMessage').text(response.success);
                    $('#successMessage').fadeIn().delay(3000).fadeOut();
                    $('#modal-right').modal('hide');
                    table.ajax.reload();
                }
                if (response.error) {
                    $('#errorMessagecreate').text(response.error);
                    $('#errorMessagecreate').fadeIn().delay(3000).fadeOut();
                }
            },
            error: function(xhr) {
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
        if (leaveType == 19) {
        // Disable leave_to again after the AJAX request
            $('#leave_to').prop('disabled', true);
        }
    });

    // Reset form and errors on modal close
    $('#modal-right').on('hidden.bs.modal', function() {
        $('#createLeaveForm').trigger('reset');
        $('#leave_type').removeClass('is-invalid');
        $('#leave_from').removeClass('is-invalid');
        $('#leave_to').removeClass('is-invalid');
        $('#reason').removeClass('is-invalid');
        $('#compensation_date').removeClass('is-invalid');
        $('#leaveTypeError').text('');
        $('#reasonError').text('');
        $('#leaveFromError').text('');
        $('#leaveToError').text('');
        $('#compensationDateError').text('');
        $('#compensationDateGroup').hide(); 
    });
});

</script>
