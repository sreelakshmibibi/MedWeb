@extends('layouts.dashboard')
@section('title', 'Patients')
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
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Phone Number</th>
                                        <th>Last Appointment Date</th>
                                        <th>Upcoming (if any)</th>
                                        {{-- <th>New Appointment</th> --}}
                                        <th>Status</th>
                                        <th width="170px">Action</th>
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
    @include('patient.patient_list.delete')
    @include('patient.patient_list.status')
    @include('patient.patient_list.booking')

    {{-- </div> --}}

    <!-- ./wrapper -->
    <script type="text/javascript">
        jQuery(function($) {
            var table;
            table = $('.data-table').DataTable({
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
                        data: 'patient_id',
                        name: 'patient_id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'appointment',
                        name: 'appointment',
                        render: function(data, type, row) {
                            if (data == 'N/A') {
                                return data;
                            } else {
                                return moment(data, 'YYYY-MM-DD HH:mm:ss').format(
                                    'DD-MM-YYYY hh:mm A');
                            }
                        }
                    },
                    {
                        data: 'next_appointment',
                        name: 'next_appointment',
                        render: function(data, type, row) {
                            // Use Moment.js to format the date
                            return moment(data, 'YYYY-MM-DD HH:mm:ss').format('DD-MM-YYYY hh:mm A');

                        }
                    },
                    // {
                    //     data: 'new_appointment',
                    //     name: 'new_appointment'
                    // },
                    {
                        data: 'record_status',
                        name: 'record_status',
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

            // $(document).on('click', '.btn-add', function() {
            //     var app_parent_id = $(this).data('parent-id');
            //     var patientId = $(this).data('patient-id');
            //     var patientName = $(this).data('patient-name');
            //     $('#patient_id').val(patientId); // Set app ID in the hidden input
            //     $('#patient_name').val(patientName); // Set app ID in the hidden input
            //     $('#app_parent_id').val(app_parent_id);
            //     //$('#modal-booking').modal('show');
            //     $.ajax({
            //         url: '{{ url('patient', '') }}' + "/" + patientId + "/appointment",
            //         method: 'GET',
            //         success: function(response) {
            //             $('#weight').val(response.weight);

            //             $('#modal-booking').modal('show');
            //         },
            //         error: function(error) {
            //             console.log(error)
            //         }
            //     });


            // });
            $(document).on('click', '.btn-add', function() {
                var app_parent_id = $(this).data('parent-id');
                var patientId = $(this).data('patient-id');
                var patientName = $(this).data('patient-name');

                // Set patient ID and patient name in the form fields
                $('#patient_id').val(patientId);
                $('#patient_name').val(patientName);
                $('#app_parent_id').val(app_parent_id);
                // Replace with dynamic patient ID

                var url = "{{ route('patient.patient_list.appointment', [':patientId']) }}";
                url = url.replace(':patientId', patientId);
                // Fetch patient appointment details using AJAX
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        // Populate the form fields with the fetched data
                        $('#height').val(response.height_cm);
                        $('#weight').val(response.weight_kg);
                        $('#bp').val(response.blood_pressure);
                        //$('#temperature').val(response.temperature);
                        $('#smoking_status').val(response.smoking_status);
                        $('#alcoholic_status').val(response.alcoholic_status);
                        $('#diet').val(response.diet);
                        $('#allergies').val(response.allergies);
                        $('#pregnant').val(response.pregnant);
                        $('#rdoctor').val(response.referred_doctor);
                        if (response.history && response.history.length > 0) {
                            $('#medical-conditions-wrapper').empty();
                            response.history.forEach(function(condition, index) {
                                var medicalConditionInput = `
                                    <div class="input-group mb-0">
                                        <input type="text" class="form-control" name="medical_conditions[]" value="${condition.history}" placeholder="Medical Condition">
                                        <button class="btn-sm ${index === 0 ? 'btn-success' : 'btn-danger'}" type="button" onclick="${index === 0 ? 'addPatientMedicalCondition()' : 'removePatientMedicalCondition(this)'}">${index === 0 ? '+' : '-'}</button>
                                    </div>`;
                                $('#medical-conditions-wrapper').append(
                                    medicalConditionInput);
                            });
                        }

                        // Show the modal after setting the values
                        $('#modal-booking').modal('show');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            $(document).on('click', '.btn-danger', function() {
                var patientId = $(this).data('id');
                $('#delete_patient_id').val(patientId); // Set patient ID in the hidden input
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var patientId = $('#delete_patient_id').val();
                var url = "{{ route('patient.patient_list.changeStatus', [':patientId']) }}";
                url = url.replace(':patient', patientId);

                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#successMessage').text('Patient deleted successfully');
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                        table.draw();

                    },
                    error: function(xhr) {
                        $('#modal-delete').modal('hide');
                        swal("Error!", xhr.responseJSON.message, "error");
                    }
                });
            });

            $(document).on('click', '.btn-warning', function() {
                var patientId = $(this).data('id');
                console.log(patientId);
                $('#delete_patient_id').val(patientId); // Set staff ID in the hidden input
                $('#modal-status').modal('show');
            });
            $('#btn-confirm-status').click(function() {
                var patientId = $('#delete_patient_id').val();
                var url = "{{ route('patient.patient_list.changeStatus', [':patientId']) }}";
                url = url.replace(':patientId', patientId);

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#successMessage').text('Patient status changed successfully');
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                        table.draw(); // Assuming 'table' is your DataTable instance
                    },
                    error: function(xhr) {
                        // Handle error response, e.g., hide modal and show error message
                        $('#modal-status').modal('hide');
                        swal("Error!", xhr.responseJSON.message, "error");
                    }
                });
            });

        });
    </script>
@endsection
