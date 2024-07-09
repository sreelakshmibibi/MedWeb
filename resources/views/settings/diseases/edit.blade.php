<form id="editDiseaseForm" method="post" action="{{ route('settings.disease.update') }}">
    @csrf
    <input type="hidden" id="edit_disease_id" name="edit_disease_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-square-virus"></i> Edit Disease Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="edit_icd_code">Disease</label>
                            <input class="form-control" type="text" id="edit_icd_code" name="edit_icd_code" required
                                minlength="3" placeholder="ICD Code" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_disease">Disease</label>
                            <input class="form-control" type="text" id="edit_disease" name="edit_disease" required
                                minlength="3" placeholder="Disease Name" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="edit_description">Description (if any)</label>
                            <textarea class="form-control" id="edit_description" name="edit_description" placeholder="Description"></textarea>
                            <div id="descriptionError" class="invalid-feedback"></div>
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
                    <button type="button" class="btn btn-success float-end" id="updateDiseaseBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Update button click
        $('#updateDiseaseBtn').click(function() {
            // Reset previous error messages
            $('#edit_icd_code').removeClass('is-invalid');
            $('#edit_icd_code').next('.invalid-feedback').text('');
            $('#edit_disease').removeClass('is-invalid');
            $('#edit_disease').next('.invalid-feedback').text('');
            $('#edit_description').removeClass('is-invalid');
            $('#edit_description').next('.invalid-feedback').text('');
            $('#statusError').text('');

            // Validate form inputs
            var icdCode = $('#edit_icd_code').val();
            var disease = $('#edit_disease').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (disease.length === 0) {
                $('#edit_disease').addClass('is-invalid');
                $('#edit_disease').next('.invalid-feedback').text('Disease name is required.');
                return; // Prevent further execution
            }
            if (icdCode.length === 0) {
                $('#edit_icd_code').addClass('is-invalid');
                $('#edit_icd_code').next('.invalid-feedback').text('ICD Code is required.');
                return; // Prevent further execution
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editDiseaseForm');
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
                    $('#successMessage').text('Disease updated successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                    // location.reload(); // Refresh the page or update the table as needed
                    table.ajax.reload();
                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;
                    if (errors.hasOwnProperty('name')) {
                        $('#edit_disease').addClass('is-invalid');
                        $('#edit_disease').next('.invalid-feedback').text(errors
                            .name[0]);
                    }
                    if (errors.hasOwnProperty('icd_code')) {
                        $('#edit_icd_code').addClass('is-invalid');
                        $('#edit_icd_code').next('.invalid-feedback').text(errors
                            .icd_code[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#statusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editDiseaseForm').trigger('reset');
            $('#edit_disease').removeClass('is-invalid');
            $('#edit_disease').next('.invalid-feedback').text('');
            $('#edit_description').removeClass('is-invalid');
            $('#edit_description').next('.invalid-feedback').text('');
            $('#edit_icd_code').removeClass('is-invalid');
            $('#edit_icd_code').next('.invalid-feedback').text('');

            $('#statusError').text('');
        });

        // Pre-populate form fields when modal opens for editing
        $('#modal-edit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var diseaseId = button.data('id'); // Extract disease ID from data-id attribute

            // Fetch disease details via AJAX
            $.ajax({
                url: '{{ url('disease') }}' + "/" + diseaseId + "/edit",
                method: 'GET',
                success: function(response) {
                    // Populate form fields
                    $('#edit_disease_id').val(response.id);
                    $('#edit_icd_code').val(response.icd_code);
                    $('#edit_disease').val(response.name);
                    $('#edit_decription').val(response.description);

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
