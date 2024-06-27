@extends('layouts.dashboard')
@section('title', 'Staff')
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
                    <h3 class="page-title">Staff List</h3>
                    {{-- <button type="button" class="waves-effect waves-light btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-right"> <i class="fa fa-add"></i> Add New</button> --}}
                    <a type="button" class="waves-effect waves-light btn btn-primary"
                        href="{{ route('staff.staff_list.create') }}"> <i class="fa fa-add"></i> Add New</a>

                </div>
            </div>

            <section class="content">
                <div class="box">
                    {{-- <div class="box-body p-0"> --}}
                    <div class="box-body">
                        <div class="table-responsive">
                            <!-- Main content -->
                            <table
                                class="table table-bordered table-hover table-striped mb-0 border-2 data-table text-center"
                                width="100%">
                                <thead class="bg-primary-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Staff ID</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Qualification</th>
                                        <!-- <th>Department</th> -->
                                        <th>Phone Number</th>
                                        <th>Email</th>
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

    {{-- @include('staff.staff_list.create') --}}
    @include('staff.staff_list.delete')
    @include('staff.staff_list.status')

    {{-- </div> --}}

    <!-- ./wrapper -->
    <script type="text/javascript">
        jQuery(function($) {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "",
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
                        data: 'staff_id',
                        name: 'staff_idid'
                    },
                    {
                        data: 'photo',
                        name: 'photo',
                        render: function(data, type, full, meta) {
                            data = "{{ asset('storage/') }}/" + data;
                            return '<img src="' + data +
                                '" height="50" style="border-radius:50%;"/>';
                        },
                        orderable: false,
                        searchable: false

                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'qualification',
                        name: 'qualification'
                    },

                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },

                    // {
                    //     data: 'department',
                    //     name: 'role'
                    // },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: true
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
                var staffId = $(this).data('id');
                $('#edit_staff_id').val(staffId); // Set staff ID in the hidden input
                $.ajax({
                    url: '{{ url("staff", "") }}' + "/" + staffId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_staff_id').val(response.id);
                        $('#edit_staff').val(response.staff);

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
                var staffId = $(this).data('id');
                $('#delete_staff_id').val(staffId); // Set staff ID in the hidden input
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var staffId = $('#delete_staff_id').val();
                var url = "";
                url = url.replace(':staff', staffId);

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
