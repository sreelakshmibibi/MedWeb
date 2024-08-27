@extends('layouts.dashboard')
@section('title', 'Staff')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">

                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Add Staff Member</h3>
                    <a type="button" class="waves-effect waves-light btn btn-primary" href="{{ route('staff.staff_list') }}">
                        <i class="fa-solid fa-angles-left"></i> Back</a>
                </div>
                <div id="error-message-container">
                    <p id="error-message"
                        class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fadeOut"
                        style="display: none;"></p>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body wizard-content px-2 pb-0">
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

            var today = new Date().toISOString().split('T')[0];
            // document.getElementById('date_of_joining').setAttribute('min', today);

            var input = document.getElementById('profile_photo');
            var canvas = document.getElementById('logoCanvas');
            var ctx = canvas.getContext('2d');

            input.addEventListener('change', function(event) {
                var file = event.target.files[0];
                var reader = new FileReader();

                reader.onload = function(e) {
                    var img = new Image();
                    img.onload = function() {
                        // canvas.width = img.width;
                        // canvas.height = img.height;
                        // ctx.drawImage(img, 0, 0, img.width, img.height);
                        canvas.width = img.height;
                        canvas.height = img.height;
                        ctx.drawImage(img, 0, 0, img.height, img.height);
                    };
                    img.src = e.target.result;
                };

                if (file) {
                    reader.readAsDataURL(file);
                }
            });

            let count = 1;

            let selectedBranchIds = [];
            // Function to update selected branch IDs
            function updateSelectedBranchIds() {
                selectedBranchIds = $('select.clinic_branch_select').map(function() {
                    return $(this).val();
                }).get();
            }
            // Function to populate options for a select element
            function populateOptions($selectElement) {
                $selectElement.empty(); // Clear existing options
                // Add placeholder option
                $selectElement.append(new Option('Select a Branch', ''));
                // Populate options excluding selected ones
                @foreach ($clinicBranches as $clinicBranch)
                    <?php
                    $clinicAddress = $clinicBranch->clinic_address;
                    $clinicAddress = explode('<br>', $clinicBranch->clinic_address);
                    $clinicAddress = implode(', ', $clinicAddress);
                    $branch = $clinicAddress . ', ' . $clinicBranch->city->city . ', ' . $clinicBranch->state->state;
                    ?>
                    if (!selectedBranchIds.includes('{{ $clinicBranch->id }}')) {
                        $selectElement.append(new Option('{{ $branch }}', '{{ $clinicBranch->id }}'));
                    }
                @endforeach
            }

            // Event listener for Add Row button click
            $(document).on('click', '#buttonAddRow', function() {
                count++;
                let newRow = `<tr>
                    <td>${count}</td>
                    <td>
                        <select class="form-control clinic_branch_select" id="clinic_branch_id${count}" name="clinic_branch_id${count}" required
                            data-placeholder="Select a Branch" style="width: 100%;">
                           
                        </select>
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="sunday_from${count}"
                            name="sunday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="sunday_to${count}"
                            name="sunday_to${count}" title="to" style="width:115px;">
                            <span class="error-message text-danger" id="error_sunday_from${count}"></span>
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="monday_from${count}"
                            name="monday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="monday_to${count}"
                            name="monday_to${count}" title="to" style="width:115px;">
                        <span class="error-message text-danger" id="error_monday_from${count}"></span>
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="tuesday_from${count}"
                            name="tuesday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="tuesday_to${count}"
                            name="tuesday_to${count}" title="to" style="width:115px;">
                        <span class="error-message text-danger" id="error_tuesday_from${count}"></span>
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="wednesday_from${count}"
                            name="wednesday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="wednesday_to${count}"
                            name="wednesday_to${count}" title="to" style="width:115px;">
                        <span class="error-message text-danger" id="error_wednesday_from${count}"></span>
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="thursday_from${count}"
                            name="thursday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="thursday_to${count}"
                            name="thursday_to${count}" title="to" style="width:115px;">
                        <span class="error-message text-danger" id="error_thursday_from${count}"></span>
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="friday_from${count}"
                            name="friday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="friday_to${count}"
                            name="friday_to${count}" title="to" style="width:115px;">
                        <span class="error-message text-danger" id="error_friday_from${count}"></span>
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="saturday_from${count}"
                            name="saturday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="saturday_to${count}"
                            name="saturday_to${count}" title="to" style="width:115px;">
                        <span class="error-message text-danger" id="error_saturday_from${count}"></span>
                    </td>
                    <td>
                        <button type="button" class="btnDelete waves-effect waves-light btn btn-danger btn-sm"
                            title="delete row"> <i class="fa fa-trash"></i></button>
                    </td>
                </tr>`;

                $('#tablebody').append(newRow);
                let $newSelect = $(`#clinic_branch_id${count}`);
                populateOptions($newSelect);
                // Reinitialize Select2 on the newly added select element
                $(`#clinic_branch_id${count}`).select2({
                    width: '100%',
                    placeholder: 'Select a Branch'
                });
                updateRowCount();
                updateSelectedBranchIds();
            });

            $(document).on('change', 'select.clinic_branch_select', function() {
                updateSelectedBranchIds();
            });
            // Function to update row indices and input names
            function updateRowIndices() {
                $('#tablebody tr').each(function(index) {
                    $(this).find('td:first-child').text(index + 1); // Update row number

                    // Update input names and IDs
                    $(this).find('select, input').each(function() {
                        let name = $(this).attr('name');
                        let id = $(this).attr('id');
                        
                        if (name && id) {
                            let newName = name.replace(/\d+/, index + 1);
                            let newId = id.replace(/\d+/, index + 1);
                            
                            $(this).attr('name', newName);
                            $(this).attr('id', newId);
                        }
                    });
                });
            }
            // Event listener for Delete button click
            $(document).on('click', '.btnDelete', function() {
                $(this).closest('tr').remove();
                count--;
                updateRowIndices();
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
                let fromTimeValue = fromField.val();
                let toTimeValue = toField.val();

                if (fromTimeValue && toTimeValue) {
                    let fromTime = new Date(`1970-01-01T${fromTimeValue}:00`);
                    let toTime = new Date(`1970-01-01T${toTimeValue}:00`);

                    if (fromTime > toTime) {
                        fromField.addClass('is-invalid');
                        toField.addClass('is-invalid');
                        fromField.siblings('.error-message').text(
                            'From Time cannot be greater than To Time.');
                        toField.siblings('.error-message').text(
                            'From Time cannot be greater than To Time.');
                    } else {
                        fromField.removeClass('is-invalid');
                        toField.removeClass('is-invalid');
                        fromField.siblings('.error-message').text('');
                        toField.siblings('.error-message').text('');
                    }
                }

            });

            // Handle change event for role dropdown
            $('select[name="role[]"]').change(function() {
                if ($(this).val() && $(this).val().includes('3')) {
                    $('.doctorFields').show();
                    $('.otherFields').hide();
                    $('.nurseFields').hide();
                    $('.doctorFields input').attr('required', true);
                    $('.otherFields select').attr('required', false);
                    $('.nurseFields input').attr('required', false);
                } else if ($(this).val() && $(this).val().includes('4')) {
                    $('.doctorFields').hide();
                    $('.otherFields').show();
                    $('.nurseFields').show();
                    $('.doctorFields input').attr('required', false);
                    $('.nurseFields input').attr('required', true);
                    $('.otherFields select').attr('required', true);
                } else {
                    $('.doctorFields').hide();
                    $('.otherFields').show();
                    $('.nurseFields').hide();
                    $('.doctorFields input').attr('required', false);
                    $('.otherFields select').attr('required', true);
                    $('.nurseFields input').attr('required', false);
                }
            });

            // Function to load states based on country ID
            function loadStates(countryId, stateSelectElement) {
                if (countryId) {
                    $.ajax({
                        url: '{{ route('get.states', '') }}' + '/' + countryId,
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
                        url: '{{ route('get.cities', '') }}' + '/' + stateId,
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

            $('#date_of_birth').on('change', function() {
                const dobValue = $(this).val();
                if (!dobValue) {
                    $('#dobError').text('Date of birth is required.');
                    $(this).addClass('is-invalid');
                    return;
                }

                const dob = new Date(dobValue);
                const today = new Date();

                // Ensure the date is not in the future
                if (dob > today) {
                    $('#dobError').text('Date of birth cannot be in the future.');
                    $(this).addClass('is-invalid');
                    $(this).val(''); // Clear the input
                    return;
                }

                // Ensure the user is at least 18 years old
                const age = today.getFullYear() - dob.getFullYear();
                const monthDifference = today.getMonth() - dob.getMonth();
                const dayDifference = today.getDate() - dob.getDate();

                if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
                    age--;
                }

                if (age < 18) {
                    $('#dobError').text('You must be at least 18 years old.');
                    $(this).addClass('is-invalid');
                    $(this).val(''); // Clear the input
                } else {
                    // Clear error message if valid
                    $('#dobError').text('');
                    $(this).removeClass('is-invalid');
                }
            });
        });
    </script>

@endsection
