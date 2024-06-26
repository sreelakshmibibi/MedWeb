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
                        <form method="post" class="tab-wizard wizard-circle" action="{{ route('staff.staff_list.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <!-- Step 1 -->
                            <h6 class="tabHeading">Personal Info</h6>
                            <section class="tabSection">
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
                                            <label class="form-label" for="gender">Gender</label>
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
                                            <input type="date" class="form-control" id="date_of_birth"
                                                name="date_of_birth" required>
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
                                <h5 class="box-title text-info mb-0 mt-2"><i class="fa fa-address-card me-15"></i>Permanent
                                    Address
                                </h5>
                                <hr class="my-15">
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
                                            <select class="select2" id="country_id" name="country_id" required
                                                data-placeholder="Select a Country" style="width: 100%;">
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
                                            <select class="select2" id="state_id" name="state_id" required
                                                data-placeholder="Select a State" style="width: 100%;">

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="city_id">City</label>
                                            <select class="select2" required id="city_id" name="city_id"
                                                data-placeholder="Select a City" style="width: 100%;">

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="pincode">Pin Code</label>
                                            <input class="form-control" type="text" id="pincode" name="pincode"
                                                required placeholder="XXX XXX">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <input type="checkbox" id="add_checkbox" class="filled-in chk-col-success"
                                            checked />
                                        <label for="add_checkbox">Is permanent address and contact address same?</label>
                                    </div>
                                </div>

                                <h5 class="box-title text-info mb-0 mt-2 addressDiv" style="display: none;"><i
                                        class="fa fa-address-card me-15"></i>Contact Address
                                </h5>
                                <hr class="my-15 addressDiv" style="display: none;">

                                <div class="row addressDiv" style="display: none;">
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
                                            <select class="select2" id="country_id" name="country_id" required
                                                data-placeholder="Select a Country" style="width: 100%;">
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

                                <div class="row addressDiv" style="display: none;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="state_id">State</label>
                                            <select class="select2" id="state_id" name="state_id" required
                                                data-placeholder="Select a State" style="width: 100%;">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="city_id">City</label>
                                            <select class="select2" required id="city_id" name="city_id"
                                                data-placeholder="Select a City" style="width: 100%;">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="pincode">Pin Code</label>
                                            <input class="form-control" type="text" id="pincode" name="pincode"
                                                required placeholder="XXX XXX">
                                        </div>
                                    </div>
                                </div>
                                {{-- <hr class="my-15 addressDiv" style="display: none;"> --}}

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="role">Role</label>
                                            <select class="form-control select2 form-select" required id="role"
                                                name="role[]" data-placeholder=" Select a Role" style="width: 100%;"
                                                multiple>
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
                                                type="file" id="profile_photo" name="profile_photo"
                                                placeholder="logo">
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
                            <h6 class="tabHeading">Experience</h6>
                            <section class="tabSection">
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
                                            <input class="form-control" type="date" id="date_of_joining"
                                                name="date_of_joining" required>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="date_of_relieving">Date of Relieving</label>
                                            <input class="form-control" type="date" id="date_of_relieving"
                                                name="date_of_relieving" disabled>

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
                                            <input type="text" class="form-control" id="license_number"
                                                name="license_number" placeholder="Council No." required>
                                        </div>
                                    </div>
                                </div>


                            </section>

                            <div class="doctordiv" style="display: none;">
                                <section id="finalStepContent" class="tabHideSection">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="box-title text-info mb-0 mt-2 "><i class="fa fa-clock me-15"></i>
                                            Availability
                                        </h5>
                                        <button id="buttonAddRow" type="button"
                                            class="waves-effect waves-light btn btn-sm btn-outline-primary">
                                            <i class="fa fa-add"></i>
                                            Add</button>
                                    </div>
                                    <hr class="my-15 ">

                                    <div class="table-responsive">
                                        <table id="myTable"
                                            class="table table-bordered table-hover table-striped mb-0 text-center">

                                            <thead>
                                                <tr class="bg-primary-light">
                                                    <th>No</th>
                                                    <th>Branch</th>
                                                    <th>Sunday</th>
                                                    <th>Monday</th>
                                                    <th>Tuesday</th>
                                                    <th>Wednesday</th>
                                                    <th>Thursday</th>
                                                    <th>Friday</th>
                                                    <th>Saturday</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablebody">
                                                <tr>
                                                    <td>1</td>
                                                    <td>
                                                        <select class="form-select" id="clinic_branch_id0" name="clinic_branch_id0"
                                                            required style="width:150px;">
                                                            <option value="">Select branch</option>
                                                            @foreach ( $clinicBranches as $clinicBranch ) 

                                                                <?php
                                                                    $clinicAddress = explode("<br>", $clinicBranch->clinic_address);
                                                                    $clinicAddress = implode(", ", $clinicAddress);
                                                                    $branch = $clinicAddress. ", ".
                                                                    $clinicBranch->city->city. ", ". $clinicBranch->state->state;
                                                                ?>
                                                                <option value="{{ $clinicBranch->id}}"> {{ $branch}}</option>
                                                                
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control timeInput"
                                                            id="sunday_from0" title="from" name="sunday_from0"
                                                            style="width:115px;">
                                                        <input type="time" class="form-control" id="sunday_to0"
                                                            title="to" name="sunday_to0" style="width:115px;">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" id="monday_from0"
                                                            name="monday_from0" title="from" style="width:115px;">
                                                        <input type="time" class="form-control" id="monday_to0"
                                                            name="monday_to0" title="to" style="width:115px;">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" id="tuesday_from0"
                                                            name="tuesday_from0" title="from" style="width:115px;">
                                                        <input type="time" class="form-control" id="tuesday_to0"
                                                            name="tuesday_to0" title="to" style="width:115px;">

                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" id="wednesday_from0"
                                                            name="wednesday_from0" title="from" style="width:115px;">
                                                        <input type="time" class="form-control" id="wednesday_to0"
                                                            name="wednesday_to0" title="to" style="width:115px;">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" id="thursday_from0"
                                                            name="thursday_from0" title="from" style="width:115px;">
                                                        <input type="time" class="form-control" id="thursday_to0"
                                                            name="thursday_to0" title="to" style="width:115px;">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" id="friday_from0"
                                                            name="friday_from0" title="from" style="width:115px;">
                                                        <input type="time" class="form-control" id="friday_to0"
                                                            name="friday_to0" title="to" style="width:115px;">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" id="saturday_from0"
                                                            name="saturday_from0" title="from" style="width:115px;">
                                                        <input type="time" class="form-control" id="saturday_to0"
                                                            name="saturday_to0" title="to" style="width:115px;">
                                                    </td>
                                                    <td>
                                                        <button type="button" id="btnDelete" title="delete row"
                                                            class="waves-effect waves-light btn btn-danger btn-sm"> <i
                                                                class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            </div>

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
            let count = 2;
            // Event listener for Add Row button click
            $(document).on('click', '#buttonAddRow', function() {
                let newRow = `<tr>
                                <td>${count}</td>
                                                    <td>
                                                        <select class="form-select" id="clinic_branch_id${count}"
                                                            name="clinic_branch_id${count}" required style="width:150px;">
                                                            <option value="">Select branch</option>
                                                           <?php foreach ( $clinicBranches as $clinicBranch ) 

                                                                
                                                                    $clinicAddress = explode("<br>", $clinicBranch->clinic_address);
                                                                    $clinicAddress = implode(", ", $clinicAddress);
                                                                    $branch = $clinicAddress. ", ".
                                                                    $clinicBranch->city->city. ", ". $clinicBranch->state->state;
                                                                ?>
                                                                <option value="{{ $clinicBranch->id}}"> {{ $branch}}</option>
                                                                
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" id="sunday_from${count}"
                                                            name="sunday_from${count}" title="from"
                                                            style="width:115px;">
                                                        <input type="time" class="form-control" id="sunday_to${count}"
                                                            name="sunday_to${count}" title="to" style="width:115px;">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" id="monday_from${count}"
                                                            name="monday_from${count}" title="from" style="width:115px;">
                                                        <input type="time" class="form-control" id="monday_to${count}"
                                                            name="monday_to${count}" title="to" style="width:115px;">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" id="tuesday_from${count}"
                                                            name="tuesday_from${count}" title="from" style="width:115px;">
                                                        <input type="time" class="form-control" id="tuesday_to${count}"
                                                            name="tuesday_to${count}" title="to" style="width:115px;">

                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" id="wednesday_from${count}"
                                                            name="wednesday_from${count}" title="from"
                                                            style="width:115px;">
                                                        <input type="time" class="form-control" id="wednesday_to${count}"
                                                            name="wednesday_to${count}" title="to" style="width:115px;">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" id="thursday_from${count}"
                                                            name="thursday_from${count}" title="from" style="width:115px;">
                                                        <input type="time" class="form-control" id="thursday_to${count}"
                                                            name="thursday_to${count}" title="to" style="width:115px;">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" id="friday_from${count}"
                                                            name="friday_from${count}" title="from" style="width:115px;">
                                                        <input type="time" class="form-control" id="friday_to${count}"
                                                            name="friday_to${count}" title="to" style="width:115px;">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" id="saturday_from${count}"
                                                            name="saturday_from${count}" title="from" style="width:115px;">
                                                        <input type="time" class="form-control" id="saturday_to${count}"
                                                            name="saturday_to${count}" title="to" style="width:115px;">
                                                    </td>
                                                    <td>
                                                        <button type="button" id="btnDelete" title="delete row"
                                                            class="waves-effect waves-light btn btn-danger btn-sm"> <i
                                                                class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr><input type="hidden" name="row_count" value="${count}">`;

                $('#tablebody').append(newRow);
                count++;
            });

            // Event listener for Delete button click (on dynamically added rows)
            $(document).on('click', '#btnDelete', function() {
                // Remove the row when Delete button is clicked
                $(this).closest('tr').remove();
                count--;
                updateRowCount(); 
            });

            function updateRowCount() {
                // Update count for each row after deletion
                $('#tablebody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1); // Update visible row number
                    // Update hidden input value if you have one
                    // $(this).find('input[name="row_count[]"]').val(index + 1);
                });
                
                // Update count variable to reflect the number of rows
                count = $('#tablebody tr').length;
            }

            $("#add_checkbox").change(function() {
                if (!this.checked) {
                    $('.addressDiv').show();
                } else {
                    $('.addressDiv').hide();
                }
            })

            $(".tab-wizard .actions ul li:last-child a").addClass("bg-success btn btn-success");

            $('select[name="role[]"]').change(function() {
                // if (this.value === '3') {
                if (this.value && this.value.includes('3')) {
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


        });
    </script>
@endsection
