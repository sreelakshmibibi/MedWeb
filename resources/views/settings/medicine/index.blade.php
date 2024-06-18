@extends('layouts.dashboard')
@section('title', 'Medicines')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                @if (session('success'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-success alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('success') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                @if (session('error'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fade fadeOut"
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
                                        <th>med_bar_code</th>
                                        <th>Name</th>
                                        <th>Strength</th>
                                        <th>remarks</th>
                                        <th>price</th>
                                        <th>med_status</th>
                                        <th>Status</th>
                                        <th>med_date</th>
                                        <th>med_last_update</th>
                                        <th>company_name</th>
                                        <th>rep_name</th>
                                        <th>rep_phone number</th>
                                        <th>med_name</th>
                                        <th>med_strength</th>
                                        <th>Status</th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

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

    <!-- ./wrapper -->
    {{-- <script type="module"> --}}
    <script type="text/javascript">
        jQuery(function($) {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('settings.medicine') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: true
                    },
                ]
            });
            $(document).on('click', '.btn-edit', function() {
                var departmentId = $(this).data('id');
                $('#edit_department_id').val(departmentId); // Set department ID in the hidden input
                $.ajax({
                    url: '{{ url('department', '') }}' + "/" + departmentId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_department_id').val(response.id);
                        $('#edit_department').val(response.department);

                        if (response.status === 'Y') {
                            $('#edit_yes').prop('checked', true);
                        } else {
                            $('#edit_no').prop('checked', true);
                        }

                        $('#modal-edit').modal('show');
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });

            });
            $(document).on('click', '.btn-danger', function() {
                var departmentId = $(this).data('id');
                $('#delete_department_id').val(departmentId); // Set department ID in the hidden input
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var departmentId = $('#delete_department_id').val();
                var url = "{{ route('settings.departments.destroy', ':department') }}";
                url = url.replace(':department', departmentId);

                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.draw();

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
            var inputValue = document.getElementById("barcodeInput").value.trim();

            // Check if input value is empty
            if (inputValue === "") {
                alert("Please enter text to generate barcode.");
                return;
            }

            // Generate barcode
            JsBarcode("#barcodeCanvas", inputValue, {
                format: "CODE128", // Barcode format (you can choose other formats like EAN-13, QR Code, etc.)
                displayValue: true, // Display value below barcode
                fontSize: 16,
                textMargin: 10,
                width: 2,
                height: 50
            });

            JsBarcode("#barcodeCanvas", inputValue, {
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
