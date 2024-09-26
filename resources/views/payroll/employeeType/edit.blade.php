<form id="editemployeeTypeForm" method="post" action="{{ route('employeeTypes.update') }}">
    @csrf
    <input type="hidden" id="edit_employee_type_id" name="edit_employee_type_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-employee-type-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Edit PayHead Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessage" style="display:none;" class="alert alert-danger"></div>
                
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="edit_employee_type">PayHead Type <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="edit_employee_type" name="edit_employee_type" required minlength="3" placeholder="Employee Type" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <div>
                                <input name="edit_status" type="radio" class="form-control with-gap" id="edit_yes" value="Y">
                                <label class="form-check-label" for="edit_yes">Yes</label>
                                <input name="edit_status" type="radio" class="form-control with-gap" id="edit_no" value="N">
                                <label class="form-check-label" for="edit_no">No</label>
                            </div>
                            <div class="text-danger" id="statusError"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="updateEmployeeTypeBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Update button click
        $('#updateEmployeeTypeBtn').click(function() {
            // Reset previous error messages
            $('#edit_employee_type').removeClass('is-invalid');
            $('#edit_employee_type').next('.invalid-feedback').text('');
            $('#statusError').text('');

            // Validate form inputs
            var employeeType = $('#edit_employee_type').val();
            var status = $('input[name="edit_status"]:checked').val(); // Corrected name here

            // Basic client-side validation
            if (employeeType.length === 0) {
                $('#edit_employee_type').addClass('is-invalid');
                $('#edit_employee_type').next('.invalid-feedback').text('Type is required.');
                return; // Prevent further execution
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editemployeeTypeForm'); // Make sure this matches the form ID
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // If successful, hide modal and show success message
                    $('#modal-employee-type-edit').modal('hide');
                    if (response.success) {
                        $('#successMessage').text(response.success);
                        $('#successMessage').fadeIn().delay(3000).fadeOut(); // Show for 3 seconds
                    }
                    table.ajax.reload(); // Reload table data
                },
                error: function(xhr) {
                    // Handle error responses
                    if (xhr.responseJSON.error) {
                        $('#errorMessage').text(xhr.responseJSON.error).show();
                    }
                    
                    // If error, update modal to show specific field errors
                    var errors = xhr.responseJSON.errors;
                    if (errors.hasOwnProperty('edit_employee_type')) {
                        $('#edit_employee_type').addClass('is-invalid');
                        $('#edit_employee_type').next('.invalid-feedback').text(errors.edit_employee_type[0]);
                    }
                    if (errors.hasOwnProperty('edit_status')) {
                        $('#statusError').text(errors.edit_status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-employee-type-edit').on('hidden.bs.modal', function() {
            $('#editemployeeTypeForm').trigger('reset');
            $('#edit_employee_type').removeClass('is-invalid').next('.invalid-feedback').text('');
            $('#statusError').text('');
        });
    });
</script>
