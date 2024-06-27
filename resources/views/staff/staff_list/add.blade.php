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
                        <form method="post" class="tab-wizard wizard-circle" id="staffform"
                            action="{{ route('staff.staff_list.store') }}" enctype="multipart/form-data">
                            @csrf
                            <!-- Step 1 -->
                            <h6 class="tabHeading">Personal Info</h6>
                            <section class="tabSection">
                                @include('staff.staff_list.personal_info')
                            </section>

                            <!--Education-->
                            <h6 class="tabHeading">Experience</h6>
                            <section class="tabSection">
                                @include('staff.staff_list.experience')
                            </section>

                            @include('staff.staff_list.availability')

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
                                                        <select class="select2" id="clinic_branch_id${count}" name="clinic_branch_id${count}" required
                                data-placeholder="Select a Branch" style="width: 100%;">
                                @foreach ($clinicBranches as $clinicBranch)
                                    <?php
                                    $clinicAddress = $clinicBranch->clinic_address;
                                    $clinicAddress = explode('<br>', $clinicBranch->clinic_address);
                                    $clinicAddress = implode(', ', $clinicAddress);
                                    $branch = $clinicAddress . ', ' . $clinicBranch->city->city . ', ' . $clinicBranch->state->state;
                                    ?>
                                    <option value="{{ $clinicBranch->id }}">
                                        {{ $branch }}</option>
                                @endforeach
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

            // $("#add_checkbox").change(function() {
            //     if (!this.checked) {
            //         $('.addressDiv').show();
            //     } else {
            //         $('.addressDiv').hide();
            //     }
            // })

            $(".tab-wizard .actions ul li:last-child a").addClass("bg-success btn btn-success");

            $('select[name="role[]"]').change(function() {
                // if (this.value === '3') {
                if (this.value && this.value.includes('3')) {
                    $('.doctorFields').show();
                    $('.otherFields').hide();
                } else {
                    $('.doctorFields').hide();
                    $('.otherFields').show();
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
