@extends('layouts.dashboard')
@section('title', 'Appointment')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">Appointment created successfully
                </div>
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
                    <h3 class="page-title">Appointment List</h3>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div id="paginator"></div>
                        <br />
                        <div class="table-responsive">
                            <!-- Main content -->
                            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                                width="100%">
                                <thead class="bg-primary-light">
                                    <tr>
                                        <th width="10px">Token No</th>
                                        <th width="60px">Patient ID</th>
                                        <th>Patient Name</th>
                                        <th>Consulting Doctor</th>
                                        <th width="60px">Phone number</th>
                                        <th width="180px">Branch</th>
                                        <th width="10px">Time</th>
                                        <th width="10px">Type</th>
                                        <th>Status</th>
                                        <th width="144px">Action</th>
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

    @include('appointment.booking')
    @include('appointment.reschedule')
    @include('appointment.cancel')
    @include('appointment.pdf_option')
    @include('appointment.pdf_option')

    <!-- ./wrapper -->
    <script type="text/javascript">
        var selectedDate;
        var table;

        $(document).ready(function() {

            $("#paginator").datepaginator({
                onSelectedDateChanged: function(a, t) {
                    selectedDate = moment(t).format("YYYY-MM-DD");
                    table.ajax.reload();
                },
            });

            var initialDate = $("#paginator").datepaginator("getDate");
            selectedDate = moment(initialDate).format("YYYY-MM-DD")
        });

        jQuery(function($) {
            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('appointment') }}",
                    type: 'GET',
                    data: function(d) {
                        d.selectedDate = selectedDate; // Add selectedDate as a query parameter
                    }
                },
                columns: [{
                        data: 'token_no',
                        name: 'token_no'
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
                        data: 'doctor',
                        name: 'doctor'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'branch',
                        name: 'branch'
                    },

                    {
                        data: 'app_time',
                        name: 'app_time',
                        render: function(data, type, row) {
                            var timeParts = data.split(':');
                            var hours = parseInt(timeParts[0], 10);
                            var minutes = parseInt(timeParts[1], 10);
                            var ampm = hours >= 12 ? 'PM' : 'AM';
                            hours = hours % 12;
                            hours = hours ? hours : 12; // Handle midnight (00:xx) as 12
                            var formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
                            var formattedTime = hours + ':' + formattedMinutes + ' ' + ampm;
                            return formattedTime;
                        }
                    },
                    {
                        data: 'app_type',
                        name: 'app_type',
                        render: function(data, type, row) {
                            if (data === 'NEW') {
                                return '<span class="fa-stack fa-1x text-white fs-10" title="' +
                                    data +
                                    '"><i class="fas fa-square fa-stack-2x"></i> <i class="fas fa-n fa-stack-1x fa-inverse text-primary"></i></span>';

                            } else {
                                return '<span class="fa-stack fa-1x text-primary fs-10" title="' +
                                    data +
                                    '"><i class="fas fa-square fa-stack-2x"></i> <i class="fas fa-f fa-stack-1x fa-inverse text-white"></i></span>';
                            }
                        }
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

            $(document).on('click', '.btn-add', function() {
                var app_parent_id = $(this).data('parent-id');
                var patientId = $(this).data('patient-id');
                var patientName = $(this).data('patient-name');
                $('#patient_id').val(patientId); // Set app ID in the hidden input
                $('#patient_name').val(patientName); // Set app ID in the hidden input
                $('#app_parent_id').val(app_parent_id);

                var now = new Date();
                var year = now.getFullYear();
                var month = (now.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
                var day = now.getDate().toString().padStart(2, '0');
                var hours = now.getHours().toString().padStart(2, '0');
                var minutes = now.getMinutes().toString().padStart(2, '0');
                var datetime = `${year}-${month}-${day}T${hours}:${minutes}`;

                document.getElementById('appdate').value = datetime;
                $('#modal-booking').modal('show');
            });

            $(document).on('click', '.btn-reschedule', function() {
                var appId = $(this).data('id');
                var patientId = $(this).data('patient-id');
                var patientName = $(this).data('patient-name');

                $('#reschedule_app_id').val(appId); // Set app ID in the hidden input
                $.ajax({
                    url: '{{ url('appointment', '') }}' + "/" + appId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_app_id').val(response.id);
                        $('#edit_patient_id').val(response
                            .patient_id); // Set app ID in the hidden input
                        $('#edit_patient_name').val(patientName);
                        var doctorName = response.doctor.name;
                        var formattedDoctorName = doctorName.replace(/<br>/g, ' ');
                        $('#edit_doctor').val(formattedDoctorName);

                        $('#edit_clinic_branch').val(response.clinic_branch);
                        $('#edit_doctor_id').val(response.doctor_id);

                        $('#edit_clinic_branch_id').val(response.app_branch);
                        // $('#edit_staff').val(response.staff);
                        var app_date = response.app_date;
                        var app_time = response.app_time;
                        $('#scheduled_appdate').val(app_date + ' ' + app_time);
                        $('#modal-reschedule').modal('show');
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });
            });
        });
        $('#clinic_branch_id, #appdate').change(function() {
            var branchId = $('#clinic_branch_id').val();
            var appDate = $('#appdate').val();
            $('#existAppContainer').hide();
            $('#existingAppointments').empty();
            loadDoctors(branchId, appDate);

        });

        // Function to load doctors based on branch ID
        function loadDoctors(branchId, appDate) {
            if (branchId && appDate) {
                $.ajax({
                    url: '{{ route('get.doctors', '') }}' + '/' + branchId,
                    type: "GET",
                    data: {
                        appdate: appDate
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#doctor_id').empty();
                        $('#doctor_id').append('<option value="">Select a doctor</option>');
                        $.each(data, function(key, value) {
                            var doctorName = value.user.name.replace(/<br>/g, ' ');
                            $('#doctor_id').append('<option value="' + value.user_id + '">' +
                                doctorName + '</option>');
                        });
                    }
                });
            } else {
                $('#doctor_id').empty();
            }
        }

        $('#clinic_branch_id, #appdate, #doctor_id').change(function() {
            var branchId = $('#clinic_branch_id').val();
            var appDate = $('#appdate').val();
            var doctorId = $('#doctor_id').val();
            var patientId = $('#patient_id').val();
            $('#alreadyExistsPatient').hide();
            $('#existingAppointmentsError').hide();
            $('#existAppContainer').hide();
            $('#existingAppointments').empty();
            $('#doctorNotAvailable').hide();
            showExistingAppointments(branchId, appDate, doctorId, patientId, 'store');
        });
        $('#rescheduledAppdate').change(function() {
            var branchId = $('#edit_clinic_branch_id').val();
            var appDate = $('#rescheduledAppdate').val();
            var doctorId = $('#edit_doctor_id').val();
            var patientId = $('#patient_id').val();
            $('#alreadyExistsPatient').hide();
            $('#existingAppointmentsError').hide();
            $('#rescheduleDoctorNotAvailable').hide();
            showExistingAppointments(branchId, appDate, doctorId, patientId, 'edit');
        });

        function convertTo12HourFormat(railwayTime) {
            var timeArray = railwayTime.split(':');
            var hours = parseInt(timeArray[0]);
            var minutes = timeArray[1];
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            var formattedTime = hours + ':' + minutes + ' ' + ampm;
            return formattedTime;
        }

        function showExistingAppointments(branchId, appDate, doctorId, patientId, methodType) {
            if (branchId && appDate && doctorId) {
                $.ajax({
                    url: '{{ route('get.exisitingAppointments', '') }}' + '/' + branchId,
                    type: "GET",
                    data: {
                        appdate: appDate,
                        doctorId: doctorId,
                        patientId: patientId,
                    },
                    dataType: "json",
                    success: function(data) {
                        // Show/hide the 'alreadyExistsPatient' div based on 'checkAppointmentDate'
                        if (data.checkAppointmentDate === 1 && methodType === 'store') {
                            $('#alreadyExistsPatient').show();
                        } else {
                            $('#alreadyExistsPatient').hide();
                        }
                        if (data.doctorAvailable == true && methodType === 'store') {
                            $('#doctorNotAvailable').hide();
                        } else {
                            $('#doctorNotAvailable').show();
                        }
                        if (data.doctorAvailable == true && methodType === 'edit') {
                            $('#rescheduleDoctorNotAvailable').hide();
                        } else {
                            $('#rescheduleDoctorNotAvailable').show();
                        }
                        // Show/hide the 'existingAppointmentsError' div based on 'checkAllocated'
                        if (data.checkAllocated.length > 0) {
                            $('#existingAppointmentsError').show();
                        } else {
                            $('#existingAppointmentsError').hide();
                        }

                        // Handle existing appointments display
                        if (data.existingAppointments.length > 0) {
                            $('#existAppContainer').hide();
                            $('#existingAppointments').empty();
                            $('#rescheduleExistingAppointments').empty();

                            var table = $('<table class="table table-striped mb-0">').addClass(
                                'appointment-table').css({
                                'border-collapse': 'separate',
                                'border-spacing': '0.5rem'
                            });

                            var numRows = Math.ceil(data.existingAppointments.length / 5);

                            for (var i = 0; i < numRows; i++) {
                                var row = $('<tr>');
                                for (var j = 0; j < 5; j++) {
                                    var dataIndex = i * 5 + j;
                                    if (dataIndex < data.existingAppointments.length) {
                                        var app_time = data.existingAppointments[dataIndex]
                                            .app_time;
                                        var formattedTime = convertTo12HourFormat(app_time);
                                        var cell = $('<td class="b-1 w-100 text-center">').text(
                                            formattedTime);
                                        row.append(cell);
                                    } else {
                                        var cell = $('<td>'); // Empty cell
                                        row.append(cell);
                                    }
                                }
                                table.append(row);
                            }

                            if (methodType === 'store') {
                                $('#existingAppointments').append($(
                                    '<h6 class="text-warning mb-1">').text(
                                    'Scheduled Appointments'));
                                $('#existingAppointments').append(table);
                                $('#existAppContainer').show();
                                $('#existingAppointments').show();
                            } else if (methodType === 'edit') {
                                $('#rescheduleExistingAppointments').append($(
                                        '<h6 class="text-warning mb-1">')
                                    .text('Scheduled Appointments'));
                                $('#rescheduleExistingAppointments').append(table);
                                $('#rescheduleExistingAppointments').show();
                            }

                        } else {
                            $('#existAppContainer').show();
                            $('#existingAppointments').html('No existing appointments found.');
                            $('#existingAppointments').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching existing appointments:', error);
                        $('#existingAppointments').html(
                            'Error fetching existing appointments. Please try again later.');
                        $('#existAppContainer').show();
                        $('#existingAppointments').show();
                    }
                });
            } else {
                console.log('Missing required parameters for fetching existing appointments.');
            }
        }

        $(document).on('click', '#btn-cancel', function() {
            var appId = $(this).data('id');
            $('#delete_app_id').val(appId); // Set staff ID in the hidden input
            $('#modal-cancel').modal('show');
        });

        $('#btn-confirm-cancel').click(function() {
            var appId = $('#delete_app_id').val();
            var reason = $('#app_status_change_reason').val();

            if (reason.length === 0) {
                $('#app_status_change_reason').addClass('is-invalid');
                $('#reasonError').text('Reason is required.');
                return; // Stop further execution
            }

            var url = "{{ route('appointment.destroy', ':appId') }}";
            url = url.replace(':appId', appId);

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "app_status_change_reason": reason
                },
                success: function(response) {
                    $('#modal-cancel').modal('hide'); // Close modal after success
                    table.draw(); // Refresh DataTable
                    $('#successMessage').text('Appointment cancelled successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds

                },
                error: function(xhr) {
                    $('#modal-cancel').modal(
                        'hide'); // Close modal in case of error
                    console.log("Error!", xhr.responseJSON.message, "error");
                }
            });
        });

        $(document).on('click', '.btn-treatment-pdf-generate', function() {
            var appId = $(this).data('app-id');
            var parentId = $(this).data('parent-id');
            var patientId = $(this).data('patient-id');

            $('#pdf_appointment_id').val(appId);
            $('#pdf_patient_id').val(patientId);
            $('#pdf_app_parent_id').val(parentId);
            $('#pdfType').val('appointment'); // Default to 'appointment'
            $('#toothSelection').addClass('d-none'); // Hide tooth selection by default

            $('#modal-download').modal('show'); // Show the modal
        });

        $(document).on('click', '.btn-prescription-pdf-generate', function() {
            var appId = $(this).data('app-id');
            var patientId = $(this).data('patient-id');

            const url = '{{ route('download.prescription') }}';

            // Make the AJAX request
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    app_id: appId,
                    patient_id: patientId,
                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                },
                xhrFields: {
                    responseType: 'blob' // Important for handling binary data like PDFs
                },
                success: function(response) {
                    var blob = new Blob([response], {
                        type: 'application/pdf'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'prescription.pdf';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        function reloadTableData() {
            table.ajax.reload();
        }
    </script>
@endsection
