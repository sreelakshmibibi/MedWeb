<form id="createDiseaseForm" method="post" action="{{ route('settings.disease.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-square-virus"></i> Disease Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="icd_code">ICD Code <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="icd_code" name="icd_code"
                                placeholder="Disease ICD Code">
                            <div id="codeError" class="invalid-feedback"></div>
                        </div>
                        <!-- Disease Name -->
                        <div class="form-group">
                            <label class="form-label" for="name">Disease <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="name" name="name"
                                placeholder="Disease Name">
                            <div id="diseaseError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Description (if any)</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Description"></textarea>
                            <div id="descriptionError" class="invalid-feedback"></div>
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
                    <button type="button" class="btn btn-success float-end" id="saveDiseaseBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Save button click
        $('#saveDiseaseBtn').click(function() {
            // Reset previous error messages
            $('#codeError').text('');
            $('#diseaseError').text('');
            $('#descriptionError').text('');
            $('#statusError').text('');

            // Validate form inputs
            var icdCode = $('#icd_code').val();
            var disease = $('#name').val();
            var description = $('#description').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (icdCode.length === 0) {
                $('#icd_code').addClass('is-invalid');
                $('#codeError').text('ICD Code is required.');
                return; // Prevent further execution
            } else {
                $('#icd_code').removeClass('is-invalid');
                $('#codeError').text('');
            }
            if (disease.length === 0) {
                $('#name').addClass('is-invalid');
                $('#diseaseError').text('Disease name is required.');
                return; // Prevent further execution
            } else {
                $('#name').removeClass('is-invalid');
                $('#diseaseError').text('');
            }

            if (!status) {
                $('#statusError').text('Status is required.');
                return; // Prevent further execution
            } else {
                $('#statusError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#createDiseaseForm');
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
                    $('#successMessage').text('Disease added successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                    // location.reload();
                    table.ajax.reload();
                    // Optionally, you can reload or update the table here
                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;
                    if (errors.hasOwnProperty('icd_code')) {
                        $('#icd_code').addClass('is-invalid');
                        $('#codeError').text(errors.disease[0]);
                    }
                    if (errors.hasOwnProperty('name')) {
                        $('#name').addClass('is-invalid');
                        $('#diseaseError').text(errors.name[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#statusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createDiseaseForm').trigger('reset');
            $('#icd_code').removeClass('is-invalid');
            $('#codeError').text('');
            $('#name').removeClass('is-invalid');
            $('#diseaseError').text('');
            $('#descriptionError').text('');
            $('#statusError').text('');
        });
    });
</script>
