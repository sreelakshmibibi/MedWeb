<form id="editHolidayForm" method="post" action="{{ route('holidays.update') }}">
    @csrf
    <input type="hidden" id="edit_holiday_id" name="edit_holiday_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-holiday-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Edit Holiday Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessage" style="display:none;" class="alert alert-danger">
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- Department Name -->
                        <div class="form-group">
                            <label class="form-label" for="edit_holiday_on">Date <span class="text-danger">
                                    *</span></label>
                                <input type="date" class="form-control" id="edit_holiday_on" name="edit_holiday_on"
                                placeholder="Holiday On"  value="<?php echo date('Y-m-d'); ?>" required>
                            <div id="editDateError" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_reason">Reason <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="edit_reason" name="edit_reason"
                                placeholder="Reason for holiday">
                            <div id="editReasonError" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_branches">Applicable To <span class="text-danger">
                                    *</span></label>
                            <select multiple="true" class="form-control " type="text" id="edit_branches" name="edit_branches[]">
                                <option value="" selected>All Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch['id'] }}"> {{ $branch['name'] }}</option>
                                @endforeach
                            </select>
                            <div id="editBranchError" class="invalid-feedback"></div>
                        </div>
                        

                        <!-- Status -->
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
                    <button type="button" class="btn btn-success float-end" id="updateHolidayBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        $('#errorMessage').text(''); 
        // Handle Update button click
        $('#updateHolidayBtn').click(function() {
            // Reset previous error messages
            $('#edit_holiday_on').removeClass('is-invalid');
            $('#edit_reason').removeClass('is-invalid');
            $('#edit_branches').removeClass('is-invalid');
            $('#edit_holiday_on').next('.invalid-feedback').text('');
            $('#edit_reason').next('.invalid-feedback').text('');
            $('#edit_branches').next('.invalid-feedback').text('');
            $('#errorMessage').text(''); 
            $('#statusError').text('');

            // Validate form inputs
            var holidayOn = $('#edit_holiday_on').val();
            var reason = $('#edit_reason').val();
            var status = $('input[name="edit_status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (holidayOn.length === 0) {
                $('#edit_holiday_on').addClass('is-invalid');
                $('#editDateError').text('Holiday Date is required.');
                return; // Prevent further execution
            } else {
                $('#edit_holiday_on').removeClass('is-invalid');
                $('#editDateError').text('');
            }
            if (reason.length === 0) {
                $('#edit_reason').addClass('is-invalid');
                $('#editReasonError').text('Holiday Reason is required.');
                return; // Prevent further execution
            } else {
                $('#edit_reason').removeClass('is-invalid');
                $('#editReasonError').text('');
            }


            // If validation passed, submit the form via AJAX
            var form = $('#editHolidayForm');
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#successMessage').text(response.success).fadeIn().delay(3000).fadeOut();
                        $('#modal-holiday-edit').modal('hide');
                        table.ajax.reload();
                    }
                },
                error: function(xhr) {
                    $('#errorMessage').text(''); // Clear previous messages

                    if (xhr.status === 409) {
                        $('#errorMessage').text(xhr.responseJSON.error).show(); // Show conflict error
                    } else {
                        var errors = xhr.responseJSON.errors;
                        if (errors.hasOwnProperty('holiday_on')) {
                            $('#edit_holiday_on').addClass('is-invalid');
                            $('#editDateError').text(errors.holiday_on[0]);
                        }
                        if (errors.hasOwnProperty('reason')) {
                            $('#edit_reason').addClass('is-invalid');
                            $('#editReasonError').text(errors.reason[0]);
                        }
                    }
                }
            });
    
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editHolidayForm').trigger('reset');
            $('#edit_holiday_on').removeClass('is-invalid');
            $('#edit_reason').removeClass('is-invalid');
            $('#edit_branches').removeClass('is-invalid');
            $('#edit_holiday_on').next('.invalid-feedback').text('');
            $('#edit_reason').next('.invalid-feedback').text('');
            $('#edit_branches').next('.invalid-feedback').text('');
            $('#statusError').text('');
        });

        // Pre-populate form fields when modal opens for editing
     });
</script>
