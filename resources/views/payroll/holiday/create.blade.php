<form id="createHolidayForm" method="post" action="{{ route('holidays.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Add Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessagecreate" style="display:none;" class="alert alert-danger">
                </div>
                
                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- Department Name -->
                        <div class="form-group">
                            <label class="form-label" for="holiday_on">Date <span class="text-danger">
                                    *</span></label>
                                <input type="date" class="form-control" id="holiday_on" name="holiday_on"
                                placeholder="Holiday On"  value="<?php echo date('Y-m-d'); ?>" required>
                            <div id="dateError" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="reason">Reason <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="reason" name="reason"
                                placeholder="Reason for holiday">
                            <div id="reasonError" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="branches">Applicable To <span class="text-danger">
                                    *</span></label>
                            <select multiple="true" class="form-control " type="text" id="branches" name="branches[]">
                                <option value="" selected>All Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch['id'] }}"> {{ $branch['name'] }}</option>
                                @endforeach
                            </select>
                            <div id="branchError" class="invalid-feedback"></div>
                        </div>
                        

                        <!-- Status -->
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
                    <button type="button" class="btn btn-success float-end" id="saveHolidayBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Include custom JavaScript file -->
<script src="{{ asset('js/holidays.js') }}"></script>

<script>
    $(function() {
        // Handle Save button click
        $('#saveHolidayBtn').click(function() {
            $('#errorMessagecreate').text('');
            // Reset previous error messages
            $('#departmentError').text('');
            $('#statusError').text('');

            // Validate form inputs
            var holidayOn = $('#holiday_on').val();
            var reason = $('#reason').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (holidayOn.length === 0) {
                $('#holiday_on').addClass('is-invalid');
                $('#dateError').text('Holiday Date is required.');
                return; // Prevent further execution
            } else {
                $('#holiday_on').removeClass('is-invalid');
                $('#dateError').text('');
            }
            if (reason.length === 0) {
                $('#reason').addClass('is-invalid');
                $('#reasonError').text('Holiday reason is required.');
                return; // Prevent further execution
            } else {
                $('#reason').removeClass('is-invalid');
                $('#reasonError').text('');
            }

            if (!status) {
                $('#statusError').text('Status is required.');
                return; // Prevent further execution
            } else {
                $('#statusError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#createHolidayForm');
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
                        $('#modal-right').modal('hide');
                        table.ajax.reload();
                    }
                },
                error: function(xhr) {
                    // Clear any previous error messages
                    $('#errorMessagecreate').text('');

                    if (xhr.status === 409) {
                        // Show the conflict error message
                        $('#errorMessagecreate').text(xhr.responseJSON.error).show();
                    } else {
                        // Handle validation errors
                        var errors = xhr.responseJSON.errors;
                        if (errors.hasOwnProperty('holiday_on')) {
                            $('#holiday_on').addClass('is-invalid');
                            $('#dateError').text(errors.holiday_on[0]);
                        }
                        if (errors.hasOwnProperty('reason')) {
                            $('#reason').addClass('is-invalid');
                            $('#reasonError').text(errors.reason[0]);
                        }
                        if (errors.hasOwnProperty('status')) {
                            $('#statusError').text(errors.status[0]);
                        }
                    }
                }
            });
    
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createHolidayForm').trigger('reset'); // Reset the form fields
            $('#holiday_on').removeClass('is-invalid'); // Remove invalid class
            $('#reason').removeClass('is-invalid'); // Remove invalid class
            $('#branches').removeClass('is-invalid'); // Remove invalid class for branches
            $('#dateError').text(''); // Clear error messages
            $('#reasonError').text('');
            $('#branchError').text('');
            $('#statusError').text(''); // Clear status error message

            // Reset radio buttons if necessary
            $('input[name="status"][value="Y"]').prop('checked', true); // Default to "Yes"
        });
    });
</script>
