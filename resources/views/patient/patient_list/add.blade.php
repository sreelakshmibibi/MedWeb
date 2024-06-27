@extends('layouts.dashboard')
@section('title', 'Patient')
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
                    <h3 class="page-title">Add Patient</h3>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body wizard-content">
                        <form method="post" class="tab-wizard wizard-circle" id="patientform"
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

                            {{-- @include('staff.staff_list.availability') --}}

                            <div id="storeRoute" data-url="{{ route('patient.patient_list.store') }}"></div>
                        </form>
                    </div>

                    <!-- /.box-body -->
                </div>
            </section>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            $("#patientform").steps({
                headerTag: "h6.tabHeading",
                bodyTag: "section.tabSection",
                transitionEffect: "none",
                titleTemplate: "#title#",
                labels: {
                    finish: '<span><i class="fa fa-save"></i> Save</span>',
                },
                onFinished: function(event, currentIndex) {
                    swal(
                        "Your Order Submitted!",
                        "Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor."
                    );
                },
            });


            $(".tab-wizard .actions ul li:last-child a").addClass("bg-success btn btn-success");



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


            function validateWeekdayTime(day) {
                var fromValue = $('#' + day + '_from').val();
                var toValue = $('#' + day + '_to').val();

                // Check if fromValue is filled and toValue is empty
                if (fromValue && !toValue) {
                    $('#' + day + '_to').addClass('is-invalid'); // Add Bootstrap's is-invalid class
                    return false;
                } else {
                    $('#' + day + '_to').removeClass('is-invalid'); // Remove is-invalid class if valid
                    return true;
                }
            }

            // Event handlers for weekday inputs
            ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].forEach(function(
                day) {
                // Validate on change of weekday from
                $('#' + day + '_from').change(function() {
                    validateWeekdayTime(day);
                });

                // Validate on change of weekday to
                $('#' + day + '_to').change(function() {
                    validateWeekdayTime(day);
                });
            });
            // Form submit validation
            $('form.tab-wizard').submit(function(event) {
                var isValid = true;

                // Validate all weekdays
                ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].forEach(
                    function(day) {
                        if (!validateWeekdayTime(day)) {
                            isValid = false;
                        }
                    });

                if (!isValid) {
                    console.log('hi');

                    event.preventDefault(); // Prevent form submission if validation fails
                    $('.error-message').text('Please fill all weekday times');
                } else {
                    $('.error-message').text(''); // Clear error message if validation passes
                }
            });

            // Event listener for dropdown item click
            $(".dropdown-menu .dropdown-item").click(function() {
                // Get the selected salutation text
                let salutation = $(this).text().trim();

                // Update the button text with the selected salutation
                $(".input-group .dropdown-toggle").text(salutation);
            });

            $("#add_checkbox").change(function() {
                // Check if checkbox is checked
                if ($(this).is(":checked")) {
                    // Copy value from address1 to caddress1
                    $("#caddress1").val($("#address1").val());
                    $("#caddress2").val($("#address2").val());
                    $("#ccity_id").val($("#city_id").val());
                    $("#cstate_id").val($("#state_id").val());
                    $("#ccountry_id").val($("country_id").val());
                    $("#cpincode").val($("#pincode").val());
                } else {
                    // Clear caddress1 if checkbox is unchecked
                    $("#caddress1").val("");
                    $("#caddress2").val("");
                    $("#ccity_id").val("");
                    $("#cstate_id").val("");
                    $("#ccountry_id").val("");
                    $("#cpincode").val("");
                }
            });


        });
    </script>
@endsection
