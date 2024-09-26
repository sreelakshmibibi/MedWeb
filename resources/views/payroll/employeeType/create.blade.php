<form id="createEmployeeTypeForm" method="post" action="{{ route('employeeTypes.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Add Employee Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessagecreate" style="display:none;" class="alert alert-danger">
                </div>
                
                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- Department Name -->
                        <div class="form-group">
                            <label class="form-label" for="employee_type">Type <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="employee_type" name="employee_type"
                                placeholder="Employee Type">
                            <div id="employeeTypeError" class="invalid-feedback"></div>
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
                    <button type="button" class="btn btn-success float-end" id="saveEmployeeTypeBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Include custom JavaScript file -->
<script src="{{ asset('js/employeeType.js') }}"></script>

<script>
    $(function() {
        // Handle Save button click
        $('#saveEmployeeTypeBtn').click(function() {
            // Reset previous error messages
            $('#employeeTypeError').text('');
            $('#statusError').text('');
            $('#errorMessagecreate').text('');
            // Validate form inputs
            var emp_type = $('#employee_type').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (emp_type.length === 0) {
                $('#employee_type').addClass('is-invalid');
                $('#employeeTypeError').text('Type is required.');
                return; // Prevent further execution
            } else {
                $('#employee_type').removeClass('is-invalid');
                $('#employeeTypeError').text('');
            }

            if (!status) {
                $('#statusError').text('Status is required.');
                return; // Prevent further execution
            } else {
                $('#statusError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#createEmployeeTypeForm');
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
                       
                    }
                    
                    // location.reload();
                  
                },
                error: function(xhr) {
                    
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('employee_type')) {
                        $('#employee_type').addClass('is-invalid');
                        $('#employeeTypeError').text(errors.employee_type[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#statusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createEmployeeTypeForm').trigger('reset');
            $('#employee_type').removeClass('is-invalid');
            $('#employeeTypeError').text('');
            $('#statusError').text('');
        });
    });
</script>
