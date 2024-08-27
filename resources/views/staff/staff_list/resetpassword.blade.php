<form action="{{ route('reset.password') }}" method="POST" id="resetProfilePassword">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-resetpassword" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-key"></i> Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                    <div id="errorMessagecreate" style="display:none;" class="alert alert-danger">
                    </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email ID</label>
                            <input class="form-control" type="text" id="email" name="email"
                                placeholder="Email ID" required readonly>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cpassword">Current Password <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="password" id="cpassword" name="cpassword" required
                                placeholder="Current Password">
                            <div id="cPasswordError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="newpassword">New Password <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="password" id="newpassword" name="newpassword"
                                placeholder="New Password" required>
                                <div id="nPasswordError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="retypepassword">Retype New Password <span
                                    class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="retypepassword" name="retypepassword"
                                placeholder="Retype New Password" required>
                                <div id="rPasswordError" class="invalid-feedback"></div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="resetPasswordBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        // Handle Save button click
        $('#resetPasswordBtn').click(function() {
            // Reset previous error messages
            $('#cPasswordError').text('');
            $('#nPasswordError').text('');
            $('#rPasswordError').text('');
            // Validate form inputs
            var cPassword = $('#cpassword').val();
            var nPassword = $('#newpassword').val();
            var rPassword = $('#retypepassword').val();
            
            // Basic client-side validation (you can add more as needed)
            if (cPassword.length === 0) {
                $('#cpassword').addClass('is-invalid');
                $('#cPasswordError').text('Current password is required.');
                return; // Prevent further execution
            } else {
                $('#cpassword').removeClass('is-invalid');
                $('#cPasswordError').text('');
            }
            if (nPassword.length === 0) {
                $('#newpassword').addClass('is-invalid');
                $('#nPasswordError').text('New Password is required.');
                return; // Prevent further execution
            } else {
                $('#newpassword').removeClass('is-invalid');
                $('#nPasswordError').text('');
            }
            if (rPassword.length === 0) {
                $('#retypepassword').addClass('is-invalid');
                $('#rPasswordError').text('confirm password is required.');
                return; // Prevent further execution
            } else {
                $('#retypepassword').removeClass('is-invalid');
                $('#rPasswordError').text('');
            }

            if (rPassword !== nPassword) {
                $('#retypepassword').addClass('is-invalid');
                $('#rPasswordError').text('Password mismatch.');
                return; // Prevent further execution
            } else {
                $('#retypepassword').removeClass('is-invalid');
                $('#rPasswordError').text('');
            }

            

            // If validation passed, submit the form via AJAX
            var form = $('#resetProfilePassword');
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
                            $('#modal-resetpassword').modal('hide');
                            table.ajax.reload();
                    
                    }
                    if (response.error) {
                        $('#errorMessagecreate').text(response.error);
                        $('#errorMessagecreate').fadeIn().delay(3000)
                        .fadeOut(); 
                    }
                    
                    // location.reload();
                  
                },
                error: function(xhr) {
                    
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('password')) {
                        $('#cpassword').addClass('is-invalid');
                        $('#cPasswordError').text(errors.password[0]);
                    }

                }
            });
        });
        // Reset form and errors on modal close
        $('#modal-resetpassword').on('hidden.bs.modal', function() {
            $('#resetProfilePassword').trigger('reset');
            $('#cpassword').removeClass('is-invalid');
            $('#cPasswordError').text('');
            $('#npassword').removeClass('is-invalid');
            $('#nPasswordError').text('');
            $('#retypepassword').removeClass('is-invalid');
            $('#rPasswordError').text('');
            
        });
    });
</script>
