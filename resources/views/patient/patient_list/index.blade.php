@extends('layouts.dashboard')
@section('title', 'Patients')
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
                    <h3 class="page-title">Patient List</h3>
                    <a type="button" class="waves-effect waves-light btn btn-primary"
                        href="{{ route('patient.patient_list.create') }}"> <i class="fa fa-add"></i> Add New</a>
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
                                        <th>Patient ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Gender</th>
                                        <th>Address</th>
                                        <th>Phone Number</th>
                                        <th>Last Appointment Date</th>
                                        <th>Upcoming (if any)</th>
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

    {{-- </div> --}}

    <!-- ./wrapper -->
    <script type="text/javascript">
        jQuery(function($) {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        data: 'last_name',
                        name: 'last_name'
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'lastappointmentdate',
                        name: 'lastappointmentdate'
                    },
                    {
                        data: 'upcomingappointmentdate',
                        name: 'upcomingappointmentdate'
                    },
                    {
                        data: 'pstatus',
                        name: 'pstatus'
                    },
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
                var patientId = $(this).data('id');
                $('#edit_patient_id').val(patientId); // Set patient ID in the hidden input
                $.ajax({
                    url: '{{ url('patient', '') }}' + "/" + patientId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_patient_id').val(response.id);
                        $('#edit_patient').val(response.patient);

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
                var patientId = $(this).data('id');
                $('#delete_patient_id').val(patientId); // Set patient ID in the hidden input
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var departmentId = $('#delete_patient_id').val();
                var url = "";
                url = url.replace(':patient', patientId);

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
