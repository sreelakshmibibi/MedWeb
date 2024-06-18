@extends('layouts.dashboard')
@section('title', 'Treatment Cost')
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
                    <h3 class="page-title">Treatment Cost Details</h3>
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
                                        <th>Treatment Name</th>
                                        <th>Cost</th>
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

    @include('settings.treatment_cost.create')
    @include('settings.treatment_cost.edit')
    @include('settings.treatment_cost.delete')
    {{-- </div> --}}

    <!-- ./wrapper -->
    {{-- <script type="module"> --}}
    <script type="text/javascript">
        jQuery(function($) {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('settings.treatment_cost') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'treatment name',
                        name: 'treatment name'
                    },
                    {
                        data: 'cost',
                        name: 'cost'
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
                var treatmentId = $(this).data('id');
                $('#edit_treatment_cost_id').val(treatmentId); // Set treatment ID in the hidden input
                $.ajax({
                    url: '{{ url('treatment', '') }}' + "/" + treatmentId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_treatment_cost_id').val(response.id);
                        $('#edit_treatment_cost').val(response.treatment);

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
                var treatmentId = $(this).data('id');
                $('#delete_treatment_id').val(treatmentId); // Set treatment ID in the hidden input
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var treatmentId = $('#delete_treatment_id').val();
                var url = "{{ route('settings.treatment_cost.destroy', ':treatment') }}";
                url = url.replace(':treatment', treatmentId);

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
    </script>
@endsection
