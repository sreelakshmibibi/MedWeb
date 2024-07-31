<form id="editInsuranceForm" method="post" action="{{ route('settings.insurance.update') }}">
    @csrf
    <input type="hidden" id="edit_insurance_id" name="edit_insurance_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Edit Insurance Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="edit_insurance">Insurance Name <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="edit_insurance" name="company_name" required
                                minlength="3" placeholder="Company Name" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <!--Claim types-->
                        <div class="form-group">
                                <label class="form-label" for="edit_claim_type">Claim Type <span
                                        class="text-danger">
                                        *</span></label>
                                <select class="form-select" id="edit_claim_type" name="claim_type">
                                    <option value="">Select Claim Type</option>
                                    <option value="Cashless">Cashless</option>
                                    <option value="Reimbursement">Reimbursement</option>
                                </select>
                                <div id="treatmentStatusError" class="invalid-feedback"></div>
                        </div>
                
                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <div>
                                <input name="status" type="radio" class="form-control-input" id="edit_yes"
                                    value="Y">
                                <label class="form-check-label" for="edit_yes">Yes</label>
                                <input name="status" type="radio" class="form-control-input" id="edit_no"
                                    value="N">
                                <label class="form-check-label" for="edit_no">No</label>
                            </div>
                            <div class="text-danger" id="statusError"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="updateInsuranceBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Update button click
        $('#updateInsuranceBtn').click(function() {
            // Reset previous error messages
            $('#edit_insurance').removeClass('is-invalid');
            $('#edit_insurance').next('.invalid-feedback').text('');
            $('#statusError').text('');

            // Validate form inputs
            var insurance = $('#edit_insurance').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (insurance.length === 0) {
                $('#edit_insurance').addClass('is-invalid');
                $('#edit_insurance').next('.invalid-feedback').text('Insurance name is required.');
                return; // Prevent further execution
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editInsuranceForm');
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
                    $('#successMessage').text('Insurance updated successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                    // location.reload(); // Refresh the page or update the table as needed
                    table.ajax.reload();
                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('company_name')) {
                        $('#edit_insurance').addClass('is-invalid');
                        $('#edit_insurance').next('.invalid-feedback').text(errors
                            .company_name[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#statusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editInsuranceForm').trigger('reset');
            $('#edit_insurance').removeClass('is-invalid');
            $('#edit_insurance').next('.invalid-feedback').text('');
            $('#statusError').text('');
        });

        // Pre-populate form fields when modal opens for editing
        $('#modal-edit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var insuranceId = button.data('id'); // Extract department ID from data-id attribute

            // Fetch department details via AJAX
            $.ajax({
                url: '{{ url("insurance") }}' + "/" + insuranceId + "/edit",
                method: 'GET',
                success: function(response) {
                    // Populate form fields
                    $('#edit_insurance_id').val(response.id);
                    $('#edit_insurance').val(response.company_name);

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
