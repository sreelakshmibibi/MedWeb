@extends('layouts.dashboard')
@section('title', 'Insurances')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">Insurance Company added successfully
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
                    <h3 class="page-title">Insurance Details</h3>
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
                                        <th>Company</th>
                                        <th>Claim Type</th>
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

    @include('settings.insurance.create')
    @include('settings.insurance.edit')
    @include('settings.insurance.delete')

@endsection

@section('scripts')

    <script>
        var insuranceUrl = "{{ route('settings.insurance') }}";
    </script>

    <!-- custom JavaScript file -->
    <script src="{{ asset('js/insurance.js') }}"></script>

    <script type="text/javascript">
        jQuery(function($) {

            $(document).on('click', '.btn-edit', function() {
                var insuranceId = $(this).data('id');
                $('#edit_insurance_id').val(insuranceId); // Set department ID in the hidden input
                $.ajax({
                    url: '{{ url("insurance") }}' + "/" + insuranceId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_insurance_id').val(response.id);
                        $('#edit_insurance').val(response.company_name);
                        $('#edit_claim_type').val(response.claim_type);
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
                var insuranceId = $(this).data('id');
                $('#delete_insurance_id').val(insuranceId); // Set department ID in the hidden input
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var insuranceId = $('#delete_insurance_id').val();
                var url = "{{ route('settings.insurance.destroy', ':insurance') }}";
                url = url.replace(':insurance', insuranceId);
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.draw(); // Refresh DataTable
                        $('#successMessage').text('Insurance deleted successfully');
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
