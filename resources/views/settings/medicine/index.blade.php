@extends('layouts.dashboard')
@section('title', 'Medicines')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">Medicine created successfully
                </div>
                @if (session('success'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-success alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('success') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                @if (session('error'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('error') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Medicine Details</h3>
                    <button type="button" class="waves-effect waves-light btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-right"> <i class="fa fa-add"></i> Add New</button>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    {{-- <div class="box-body p-0"> --}}
                    <div class="box-body">
                        <div class="table-responsive">
                            <!-- Main content -->
                            <table
                                class="table table-bordered table-hover table-striped mb-0 border-2 data-table text-center">
                                <thead class="bg-primary-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Barcode</th>
                                        <th>Name</th>
                                        <th>Company</th>
                                        <th>Price</th>
                                        <th>Expiry Date</th>
                                        <th>Total Quantity</th>
                                        <th>Packaging Type</th>
                                        <th width="20px">Stock Status</th>
                                        <th width="80px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populate table rows with medicine data -->
                                </tbody>
                            </table>
                            <!-- /.content -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->

    @include('settings.medicine.create')
    @include('settings.medicine.edit')
    @include('settings.medicine.delete')
    {{-- </div> --}}

    <!-- JsBarcode library -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode/dist/JsBarcode.all.min.js"></script>

    <script type="text/javascript">
        var table;
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('expiry_date').setAttribute('min', today);
        document.getElementById('edit_expiry_date').setAttribute('min', today);

        jQuery(function($) {
            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('settings.medicine') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            // Return the row index (starts from 0)
                            return meta.row + 1; // Adding 1 to start counting from 1
                        }
                    },
                    {
                        data: 'med_bar_code',
                        name: 'med_bar_code',
                        render: function(data, type, row) {
                            if (!data) {
                                data = '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'med_name',
                        name: 'med_name'
                    },
                    {
                        data: 'med_company',
                        name: 'med_company',
                        render: function(data, type, row) {
                            if (!data) {
                                data = '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'med_price',
                        name: 'med_price',
                        render: function(data, type, row) {
                            if (!data) {
                                data = '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'expiry_date',
                        name: 'expiry_date',
                        render: function(data, type, row) {
                            if (data) {
                                var date = new Date(data);
                                var day = ("0" + date.getDate()).slice(-2);
                                var month = ("0" + (date.getMonth() + 1)).slice(-2);
                                var year = date.getFullYear();
                                return day + '-' + month + '-' + year;
                            } else {
                                data = '-';
                            }
                            return data;
                        }
                    },

                    {
                        data: 'total_quantity',
                        name: 'total_quantity',
                        render: function(data, type, row) {
                            if (!data) {
                                data = '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'package_type',
                        name: 'package_type',
                        render: function(data, type, row) {
                            if (!data) {
                                data = '-';
                            }
                            return data;
                        }
                    },
                    // {
                    //     data: 'stock_status',
                    //     name: 'stock_status'
                    // },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: true
                    }
                ]
            });

            $(document).on('click', '.btn-edit', function() {

                var medicineId = $(this).data('id');
                $('#edit_medicine_id').val(medicineId); // Set medicine ID in the hidden input
                $.ajax({
                    url: '{{ url('medicine', '') }}' + "/" + medicineId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_medicine_id').val(response.id);
                        $('#edit_med_name').val(response.med_name);
                        $('#edit_med_bar_code').val(response.med_bar_code);
                        $('#edit_med_company').val(response.med_company);
                        $('#edit_med_price').val(response.med_price);
                        $('#edit_expiry_date').val(response.expiry_date);
                        $('#edit_units_per_package').val(response.units_per_package);
                        $('#edit_package_count').val(response.package_count);
                        $('#edit_total_quantity').val(response.total_quantity);
                        $('#edit_package_type').val(response.package_type);
                        $('#edit_med_remarks').val(response.med_remarks);
                        $('#edit_in').prop('checked', response.stock_status === 'In Stock');
                        $('#edit_out').prop('checked', response.stock_status ===
                            'Out of Stock');
                        $('#med_edit_yes').prop('checked', response.status === 'Y');
                        $('#med_edit_no').prop('checked', response.status === 'N');
                        generateeditBarcode();
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });

            });

            $(document).on('click', '.btn-danger', function() {
                var medicineId = $(this).data('id');
                $('#delete_medicine_id').val(medicineId); // Set medicine ID in the hidden input
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var medicineId = $('#delete_medicine_id').val();
                var url = "{{ route('settings.medicine.destroy', ':medicine') }}";
                url = url.replace(':medicine', medicineId);
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.draw(); // Refresh DataTable
                        $('#successMessage').text('Medicine deleted successfully');
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                    },
                    error: function(xhr) {
                        $('#modal-delete').modal('hide');
                        swal("Error!", xhr.responseJSON.message, "error");
                    }
                });
            });

        });

        // barcode
        function generateBarcode() {

            // Get input value
            var barcode = '';
            var inputValue = '';
            inputValue = document.getElementById("med_bar_code").value.trim();
            barcode = 'barcodeCanvas';

            // if ( ($('#med_bar_code').length > 0) && ($('#med_bar_code').val()!='') ) {
            //     inputValue = document.getElementById("med_bar_code").value.trim();
            //     barcode = 'barcodeCanvas';
            //     $('#medBarcodeError').text('');
            // } 
            // if ( ($('#edit_med_bar_code').length > 0) && ($('#edit_med_bar_code').val()!='') ) {
            //     inputValue = document.getElementById("edit_med_bar_code").value.trim();
            //     barcode = 'edit_barcodeCanvas';
            //     $('#editMedBarcodeError').text('');

            // } 

            // Check if input value is empty
            if (inputValue === "") {
                alert('Please enter text to generate barcode.');
                return;
            }

            // Generate barcode
            JsBarcode("#" + barcode, inputValue, {
                format: "CODE128", // Barcode format (you can choose other formats like EAN-13, QR Code, etc.)
                displayValue: true, // Display value below barcode
                fontSize: 16,
                textMargin: 10,
                width: 2,
                height: 50
            });

            JsBarcode("#" + barcode, inputValue, {
                format: "CODE128", // Barcode format (CODE128, EAN-13, etc.)
                displayValue: true, // Show human-readable value below barcode
                fontSize: 16, // Font size of the value text
                textMargin: 10, // Margin between barcode and value text
                width: 2, // Barcode bar width (in pixels)
                height: 50, // Barcode height (in pixels)
                margin: 10, // Margin around the barcode (in pixels)
                background: "#f0f0f0", // Background color of the barcode
                // height: 25,
                lineColor: "#333" // Color of the barcode bars
            });
        }

        function clearBarcodeCanvas() {
            var barcodeCanvas = document.getElementById('barcodeCanvas');
            if (barcodeCanvas) {
                var parent = barcodeCanvas.parentNode;
                if (parent) {
                    var newCanvas = document.createElement('canvas');
                    newCanvas.id = 'barcodeCanvas';
                    newCanvas.classList.add('col-md-4');
                    newCanvas.style.height = '64px';
                    newCanvas.width = barcodeCanvas.width;
                    newCanvas.height = barcodeCanvas.height;
                    parent.replaceChild(newCanvas, barcodeCanvas);
                } else {
                    console.error('Parent node is null for barcodeCanvas.');
                }
            } else {
                console.error('Canvas element with ID barcodeCanvas not found.');
            }
            var editbarcodeCanvas = document.getElementById('editbarcodeCanvas');
            if (editbarcodeCanvas) {
                var parent = editbarcodeCanvas.parentNode;
                if (parent) {
                    var newCanvas = document.createElement('canvas');
                    newCanvas.id = 'editbarcodeCanvas';
                    newCanvas.classList.add('col-md-4');
                    newCanvas.style.height = '64px';
                    newCanvas.width = editbarcodeCanvas.width;
                    newCanvas.height = editbarcodeCanvas.height;
                    parent.replaceChild(newCanvas, editbarcodeCanvas);
                } else {
                    console.error('Parent node is null for editbarcodeCanvas.');
                }
            } else {
                console.error('Canvas element with ID editbarcodeCanvas not found.');
            }
        }

        function generateeditBarcode() {

            // Get input value
            var barcode = '';
            var inputValue = '';
            inputValue = document.getElementById("edit_med_bar_code").value.trim();
            barcode = 'editbarcodeCanvas';

            // Generate barcode
            JsBarcode("#" + barcode, inputValue, {
                format: "CODE128", // Barcode format (you can choose other formats like EAN-13, QR Code, etc.)
                displayValue: true, // Display value below barcode
                fontSize: 16,
                textMargin: 10,
                width: 2,
                height: 50
            });

            JsBarcode("#" + barcode, inputValue, {
                format: "CODE128", // Barcode format (CODE128, EAN-13, etc.)
                displayValue: true, // Show human-readable value below barcode
                fontSize: 16, // Font size of the value text
                textMargin: 10, // Margin between barcode and value text
                width: 2, // Barcode bar width (in pixels)
                height: 50, // Barcode height (in pixels)
                margin: 10, // Margin around the barcode (in pixels)
                background: "#f0f0f0", // Background color of the barcode
                // height: 25,
                lineColor: "#333" // Color of the barcode bars
            });
        }
    </script>
@endsection
