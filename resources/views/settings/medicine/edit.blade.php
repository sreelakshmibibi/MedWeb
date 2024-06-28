<form id="editMedicineForm" method="post" action="{{ route('settings.medicine.update', ['medicine' => ':id']) }}">
    @csrf
    <input type="hidden" id="edit_medicine_id" name="edit_medicine_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"> </i> Edit Medicine Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="form-group">
                            <label class="form-label" for="name">Medicine Name</label>
                            <input class="form-control" type="text" id="edit_med_name" name="med_name"
                                placeholder="Medicine Name">
                            <div id="editMedNameError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-label" for="med_bar_code">Barcode</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="edit_med_bar_code"
                                        name="med_bar_code" placeholder="Enter text..." readonly>
                                    <div id="medBarcodeError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="form-label" for="med_company">Company Name</label>
                            <input class="form-control" type="text" id="edit_med_company" name="med_company"
                                placeholder="Medicine Company Name">
                            <div id="editMedCompanyError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="med_price">Price</label>
                                    <input class="form-control" type="text" id="edit_med_price" name="med_price"
                                        placeholder="Medicine Price">
                                    <div id="editMedPriceError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="expiry_date">Expiring Date</label>
                                    <input class="form-control" type="date" id="edit_expiry_date" name="expiry_date">
                                    <div id="editMedExpDateError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_units_per_package">Units per Package</label>
                                    <input class="form-control" type="text" id="edit_units_per_package"
                                        name="units_per_package" placeholder="Number of units per package.">
                                    <div id="editMedUnitPerPackError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_package_count">Package Count</label>
                                    <input class="form-control" type="text" id="edit_package_count"
                                        name="package_count" placeholder="Total number of packages"
                                        onblur="generateTotalQuantity()">
                                    <div id="editmedPackageCountErrorError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_total_quantity">Total Quantity</label>
                                    <input class="form-control" type="text" id="edit_total_quantity"
                                        name="total_quantity"
                                        placeholder=" Total number of units available across all packages" readonly>
                                    <div id="editMedQuantityError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_package_type">Packaging Type</label>
                                    <select class="form-control" id="edit_package_type" name="package_type">
                                        <option value="">Select packaging type</option>
                                        <option value="Strip">Strip</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div id="editMedPackageTypeError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="med_remarks">Remarks</label>
                            <input class="form-control" type="text" id="edit_med_remarks" name="med_remarks"
                                placeholder="Medicine Remarks">
                            <div id="editMedRemarkError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Stock Status</label>
                            <div>
                                <input name="stock_status" type="radio" class="form-control-input" id="edit_in"
                                    value="In Stock">
                                <label class="form-check-label" for="edit_in">In Stock</label>
                                <input name="stock_status" type="radio" class="form-control-input" id="edit_out"
                                    value="Out of Stock">
                                <label class="form-check-label" for="edit_out">Out of Stock</label>
                            </div>
                            <div class="text-danger" id="editMedStockStatusError"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <div>
                                <input name="status" type="radio" class="form-control-input" id="med_edit_yes"
                                    value="Y">
                                <label class="form-check-label" for="med_edit_yes">Yes</label>
                                <input name="status" type="radio" class="form-control-input" id="med_edit_no"
                                    value="N">
                                <label class="form-check-label" for="med_edit_no">No</label>
                            </div>
                            <div class="text-danger" id="editMedStatusError"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="updateMedicineBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {

        // Handle Update button click
        $('#updateMedicineBtn').click(function() {
            // Reset previous error messages
            $('#editMedNameError').text('');
            $('#editMedBarcodeError').text('');
            $('#editMedCompanyError').text('');
            $('#editMedPriceError').text('');
            $('#editMedExpDateError').text('');
            $('#editMedUnitPerPackError').text('');
            $('#editmedPackageCountErrorError').text('');
            $('#editMedQuantityError').text('');
            $('#editMedPackageTypeError').text('');
            $('#editMedRemarkError').text('');
            $('#editMedStockStatusError').text('');
            $('#editMedStatusError').text('');

            // Validate form inputs
            var medName = $('#edit_med_name').val();
            var medBarcode = $('#edit_med_bar_code').val();
            var medCompany = $('#edit_med_company').val();
            var medPrice = $('#edit_med_price').val();
            var medExpiryDate = $('#edit_expiry_date').val();
            var medUnittPerPack = $('#edit_units_per_package').val();
            var packageCount = $('#edit_package_count').val();
            var packageType = $('#edit_package_type').val();
            var medQuantity = $('#edit_total_quantity').val();
            var medRemarks = $('#edit_med_remarks').val();
            var stockStatus = $('input[name="stock_status"]:checked').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (medName.length === 0) {
                $('#edit_med_name').addClass('is-invalid');
                $('#editMedNameError').text('Medicine name is required.');
                return; // Prevent further execution
            } else {
                $('#edit_med_name').removeClass('is-invalid');
                $('#editMedNameError').text('');
            }
            if (medBarcode.length === 0) {
                $('#edit_med_bar_code').addClass('is-invalid');
                $('#editMedBarcodeError').text('Medicine Barcode is required.');
                return; // Prevent further execution
            } else {
                $('#edit_med_bar_code').removeClass('is-invalid');
                $('#editMedBarcodeError').text('');
            }
            if (medCompany.length === 0) {
                $('#edit_med_company').addClass('is-invalid');
                $('#editMedCompanyError').text('Medicine Company name is required.');
                return; // Prevent further execution
            } else {
                $('#edit_med_company').removeClass('is-invalid');
                $('#editMedCompanyError').text('');
            }
            if (medPrice.length === 0) {
                $('#edit_med_price').addClass('is-invalid');
                $('#editMedPriceError').text('Medicine price is required.');
                return; // Prevent further execution
            } else if (!$.isNumeric(medPrice)) {
                $('#edit_med_price').addClass('is-invalid');
                $('#editMedPriceError').text('Medicine price must be a number.');
                return; // Prevent further execution
            } else {
                $('#edit_med_price').removeClass('is-invalid');
                $('#editMedPriceError').text('');
            }
            if (medExpiryDate.length === 0) {
                $('#edit_expiry_date').addClass('is-invalid');
                $('#editMedExpDateError').text(' The expiry date is required.');
                return; // Prevent further execution
            } else {
                $('#edit_expiry_date').removeClass('is-invalid');
                $('#editMedExpDateError').text('');
            }
            if (medUnittPerPack.length === 0) {
                $('#edit_units_per_package').addClass('is-invalid');
                $('#editMedUnitPerPackError').text('The units per package are required.');
                return; // Prevent further execution
            } else if (!$.isNumeric(medUnittPerPack)) {
                $('#edit_units_per_package').addClass('is-invalid');
                $('#medUnitPerPackError').text('The units per package must be an integer.');
                return; // Prevent further execution
            } else {
                $('#edit_units_per_package').removeClass('is-invalid');
                $('#editMedUnitPerPackError').text('');
            }
            if (packageCount.length === 0) {
                $('#edit_package_count').addClass('is-invalid');
                $('#editMedPackageCountError').text('The package count is required.');
                return; // Prevent further execution
            } else if (!$.isNumeric(packageCount)) {
                $('#edit_package_count').addClass('is-invalid');
                $('#editMedPackageCountError').text('The package count must be an integer.');
                return; // Prevent further execution
            } else {
                $('#edit_package_count').removeClass('is-invalid');
                $('#editMedPackageCountError').text('');
            }
            if (medQuantity.length === 0) {
                $('#edit_total_quantity').addClass('is-invalid');
                $('#editMedQuantityError').text('The total quantity is required.');
                return; // Prevent further execution
            } else if (!$.isNumeric(medQuantity)) {
                $('#edit_total_quantity').addClass('is-invalid');
                $('#editMedQuantityError').text('The total quantity must be an integer.');
                return; // Prevent further execution
            } else {
                $('#edit_total_quantity').removeClass('is-invalid');
                $('#editMedQuantityError').text('');
            }
            if (packageType.length === 0) {
                $('#edit_package_type').addClass('is-invalid');
                $('#editMedPackageTypeError').text('The package type is required.');
                return; // Prevent further execution
            } else {
                $('#edit_package_type').removeClass('is-invalid');
                $('#editMedPackageTypeError').text('');
            }
            if (!stockStatus) {
                $('#editMedStockStatusError').text('Medicine stock status is required.');
                return; // Prevent further execution
            } else {
                $('#editMedStockStatusError').text('');
            }
            if (!status) {
                $('#editMedStatusError').text('Status is required.');
                return; // Prevent further execution
            } else {
                $('#editMedStatusError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editMedicineForm');
            var medicineId = $('#edit_medicine_id').val();
            var url = form.attr('action').replace(':id', medicineId);
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // If successful, hide modal and show success message
                    $('#modal-edit').modal('hide');
                    $('#successMessage').text('Medicine updated successfully');
                    $('#successMessage').fadeIn().delay(3000)
                .fadeOut(); // Show for 3 seconds
                    // table.draw(); // Refresh DataTable
                    location.reload();
                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('med_name')) {
                        $('#edit_med_name').addClass('is-invalid');
                        $('#editMedNameError').text(errors.med_name[0]);
                    }
                    if (errors.hasOwnProperty('med_bar_code')) {
                        $('#edit_med_bar_code').addClass('is-invalid');
                        $('#editMedBarcodeError').text(errors.med_bar_code[0]);
                    }
                    if (errors.hasOwnProperty('med_company')) {
                        $('#edit_med_company').addClass('is-invalid');
                        $('#editMedCompanyError').text(errors.med_company[0]);
                    }
                    if (errors.hasOwnProperty('med_remarks')) {
                        $('#edit_med_remarks').addClass('is-invalid');
                        $('#editMedRemarkError').text(errors.med_remarks[0]);
                    }
                    if (errors.hasOwnProperty('med_price')) {
                        $('#edit_med_price').addClass('is-invalid');
                        $('#editMedPriceError').text(errors.med_price[0]);
                    }
                    if (errors.hasOwnProperty('expiry_date')) {
                        $('#edit_expiry_date').addClass('is-invalid');
                        $('#editMedExpDateError').text(errors.expiry_date[0]);
                    }
                    if (errors.hasOwnProperty('units_per_package')) {
                        $('#edit_units_per_package').addClass('is-invalid');
                        $('#editMedUnitPerPackError').text(errors.units_per_package[0]);
                    }
                    if (errors.hasOwnProperty('package_count')) {
                        $('#edit_package_count').addClass('is-invalid');
                        $('#editMedPackageCountError').text(errors.package_count[0]);
                    }
                    if (errors.hasOwnProperty('total_quantity')) {
                        $('#edit_total_quantity').addClass('is-invalid');
                        $('#editMedQuantityError').text(errors.total_quantity[0]);
                    }
                    if (errors.hasOwnProperty('package_type')) {
                        $('#edit_package_type').addClass('is-invalid');
                        $('#editMedPackageTypeError').text(errors.package_type[0]);
                    }
                    if (errors.hasOwnProperty('stock_status')) {
                        $('#editMedStockStatusError').text(errors.stock_status[0]);
                    }
                    if (errors.hasOwnProperty('status')) {
                        $('#editMedStatusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editMedicineForm').trigger('reset');
            $('#edit_med_name').removeClass('is-invalid');
            $('#edit_med_bar_code').removeClass('is-invalid');
            $('#edit_med_company').removeClass('is-invalid');
            $('#edit_med_price').removeClass('is-invalid');
            $('#edit_expiry_date').removeClass('is-invalid');
            $('#edit_units_per_package').removeClass('is-invalid');
            $('#edit_package_count').removeClass('is-invalid');
            $('#edit_total_quantity').removeClass('is-invalid');
            $('#edit_package_type').removeClass('is-invalid');
            $('#edit_med_remarks').removeClass('is-invalid');

            $('#edit_med_name').next('.invalid-feedback').text('');
            $('#edit_med_bar_code').next('.invalid-feedback').text('');
            $('#edit_med_company').next('.invalid-feedback').text('');
            $('#edit_med_price').next('.invalid-feedback').text('');
            $('#edit_expiry_date').next('.invalid-feedback').text('');
            $('#edit_units_per_package').next('.invalid-feedback').text('');
            $('#edit_total_quantity').next('.invalid-feedback').text('');
            $('#edit_package_count').next('.invalid-feedback').text('');
            $('#edit_package_type').next('.invalid-feedback').text('');
            $('#edit_med_remarks').next('.invalid-feedback').text('');

            $('#editMedNameError').text('');
            $('#editMedBarcodeError').text('');
            $('#editMedCompanyError').text('');
            $('#editMedPriceError').text('');
            $('#editMedExpDateError').text('');
            $('#editMedUnitPerPackError').text('');
            $('#editmedPackageCountErrorError').text('');
            $('#editMedQuantityError').text('');
            $('#editMedPackageTypeError').text('');
            $('#editMedRemarkError').text('');
            $('#editMedStockStatusError').text('');
            $('#editMedStatusError').text('');

        });

        // Pre-populate form fields when modal opens for editing
        $('#modal-edit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var medicineId = button.data('id'); // Extract medicine ID from data-id attribute

            // Fetch medicine details via AJAX
            $.ajax({
                url: '{{ url('medicine', '') }}' + "/" + medicineId + "/edit",
                method: 'GET',
                success: function(response) {
                    // Populate form fields
                    $('#edit_medicine_id').val(response.id);
                    // Clear input value and ensure no autofill suggestions
                    $('#edit_med_name').val('').focus().val(response.med_name);
                    $('#edit_med_bar_code').val('').focus().val(response.med_bar_code);
                    $('#edit_med_company').val('').focus().val(response.med_company);
                    $('#edit_med_price').val('').focus().val(response.med_price);
                    $('#edit_expiry_date').val('').focus().val(response.expiry_date);
                    $('#edit_units_per_package').val(response.units_per_package);
                    $('#edit_package_count').val(response.package_count);
                    $('#edit_total_quantity').val(response.total_quantity);
                    $('#edit_package_type').val(response.package_type);
                    $('#edit_med_remarks').val('').focus().val(response.med_remarks);
                    // Set radio button status
                    if (response.status === 'Y') {
                        $('#med_edit_yes').prop('checked', true);
                    } else {
                        $('#med_edit_no').prop('checked', true);
                    }

                    if (response.stock_status === 'In Stock') {
                        $('#edit_in').prop('checked', true);
                    } else {
                        $('#edit_out').prop('checked', true);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });

    function generateTotalQuantity() {
        var medUnittPerPack = $('#edit_units_per_package').val();
        var packageCount = $('#edit_package_count').val();
        if (medUnittPerPack.length === 0) {
            alert('The units per package are required.');
            return; // Prevent further execution
        } else if (!$.isNumeric(medUnittPerPack)) {
            alert('The units per package must be an integer.');
            return; // Prevent further execution
        } else if (packageCount.length === 0) {
            alert('The package count is required.');
            return; // Prevent further execution
        } else if (!$.isNumeric(packageCount)) {
            alert('The package count must be an integer.');
            return; // Prevent further execution
        } else {
            var total = medUnittPerPack * packageCount;
            $('#edit_total_quantity').val(total);
        }
    }
</script>
