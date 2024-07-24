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
                    <div class="box-body wizard-content">
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
                } else {
                    pregnantContainer.style.display = 'none';
                    document.getElementById('pregnant').value = '';
                }
            });

            // Trigger change event to set initial state
            genderSelect.dispatchEvent(new Event('change'));
        });
    </script>
@endsection
