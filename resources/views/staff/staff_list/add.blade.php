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
                    <h3 class="page-title">Add Staff Member</h3>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body wizard-content">
                        <form method="post" class="tab-wizard wizard-circle" action="{{ route('staff.staff_list.store') }}" enctype="multipart/form-data">
                            @csrf
                            <!-- Step 1 -->
                            <h6>Personal Info</h6>
                            <section>
                                <!--name and gender-->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="firstname">First Name</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname"
                                                placeholder="First Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="lastname">Last Name</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname"
                                                placeholder="Last Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="form-label" for="role">Role</label>
                                                <select class="form-select" id="gender" name="gender" required>
                                                    <option value="">Select Gender</option>
                                                    <option value="M">Male</option>
                                                    <option value="F">Female</option>
                                                    <option value="O">Others</option>
                                                </select>
                                            
                                        </div>
                                    </div>
                                </div>
                                <!--dob, email, mob-->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="date_of_birth">Date of Birth</label>
                                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="email">E-mail Address</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="E-mail" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="phone">Contact
                                                Number</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                placeholder="Phone Number" required>
                                        </div>
                                    </div>
                                </div>
                                {{-- <h4 class="box-title text-info mb-0 mt-2"><i class="fa fa-address-card me-15"></i> Address
                                </h4> --}}
                                {{-- <hr class="my-15"> --}}
                                <!--address-->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="address1">Address Line
                                                1</label>
                                            <input type="text" class="form-control" id="address1" name="address1"
                                                placeholder="Adress line 1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="address2">Address Line
                                                2</label>
                                            <input type="text" class="form-control" id="address2" name="address2"
                                                placeholder="Adress line 2" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="country_id">Country</label>
                                            <select class="form-select" id="country_id" name="country_id" required>
                                                <option value="">Select Country</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" <?php if ($country->id == 101) {
                                                            echo 'selected';
                                                        } ?>>
                                                            {{ $country->country }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="state_id">State</label>
                                            <select class="form-select" id="state_id" name="state_id" required>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="city_id">City</label>
                                            <select class="form-select" id="city_id" name="city_id" required>
                                               
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="pincode">Pin Code</label>
                                            <input class="form-control" type="text" id="pincode" name="pincode" required
                                                placeholder="XXX XXX">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                    <div class="form-group">
                                            <label class="form-label" for="role">Role</label>
                                            <select class="form-select" id="role" name="role" required>
                                                <option value="">Select Role</option>
                                                @foreach ($userTypes as $userType)
                                                    <option value="{{ $userType->id }}">{{ $userType->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="profile_photo">Photo</label>
                                            <input class="form-control @error('profile_photo') is-invalid @enderror"
                                                type="file" id="profile_photo" name="profile_photo" placeholder="logo">
                                            @error('profile_photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <canvas id="logoCanvas" style="height: 64px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!--Education-->
                            <h6>Experience</h6>
                            <section>
                                <!--qualification-->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="qualification">Qualification</label>
                                            <input type="text" class="form-control" id="qualification"
                                                name="qualification" placeholder="qualification" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="experience">Experience</label>
                                            <input type="text" class="form-control" id="experience" name="experience"
                                                placeholder="experience" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="department">Department</label>
                                            <select class="form-select" id="department_id" name="department_id" required>
                                                <option value="">Select department</option>
                                                @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}" <?php if ($department->id == 101) {
                                                            echo 'selected';
                                                        } ?>>
                                                            {{ $department->department }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="designation">Designation</label>
                                            <input type="text" class="form-control" id="designation"
                                                name="designation" placeholder="Designation" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="date_of_joining">Date of Joining</label>
                                            <input class="form-control" type="date" id="date_of_joining" name="date_of_joining" required>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="date_of_relieving">Date of Relieving</label>
                                            <input class="form-control" type="date" id="date_of_relieving" name="date_of_relieving"
                                                disabled>

                                        </div>
                                    </div>
                                </div>

                                <!--for doctors-->
                                <div class="row doctorFields" style="display: none;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="specialization">Specialization</label>
                                            <input type="text" class="form-control" id="specialization"
                                                name="specialization" placeholder="Specialization" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="subspecial">Subspeciality</label>
                                            <input type="text" class="form-control" id="subspecial" name="subspecial"
                                                placeholder="Subspeciality" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="license_number">Licence</label>
                                            <input type="text" class="form-control" id="license_number" name="license_number"
                                                placeholder="Council No." required>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="box-title text-info mb-0 mt-2 doctorFields" style="display: none;"><i
                                        class="fa fa-clock me-15"></i> Availability
                                </h4>
                                <hr class="my-15 doctorFields" style="display: none;">

                                <!--availability-->
                                <div class="row doctorFields" style="display: none;">
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="sunday">Sunday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="sunday_from"
                                                    name="sunday_from" placeholder="from">
                                                <span class="input-group-text col-md-1">-</span>
                                                <input type="time" class="form-control" id="sunday_to"
                                                    name="sunday_to" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="monday">Monday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="monday_from"
                                                    name="monday_from" placeholder="from">
                                                <span class="input-group-text col-md-1">-</span>
                                                <input type="time" class="form-control" id="monday_to"
                                                    name="monday_to" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="tuesday">Tuesday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="tuesday_from"
                                                    name="tuesday_from" placeholder="from">
                                                <span class="input-group-tfromext col-md-1">-</span>
                                                <input type="time" class="form-control" id="tuesday_to"
                                                    name="tuesday_to" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="wednesday">Wednesday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="wednesday_from"
                                                    name="wednesday_from" placeholder="from">
                                                <span class="input-group-text col-md-1">-</span>
                                                <input type="time" class="form-control" id="wednesday_to"
                                                    name="wednesday_to" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row doctorFields" style="display: none;">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="thursday">Thursday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="thursday_from"
                                                    name="thursday_from" placeholder="from">
                                                <span class="input-group-text col-md-1">-</span>
                                                <input type="time" class="form-control" id="thursday_to"
                                                    name="thursday_to" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="friday">Friday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="friday_from"
                                                    name="friday_from" placeholder="from">
                                                <span class="input-group-text col-md-1">-</span>
                                                <input type="time" class="form-control" id="friday_to"
                                                    name="friday_to" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="saturday">Saturday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="saturday_from"
                                                    name="saturday_from" placeholder="from">
                                                <span class="input-group-text col-md-1">-</span>
                                                <input type="time" class="form-control" id="saturday_to"
                                                    name="saturday_to" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <div id="storeRoute" data-url="{{ route('staff.staff_list.store') }}"></div>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
            </section>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $(".tab-wizard .actions ul li:last-child a").addClass("bg-success btn btn-success");

            $('select[name=role]').change(function() {
                if (this.value === '3') {
                    $('.doctorFields').show();
                } else {
                    $('.doctorFields').hide();
                }
            });

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
                        url: '{{ route("get.states", "") }}' + '/' + countryId,
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
                        url: '{{ route("get.cities", "") }}' + '/' + stateId,
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
                var fromValue = $('#'+day+'_from').val();
                var toValue = $('#'+day+'_to').val();

                // Check if fromValue is filled and toValue is empty
                if (fromValue && !toValue) {
                    $('#'+day+'_to').addClass('is-invalid'); // Add Bootstrap's is-invalid class
                    return false;
                } else {
                    $('#'+day+'_to').removeClass('is-invalid'); // Remove is-invalid class if valid
                    return true;
                }
            }

            // Event handlers for weekday inputs
            ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].forEach(function(day) {
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
                ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].forEach(function(day) {
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
           
        });


    </script>
@endsection
