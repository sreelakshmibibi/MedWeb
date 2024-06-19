<form id="editTreatmentCostForm" method="post" action="{{ route('settings.treatment_cost.update') }}">
    @csrf
    <input type="hidden" id="edit_treatment_cost_id" name="edit_treatment_cost_id" value="">
    <div class="modal modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog" style="width:40%; max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-kit-medical"></i> Edit Treatment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="form-group">
                            <label class="form-label" for="treatment">Treatment name</label>
                            <input class="form-control" type="text" id="edit_treatment_name" name="treat_name"  placeholder="Treatment Name" autocomplete="off">
                            <div id="editTreatmentError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cost">Cost</label>
                            <input class="form-control" type="text" id="edit_treatment_cost" name="treat_cost"    placeholder="Treatment Cost" autocomplete="off">
                            <div id="editTreatmentCostError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <input name="status" type="radio"  class="form-control with-gap" id="edit_yes"
                                value="Y">
                            <label for="yes">Yes</label>
                            <input name="status" type="radio" class="form-control with-gap" id="edit_no"
                                value="N">
                            <label for="no">No</label>
                            <div id="editStatusError" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="updateTreatmentCostBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Update button click
        $('#updateTreatmentCostBtn').click(function() {
            // Reset previous error messages
            $('#editTreatmentError').text('');
            $('#editTreatmentCostError').text('');
            $('#editStatusError').text('');

            // Validate form inputs
            var treatment = $('#edit_treatment_name').val();
            var treatmentCost = $('#edit_treatment_cost').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (treatment.length === 0) {
                $('#edit_treatment_name').addClass('is-invalid');
                $('#editTreatmentError').text('Treatment name is required.');
                return; // Prevent further execution
            } else {
                $('#edit_treatment_name').removeClass('is-invalid');
                $('#editTreatmentError').text('');
            }

            if (treatmentCost.length === 0) {
                $('#edit_treatment_cost').addClass('is-invalid');
                $('#editTreatmentCostError').text('Treatment cost is required.');
                return; // Prevent further execution
            }else if (!$.isNumeric(treatmentCost)) {
                $('#edit_treatment_cost').addClass('is-invalid');
                $('#editTreatmentCostError').text('The treatment cost must be a number.');
                return; // Prevent further execution
            }else {
                $('#edit_treatment_cost').removeClass('is-invalid');
                $('#editTreatmentCostError').text('');
            }

            if (!status) {
                $('#editStatusError').text('Status is required.');
                return; // Prevent further execution
            } else {
                $('#editStatusError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editTreatmentCostForm');
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
                    $('#successMessage').text('Treatment cost updated successfully');
                    $('#successMessage').fadeIn().delay(3000).fadeOut(); // Show for 3 seconds
                    // table.draw(); // Refresh DataTable
                    location.reload();
                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('edit_treatment_name')) {
                        $('#edit_treatment_name').addClass('is-invalid');
                        $('#editTreatmentError').text(errors.edit_treatment_name[0]);
                    }

                    if (errors.hasOwnProperty('department')) {
                        $('#edit_treatment_cost').addClass('is-invalid');
                        $('#editTreatmentCostError').text(errors.edit_treatment_cost[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#editStatusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editTreatmentCostForm').trigger('reset');
            $('#edit_treatment_name').removeClass('is-invalid');
            $('#edit_treatment_cost').removeClass('is-invalid');
            $('#edit_treatment_name').next('.invalid-feedback').text('');
            $('#edit_treatment_cost').next('.invalid-feedback').text('');
            $('#editTreatmentError').text('');
            $('#editTreatmentCostError').text('');
            $('#editStatusError').text('');

        });

        // Pre-populate form fields when modal opens for editing
        $('#modal-edit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var treatmentId = button.data('id'); // Extract department ID from data-id attribute

            // Fetch department details via AJAX
            $.ajax({
                url: '{{ url("treatment_cost", "") }}' + "/" + treatmentId + "/edit",
                method: 'GET',
                success: function(response) {
                    // Populate form fields
                    $('#edit_treatment_cost_id').val(response.id);
                    // Format the treat_cost to two decimal points
                    var formattedCost = parseFloat(response.treat_cost).toFixed(2);
                    // Clear input value and ensure no autofill suggestions
                    $('#edit_treatment_name').val('').focus().val(response.treat_name);
                    $('#edit_treatment_cost').val('').focus().val(formattedCost);
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

