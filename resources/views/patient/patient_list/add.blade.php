@extends('layouts.dashboard')
@section('title', 'Patient')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Add Patient</h3>
                    <a type="button" class="waves-effect waves-light btn btn-primary"
                        href="{{ route('patient.patient_list') }}"> <i class="fa-solid fa-angles-left"></i> Back</a>
                </div>
                <div id="error-message-container">
                    <p id="error-message"
                        class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fadeOut"
                        style="display: none;"></p>
                </div>
                {{-- @if (session('success'))
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
                @endif --}}
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body wizard-content px-2 pb-0">
                        <form method="post" class="validation-wizard wizard-circle" id="patientform"
                            action="{{ route('patient.patient_list.store') }}" enctype="multipart/form-data">
                            @csrf
                            <!-- Step 1 -->
                            <h6 class="tabHeading">Personal Info</h6>
                            <section class="tabSection">
                                @include('patient.patient_list.personal_info')
                            </section>

                            <!--Education-->
                            <h6 class="tabHeading">Appointment</h6>
                            <section class="tabSection">
                                @include('patient.patient_list.appointment')
                            </section>


                            <div id="storeRoute" data-url="{{ route('patient.patient_list.store') }}"
                                data-patientlist-route="{{ route('patient.patient_list') }}"></div>
                        </form>
                    </div>

                    <!-- /.box-body -->
                </div>
            </section>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            $("#patientform .actions ul li:last-child a").addClass("bg-success btn btn-success");

            var today = new Date().toISOString().split('T')[0];
            document.getElementById('regdate').setAttribute('min', today);

            var now = new Date().toISOString().slice(0, 16);
            document.getElementById('appdate').setAttribute('min', now);

            $('#pregnant').hide();

            var initialCountryId = $('#country_id').val(); // Assuming India is selected initially
            loadStates(initialCountryId);

            // Handle change event for country dropdown
            $('#country_id').change(function() {
                var countryId = $(this).val();
                loadStates(countryId);
            });

            // Handle change event for state dropdown
            $('#state_id').change(function() {
                var stateId = $(this).val();
                loadCities(stateId);
            });

            // Function to load states based on country ID
            function loadStates(countryId) {
                if (countryId) {
                    $.ajax({
                        url: '{{ route('get.states', '') }}' + '/' + countryId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#state_id').empty();
                            $('#state_id').append('<option value="">Select State</option>');
                            $.each(data, function(key, value) {
                                $('#state_id').append('<option value="' + key + '">' +
                                    value + '</option>');
                            });
                            var initialStateId = $('#state_id').val();
                            loadCities(initialStateId);
                        }
                    });
                } else {
                    $('#state_id').empty();
                }
            }

            // Function to load cities based on state ID
            function loadCities(stateId) {
                if (stateId) {
                    $.ajax({
                        url: '{{ route('get.cities', '') }}' + '/' + stateId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#city_id').empty();
                            $('#city_id').append('<option value="">Select City</option>');
                            $.each(data, function(key, value) {
                                $('#city_id').append('<option value="' + key + '">' +
                                    value + '</option>');
                            });
                        }
                    });
                } else {
                    $('#city_id').empty();
                }
            }

            // Handle change event for branch dropdown
            // $('#clinic_branch_id0').change(function() {

            //     var branchId = $(this).val();
            //     var appDate = $('#appdate').val();
            //     loadDoctors(branchId, appDate);
            // });
            // Handle change event for branch dropdown and appdate
            $('#clinic_branch_id0, #appdate').change(function() {
                var branchId = $('#clinic_branch_id0').val();
                var appDate = $('#appdate').val();
                $('#doctorNotAvailable').hide();
                loadDoctors(branchId, appDate);
            });

            $('#clinic_branch_id0, #appdate, #doctor2').change(function() {
                var branchId = $('#clinic_branch_id0').val();
                var appDate = $('#appdate').val();
                var doctorId = $('#doctor2').val();
                var patientId = '';
                $('#existingAppointmentsError').hide();
                $('#existAppContainer').hide();
                $('#existingAppointments').empty();
                $('#doctorNotAvailable').hide();
                showExistingAppointments(branchId, appDate, doctorId, patientId, 'store');

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

                            $('#doctor2').empty();
                            $('#doctor2').append('<option value="">Select a doctor</option>');
                            $.each(data, function(key, value) {
                                var doctorName = value.user.name.replace(/<br>/g, ' ');
                                $('#doctor2').append('<option value="' + value.user_id + '">' +
                                    doctorName + '</option>');
                            });
                        }
                    });
                } else {
                    $('#doctor2').empty();
                }
            }

            // Form submit validation
            // $('form.tab-wizard').submit(function(event) {
            //     var isValid = true;

            //     // Validate all weekdays
            //     ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].forEach(
            //         function(day) {
            //             if (!validateWeekdayTime(day)) {
            //                 isValid = false;
            //             }
            //         });

            //     if (!isValid) {
            //         console.log('hi');

            //         event.preventDefault(); // Prevent form submission if validation fails
            //         $('.error-message').text('Please fill all weekday times');
            //     } else {
            //         $('.error-message').text(''); // Clear error message if validation passes
            //     }
            // });

            // Event listener for dropdown item click
            $(".dropdown-menu .dropdown-item").click(function() {
                // Get the selected salutation text
                let salutation = $(this).text().trim();

                // Update the button text with the selected salutation
                $(".input-group .dropdown-toggle").text(salutation);
            });

        });

        function addMedicalCondition() {
            const wrapper = document.getElementById('medical-conditions-wrapper');
            const div = document.createElement('div');
            div.className = 'input-group mb-0';
            div.innerHTML = `
                <input type="text" class="form-control" name="medical_conditions[]" placeholder="Medical Condition">
                <button class="btn-sm btn-danger" type="button" onclick="removeMedicalCondition(this)">-</button>
            `;
            wrapper.appendChild(div);
        }

        function removeMedicalCondition(button) {
            const div = button.parentElement;
            div.remove();
        }
        document.addEventListener('DOMContentLoaded', function() {
            const genderSelect = document.getElementById('gender');
            const pregnantContainer = document.getElementById('pregnant_container');

            genderSelect.addEventListener('change', function() {
                if (genderSelect.value === 'F') {
                    pregnantContainer.style.display = 'block';
                    $('#pregnant').show();
                } else {
                    $('#pregnant').hide();
                    pregnantContainer.style.display = 'none';
                    document.getElementById('pregnant').value = '';
                }
            });

            // Trigger change event to set initial state
            genderSelect.dispatchEvent(new Event('change'));
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

                        // Show/hide the 'existingAppointmentsError' div based on 'checkAllocated'
                        if (data.checkAllocated.length > 0) {
                            $('#existingAppointmentsError').show();
                        } else {
                            $('#existingAppointmentsError').hide();
                        }

                        if (data.doctorAvailable == true) {

                            $('#doctorNotAvailable').hide();
                        } else {

                            $('#doctorNotAvailable').show();

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
                                        var app_time = data.existingAppointments[dataIndex].app_time;
                                        var formattedTime = convertTo12HourFormat(app_time);
                                        var cell = $('<td class="b-1 w-100 text-center">').text(formattedTime);
                                        row.append(cell);
                                    } else {
                                        var cell = $('<td>'); // Empty cell
                                        row.append(cell);
                                    }
                                }
                                table.append(row);
                            }

                            if (methodType === 'store') {
                                $('#existingAppointments').append($('<h6 class="text-warning mb-1">').text(
                                    'Scheduled Appointments'));
                                $('#existingAppointments').append(table);
                                $('#existAppContainer').show();
                                $('#existingAppointments').show();
                            } else if (methodType === 'edit') {
                                $('#rescheduleExistingAppointments').append($('<h6 class="text-warning mb-1">')
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
    </script>
@endsection
