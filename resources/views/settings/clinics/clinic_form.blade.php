@extends('layouts.dashboard')
@section('title', 'Clinics')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- alert -->
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

            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Clinic Details</h3>
                    <button type="button" class="waves-effect waves-light btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-right"> <i class="fa fa-add"></i> Add New</button>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-body">
                            <div class="table-responsive">
                                <!-- Main content -->
                                <table class="table table-bordered table-hover table-striped mb-0 border-2 data-table">
                                    <thead class="bg-primary-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Phone Number</th>
                                            <th>Address</th>
                                            <th width="100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                <!-- /.content -->
                            </div>

                        </div>
                        <!-- /.box -->
                    </div>
                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- modal -->
    @include('settings.clinics.create')
    @include('settings.clinics.edit')
    @include('settings.clinics.delete')

    <script type="text/javascript">
        jQuery(function($) {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('settings.clinic') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'clinic_name',
                        name: 'clinic_name'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'clinic_address',
                        name: 'clinic_address'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: true
                    },
                ]
            });

        });

        $("#buttonalert").click(function() {
            // swal("Success!", "New Clinic Added");

            swal({
                    title: "Are you sure?",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: true,
                },
                // function (isConfirm) {
                //     if (isConfirm) {
                //         swal(
                //             "Saved!",
                //             "Your data is updated.",
                //             "success"
                //         );
                //     } else {
                //         swal(
                //             "Cancelled",
                //             "cancelled",
                //             "error"
                //         );
                //     }
                // }
            );
        });
    </script>
@endsection
