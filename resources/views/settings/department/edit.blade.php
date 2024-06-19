<form id="editDepartmentForm" method="post" action="{{ route('settings.department.update') }}">
    @csrf
    <input type="hidden" id="edit_department_id" name="edit_department_id" value="">
    <div class="modal modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog" style="width:40%; max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Edit Department Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="edit_department">Department</label>
                            <input class="form-control" type="text" id="edit_department" name="department" required minlength="3" placeholder="Department Name" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <div>
                                <input name="status" type="radio" class="form-control-input" id="edit_yes" value="Y">
                                <label class="form-check-label" for="edit_yes">Yes</label>
                                <input name="status" type="radio" class="form-control-input" id="edit_no" value="N">
                                <label class="form-check-label" for="edit_no">No</label>
                            </div>
                            <div class="text-danger" id="statusError"></div>
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
                    $('#successMessage').text('Department updated successfully');
                    $('#successMessage').fadeIn().delay(3000).fadeOut(); // Show for 3 seconds
                    location.reload(); // Refresh the page or update the table as needed
                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('department')) {
                        $('#edit_department').addClass('is-invalid');
                        $('#edit_department').next('.invalid-feedback').text(errors.department[0]);
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
                url: '{{ url("department") }}' + "/" + departmentId + "/edit",
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
