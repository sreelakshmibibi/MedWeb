@extends('layouts.dashboard')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                @if (session('success'))
                    {{-- <div class="alert alert-success">
                    {{ session('success') }}
                </div> --}}
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-success alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('success') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                @if (session('error'))
                    {{-- <div class="alert alert-error">
                    {{ session('error') }}
                </div> --}}
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fade fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('error') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Department Details</h3>
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
                                        <th>Name</th>
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

    @include('settings.department.create')
    @include('settings.department.edit')
    {{-- </div> --}}

    <!-- ./wrapper -->
    <script type="text/javascript">
        jQuery(function($) {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('settings.department') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'department',
                        name: 'department'
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
                function(isConfirm) {
                    if (isConfirm) {
                        $("#modal-right").modal('toggle');
                    }
                }
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

        $(".buttondelete").click(function() {
            swal({
                    title: "Are you sure?",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: true,
                },
                function(isConfirm) {
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
                }
            );
        });
    </script>
@endsection
