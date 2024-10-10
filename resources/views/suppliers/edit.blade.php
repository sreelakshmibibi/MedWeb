<form id="editSupplierForm" method="post" action="{{ route('suppliers.update') }}">
    @csrf
    <input type="hidden" id="edit_supplier_id" name="edit_supplier_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Edit Supplier Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessage" style="display:none;" class="alert alert-danger">
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                    <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_supplier_name" class="form-label">Name<span
                                                        class="text-danger">*</span></label>                    
                                    <input class="form-control" type="text" id="edit_supplier_name" name="edit_supplier_name"
                                    placeholder="Supplier Name">
                                    <div id="editSupplierNameError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_supplier_name" class="form-label">Phone<span
                                                        class="text-danger">*</span></label>                    
                                    <input class="form-control" type="text" id="edit_supplier_phone" name="edit_supplier_phone"
                                    placeholder="Supplier Phone">
                                    <div id="editSupplierPhoneError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_gst" class="form-label">GST No.<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="edit_supplier_gst" name="edit_supplier_gst"
                                            placeholder="Supplier GST">
                                    <div id="editSupplierGstError" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        <div>
                            
                        <div class="row">
                            <div class="form-group">
                                <label for="edit_supplier_address" class="form-label">Address<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" type="text" id="edit_supplier_address" name="edit_supplier_address"
                                        placeholder="Supplier Address"></textarea>
                                <div id="editSupplierAddressError" class="invalid-feedback"></div>
                            </div>
                        </div>
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
                    <button type="button" class="btn btn-success float-end" id="updateSupplierBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Update button click
        $('#updateSupplierBtn').click(function() {
            // Reset previous error messages
            $('#edit_supplier_name').removeClass('is-invalid');
            $('#edit_supplier_phone').removeClass('is-invalid');
            $('#edit_gst').removeClass('is-invalid');
            $('#edit_supplier_address').removeClass('is-invalid');
            $('#editSupplierNameError').text('');
            $('#editSupplierPhoneError').text('');
            $('#editSupplierGSTError').text('');
            $('#editSupplierAddressError').text('');
            // Validate form inputs
            var supplierName = $('#edit_supplier_name').val();
            var supplierPhone = $('#edit_supplier_phone').val();
            var supplierGst = $('#edit_supplier_gst').val();
            var supplierAddress = $('#edit_supplier_address').val();


            // Basic client-side validation (you can add more as needed)
            if (supplierName.length == 0) {
                $('#edit_supplier_name').addClass('is-invalid');
                $('#editSupplierNameError').text('Supplier name is required.');
                return; // Prevent further execution
            } else {
                $('#edit_supplier_name').removeClass('is-invalid');
                $('#editSupplierNameError').text('');
            }

            if (supplierPhone.length == 0) {
                $('#edit_supplier_phone').addClass('is-invalid');
                $('#editSupplierPhoneError').text('Phone number is required.');
                return; // Prevent further execution
            } else {
                $('#edit_supplier_phone').removeClass('is-invalid');
                $('#editSupplierPhoneError').text('');
            }
            
            // if (supplierGst.length == 0) {
            //     $('#edit_supplier_gst').addClass('is-invalid');
            //     $('#editSupplierGstError').text('GST is required.');
            //     return; // Prevent further execution
            // } else {
            //     $('#edit_supplier_phone').removeClass('is-invalid');
            //     $('#editsupplierGstError').text('');
            // }

            if (supplierAddress.length == 0) {
                $('#edit_supplier_address').addClass('is-invalid');
                $('#editsSupplierAddressError').text('Address is required.');
                return; // Prevent further execution
            } else {
                $('#edit_supplier_address').removeClass('is-invalid');
                $('#editSupplierAddressError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editSupplierForm');
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
                        $('#errorMessage').fadeIn().delay(3000)
                            .fadeOut();
                    }
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('name')) {
                        $('#edit_supplier_name').addClass('is-invalid');
                        $('#editSupplierNameError').text(errors.name[0]);
                    }

                    if (errors.hasOwnProperty('phone')) {
                        $('#edit_supplier_phone').addClass('is-invalid');
                        $('#editSupplierPhoneError').text(errors.phone[0]);
                    }
                    
                    if (errors.hasOwnProperty('address')) {
                        $('#edit_supplier_address').addClass('is-invalid');
                        $('#editSupplierAddressError').text(errors.address[0]);
                    }
                    if (errors.hasOwnProperty('gst')) {
                        $('#edit_supplier_gst').addClass('is-invalid');
                        $('#editSupplierPhoneError').text(errors.gst[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editSupplerForm').trigger('reset');
            $('#edit_supplier_name').removeClass('is-invalid');
            $('#edit_supplier_phone').removeClass('is-invalid');
            $('#edit_supplier_gst').removeClass('is-invalid');
            $('#edit_supplier_address').removeClass('is-invalid');
            $('#editsSupplierAddressError').text('');
            $('#editSupplierGSTError').text('');
            $('#editSupplierNameError').text('');
            $('#editSupplierPhoneError').text('');
        });
    });
</script>
