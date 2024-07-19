<form id="createComboOfferForm" method="post" action="{{ route('settings.combo_offer.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-kit-medical"></i> Combo Offer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- Treatments -->
                        <div class="form-group">
                            <label class="form-label" for="treatments">Treatments <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="treatments" name="treatments[]" multiple>
                                @foreach ($treatments as $treatment)
                                    <option value="{{ $treatment->id }}" data-cost="{{ $treatment->treat_cost }}">
                                        {{ $treatment->treat_name }}</option>
                                @endforeach
                            </select>
                            <div id="treatmentsError" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group mt-3" id="treatDiv" style="display: none;">
                            <h5>Treatment Details</h5>
                            <table class="table table-bordered" id="treatmentDetailsTable">
                                <thead>
                                    <tr>
                                        <th>Treatment Name</th>
                                        <th>Treatment Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dynamically added rows will go here -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th id="totalCost">0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Offer Amount -->
                        <div class="form-group">
                            <label class="form-label" for="offer_amount">Offer Amount <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="offer_amount" name="offer_amount"
                                placeholder="Offer Amount">
                            <div id="offerAmountError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label for="offer_from" class="form-label">Offer From <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="offer_from" name="offer_from"
                                placeholder="Offer From Date" autocomplete="off">
                            <div id="offerFromError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label for="offer_to" class="form-label">Offer To <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="offer_to" name="offer_to"
                                placeholder="Offer To Date" autocomplete="off">
                            <div id="offerToError" class="invalid-feedback"></div>
                        </div>

                        <!-- Status -->
                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <input name="status" type="radio" checked class="form-check-input" id="yes"
                                value="Y">
                            <label class="form-check-label" for="yes">Yes</label>
                            <input name="status" type="radio" class="form-check-input" id="no" value="N">
                            <label class="form-check-label" for="no">No</label>
                            <div id="statusError" class="invalid-feedback"></div>
                        </div>



                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="saveComboOfferBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Save button click
        $('#saveComboOfferBtn').click(function() {
            // Reset previous error messages
            $('#treatmentsError').text('');
            $('#offerAmountError').text('');
            $('#offerFromError').text('');
            $('#offerToError').text('');

            // Validate form inputs
            var treatments = $('#treatments').val();
            var offerAmount = parseFloat($('#offer_amount').val());
            var offerFrom = $('#offer_from').val();
            var offerTo = $('#offer_to').val();
            var totalTreatmentCost = 0;

            // Calculate total treatment cost
            $('#treatments option:selected').each(function() {
                totalTreatmentCost += parseFloat($(this).data('cost'));
            });

            // Basic client-side validation
            if (!treatments || treatments.length === 0) {
                $('#treatments').addClass('is-invalid');
                $('#treatmentsError').text('At least one treatment must be selected.');
                return; // Prevent further execution
            } else {
                $('#treatments').removeClass('is-invalid');
                $('#treatmentsError').text('');
            }

            if (isNaN(offerAmount) || offerAmount.length === 0) {
                $('#offer_amount').addClass('is-invalid');
                $('#offerAmountError').text('Offer amount is required.');
                return; // Prevent further execution
            } else if (!$.isNumeric(offerAmount)) {
                $('#offer_amount').addClass('is-invalid');
                $('#offerAmountError').text('The offer amount must be a number.');
                return; // Prevent further execution
            } else if (offerAmount > totalTreatmentCost) {
                $('#offer_amount').addClass('is-invalid');
                $('#offerAmountError').text('The offer amount cannot exceed the total treatment cost.');
                return; // Prevent further execution
            } else {
                $('#offer_amount').removeClass('is-invalid');
                $('#offerAmountError').text('');
            }

            if (!offerFrom) {
                $('#offer_from').addClass('is-invalid');
                $('#offerFromError').text('Offer from date is required.');
                return; // Prevent further execution
            } else {
                $('#offer_from').removeClass('is-invalid');
                $('#offerFromError').text('');
            }

            if (!offerTo) {
                $('#offer_to').addClass('is-invalid');
                $('#offerToError').text('Offer to date is required.');
                return; // Prevent further execution
            } else {
                $('#offer_to').removeClass('is-invalid');
                $('#offerToError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#createComboOfferForm');
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
                    $('#successMessage').text('Combo offer created successfully').fadeIn()
                        .delay(3000).fadeOut();
                    table.ajax.reload(); // Reload the DataTable
                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('treatments')) {
                        $('#treatments').addClass('is-invalid');
                        $('#treatmentsError').text(errors.treatments[0]);
                    }

                    if (errors.hasOwnProperty('offer_amount')) {
                        $('#offer_amount').addClass('is-invalid');
                        $('#offerAmountError').text(errors.offer_amount[0]);
                    }
                    if (errors.hasOwnProperty('offer_from')) {
                        $('#offer_from').addClass('is-invalid');
                        $('#offerFromError').text(errors.offer_from[0]);
                    }

                    if (errors.hasOwnProperty('offer_to')) {
                        $('#offer_to').addClass('is-invalid');
                        $('#offerToError').text(errors.offer_to[0]);
                    }
                }
            });
        });

        // Update treatment table when treatments are selected
        $('#treatments').change(function() {
            var selectedTreatments = $(this).find('option:selected');
            var treatmentDetailsTable = $('#treatmentDetailsTable tbody');
            var totalCost = 0;
            treatmentDetailsTable.empty();
            if (selectedTreatments.length > 0) {
                $('#treatDiv').show();
            } else {
                alert('out');
                $('#treatDiv').hide();
            }

            selectedTreatments.each(function() {
                var treatmentName = $(this).text();
                var treatmentCost = parseFloat($(this).data('cost'));
                totalCost += treatmentCost;
                treatmentDetailsTable.append(
                    '<tr><td>' + treatmentName + '</td><td>' + treatmentCost
                    .toFixed(2) + '</td></tr>'
                );
            });

            // Update total cost
            $('#totalCost').text(totalCost.toFixed(2));
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createComboOfferForm').trigger('reset');
            $('#treatments').removeClass('is-invalid');
            $('#offer_amount').removeClass('is-invalid');
            $('#offer_from').removeClass('is-invalid');
            $('#offer_to').removeClass('is-invalid');

            $('#treatmentsError').text('');
            $('#offerAmountError').text('');
            $('#offerFromError').text('');
            $('#offerToError').text('');
            $('#treatmentDetailsTable tbody').empty();
            $('#totalCost').text('0.00'); // Reset total cost
            $('#treatDiv').hide();
        });
    });
</script>
