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
                        <form method="post" class="validation-wizard wizard-circle" id="staffform"
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



                            <div id="storeRoute" data-url="{{ route('staff.staff_list.store') }}"
                                data-stafflist-route="{{ route('staff.staff_list') }}"></div>
                            <input type="hidden" name="row_count" id="row_count">
                        </form>
                    </div>
                    <div class="doctordiv" style="display: none;">
                        @include('staff.staff_list.availability')
                    </div>
                    <!-- /.box-body -->
                </div>
            </section>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#staffform .actions ul li:last-child a").addClass("bg-success btn btn-success");

            let count = 1;

            // Event listener for Add Row button click
            $(document).on('click', '#buttonAddRow', function() {
                count++;
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
                        <input type="time" class="form-control timeInput fromTime" id="sunday_from${count}"
                            name="sunday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="sunday_to${count}"
                            name="sunday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="monday_from${count}"
                            name="monday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="monday_to${count}"
                            name="monday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="tuesday_from${count}"
                            name="tuesday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="tuesday_to${count}"
                            name="tuesday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="wednesday_from${count}"
                            name="wednesday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="wednesday_to${count}"
                            name="wednesday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="thursday_from${count}"
                            name="thursday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="thursday_to${count}"
                            name="thursday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="friday_from${count}"
                            name="friday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="friday_to${count}"
                            name="friday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="saturday_from${count}"
                            name="saturday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="saturday_to${count}"
                            name="saturday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <button type="button" class="btnDelete waves-effect waves-light btn btn-danger btn-sm"
                            title="delete row"> <i class="fa fa-trash"></i></button>
                    </td>
                </tr>`;

                $('#tablebody').append(newRow);
                updateRowCount();
            });

            // Event listener for Delete button click
            $(document).on('click', '.btnDelete', function() {
                $(this).closest('tr').remove();
                updateRowCount();
            });

            // Function to update row count input field value
            function updateRowCount() {
                $('#row_count').val(count);
            }

            // Validation for 'from' and 'to' time fields
            $(document).on('focusout', '.fromTime, .toTime', function() {
                let index = $(this).attr('id').match(/\d+/)[0];
                let day = $(this).closest('td').prevAll().length; // Determine the column index
                switch (day) {
                    case 2:
                        fromField = $(`#sunday_from${index}`);
                        toField = $(`#sunday_to${index}`);
                        break;
                    case 3: // Monday column (index 3)
                        fromField = $(`#monday_from${index}`);
                        toField = $(`#monday_to${index}`);
                        break;
                    case 4: // Tuesday column (index 4)
                        fromField = $(`#tuesday_from${index}`);
                        toField = $(`#tuesday_to${index}`);
                        break;
                    case 5: // Wednesday column (index 5)
                        fromField = $(`#wednesday_from${index}`);
                        toField = $(`#wednesday_to${index}`);
                        break;
                    case 6: // Thursday column (index 6)
                        fromField = $(`#thursday_from${index}`);
                        toField = $(`#thursday_to${index}`);
                        break;
                    case 7: // Friday column (index 7)
                        fromField = $(`#friday_from${index}`);
                        toField = $(`#friday_to${index}`);
                        break;
                    case 8: // Saturday column (index 8)
                        fromField = $(`#saturday_from${index}`);
                        toField = $(`#saturday_to${index}`);
                        break;
                    default:
                        break;
                }

                if ($(this).hasClass('fromTime')) {
                    if ($(this).val()) {
                        toField.rules('add', {
                            required: true,
                            messages: {
                                required: `To Time is required when From time is entered`
                            }
                        });
                    } else {
                        toField.rules('remove', 'required');
                    }
                }

                if ($(this).hasClass('toTime')) {
                    if ($(this).val()) {
                        fromField.rules('add', {
                            required: true,
                            messages: {
                                required: `From Time is required when To time is entered`
                            }
                        });
                    } else {
                        fromField.rules('remove', 'required');
                    }
                }
            });

            // Handle change event for role dropdown
            $('select[name="role[]"]').change(function() {
                if ($(this).val() && $(this).val().includes('3')) {
                    $('.doctorFields').show();
                    $('.otherFields').hide();
                } else {
                    $('.doctorFields').hide();
                    $('.otherFields').show();
                }
            });

            // Function to load states based on country ID
            function loadStates(countryId, stateSelectElement) {
                if (countryId) {
                    $.ajax({
                        url: '{{ route("get.states", "") }}' + '/' + countryId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            stateSelectElement.empty();
                            stateSelectElement.append('<option value="">Select State</option>');
                            $.each(data, function(key, value) {
                                stateSelectElement.append('<option value="' + key + '">' +
                                    value + '</option>');
                            });
                            // Trigger change event to load initial cities
                            stateSelectElement.trigger('change');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error loading states:', textStatus, errorThrown);
                        }
                    });
                } else {
                    stateSelectElement.empty();
                }
            }

            // Function to load cities based on state ID
            function loadCities(stateId, citySelectElement) {
                if (stateId) {
                    $.ajax({
                        url: '{{ route("get.cities", "") }}' + '/' + stateId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            citySelectElement.empty();
                            citySelectElement.append('<option value="">Select City</option>');
                            $.each(data, function(key, value) {
                                citySelectElement.append('<option value="' + key + '">' +
                                    value + '</option>');
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error loading cities:', textStatus, errorThrown);
                        }
                    });
                } else {
                    citySelectElement.empty();
                }
            }

            // Initializations
            var initialCountryId = $('#country_id').val(); // Assuming India is selected initially
            loadStates(initialCountryId, $('#state_id'));

            // Handle change event for country dropdown
            $('#country_id').change(function() {
                var countryId = $(this).val();
                loadStates(countryId, $('#state_id'));
            });

            // Handle change event for state dropdown
            $('#state_id').change(function() {
                var stateId = $(this).val();
                loadCities(stateId, $('#city_id'));
            });

            // Same logic for communication address
            var com_initialCountryId = $('#com_country_id').val(); // Assuming India is selected initially
            loadStates(com_initialCountryId, $('#com_state_id'));

            $('#com_country_id').change(function() {
                var countryId = $(this).val();
                loadStates(countryId, $('#com_state_id'));
            });

            $('#com_state_id').change(function() {
                var stateId = $(this).val();
                loadCities(stateId, $('#com_city_id'));
            });

            // Validate weekday time inputs
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
            $('form.validation-wizard').submit(function(event) {
                var isValid = true;

                // Validate all weekdays
                ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].forEach(
                    function(day) {
                        if (!validateWeekdayTime(day)) {
                            isValid = false;
                        }
                    });

                if (!isValid) {
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

            // Event listener for communication address checkbox
            $("#add_checkbox").change(function() {
                if ($(this).is(':checked')) {
                    $('#communicationAddress').hide();
                    $('#com_address1').removeAttr('required');
                    $('#com_address2').removeAttr('required');
                    $('#com_city_id').removeAttr('required');
                    $('#com_state_id').removeAttr('required');
                    $('#com_country_id').removeAttr('required');
                    $('#com_pincode').removeAttr('required');
                } else {
                    $('#communicationAddress').show();
                    $('#com_address1').attr('required', true);
                    $('#com_address2').attr('required', true);
                    $('#com_city_id').attr('required', true);
                    $('#com_state_id').attr('required', true);
                    $('#com_country_id').attr('required', true);
                    $('#com_pincode').attr('required', true);
                }
            });
        });
    </script>

@endsection
