<form id="editDepartmentForm" method="post" action="{{ route('settings.department.update') }}">
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
                                <option value="Strip">Casual Leave</option>
                                <option value="Strip">Medical Leave</option>
                                <option value="Strip">Loss of Pay</option>
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="total_days">Total Days</label>
                                    <input class="form-control" type="text" id="total_days" name="total_days"
                                        placeholder="number of days" readonly>
                                    <div id="daysError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="remaining">Remaining Leaves</label>
                                    <input class="form-control" type="text" id="remaining" name="remaining"
                                        placeholder="remaining leaves" readonly>
                                    <div id="remainingError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="editreason">Reason</label>
                            <textarea class="form-control" id="editreason" name="editreason" placeholder="Leave Reason"></textarea>
                            <div id="reasonError" class="invalid-feedback"></div>
                        </div>

                        <!-- Status -->
                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <input name="status" type="radio" checked class="form-control with-gap"
                                id="edityes" value="Y">
                            <label for="edityes">Yes</label>
                            <input name="status" type="radio" class="form-control with-gap" id="editno"
                                value="N">
                            <label for="editno">No</label>
                            <div id="editstatusError" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="updateDepartmentBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Update button click
        $('#updateDepartmentBtn').click(function() {
            // Reset previous error messages
            $('#edit_department').removeClass('is-invalid');
            $('#edit_department').next('.invalid-feedback').text('');
            $('#statusError').text('');

            // Validate form inputs
            var department = $('#edit_department').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (department.length === 0) {
                $('#edit_department').addClass('is-invalid');
                $('#edit_department').next('.invalid-feedback').text('Department name is required.');
                return; // Prevent further execution
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editDepartmentForm');
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

                    if (errors.hasOwnProperty('department')) {
                        $('#edit_department').addClass('is-invalid');
                        $('#edit_department').next('.invalid-feedback').text(errors
                            .department[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#statusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editDepartmentForm').trigger('reset');
            $('#edit_department').removeClass('is-invalid');
            $('#edit_department').next('.invalid-feedback').text('');
            $('#statusError').text('');
        });

        // Pre-populate form fields when modal opens for editing
        $('#modal-edit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var departmentId = button.data('id'); // Extract department ID from data-id attribute

            // Fetch department details via AJAX
            $.ajax({
                url: '{{ url('department') }}' + "/" + departmentId + "/edit",
                method: 'GET',
                success: function(response) {
                    // Populate form fields
                    $('#edit_department_id').val(response.id);
                    $('#edit_department').val(response.department);

                    // Set radio button status
                    if (response.status === 'Y') {
                        $('#edit_yes').prop('checked', true);
                    } else {
                        $('#edit_no').prop('checked', true);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>
