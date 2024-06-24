<form id="createMedicineForm" method="post" action="{{ route('settings.medicine.store') }}">
    @csrf
    <div class="modal modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog" style="width:40%; max-width: 80%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-prescription-bottle-medical"> </i> Medicine Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="form-group">
                            <label class="form-label" for="med_name">Medicine Name</label>
                            <input class="form-control" type="text" id="med_name" name="med_name" placeholder="Medicine Name">
                            <div id="medNameError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-label" for="med_bar_code">Barcode</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="med_bar_code" name="med_bar_code" placeholder="Enter text..." >
                                    {{-- <input type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload"> --}}
                                    <button class="btn btn-primary btn-sm input-group-text" type="button"
                                        id="inputGroupFileAddon04" onclick="generateBarcode()">Generate
                                        Barcode</button>
                                        <div id="medBarcodeError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <canvas id="barcodeCanvas" class="col-md-4" style=" height:64px;"></canvas>
                        </div>
                        

                        <div class="form-group">
                            <label class="form-label" for="med_company">Company Name</label>
                            <input class="form-control" type="text" id="med_company" name="med_company"  placeholder="Medicine Company Name">
                            <div id="medCompanyError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="med_price">Price</label>
                                    <input class="form-control" type="text" id="med_price" name="med_price"  placeholder="Medicine Price">
                                    <div id="medPriceError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="expiry_date">Expiring Date</label>
                                    <input class="form-control" type="date" id="expiry_date" name="expiry_date">
                                    <div id="medExpDateError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="units_per_package">Units per Package</label>
                                    <input class="form-control" type="text" id="units_per_package" name="units_per_package"  placeholder="Number of units per package.">
                                    <div id="medUnitPerPackError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="package_count">Package Count</label>
                                    <input class="form-control" type="text" id="package_count" name="package_count"  placeholder="Total number of packages" onblur="generateTotalQuantity()">
                                    <div id="medPackageCountError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="total_quantity">Total Quantity</label>
                                    <input class="form-control" type="text" id="total_quantity" name="total_quantity"  placeholder=" Total number of units available across all packages" readonly>
                                    <div id="medQuantityError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="package_type">Packaging Type</label>
                                    <select class="form-control" id="package_type" name="package_type">
                                        <option value="" disabled selected>Select packaging type</option>
                                        <option value="Strip">Strip</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div id="medPackageTypeError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="med_remarks">Remarks</label>
                            <input class="form-control" type="text" id="med_remarks" name="med_remarks" placeholder="Medicine Remarks">
                            <div id="medRemarkError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Stock Status</label>
                            <input name="stock_status" type="radio" checked class="form-control with-gap" id="in" value="In Stock">
                            <label for="in">In Stock</label>
                            <input name="stock_status" type="radio" class="form-control with-gap" id="out" value="Out of Stock">
                            <label for="out">Out of Stock</label>
                            <div id="medStockStatusError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <input name="status" type="radio" checked class="form-control with-gap"
                                id="yes" value="Y">
                            <label for="yes">Yes</label>
                            <input name="status" type="radio" class="form-control with-gap" id="no"
                                value="N">
                            <label for="no">No</label>
                            <div id="medStatusError" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="saveMedicineBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        $('#barcodeCanvas').empty();
        // Handle Save button click
        $('#saveMedicineBtn').click(function() {
            // Reset previous error messages
            $('#medNameError').text('');
            $('#medBarcodeError').text('');
            $('#medCompanyError').text('');
            $('#medPriceError').text('');
            $('#medExpDateError').text('');
            $('#medUnitPerPackError').text('');
            $('#medPackageCountError').text('');
            $('#medQuantityError').text('');
            $('#medPackageTypeError').text('');
            $('#medRemarkError').text('');
            $('#medStockStatusError').text('');
            $('#medStatusError').text('');

            // Validate form inputs
            var medName = $('#med_name').val();
            var medBarcode = $('#med_bar_code').val();
            var medCompany = $('#med_company').val();
            var medPrice = $('#med_price').val();
            var medExpiryDate = $('#expiry_date').val();
            var medUnittPerPack = $('#units_per_package').val();
            var packageCount = $('#package_count').val();
            var packageType = $('#package_type').val();
            var medQuantity = $('#total_quantity').val();
            var stockStatus = $('input[name="stock_status"]:checked').val();
            var status = $('input[name="status"]:checked').val();

            // Basic client-side validation (you can add more as needed)
            if (medName.length === 0) {
                $('#med_name').addClass('is-invalid');
                $('#medNameError').text('Medicine name is required.');
                return; // Prevent further execution
            } else {
                $('#med_name').removeClass('is-invalid');
                $('#medNameError').text('');
            }
            if (medBarcode.length === 0) {
                $('#med_bar_code').addClass('is-invalid');
                $('#medBarcodeError').text('Medicine Barcode is required.');
                return; // Prevent further execution
            } else {
                $('#med_bar_code').removeClass('is-invalid');
                $('#medBarcodeError').text('');
            }
            if (medCompany.length === 0) {
                $('#med_company').addClass('is-invalid');
                $('#medCompanyError').text('Medicine Company name is required.');
                return; // Prevent further execution
            } else {
                $('#med_company').removeClass('is-invalid');
                $('#medCompanyError').text('');
            }
            if (medPrice.length === 0) {
                $('#med_price').addClass('is-invalid');
                $('#medPriceError').text('Medicine price is required.');
                return; // Prevent further execution
            }else if (!$.isNumeric(medPrice)) {
                $('#med_price').addClass('is-invalid');
                $('#medPriceError').text('Medicine price must be a number.');
                return; // Prevent further execution
            }else {
                $('#med_price').removeClass('is-invalid');
                $('#medPriceError').text('');
            }
            if (medExpiryDate.length === 0) {
                $('#expiry_date').addClass('is-invalid');
                $('#medExpDateError').text(' The expiry date is required.');
                return; // Prevent further execution
            } else {
                $('#expiry_date').removeClass('is-invalid');
                $('#medExpDateError').text('');
            }
            if (medUnittPerPack.length === 0) {
                $('#units_per_package').addClass('is-invalid');
                $('#medUnitPerPackError').text('The units per package are required.');
                return; // Prevent further execution
            }else if (!$.isNumeric(medUnittPerPack)) {
                $('#units_per_package').addClass('is-invalid');
                $('#medUnitPerPackError').text('The units per package must be an integer.');
                return; // Prevent further execution
            }else {
                $('#units_per_package').removeClass('is-invalid');
                $('#medUnitPerPackError').text('');
            }
            if (packageCount.length === 0) {
                $('#package_count').addClass('is-invalid');
                $('#medPackageCountError').text('The package count is required.');
                return; // Prevent further execution
            }else if (!$.isNumeric(packageCount)) {
                $('#package_count').addClass('is-invalid');
                $('#medPackageCountError').text('The package count must be an integer.');
                return; // Prevent further execution
            }else {
                $('#package_count').removeClass('is-invalid');
                $('#medPackageCountError').text('');
            }
            if (medQuantity.length === 0) {
                $('#total_quantity').addClass('is-invalid');
                $('#medQuantityError').text('The total quantity is required.');
                return; // Prevent further execution
            }else if (!$.isNumeric(medQuantity)) {
                $('#total_quantity').addClass('is-invalid');
                $('#medQuantityError').text('The total quantity must be an integer.');
                return; // Prevent further execution
            }else {
                $('#total_quantity').removeClass('is-invalid');
                $('#medQuantityError').text('');
            }
            if (packageType.length === 0) {
                $('#package_type').addClass('is-invalid');
                $('#medPackageTypeError').text('The package type is required.');
                return; // Prevent further execution
            } else {
                $('#package_type').removeClass('is-invalid');
                $('#medPackageTypeError').text('');
            }
            if (!stockStatus) {
                $('#medStockStatusError').text('Medicine stock status is required.');
                return; // Prevent further execution
            } else {
                $('#medStockStatusError').text('');
            }
            if (!status) {
                $('#medStatusError').text('Status is required.');
                return; // Prevent further execution
            } else {
                $('#medStatusError').text('');
            }

            // If validation passed, submit the form via AJAX
            var form = $('#createMedicineForm');
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
                    $('#successMessage').text('Medicine created successfully');
                    $('#successMessage').fadeIn().delay(3000).fadeOut(); // Show for 3 seconds
                    location.reload();
                    // Optionally, you can reload or update the table here
                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('med_name')) {
                        $('#med_name').addClass('is-invalid');
                        $('#medNameError').text(errors.med_name[0]);
                    }
                    if (errors.hasOwnProperty('med_bar_code')) {
                        $('#med_bar_code').addClass('is-invalid');
                        $('#medBarcodeError').text(errors.med_bar_code[0]);
                    }
                    if (errors.hasOwnProperty('med_company')) {
                        $('#med_company').addClass('is-invalid');
                        $('#medCompanyError').text(errors.med_company[0]);
                    }
                    if (errors.hasOwnProperty('med_remarks')) {
                        $('#med_remarks').addClass('is-invalid');
                        $('#medRemarkError').text(errors.med_remarks[0]);
                    }
                    if (errors.hasOwnProperty('med_price')) {
                        $('#med_price').addClass('is-invalid');
                        $('#medPriceError').text(errors.med_price[0]);
                    }
                    if (errors.hasOwnProperty('expiry_date')) {
                        $('#expiry_date').addClass('is-invalid');
                        $('#medExpDateError').text(errors.expiry_date[0]);
                    }
                    if (errors.hasOwnProperty('units_per_package')) {
                        $('#units_per_package').addClass('is-invalid');
                        $('#medUnitPerPackError').text(errors.units_per_package[0]);
                    }
                    if (errors.hasOwnProperty('package_count')) {
                        $('#package_count').addClass('is-invalid');
                        $('#medPackageCountError').text(errors.package_count[0]);
                    }
                    if (errors.hasOwnProperty('total_quantity')) {
                        $('#total_quantity').addClass('is-invalid');
                        $('#medQuantityError').text(errors.total_quantity[0]);
                    }
                    if (errors.hasOwnProperty('package_type')) {
                        $('#package_type').addClass('is-invalid');
                        $('#medPackageTypeError').text(errors.package_type[0]);
                    }
                    if (errors.hasOwnProperty('stock_status')) {
                        $('#medStockStatusError').text(errors.stock_status[0]);
                    }
                    if (errors.hasOwnProperty('status')) {
                        $('#medStatusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createMedicineForm').trigger('reset');
            $('#med_bar_code').removeClass('is-invalid');
            $('#med_name').removeClass('is-invalid');
            $('#med_company').removeClass('is-invalid');
            $('#med_remarks').removeClass('is-invalid');
            $('#med_price').removeClass('is-invalid');
            $('#expiry_date').removeClass('is-invalid');
            $('#units_per_package').removeClass('is-invalid');
            $('#package_count').removeClass('is-invalid');
            $('#package_type').removeClass('is-invalid');
            $('#total_quantity').removeClass('is-invalid');
            $('#stock_status').removeClass('is-invalid');
            $('#status').removeClass('is-invalid');
            $('#barcodeCanvas').empty();


            $('#medNameError').text('');
            $('#medBarcodeError').text('');
            $('#medCompanyError').text('');
            $('#medPriceError').text('');
            $('#medExpDateError').text('');
            $('#medUnitPerPackError').text('');
            $('#medPackageCountError').text('');
            $('#medQuantityError').text('');
            $('#medPackageTypeError').text('');
            $('#medRemarkError').text('');
            $('#medStockStatusError').text('');
            $('#medStatusError').text('');
            
        });
    });

    function generateTotalQuantity() {
            var medUnittPerPack = $('#units_per_package').val();
            var packageCount = $('#package_count').val();
            if (medUnittPerPack.length === 0) {
                alert('The units per package are required.');
                return; // Prevent further execution
            }else if (!$.isNumeric(medUnittPerPack)) {
                alert('The units per package must be an integer.');
                return; // Prevent further execution
            }else if (packageCount.length === 0) {
                alert('The package count is required.');
                return; // Prevent further execution
            }else if (!$.isNumeric(packageCount)) {
                alert('The package count must be an integer.');
                return; // Prevent further execution
            }else {
                var total = medUnittPerPack * packageCount;
                $('#total_quantity').val(total);
            }
        }
</script>
