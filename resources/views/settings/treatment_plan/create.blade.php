<form id="createInsuranceForm" method="post" action="{{ route('settings.treatment_plan.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Insurance Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- Department Name -->
                        <div class="form-group">
                            <label class="form-label" for="plan">Plan <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="plan" name="plan"
                                placeholder="plan">
                            <div id="planError" class="invalid-feedback"></div>
                        </div>

                        <!--Claim types-->
                        <div class="form-group">
                            <label class="form-label" for="cost">Cost <span
                                    class="text-danger">
                                    *</span></label>
                                    <input class="form-control" type="text" id="cost" name="cost"
                                    placeholder="cost">
                            <div id="costError" class="invalid-feedback"></div>
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
                    <button type="button" class="btn btn-success float-end" id="saveInsuranceBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Include custom JavaScript file -->
<script src="{{ asset('js/treatment_plan.js') }}"></script>

<script>
    $(function() {
        var formSubmitting = false; // Flag to prevent multiple submissions
            
        // Handle Save button click
        $('#saveInsuranceBtn').click(function() {
            if (formSubmitting) {
                return;
            }
            formSubmitting = true;
            // Reset previous error messages
            $('#planError').text('');
            $('#costError').text('');
            $('#statusError').text('');

            // Validate form inputs
            var plan = $('#plan').val();
            var cost = $('#cost').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (plan.length === 0) {
                $('#plan').addClass('is-invalid');
                $('#planError').text('Plan is required.');
                return; // Prevent further execution
            } else {
                $('#plan').removeClass('is-invalid');
                $('#planError').text('');
            }
            if (cost.length === 0) {
                $('#cost').addClass('is-invalid');
                $('#costError').text('Cost is required.');
                return; // Prevent further execution
            } else {
                $('#cost').removeClass('is-invalid');
                $('#costError').text('');
            }

            if (!status) {
                $('#statusError').text('Status is required.');
                return; // Prevent further execution
            } else {
                $('#statusError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#createInsuranceForm');
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // If successful, hide modal and show success message
                    $('#modal-right').modal('hide');
                    $('#successMessage').text('Plan created successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                    // location.reload();
                    table.ajax.reload();
                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('plan')) {
                        $('#plan').addClass('is-invalid');
                        $('#planError').text(errors.plan[0]);
                    }
                    if (errors.hasOwnProperty('cost')) {
                        $('#cost').addClass('is-invalid');
                        $('#costError').text(errors.cost[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#statusError').text(errors.status[0]);
                    }
                },
                complete: function() {
                    // Re-enable the button regardless of success or failure
                    formSubmitting = false;
                },
            });
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createInsuranceForm').trigger('reset');
            $('#plan').removeClass('is-invalid');
            $("#planError").text('');
            $('#costError').text('');
            $('#statusError').text('');
            formSubmitting = false; // Reset the flag
        });
    });
</script>
