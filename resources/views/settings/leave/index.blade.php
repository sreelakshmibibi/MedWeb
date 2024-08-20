@extends('layouts.dashboard')
@section('title', 'Leaves')
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
                    <h3 class="page-title">Leave Details</h3>
                    @if (Auth::user()->can('leave apply'))
                        <button type="button" class="waves-effect waves-light btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-right"> <i class="fa fa-add"></i> Apply Leave</button>
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
                                        @if (Auth::user()->can('leave approve'))
                                            <th width="20%">Staff</th>
                                        @endif
                                        <th width="15%">Leave Type</th>
                                        <th width="25%">Dates (Days)</th>
                                        <th width="30%">Reason</th>
                                        <th width="20px">Status</th>
                                        <th width="80px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populate table rows with leave data -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->

    @include('settings.leave.create')
    @include('settings.leave.edit')
    @include('settings.leave.delete')
    @include('settings.leave.approve')
    @include('settings.leave.reject')


    <script type="text/javascript">
    var today = new Date().toISOString().split('T')[0];
    document.getElementById('leave_from').setAttribute('min', today);
    document.getElementById('leave_to').setAttribute('min', today);
    document.getElementById('editleave_from').setAttribute('min', today);
    document.getElementById('editleave_to').setAttribute('min', today);

    jQuery(function($) {
        var table = $(".data-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('leave') }}",
            columns: [
                {
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                @can('leave approve')
                {
                    data: "staff",
                    name: "staff",
                    className: "text-left",
                },
                @endcan
                {
                    data: "leave_type",
                    name: "leave_type",
                    className: "text-left",
                },
                {
                    data: "leave_applied_dates",
                    name: "leave_applied_dates",
                },
                {
                    data: "leave_reason",
                    name: "leave_reason",
                    className: "text-left",
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
                url: '{{ url("leave") }}/' + leaveId + '/edit',
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

        // Approve Leave
        $(document).on('click', '.btn-approve', function() {
            var leaveId = $(this).data('id');
            $('#approve_leave_id').val(leaveId);
            $('#modal-approve').modal('show');
        });

        $('#btn-confirm-approve').click(function() {
            var leaveId = $('#approve_leave_id').val();
            $.ajax({
                type: 'GET',
                url: "{{ route('leave.approve', ':leave') }}".replace(':leave', leaveId),
                data: { "_token": "{{ csrf_token() }}" },
                success: function(response) {
                    $('#modal-approve').modal('hide');
                    table.draw();
                    $('#successMessage').text(response.success).fadeIn().delay(3000).fadeOut();
                },
                error: function(xhr) {
                    $('#modal-approve').modal('hide');
                    console.log("Error:", xhr.responseJSON.message);
                }
            });
        });

        // Reject Leave
        $(document).on('click', '.btn-reject', function() {
            var leaveId = $(this).data('id');
            $('#reject_leave_id').val(leaveId);
            $('#modal-reject').modal('show');
        });

        $('#btn-confirm-reject').click(function() {
            var leaveId = $('#reject_leave_id').val();
            var reason = $('#reject_reason').val();

            if (reason.length === 0) {
                $('#reject_reason').addClass('is-invalid');
                $('#rejectionError').text('Reason is required.');
                return;
            }

            $.ajax({
                type: 'POST',
                url: "{{ route('leave.reject', ':leave') }}".replace(':leave', leaveId),
                data: {
                    "_token": "{{ csrf_token() }}",
                    "reject_reason": reason
                },
                success: function(response) {
                    table.draw();
                    $('#modal-reject').modal('hide');
                    $('#successMessage').text(response.success).fadeIn().delay(3000).fadeOut();
                },
                error: function(xhr) {
                    $('#modal-reject').modal('hide');
                    console.log("Error:", xhr.responseJSON.message);
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
                data: { "_token": "{{ csrf_token() }}" },
                success: function(response) {
                    table.draw();
                    $('#successMessage').text('Leave application deleted successfully').fadeIn().delay(3000).fadeOut();
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
