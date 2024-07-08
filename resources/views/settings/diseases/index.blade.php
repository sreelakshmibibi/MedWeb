@extends('layouts.dashboard')
@section('title', 'Diseases')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">Disease saved successfully
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
                    <h3 class="page-title">Diseases</h3>
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
                                        <th>ICD Code</th>
                                        <th>Name</th>
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

    @include('settings.diseases.create')
    @include('settings.diseases.edit')
    @include('settings.diseases.delete')

    <script type="text/javascript">
        jQuery(function($) {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('settings.disease') }}",
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
                        data: 'icd_code',
                        name: 'icd_code'
                    },
                    {
                        data: 'name',
                        name: 'name'
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
                var diseaseId = $(this).data('id');
                $('#edit_disease_id').val(diseaseId); // Set department ID in the hidden input
                $.ajax({
                    url: '{{ url('disease') }}' + "/" + diseaseId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_disease_id').val(response.id);
                        $('#edit_icd_code').val(response.icd_code);
                        $('#edit_disease').val(response.name);
                        $('#edit_description').val(response.description);
                        $('#edit_yes').prop('checked', response.status === 'Y');
                        $('#edit_no').prop('checked', response.status === 'N');
                        // $('#modal-edit').modal('show');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            $(document).on('click', '.btn-danger', function() {
                var diseaseId = $(this).data('id');
                $('#delete_disease_id').val(diseaseId); // Set department ID in the hidden input
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var diseaseId = $('#delete_disease_id').val();
                var url = "{{ route('settings.disease.destroy', ':disease') }}";
                url = url.replace(':disease', diseaseId);
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.draw(); // Refresh DataTable
                        $('#successMessage').text('Disease deleted successfully');
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
