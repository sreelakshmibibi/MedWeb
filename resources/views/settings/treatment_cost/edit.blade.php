<form id="editTreatmentCostForm" method="post"
    action="{{ route('settings.treatment_cost.update', ['treatment_cost' => ':id']) }}">
    @csrf
    <input type="hidden" id="edit_treatment_cost_id" name="edit_treatment_cost_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-kit-medical"> </i> Edit Treatment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                    <div class="row">
                        <!-- Treatment Cost -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="edit_treatment_name">Treatment Name <span
                                        class="text-danger">
                                        *</span></label>
                                <input class="form-control" type="text" id="edit_treatment_name" name="treat_name"
                                    placeholder="Treatment Name" autocomplete="off">
                                <div id="editTreatmentError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="edit_treatment_category">Treatment Category <span class="text-danger">
                                        *</span></label>
                                <select class="form-control" id="edit_treatment_category" name="edit_treatment_category"
                                    placeholder="Treatment Category">
                                    <option value="{{App\Models\TreatmentType::DENTAL_CLINIC}}">{{App\Models\TreatmentType::DENTAL_CLINIC_WORDS}}</option>
                                    <option value="{{App\Models\TreatmentType::COSMETIC_CLINIC}}">{{App\Models\TreatmentType::COSMETIC_CLINIC_WORDS}}</option>
                                </select>
                                <div id="editTreatmentCategoryError" class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Treatment Cost -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_treatment_cost">Cost <span class="text-danger">
                                            *</span></label>
                                    <input class="form-control" type="text" id="edit_treatment_cost"
                                        name="treat_cost" placeholder="Treatment Cost" autocomplete="off">
                                    <div id="editTreatmentCostError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <!-- Discount Percentage -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_discount_percentage">Discount Percentage</label>
                                    <input class="form-control" type="text" id="edit_discount_percentage"
                                        name="discount" placeholder="Discount Percentage" autocomplete="off">
                                    <div id="editDiscountPercentageError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Discount Start Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_discount_from">Discount From</label>
                                    <input class="form-control" type="date" id="edit_discount_from"
                                        name="discount_from" placeholder="Discount From" autocomplete="off">
                                    <div id="editDiscountFromError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <!-- Discount End Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_discount_to">Discount To</label>
                                    <input class="form-control" type="date" id="edit_discount_to" name="discount_to"
                                        placeholder="Discount To" autocomplete="off">
                                    <div id="editDiscountToError" class="invalid-feedback"></div>
                                </div>
                            </div>
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
                            <div class="text-danger" id="editStatusError"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end"
                        id="updateTreatmentCostBtn">Update</button>
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
            $('#editTreatmentCategoryError').text();
            $('#editStatusError').text('');
            $('#editDiscountPercentageError').text('');
            $('#editDiscountFromError').text('');
            $('#editDiscountToError').text('');

            // Validate form inputs
            var treatment = $('#edit_treatment_name').val();
            var treatmentCategory = $('#edit_treatment_category').val();
            var treatmentCost = $('#edit_treatment_cost').val();
            var status = $('input[name="status"]:checked').val();
            var discountPercentage = $('#edit_discount_percentage').val();
            var discountFrom = $('#edit_discount_from').val();
            var discountTo = $('#edit_discount_to').val();

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
            } else if (!$.isNumeric(treatmentCost)) {
                $('#edit_treatment_cost').addClass('is-invalid');
                $('#editTreatmentCostError').text('The treatment cost must be a number.');
                return; // Prevent further execution
            } else {
                $('#edit_treatment_cost').removeClass('is-invalid');
                $('#editTreatmentCostError').text('');
            }

            if (discountPercentage.length > 0 && !$.isNumeric(discountPercentage)) {
                $('#edit_discount_percentage').addClass('is-invalid');
                $('#editDiscountPercentageError').text('The discount percentage must be a number.');
                return; // Prevent further execution
            } else {
                $('#edit_discount_percentage').removeClass('is-invalid');
                $('#editDiscountPercentageError').text('');
            }

            if (!status) {
                $('#editStatusError').text('Status is required.');
                return; // Prevent further execution
            } else {
                $('#editStatusError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editTreatmentCostForm');
            var treatmentCostId = $('#edit_treatment_cost_id').val();
            var url = form.attr('action').replace(':id', treatmentCostId);
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
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                    table.draw(); // Refresh DataTable
                    // location.reload();

                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('treat_name')) {
                        $('#edit_treatment_name').addClass('is-invalid');
                        $('#editTreatmentError').text(errors.treat_name[0]);
                    }

                    if (errors.hasOwnProperty('treat_cost')) {
                        $('#edit_treatment_cost').addClass('is-invalid');
                        $('#editTreatmentCostError').text(errors.treat_cost[0]);
                    }

                    if (errors.hasOwnProperty('treat_category')) {
                        $('#edit_treatment_category').addClass('is-invalid');
                        $('#editTreatmentCategoryError').text(errors.treat_category[0]);
                    }

                    if (errors.hasOwnProperty('discount')) {
                        $('#edit_discount_percentage').addClass('is-invalid');
                        $('#editDiscountPercentageError').text(errors.discount[0]);
                    }

                    if (errors.hasOwnProperty('discount_from')) {
                        $('#edit_discount_from').addClass('is-invalid');
                        $('#editDiscountFromError').text(errors.discount_from[0]);
                    }

                    if (errors.hasOwnProperty('discount_to')) {
                        $('#edit_discount_to').addClass('is-invalid');
                        $('#editDiscountToError').text(errors.discount_to[0]);
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
            $('#edit_treatment_category').removeClass('is-invalid');
            $('#edit_treatment_cost').removeClass('is-invalid');
            $('#edit_discount_percentage').removeClass('is-invalid');
            $('#edit_discount_from').removeClass('is-invalid');
            $('#edit_discount_to').removeClass('is-invalid');
            $('#edit_treatment_name').next('.invalid-feedback').text('');
            $('#edit_treatment_cost').next('.invalid-feedback').text('');
            $('#edit_discount_percentage').next('.invalid-feedback').text('');
            $('#edit_discount_from').next('.invalid-feedback').text('');
            $('#edit_discount_to').next('.invalid-feedback').text('');
            $('#editTreatmentError').text('');
            $('#editTreatmentCostError').text('');
            $('#editTreatmentCategoryError').text('');
            $('#editDiscountPercentageError').text('');
            $('#editDiscountFromError').text('');
            $('#editDiscountToError').text('');
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
                    $('#edit_treatment_category').val('').focus().val(response.treat_category);
                    $('#edit_treatment_cost').val('').focus().val(formattedCost);
                    $('#edit_discount_percentage').val(response.discount_percentage || '');
                    $('#edit_discount_from').val(response.discount_from || '');
                    $('#edit_discount_to').val(response.discount_to || '');
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
