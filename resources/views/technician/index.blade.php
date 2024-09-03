@extends('layouts.dashboard')
@section('title', 'Technicians')
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
                    <h3 class="page-title">Technicians</h3>
                    @if (Auth::user()->can('technician_add'))
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
                                        <th>Lab Details</th>
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

    @include('technician.create')
    @include('technician.edit')
    @include('technician.delete')
    
    <script type="text/javascript">
        var table;
        
        jQuery(function($) {
            table = $(".data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('technicians') }}",
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
                        data: "phone_number",
                        name: "phone_number",
                        className: "text-left",
                    },
                    
                    {
                        data: "lab_details",
                        name: "lab_details",
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
                var leaveId = $(this).data('id');
                $('#edit_leave_id').val(leaveId);
                $.ajax({
                    url: '{{ url('leave') }}/' + leaveId + '/edit',
                    method: 'GET',
                    success: function(response) {
                        $('#editleave_id').val(response.id);
                        $('#editleave_type').val(response.leave_type);
                        $('#editleave_from').val(response.leave_from);
                        $('#editleave_to').val(response.leave_to);
                        $('#editreason').val(response.leave_reason);
                        $('#modal-edit').modal('show');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            // Delete Leave
            $(document).on('click', '.btn-danger', function() {
                var leaveId = $(this).data('id');
                $('#delete_leave_id').val(leaveId);
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var leaveId = $('#delete_leave_id').val();
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('leave.destroy', ':leave') }}".replace(':leave', leaveId),
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.ajax.reload();
                        // table.draw();
                        $('#successMessage').text('Leave application deleted successfully')
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
