<form id="editTreatmentPlanForm" method="post" action="{{ route('settings.treatment_plan.update') }}">
    @csrf
    <input type="hidden" id="edit_plan_id" name="edit_plan_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Edit Treatment Plan Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="edit_plan">Plan<span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="edit_plan" name="plan" required
                                minlength="3" placeholder="Plan" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <!--Claim types-->
                        <div class="form-group">
                            <label class="form-label" for="edit_cost">Cost <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="edit_cost" name="cost" required
                                minlength="3" placeholder="Plan" autocomplete="off">
                            <div id="costError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <div>
                                <input name="status" type="radio" class="form-control with-gap" id="edit_yes"
                                    value="Y">
                                <label class="form-check-label" for="edit_yes">Yes</label>
                                <input name="status" type="radio" class="form-control with-gap" id="edit_no"
                                    value="N">
                                <label class="form-check-label" for="edit_no">No</label>
                            </div>
                            <div class="text-danger" id="statusError"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="updateTreatmentPlanBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Update button click
        $('#updateTreatmentPlanBtn').click(function() {
            // Reset previous error messages
            $('#edit_plan').removeClass('is-invalid');
            $('#edit_plan').next('.invalid-feedback').text('');
            $('#statusError').text('');

            // Validate form inputs
            var plan = $('#edit_plan').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (plan.length === 0) {
                $('#edit_plan').addClass('is-invalid');
                $('#planError').next('.invalid-feedback').text('Plan is required.');
                return; // Prevent further execution
            }
            if (cost <= 0) {
                $('#edit_cost').addClass('is-invalid');
                $('#costError').next('.invalid-feedback').text('Cost is required.');
                return; // Prevent further execution
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editTreatmentPlanForm');
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
                    $('#successMessage').text('Treatment plan updated successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                    // location.reload(); // Refresh the page or update the table as needed
                    table.ajax.reload();
                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('plan')) {
                        $('#edit_plan').addClass('is-invalid');
                        $('#planError').next('.invalid-feedback').text(errors
                            .plan[0]);
                    }
                    if (errors.hasOwnProperty('cost')) {
                        $('#edit_cost').addClass('is-invalid');
                        $('#costError').next('.invalid-feedback').text(errors
                            .plan[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#statusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editTreatmentPlanForm').trigger('reset');
            $('#edit_plan').removeClass('is-invalid');
            $('#planError').next('.invalid-feedback').text('');
            $('#costError').text('');
            $('#statusError').text('');
        });

        // Pre-populate form fields when modal opens for editing
        $('#modal-edit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var planId = button.data('id'); // Extract department ID from data-id attribute

            // Fetch department details via AJAX
            $.ajax({
                url: '{{ url('treatment_plan') }}' + "/" + planId + "/edit",
                method: 'GET',
                success: function(response) {
                    // Populate form fields
                    $('#edit_plan_id').val(response.id);
                    $('#edit_plan').val(response.plan);
                    $('#edit_cost').val(response.cost);
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
