<form id="editComboOfferForm" method="post" action="{{ route('settings.combo_offer.update', ['offer' => ':id']) }}">
    @csrf
    <input type="hidden" id="edit_combo_offer_id" name="edit_combo_offer_id" value="">

    <div class="modal fade" id="modal-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-tags"></i> Edit Combo Offer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="form-group mt-3">
                            <label for="treatments" class="form-label">Treatments</label>
                            <select id="edit_treatments" name="treatments[]" class="form-select" multiple>
                                @foreach ($treatments as $treatment)
                                    <option value="{{ $treatment->id }}" data-cost="{{ $treatment->treat_cost }}">
                                        {{ $treatment->treat_name }}</option>
                                @endforeach
                            </select>
                            <div id="editTreatmentsError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="offer_amount" class="form-label">Offer Amount <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_offer_amount" name="offer_amount"
                                placeholder="Offer Amount" autocomplete="off">
                            <div id="editOfferAmountError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label">Status</label>
                            <div>
                                <input type="radio" name="status" id="edit_active" value="Y"
                                    class="form-check-input">
                                <label for="edit_active" class="form-check-label">Active</label>

                                <input type="radio" name="status" id="edit_inactive" value="N"
                                    class="form-check-input">
                                <label for="edit_inactive" class="form-check-label">Inactive</label>
                            </div>
                            <div class="text-danger" id="editStatusError"></div>
                        </div>

                        <div class="form-group mt-3" id="editTreatDiv" style="display: none;">
                            <h5>Treatment Details</h5>
                            <table class="table table-bordered" id="editTreatmentDetailsTable">
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
                                        <th id="editTotalCost">0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="updateComboOfferBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // $('#updateComboOfferBtn').click(function() {
        //     // Reset previous error messages
        //     $('#editOfferAmountError').text('');
        //     $('#editStatusError').text('');
        //     $('#editTreatmentsError').text('');

        //     // Validate form inputs
        //     var offerAmount = $('#edit_offer_amount').val();
        //     var status = $('input[name="status"]:checked').val();
        //     var treatments = $('#edit_treatments').val();

        //     if (!offerAmount) {
        //         $('#edit_offer_amount').addClass('is-invalid');
        //         $('#editOfferAmountError').text('Offer amount is required.');
        //         return;
        //     } else {
        //         $('#edit_offer_amount').removeClass('is-invalid');
        //     }

        //     if (!status) {
        //         $('#editStatusError').text('Status is required.');
        //         return;
        //     } else {
        //         $('#editStatusError').text('');
        //     }

        //     if (!treatments || treatments.length === 0) {
        //         $('#editTreatmentsError').text('At least one treatment must be selected.');
        //         return;
        //     } else {
        //         $('#editTreatmentsError').text('');
        //     }

        //     // If validation passed, submit the form via AJAX
        //     var form = $('#editComboOfferForm');
        //     var comboOfferId = $('#edit_combo_offer_id').val();
        //     var url = form.attr('action').replace(':id', comboOfferId);
        //     var formData = form.serialize();

        //     $.ajax({
        //         type: 'POST',
        //         url: url,
        //         data: formData,
        //         dataType: 'json',
        //         success: function(response) {
        //             $('#modal-edit').modal('hide');
        //             $('#successMessage').text('Combo offer updated successfully');
        //             $('#successMessage').fadeIn().delay(3000).fadeOut();
        //             table.draw(); // Refresh DataTable
        //         },
        //         error: function(xhr) {
        //             var errors = xhr.responseJSON.errors;
        //             if (errors.hasOwnProperty('offer_amount')) {
        //                 $('#edit_offer_amount').addClass('is-invalid');
        //                 $('#editOfferAmountError').text(errors.offer_amount[0]);
        //             }
        //             if (errors.hasOwnProperty('status')) {
        //                 $('#editStatusError').text(errors.status[0]);
        //             }
        //             if (errors.hasOwnProperty('treatments')) {
        //                 $('#editTreatmentsError').text(errors.treatments[0]);
        //             }
        //         }
        //     });
        // });

        // Reset form and errors on modal close
        // $('#modal-edit').on('hidden.bs.modal', function() {
        //     $('#editComboOfferForm').trigger('reset');
        //     $('#edit_offer_amount').removeClass('is-invalid');
        //     $('#editStatusError').text('');
        //     $('#editTreatmentsError').text('');
        // });

        // Pre-populate form fields when modal opens for editing

        // $('#modal-edit').on('show.bs.modal', function(event) {
        //     var button = $(event.relatedTarget);
        //     var comboOfferId = button.data('id');

        //     // Fetch combo offer details via AJAX
        //     $.ajax({
        //         url: '{{ url('combo_offer') }}' + "/" + comboOfferId + "/edit",
        //         method: 'GET',
        //         success: function(response) {
        //             $('#edit_combo_offer_id').val(response.id);
        //             $('#edit_offer_amount').val(response.offer_amount);
        //             $('input[name="status"][value="' + response.status + '"]').prop(
        //                 'checked', true);

        //             // Clear previous selections
        //             $('#edit_treatments').empty();

        //             // Populate the treatments select box
        //             response.treatments.forEach(function(treatment) {
        //                 var option = $('<option></option>')
        //                     .attr('value', treatment.id)
        //                     .text(treatment.treat_name);

        //                 // Pre-select treatments
        //                 if (response.comboOffer_treatments.includes(treatment.id)) {
        //                     option.prop('selected', true);
        //                 }

        //                 $('#edit_treatments').append(option);
        //             });

        //             $('#edit_treatments').trigger('change');
        //         },
        //         error: function(error) {
        //             console.log(error);
        //         }
        //     });
        // });

        $('#updateComboOfferBtn').click(function() {
            // Reset previous error messages
            $('#editOfferAmountError').text('');
            $('#editStatusError').text('');
            $('#editTreatmentsError').text('');

            // Validate form inputs
            var offerAmount = parseFloat($('#edit_offer_amount').val());
            var status = $('input[name="status"]:checked').val();
            var treatments = $('#edit_treatments').val();
            var totalTreatmentCost = 0;

            // Calculate total treatment cost
            $('#edit_treatments option:selected').each(function() {
                totalTreatmentCost += parseFloat($(this).data('cost'));
            });

            // Basic client-side validation
            if (!offerAmount) {
                $('#edit_offer_amount').addClass('is-invalid');
                $('#editOfferAmountError').text('Offer amount is required.');
                return;
            } else if (!$.isNumeric(offerAmount)) {
                $('#edit_offer_amount').addClass('is-invalid');
                $('#editOfferAmountError').text('The offer amount must be a number.');
                return;
            } else if (offerAmount > totalTreatmentCost) {
                $('#edit_offer_amount').addClass('is-invalid');
                $('#editOfferAmountError').text(
                    'The offer amount cannot exceed the total treatment cost.');
                return;
            } else {
                $('#edit_offer_amount').removeClass('is-invalid');
            }

            if (!status) {
                $('#editStatusError').text('Status is required.');
                return;
            } else {
                $('#editStatusError').text('');
            }

            if (!treatments || treatments.length === 0) {
                $('#editTreatmentsError').text('At least one treatment must be selected.');
                return;
            } else {
                $('#editTreatmentsError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editComboOfferForm');
            var comboOfferId = $('#edit_combo_offer_id').val();
            var url = form.attr('action').replace(':id', comboOfferId);
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('#modal-edit').modal('hide');
                    $('#successMessage').text('Combo offer updated successfully');
                    $('#successMessage').fadeIn().delay(3000).fadeOut();
                    table.draw(); // Refresh DataTable
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    if (errors.hasOwnProperty('offer_amount')) {
                        $('#edit_offer_amount').addClass('is-invalid');
                        $('#editOfferAmountError').text(errors.offer_amount[0]);
                    }
                    if (errors.hasOwnProperty('status')) {
                        $('#editStatusError').text(errors.status[0]);
                    }
                    if (errors.hasOwnProperty('treatments')) {
                        $('#editTreatmentsError').text(errors.treatments[0]);
                    }
                }
            });
        });

        // Update treatment table when treatments are selected
        $('#edit_treatments').change(function() {
            var selectedTreatments = $(this).find('option:selected');
            var treatmentDetailsTable = $('#editTreatmentDetailsTable tbody');
            var totalCost = 0;
            treatmentDetailsTable.empty();

            if (selectedTreatments.length > 0) {
                $('#editTreatDiv').show();
            } else {
                $('#editTreatDiv').hide();
            }

            selectedTreatments.each(function() {
                var treatmentName = $(this).text();
                var treatmentCost = parseFloat($(this).data('cost'));
                totalCost += treatmentCost;
                treatmentDetailsTable.append(
                    '<tr><td>' + treatmentName + '</td><td>' + treatmentCost.toFixed(2) +
                    '</td></tr>'
                );
            });

            // Update total cost
            $('#editTotalCost').text(totalCost.toFixed(2));
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editComboOfferForm').trigger('reset');
            $('#edit_offer_amount').removeClass('is-invalid');
            $('#editStatusError').text('');
            $('#editTreatmentsError').text('');
            $('#editTreatmentDetailsTable tbody').empty(); // Clear treatment details table
            $('#editTotalCost').text('0.00'); // Reset total cost
            $('#editTreatDiv').hide(); // Hide treatment table
        });

        // Pre-populate form fields when modal opens for editing
        $('#modal-edit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var comboOfferId = button.data('id');

            // Fetch combo offer details via AJAX
            $.ajax({
                url: '{{ url('combo_offer') }}' + "/" + comboOfferId + "/edit",
                method: 'GET',
                success: function(response) {
                    $('#edit_combo_offer_id').val(response.id);
                    $('#edit_offer_amount').val(response.offer_amount);
                    $('input[name="status"][value="' + response.status + '"]').prop(
                        'checked', true);

                    // Clear previous selections
                    $('#edit_treatments').empty();

                    // Populate the treatments select box
                    response.treatments.forEach(function(treatment) {
                        var option = $('<option></option>')
                            .attr('value', treatment.id)
                            .attr('data-cost', treatment
                                .treat_cost) // Add data-cost attribute
                            .text(treatment.treat_name);

                        // Pre-select treatments
                        if (response.comboOffer_treatments.includes(treatment.id)) {
                            option.prop('selected', true);
                        }

                        $('#edit_treatments').append(option);
                    });

                    $('#edit_treatments').trigger('change');
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });




    });
</script>
