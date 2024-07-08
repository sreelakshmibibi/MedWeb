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
                    {{-- <div class="box-body p-0"> --}}
                    <div class="box-body">
                        <div id="paginator"></div>
                        <br />
                        <div class="table-responsive">
                            <!-- Main content -->
                            <table
                                class="table table-bordered table-hover table-striped mb-0 border-2 data-table text-center"
                                width="100%">
                                <thead class="bg-primary-light">
                                    <tr>
                                        <!-- <th>No</th> -->
                                        <th>Token No</th>
                                        <th>Patient ID</th>
                                        <th>Patient Name</th>
                                        {{-- <th>Age</th> --}}
                                        <th>Consulting Doctor</th>
                                        {{-- <th>Department</th> --}}
                                        {{-- <th>Disease</th> --}}
                                        <th>Phone number</th>
                                        <th>Branch</th>
                                        <!-- <th>Date</th> -->
                                        <th>Time</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th width="150px">Action</th>
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

    {{-- </div> --}}

    <!-- ./wrapper -->
    <script type="text/javascript">
        var selectedDate;
        var table;

        $(document).ready(function() {

            $("#paginator").datepaginator({
                onSelectedDateChanged: function(a, t) {
                    selectedDate = moment(t).format("YYYY-MM-DD");
                    // Reload DataTable with new data based on selected date
                    // reloadTableData();
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
                // ajax: "",

                ajax: {
                    url: "{{ route('appointment') }}",
                    type: 'GET',
                    // data: {
                    //     selectedDate: selectedDate
                    // }
                    data: function(d) {
                        d.selectedDate = selectedDate; // Add selectedDate as a query parameter
                    }
                },
                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     name: 'DT_RowIndex',
                    //     orderable: false,
                    //     searchable: false,
                    //     render: function(data, type, row, meta) {
                    //         // Return the row index (starts from 0)
                    //         return meta.row + 1; // Adding 1 to start counting from 1
                    //     }
                    // },
                    {
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
                    // {
                    //     data: 'app_date',
                    //     name: 'app_date'
                    // },

                    {
                        data: 'app_time',
                        name: 'app_time'
                    },
                    

                    {
                        data: 'app_type',
                        name: 'app_type'
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
                $('#modal-booking').modal('show');
                   

            });

            $(document).on('click', '.btn-edit', function() {
                var appId = $(this).data('id');
                $('#reschedule_app_id').val(appId); // Set app ID in the hidden input
                $.ajax({
                    url: '{{ url("appointment", "") }}' + "/" + appId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#reschedule_app_id').val(response.id);

                        // $('#edit_staff').val(response.staff);
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
                showExistingAppointments(branchId, appDate, doctorId);
                
        });
            
        function showExistingAppointments(branchId, appDate, doctorId) {
            if (branchId && appDate && doctorId) {
                
                $.ajax({
                    url: '{{ route('get.exisitingAppointments', '') }}' + '/' + branchId,
                    type: "GET",
                    data: {
                        appdate: appDate,
                        doctorId:doctorId
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data.checkAllocated.length > 0) {
                            $('#existingAppointmentsError').show();
                        }
                        else {
                            $('#existingAppointmentsError').hide();
                        }
                        if (data.existingAppointments.length > 0) {
                            
                            // Clear existing content
                            $('#existingAppointments').empty();
                            
                            // Create a table element
                            var table = $('<table class="table table-striped">').addClass('appointment-table');
                            
                            // Create header row
                            var headerRow = $('<tr>');
                            headerRow.append($('<th>').text('Scheduled Appointments'));
                            table.append(headerRow);
                            
                            // Calculate number of rows needed
                            var numRows = Math.ceil(data.existingAppointments.length / 3);
                            
                            // Loop to create rows and populate cells
                            for (var i = 0; i < numRows; i++) {
                                var row = $('<tr>');
                                
                                // Create 3 cells for each row
                                for (var j = 0; j < 3; j++) {
                                    var dataIndex = i * 3 + j;
                                    if (dataIndex < data.existingAppointments.length) {
                                        var cell = $('<td>').text(data.existingAppointments[dataIndex].app_time);
                                        row.append(cell);
                                    } else {
                                        var cell = $('<td>'); // Create empty cell if no more data
                                        row.append(cell);
                                    }
                                }
                                
                                table.append(row);
                            }
                            
                            // Append table to existingAppointments div
                            $('#existingAppointments').append(table);
                            
                            // Show the div
                            $('#existingAppointments').show();
                            
                        } else {
                            $('#existingAppointments').html('No existing appointments found.');
                            $('#existingAppointments').show();

                        }
                    },

                error: function(xhr, status, error) {
                    console.error('Error fetching existing appointments:', error);
                    $('#existingAppointments').html('Error fetching existing appointments. Please try again later.');
                    $('#existingAppointments').show();
                   
                }
                });
            } else {
                console.log('hi no exisiting');
                
            }
        }
        $(document).on('click', '.btn-danger', function() {
                var appId = $(this).data('id');
                $('#delete_app_id').val(appId); // Set staff ID in the hidden input
                $('#modal-cancel').modal('show');
            });

        $('#btn-confirm-cancel').click(function() {
            var appId = $('#delete_app_id').val();
            console.log(appId,'hi');
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
                    "app_status_change_reason" : reason
                },
                success: function(response) {
                    console.log(response);a
                    table.draw(); // Refresh DataTable
                    $('#successMessage').text('Appointment cancelled successfully');
                    $('#successMessage').fadeIn().delay(3000).fadeOut(); // Show for 3 seconds
                    $('#modal-cancel').modal('hide'); // Close modal after success
                },
                error: function(xhr) {
                    $('#modal-cancel').modal('hide'); // Close modal in case of error
                    console.log("Error!", xhr.responseJSON.message, "error");
                }
            });
        });


        function reloadTableData() {
            table.ajax.reload();
        }
    </script>
@endsection
