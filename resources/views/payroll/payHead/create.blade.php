<form id="createPayHeadForm" method="post" action="{{ route('payHeads.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Add Payhead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessagecreate" style="display:none;" class="alert alert-danger">
                </div>
                
                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- Department Name -->
                        <div class="form-group">
                            <label class="form-label" for="head_type">Payhead Name <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="head_type" name="head_type"
                                placeholder="Payhead Name">
                            <div id="headTypeError" class="invalid-feedback"></div>
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
                    <button type="button" class="btn btn-success float-end" id="savePayHeadBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Include custom JavaScript file -->
<script src="{{ asset('js/payHead.js') }}"></script>

<script>
    $(function() {
        // Handle Save button click
        $('#savePayHeadBtn').click(function() {
            // Reset previous error messages
            $('#headTypeError').text('');
            $('#statusError').text('');
            $('#errorMessagecreate').text('');
            // Validate form inputs
            var head_type = $('#head_type').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (head_type.length === 0) {
                $('#head_type').addClass('is-invalid');
                $('#headTypeError').text('Pay head type is required.');
                return; // Prevent further execution
            } else {
                $('#head_type').removeClass('is-invalid');
                $('#headTypeError').text('');
            }

            if (!status) {
                $('#statusError').text('Status is required.');
                return; // Prevent further execution
            } else {
                $('#statusError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#createPayHeadForm');
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // If successful, hide modal and show success message
                    if (response.success) {
                        $('#successMessage').text(response.success);
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                            $('#modal-right').modal('hide');
                            table.ajax.reload();
                    
                    }
                    if (response.error) {
                        $('#errorMessagecreate').text(response.error);
                       
                    }
                    
                    // location.reload();
                  
                },
                error: function(xhr) {
                    
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('head_type')) {
                        $('#head_type').addClass('is-invalid');
                        $('#headTypeError').text(errors.head_type[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#statusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createPayHeadForm').trigger('reset');
            $('#head_type').removeClass('is-invalid');
            $('#headTypeError').text('');
            $('#statusError').text('');
        });
    });
</script>
