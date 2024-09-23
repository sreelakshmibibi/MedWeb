<form id="createSupplierForm" method="post" action="{{ route('suppliers.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Technician Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessagecreate" style="display:none;" class="alert alert-danger">
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Supplier Name<span
                                                        class="text-danger">*</span></label>                    
                                    <input class="form-control" type="text" id="name" name="name"
                                    placeholder="Supplier Name">
                                    <div id="supplierNameError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Supplier Phone<span
                                                        class="text-danger">*</span></label>                    
                                    <input class="form-control" type="text" id="phone" name="phone"
                                    placeholder="Supplier Phone">
                                    <div id="supplierPhoneError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gst" class="form-label">GST No.<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="gst" name="gst"
                                            placeholder="GST">
                                    <div id="supplierGstError" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        <div>
                            
                        <div class="row">
                            <div class="form-group">
                                <label for="address" class="form-label">Supplier Address<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" type="text" id="address" name="address"
                                        placeholder="Supplier Address"></textarea>
                                <div id="supplierAddressError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        </div>
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
                    <button type="button" class="btn btn-success float-end" id="saveSupplierBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Save button click
        $('#saveSupplierBtn').click(function() {
            // Reset previous error messages
            $('#supplierNameError').text('');
            $('#supplierPhoneError').text('');
            $('#supplierGstError').text('');
            $('#supplierAddressError').text('');

            // Validate form inputs
            var supplierName = $('#name').val();
            var supplierPhone = $('#phone').val();
            var supplierGst = $('#gst').val();
            var supplierAddress = $('#address').val();

            // Basic client-side validation (you can add more as needed)
            if (supplierName.length == 0) {
                $('#name').addClass('is-invalid');
                $('#supplierNameError').text('Supplier name is required.');
                return; // Prevent further execution
            } else {
                $('#name').removeClass('is-invalid');
                $('#supplierNameError').text('');
            }

            if (supplierPhone.length == 0) {
                $('#phone').addClass('is-invalid');
                $('#supplierPhoneError').text('Supplier number is required.');
                return; // Prevent further execution
            } else {
                $('#phone').removeClass('is-invalid');
                $('#supplierPhoneError').text('');
            }
            
            // if (supplierGst.length == 0) {
            //     $('#gst').addClass('is-invalid');
            //     $('#supplierGstError').text('GST is required.');
            //     return; // Prevent further execution
            // } else {
            //     $('#gst').removeClass('is-invalid');
            //     $('#supplierGstError').text('');
            // }

            if (supplierAddress.length == 0) {
                $('#address').addClass('is-invalid');
                $('#supplierAddressError').text('Supplier address is required.');
                return; // Prevent further execution
            } else {
                $('#address').removeClass('is-invalid');
                $('#supplierAddressError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#createSupplierForm');
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
                        $('#errorMessagecreate').fadeIn().delay(3000)
                        .fadeOut(); 
                    }
                    
                    // location.reload();
                  
                },
                error: function(xhr) {
                    
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('name')) {
                        $('#name').addClass('is-invalid');
                        $('#supplierNameError').text(errors.name[0]);
                    }

                    if (errors.hasOwnProperty('phone')) {
                        $('#phone').addClass('is-invalid');
                        $('#supplierPhoneError').text(errors.phone[0]);
                    }
                    
                    if (errors.hasOwnProperty('gst')) {
                        $('#gst').addClass('is-invalid');
                        $('#supplierGstError').text(errors.gst[0]);
                    }
                    
                    if (errors.hasOwnProperty('address')) {
                        $('#address').addClass('is-invalid');
                        $('#supplierAddressError').text(errors.address[0]);
                    }
                    
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createSupplierForm').trigger('reset');
            $('#name').removeClass('is-invalid');
            $('#phone').removeClass('is-invalid');
            $('#gst').removeClass('is-invalid');
            $('#address').removeClass('is-invalid');
            $('#supplierNameError').text('');
            $('#supplierPhoneError').text('');
            $('#supplierGstError').text('');
            $('#supplierAddressError').text('');
        });
    });
</script>
