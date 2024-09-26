@extends('layouts.dashboard')
@section('title', 'Employee Types')
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
                    <h3 class="page-title">Employee Types</h3>
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

    @include('payroll.employeeType.create')
    @include('payroll.employeeType.edit')
    @include('payroll.employeeType.delete')

    {{-- @endsection

@section('scripts') --}}

    <script>
        var employeeTypeUrl = "{{ route('employeeTypes') }}";
    </script>

    <!-- custom JavaScript file -->
    <script src="{{ asset('js/employeeType.js') }}"></script>

    <script type="text/javascript">
        jQuery(function($) {
            $(document).on('click', '.btn-edit', function() {
                var employeeTypeId = $(this).data('id');
                $('#edit_employee_type_id').val(employeeTypeId); // Set department ID in the hidden input
                $.ajax({
                    url: '{{ url("employee_types") }}' + "/" + employeeTypeId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_employee_type_id').val(response.id);
                        $('#edit_employee_type').val(response.employee_type);
                        $('#edit_yes').prop('checked', response.status === 'Y');
                        $('#edit_no').prop('checked', response.status === 'N');
                        $('#modal-employee-type-edit').modal('show');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

        });
    </script>
@endsection
