@extends('layouts.dashboard')
@section('title', 'Treatment Plans')
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
                    <h3 class="page-title">Treatment Plans</h3>
                    <button type="button" class="waves-effect waves-light btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-right"> <i class="fa fa-add"></i> Add New</button>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-hover table-striped mb-0 border-2 data-table text-center">
                                <thead class="bg-primary-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Plan</th>
                                        <th>Cost</th>
                                        <th>Status</th>
                                        <th width="100px">Action</th>
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

    @include('settings.treatment_plan.create')
    @include('settings.treatment_plan.edit')
    @include('settings.treatment_plan.delete')

@endsection

@section('scripts')

    <script>
        var planUrl = "{{ route('settings.treatment_plan') }}";
    </script>

    <!-- custom JavaScript file -->
    <script src="{{ asset('js/treatment_plan.js') }}"></script>

    <script type="text/javascript">
        jQuery(function($) {

            $(document).on('click', '.btn-edit', function() {
                var planId = $(this).data('id');
                $('#edit_plan_id').val(planId); // Set department ID in the hidden input
                $.ajax({
                    url: '{{ url("treatment_plan") }}' + "/" + planId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_plan_id').val(response.id);
                        $('#edit_plan').val(response.plan);
                        $('#edit_cost').val(response.cost);
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
                var planId = $(this).data('id');
                $('#delete_plan_id').val(planId); // Set department ID in the hidden input
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var planId = $('#delete_plan_id').val();
                var url = "{{ route('settings.treatment_plan.destroy', ':treatment_plan') }}";
                url = url.replace(':treatment_plan', planId);
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.draw(); // Refresh DataTable
                        $('#successMessage').text('Treatment Plan deleted successfully');
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
