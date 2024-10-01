<form id="editPayHeadForm" method="post" action="{{ route('payHeads.update') }}">
    @csrf
    <input type="hidden" id="edit_payhead_id" name="edit_payhead_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-payhead-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Edit PayHead Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessage" style="display:none;" class="alert alert-danger">
                </div>
                
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="edit_head_type">PayHead type <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="edit_head_type" name="edit_head_type" required
                                minlength="3" placeholder="Pay head Name" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_type">Type <span class="text-danger">
                                    *</span></label>
                           <select class="form-control" id="edit_type" name="edit_type">
                              <option value="{{App\Models\PayHead::E}}">{{App\Models\PayHead::E_WORDS}}</option>
                              <option value="{{App\Models\PayHead::SA}}">{{App\Models\PayHead::SA_WORDS}}</option>
                              <option value="{{App\Models\PayHead::SD}}">{{App\Models\PayHead::SD_WORDS}}</option>
                           </select>
                            <div id="editTypeError" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <div>
                                <input name="edit_status" type="radio" class="form-control with-gap" id="edit_yes"
                                    value="Y">
                                <label class="form-check-label" for="edit_yes">Yes</label>
                                <input name="edit_status" type="radio" class="form-control with-gap" id="edit_no"
                                    value="N">
                                <label class="form-check-label" for="edit_no">No</label>
                            </div>
                            <div class="text-danger" id="statusError"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="updatePayHeadBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Update button click
        $('#updatePayHeadBtn').click(function() {
            // Reset previous error messages
            $('#edit_head_type').removeClass('is-invalid');
            $('#edit_head_type').next('.invalid-feedback').text('');
            $('#statusError').text('');
            $('#edit_type').removeClass('is-invalid');
            $('#edit_type').next('.invalid-feedback').text('');

            // Validate form inputs
            var headType = $('#edit_head_type').val();
            var type = $('#edit_type').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (headType.length === 0) {
                $('#edit_head_type').addClass('is-invalid');
                $('#edit_head_type').next('.invalid-feedback').text('Pay head is required.');
                return; // Prevent further execution
            }
            if (type.length === 0) {
                $('#edit_type').addClass('is-invalid');
                $('#edit_type').next('.invalid-feedback').text('Pay head type is required.');
                return; // Prevent further execution
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editPayHeadForm');
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // If successful, hide modal and show success message
                    $('#modal-payhead-edit').modal('hide');
                     if (response.success) {
                        $('#successMessage').text(response.success);
                        $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                    }
                    
                    // location.reload(); // Refresh the page or update the table as needed
                    table.ajax.reload();
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON.error);
                    if (xhr.responseJSON.error) {
                        $('#errorMessage').text(xhr.responseJSON.error);
                        // $('#errorMessage').fadeIn().delay(3000)
                        // .fadeOut(); 
                    }
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;
                   
                    if (errors.hasOwnProperty('head_type')) {
                        $('#edit_head_type').addClass('is-invalid');
                        $('#edit_head_type').next('.invalid-feedback').text(errors
                            .head_type[0]);
                    }
                    if (errors.hasOwnProperty('type')) {
                        $('#edit_type').addClass('is-invalid');
                        $('#edit_type').next('.invalid-feedback').text(errors
                            .type[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#statusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editPayHeadForm').trigger('reset');
            $('#edit_head_type').removeClass('is-invalid');
            $('#edit_head_type').next('.invalid-feedback').text('');
            $('#edit_type').removeClass('is-invalid');
            $('#edit_type').next('.invalid-feedback').text('');
            $('#statusError').text('');
        });
    });
</script>
