<form id="createTreatmentCostForm" method="post" action="{{ route('settings.treatment_cost.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-kit-medical"> </i> Treatment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- Treatment Name -->
                        <div class="form-group">
                            <label class="form-label" for="treat_name">Treatment Name <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="treat_name" name="treat_name"
                                placeholder="Treatment Name">
                            <div id="treatmentError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <!-- Treatment Cost -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="treat_cost">Cost <span class="text-danger">
                                            *</span></label>
                                    <input class="form-control" type="text" id="treat_cost" name="treat_cost"
                                        placeholder="Treatment Cost">
                                    <div id="treatmentCostError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <!-- Discount Percentage -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="discount">Discount (%)</label>
                                    <input class="form-control" type="text" id="discount" name="discount"
                                        placeholder="Discount Percentage">
                                    <div id="discountError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Discount Start Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="discount_from">Discount Start Date</label>
                                    <input class="form-control" type="date" id="discount_from" name="discount_from">
                                    <div id="discountFromError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <!-- Discount End Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="discount_to">Discount End Date</label>
                                    <input class="form-control" type="date" id="discount_to" name="discount_to">
                                    <div id="discountToError" class="invalid-feedback"></div>
                                </div>
                            </div>
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
            $('#discountError').text('');
            $('#discountFromError').text('');
            $('#discountToError').text('');

            // Validate form inputs
            var treatment = $('#treat_name').val();
            var treatmentCost = $('#treat_cost').val();
            var status = $('input[name="status"]:checked').val();
            var discount = $('#discount').val();
            var discountFrom = $('#discount_from').val();
            var discountTo = $('#discount_to').val();

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

            if (discount.length > 0 && !$.isNumeric(discount)) {
                $('#discount').addClass('is-invalid');
                $('#discountError').text('The discount must be a number.');
                return; // Prevent further execution
            } else {
                $('#discount').removeClass('is-invalid');
                $('#discountError').text('');
            }

            if (discountFrom.length > 0 && discountTo.length > 0 && discountFrom > discountTo) {
                $('#discount_from').addClass('is-invalid');
                $('#discount_to').addClass('is-invalid');
                $('#discountFromError').text('Discount start date cannot be after the end date.');
                $('#discountToError').text('Discount end date cannot be before the start date.');
                return; // Prevent further execution
            } else {
                $('#discount_from').removeClass('is-invalid');
                $('#discount_to').removeClass('is-invalid');
                $('#discountFromError').text('');
                $('#discountToError').text('');
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

                    if (errors.hasOwnProperty('discount')) {
                        $('#discount').addClass('is-invalid');
                        $('#discountError').text(errors.discount[0]);
                    }

                    if (errors.hasOwnProperty('discount_from')) {
                        $('#discount_from').addClass('is-invalid');
                        $('#discountFromError').text(errors.discount_from[0]);
                    }

                    if (errors.hasOwnProperty('discount_to')) {
                        $('#discount_to').addClass('is-invalid');
                        $('#discountToError').text(errors.discount_to[0]);
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
            $('#discount').removeClass('is-invalid');
            $('#discount_from').removeClass('is-invalid');
            $('#discount_to').removeClass('is-invalid');
            $('#treatmentError').text('');
            $('#treatmentCostError').text('');
            $('#discountError').text('');
            $('#discountFromError').text('');
            $('#discountToError').text('');
            $('#statusError').text('');
        });
    });
</script>
