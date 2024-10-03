@extends('layouts.dashboard')
@section('title', 'Leave Types')
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
                    <h3 class="page-title">Leave Types</h3>
                    {{-- <button type="button" class="waves-effect waves-light btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-right"> <i class="fa fa-add"></i> Add New</button> --}}
                    <button type="button" class="waves-effect waves-light btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-right" title="Add New">
                        <span class="hidden-sm-up">Add New</span>
                        <span class="hidden-xs-down"><i class="fa fa-add"></i> Add New</span>
                    </button>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table width="100%" class="table table-bordered table-hover table-striped mb-0 data-table">
                                <thead class="bg-primary-light text-center">
                                    <tr>
                                        <th width="10px">No</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Duration</th>
                                        <th>Payment</th>
                                        <th width="20px">Status</th>
                                        <th width="80px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populate table rows with department data -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->

    @include('settings.leaveType.create')
    @include('settings.leaveType.edit')
    @include('settings.leaveType.delete')

    {{-- @endsection

@section('scripts') --}}

    <script>
        var leaveTypeUrl = "{{ route('settings.leaveType') }}";
    </script>

    <!-- custom JavaScript file -->
    <script src="{{ asset('js/leaveTypes.js') }}"></script>

    <script type="text/javascript">
        jQuery(function($) {
            $(document).on('click', '.btn-edit', function() {
                var leaveTypeId = $(this).data('id');
                $('#edit_leaveType_id').val(leaveTypeId); // Set department ID in the hidden input
                $.ajax({
                    url: '{{ url("leaveType") }}' + "/" + leaveTypeId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_leaveType_id').val(response.id);
                        $('#edit_type').val(response.type);
                        $('#edit_description').val(response.description);
                        $('#edit_payment_status').val(response.payment_status);
                        $('#edit_duration').val(response.duration);
                        $('#edit_duration_type').val(response.duration_type);
                        $('#edit_yes').prop('checked', response.status === 'Y');
                        $('#edit_no').prop('checked', response.status === 'N');
                        // $('#modal-edit').modal('show');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            $(document).on('click', '.btn-del', function() {
                var leaveTypeId = $(this).data('id');
                $('#delete_leaveType_id').val(leaveTypeId); // Set department ID in the hidden input
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var leaveTypeId = $('#delete_leaveType_id').val();
                var url = "{{ route('settings.leaveType.destroy', ':leaveType') }}";
                url = url.replace(':leaveType', leaveTypeId);
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.draw(); // Refresh DataTable
                        $('#successMessage').text('Leave Type deleted successfully');
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
    </script>
@endsection
