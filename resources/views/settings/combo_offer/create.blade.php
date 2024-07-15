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
                                    <option value="{{ $treatment->id }}">{{ $treatment->treat_name }}</option>
                                @endforeach
                            </select>
                            <div id="treatmentsError" class="invalid-feedback"></div>
                        </div>

                        <!-- Offer Amount -->
                        <div class="form-group">
                            <label class="form-label" for="offer_amount">Offer Amount <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="offer_amount" name="offer_amount"
                                placeholder="Offer Amount">
                            <div id="offerAmountError" class="invalid-feedback"></div>
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

            // Validate form inputs
            var treatments = $('#treatments').val();
            var offerAmount = $('#offer_amount').val();

            // Basic client-side validation
            if (!treatments || treatments.length === 0) {
                $('#treatments').addClass('is-invalid');
                $('#treatmentsError').text('At least one treatment must be selected.');
                return; // Prevent further execution
            } else {
                $('#treatments').removeClass('is-invalid');
                $('#treatmentsError').text('');
            }

            if (offerAmount.length === 0) {
                $('#offer_amount').addClass('is-invalid');
                $('#offerAmountError').text('Offer amount is required.');
                return; // Prevent further execution
            } else if (!$.isNumeric(offerAmount)) {
                $('#offer_amount').addClass('is-invalid');
                $('#offerAmountError').text('The offer amount must be a number.');
                return; // Prevent further execution
            } else {
                $('#offer_amount').removeClass('is-invalid');
                $('#offerAmountError').text('');
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
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createComboOfferForm').trigger('reset');
            $('#treatments').removeClass('is-invalid');
            $('#offer_amount').removeClass('is-invalid');
            $('#treatmentsError').text('');
            $('#offerAmountError').text('');
        });
    });
</script>
