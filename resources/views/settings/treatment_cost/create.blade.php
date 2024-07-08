<form id="createTreatmentCostForm" method="post" action="{{ route('settings.treatment_cost.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-kit-medical"></i> Treatment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- Treatment Name -->
                        <div class="form-group">
                            <label class="form-label" for="treatment">Treatment Name <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="treat_name" name="treat_name"
                                placeholder="Treatment Name">
                            <div id="treatmentError" class="invalid-feedback"></div>
                        </div>

                        <!-- Treatment Cost -->
                        <div class="form-group">
                            <label class="form-label" for="cost">Cost <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="treat_cost" name="treat_cost"
                                placeholder="Treatment Cost">
                            <div id="treatmentCostError" class="invalid-feedback"></div>
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
                    <button type="button" class="btn btn-success float-end" id="saveTreatmentCostBtn">Save</button>
                </div>

            </div>

        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Save button click
        $('#saveTreatmentCostBtn').click(function() {
            // Reset previous error messages
            $('#treatmentError').text('');
            $('#treatmentCostError').text('');
            $('#statusError').text('');

            // Validate form inputs
            var treatment = $('#treat_name').val();
            var treatmentCost = $('#treat_cost').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (treatment.length === 0) {
                $('#treat_name').addClass('is-invalid');
                $('#treatmentError').text('Treatment name is required.');
                return; // Prevent further execution
            } else {
                $('#treat_name').removeClass('is-invalid');
                $('#treatmentError').text('');
            }

            if (treatmentCost.length === 0) {
                $('#treat_cost').addClass('is-invalid');
                $('#treatmentCostError').text('Treatment cost is required.');
                return; // Prevent further execution
            } else if (!$.isNumeric(treatmentCost)) {
                $('#treat_cost').addClass('is-invalid');
                $('#treatmentCostError').text('The treatment cost must be a number.');
                return; // Prevent further execution
            } else {
                $('#treat_cost').removeClass('is-invalid');
                $('#treatmentCostError').text('');
            }

            if (!status) {
                $('#statusError').text('Status is required.');
                return; // Prevent further execution
            } else {
                $('#statusError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#createTreatmentCostForm');
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
                    $('#successMessage').text('Treatment cost created successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                    // location.reload();
                    // Optionally, you can reload or update the table here
                    table.ajax.reload();
                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('treat_name')) {
                        $('#treat_name').addClass('is-invalid');
                        $('#treatmentError').text(errors.treat_name[0]);
                    }

                    if (errors.hasOwnProperty('treat_cost')) {
                        $('#treat_cost').addClass('is-invalid');
                        $('#treatmentCostError').text(errors.treat_cost[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#statusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createTreatmentCostForm').trigger('reset');
            $('#treat_cost').removeClass('is-invalid');
            $('#treat_name').removeClass('is-invalid');
            $('#treatmentError').text('');
            $('#treatmentCostError').text('');
            $('#statusError').text('');
        });
    });
</script>
