@extends('layouts.dashboard')
@section('title', 'Suppliers')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">
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
                    <h3 class="page-title">Suppliers</h3>
                    @if (Auth::user()->can('supplier add'))
                        <button type="button" class="waves-effect waves-light btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modal-right"> <i class="fa fa-add"></i> Add</button>
                    @endif
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center table-striped mb-0 data-table"
                                width="100%">
                                <thead class="bg-primary-light">
                                    <tr>
                                        <th width="10px">No</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>GST No.</th>
                                        <th>Status</th>
                                        <th width="150px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populate table rows with technician data -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->

    @include('suppliers.create')
    @include('suppliers.edit')
    @include('suppliers.delete')
    
    <script type="text/javascript">
        var table;
        
        jQuery(function($) {
            table = $(".data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('suppliers') }}",
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                    },
                    {
                        data: "name",
                        name: "name",
                        className: "text-left",
                    },
                    {
                        data: "phone",
                        name: "phone",
                        className: "text-left",
                    },
                    
                    {
                        data: "address",
                        name: "address",
                    },
                    {
                        data: "gst",
                        name: "gst",
                    },
                    
                    {
                        data: "status",
                        name: "status",
                        className: "text-center",
                    },
                    {
                        data: "action",
                        name: "action",
                        className: "text-center",
                        orderable: false,
                        searchable: true,
                    },
                ],
            });

            // Edit Leave
            $(document).on('click', '.btn-edit', function() {
                var supplierId = $(this).data('id');
                $('#edit_supplier_id').val(supplierId);
                $.ajax({
                    url: '{{ url("suppliers") }}/' + supplierId + '/edit',
                    method: 'GET',
                    success: function(response) {
                        $('#edit_supplier_name').val(response.name);
                        $('#edit_supplier_phone').val(response.phone);
                        $('#edit_supplier_gst').val(response.gst);
                        $('#edit_supplier_address').val(response.address);
                        $('#edit_yes').prop('checked', response.status === 'Y');
                        $('#edit_no').prop('checked', response.status === 'N');
                        $('#modal-edit').modal('show');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            // Delete Leave
            $(document).on('click', '.btn-danger', function() {
                var supplierId = $(this).data('id');
                $('#delete_supplier_id').val(supplierId);
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var supplierId = $('#delete_supplier_id').val();
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('suppliers.destroy', ':supplier') }}".replace(':supplier', supplierId),
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.ajax.reload();
                        // table.draw();
                        $('#successMessage').text('Supplier deleted successfully')
                            .fadeIn().delay(3000).fadeOut();
                    },
                    error: function(xhr) {
                        $('#modal-delete').modal('hide');
                        console.log("Error:", xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>

@endsection
