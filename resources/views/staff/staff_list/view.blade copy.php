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
                    <h3 class="page-title">Staff Details</h3>
                    <div>
                        <a href="' . route('staff.staff_list.edit', $row->id) . '"
                            class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1"
                            title="edit"><i class="fa fa-pencil"></i></a>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-xs"
                            data-bs-toggle="modal" data-bs-target="#modal-status" data-id="' . $row->id . '"
                            title="change status"><i class="fa-solid fa-sliders"></i></button>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body text-end min-h-150 bg-primary-light bg-secondary bg-secondary bg-temple-white"
                        style="
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
    /* background-color: #32240c; */
">
                        <div class="bg-danger rounded10 p-10 fs-18 d-inline"><i class="fa fa-stethoscope"></i> Doctor</div>
                    </div>
                    <div class="box-body wed-up position-relative">
                        <div class="d-md-flex align-items-end ">
                            <img src="http://127.0.0.1:8000/images/avatar/avatar-1.png"
                                class="bg-success-light rounded10 me-20" alt="">
                            <div>
                                <h4>Dr. Johen doe</h4>
                                <p><i class="fa fa-clock-o"></i> Join on 15 May 2019, 10:00 AM</p>
                            </div>
                        </div>
                    </div>


                    <div class="box-body">
                        <h4>Biography</h4>
                        <p>Vestibulum tincidunt sit amet sapien et eleifend. Fusce pretium libero enim, nec lacinia est
                            ultrices id. Duis nibh sapien, ultrices in hendrerit ac, pulvinar ut mauris. Quisque eu
                            condimentum justo. In consectetur dapibus justo, et dapibus augue pellentesque sed. Etiam
                            pulvinar pharetra est, at euismod augue vulputate sed. Morbi id porta turpis, a porta turpis.
                            Suspendisse maximus finibus est at pellentesque. Integer ut sapien urna.</p>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae
                            dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut
                            fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque
                            porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia
                            non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.
                        </p>
                    </div>



                    <div class="box-body">
                        <div class="myadmin-dd dd" id="nestable">
                            <ol class="dd-list">
                                <li class="dd-item" data-id="1">
                                    <div class="dd-handle"> Item 1 </div>
                                </li>
                                <li class="dd-item" data-id="2">
                                    <div class="dd-handle"> Item 2 </div>
                                    <div class="dd-list box-body">
                                        <h4>Biography</h4>
                                        <p>Vestibulum tincidunt sit amet sapien et eleifend. Fusce pretium libero enim, nec
                                            lacinia est
                                            ultrices id. Duis nibh sapien, ultrices in hendrerit ac, pulvinar ut mauris.
                                            Quisque eu
                                            condimentum justo. In consectetur dapibus justo, et dapibus augue pellentesque
                                            sed. Etiam
                                            pulvinar pharetra est, at euismod augue vulputate sed. Morbi id porta turpis, a
                                            porta turpis.
                                            Suspendisse maximus finibus est at pellentesque. Integer ut sapien urna.</p>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                                            doloremque laudantium,
                                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                            architecto beatae vitae
                                            dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur
                                            aut odit aut
                                            fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi
                                            nesciunt. Neque
                                            porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                            velit, sed quia
                                            non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam
                                            quaerat voluptatem.
                                        </p>
                                    </div>
                                    {{-- <ol class="dd-list">
                                        <li class="dd-item" data-id="3">
                                            <div class="dd-handle"> Item 3 </div>
                                        </li>



                                    </ol> --}}
                                </li>
                            </ol>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-12">
                    <div class="box box-solid bg-primary">
                        <div class="box-header d-flex align-items-center">
                            {{-- <h4 class="box-title"><strong>Primary</strong></h4> --}}
                            <img src="{{ asset('images/avatar/avatar-1.png') }}" alt=""
                                class="bg-success-light rounded10 me-20 align-self-end h-100">
                            <div class="d-flex flex-column flex-grow-1">
                                <a href="#" class="box-title text-muted fw-600 fs-18 mb-2 hover-primary">Top Agent</a>
                                <span class="fw-500 text-fade">Most Awards Earned</span>
                            </div>
                            <div class="bg-success rounded10 p-15 fs-18 d-inline min-h-50"><i class="fa fa-stethoscope"></i>
                                ENT
                                Specialist</div>
                        </div>

                        <div class="box-body">
                            <p>Which is the same as saying through shrinking from toil and pain.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 col-12">
                    <div class="box">
                        <div class="box-body d-flex align-items-center">
                            <img src="{{ asset('images/avatar/avatar-1.png') }}" alt=""
                                class="bg-success-light rounded10 me-20 align-self-end h-100">
                            <div class="d-flex flex-column flex-grow-1">
                                <a href="#" class="box-title text-muted fw-600 fs-18 mb-2 hover-primary">Top Agent</a>
                                <span class="fw-500 text-fade">Most Awards Earned</span>
                            </div>
                            {{-- <img src="{{ asset('images/avatar/avatar-1.png') }}" alt=""
                                class="align-self-end h-100"> --}}
                            {{-- <div class="box-body text-end min-h-150"
                                style="background-image:url({{ asset('images/gallery/landscape14.jpg') }}); background-repeat: no-repeat; background-position: center;background-size: cover;"> --}}
                            <div class="bg-success rounded10 p-15 fs-18 d-inline min-h-50"><i class="fa fa-stethoscope"></i>
                                ENT
                                Specialist</div>
                            {{-- </div> --}}
                        </div>
                        <div class="box-body">
                            <h4>Biography</h4>
                            <p>Vestibulum tincidunt sit amet sapien et eleifend. Fusce pretium libero enim, nec lacinia est
                                ultrices id. Duis nibh sapien, ultrices in hendrerit ac, pulvinar ut mauris. Quisque eu
                                condimentum justo. In consectetur dapibus justo, et dapibus augue pellentesque sed. Etiam
                                pulvinar pharetra est, at euismod augue vulputate sed. Morbi id porta turpis, a porta
                                turpis.
                                Suspendisse maximus finibus est at pellentesque. Integer ut sapien urna.</p>
                            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                                laudantium,
                                totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae
                                vitae
                                dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut
                                fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque
                                porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed
                                quia
                                non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat
                                voluptatem.
                            </p>
                        </div>
                    </div>
                </div>


                {{-- <div class="col-xl-8 col-12"> --}}
                <div class="box">
                    <div class="box-body text-end min-h-150"
                        style="background-image:url({{ asset('images/gallery/landscape14.jpg') }}); background-repeat: no-repeat; background-position: center;background-size: cover;">
                        <div class="bg-success rounded10 p-15 fs-18 d-inline"><i class="fa fa-stethoscope"></i> ENT
                            Specialist</div>
                    </div>
                    <div class="box-body wed-up position-relative">
                        <div class="d-md-flex align-items-end">
                            <img src="{{ asset('images/avatar/avatar-1.png') }}" class="bg-success-light rounded10 me-20"
                                alt="" />
                            <div>
                                <h4>Dr. Johen doe</h4>
                                <p><i class="fa fa-clock-o"></i> Join on 15 May 2019, 10:00 AM</p>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6 col-12">
                        <div class="media bg-white mb-20">
                            <img class="avatar" src="{{ asset('images/avatar/avatar-1.png') }}" alt="...">
                            <div class="media-body">
                                <p><strong>Johone Doe</strong></p>
                                <p>HR</p>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-md-12 col-12 min-h-150">
                        <div class="media align-items-center bg-primary mb-20">
                            <img class="avatar" src="{{ asset('images/avatar/avatar-3.png') }}" alt="...">
                            <div class="media-body">
                                <p><strong>Johone Doe</strong></p>
                                <p class="text-white">@Johone Doe</p>
                            </div>
                            <div>
                                <a class="btn btn-outline btn-white btn-sm btn-rounded" href="#">Follow</a>
                            </div>
                        </div>
                    </div> --}}
                    <div class="box-body">
                        <h4>Biography</h4>
                        <p>Vestibulum tincidunt sit amet sapien et eleifend. Fusce pretium libero enim, nec lacinia est
                            ultrices id. Duis nibh sapien, ultrices in hendrerit ac, pulvinar ut mauris. Quisque eu
                            condimentum justo. In consectetur dapibus justo, et dapibus augue pellentesque sed. Etiam
                            pulvinar pharetra est, at euismod augue vulputate sed. Morbi id porta turpis, a porta turpis.
                            Suspendisse maximus finibus est at pellentesque. Integer ut sapien urna.</p>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae
                            dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut
                            fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque
                            porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia
                            non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.
                        </p>
                    </div>
                </div>
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Assigned Patient</h4>
                    </div>
                    <div class="box-body">
                        <div class="media d-lg-flex d-block text-lg-start text-center">
                            <img class="me-3 img-fluid rounded bg-primary-light w-100" src="../images/avatar/1.jpg"
                                alt="">
                            <div class="media-body my-10 my-lg-0">
                                <h4 class="mt-0 mb-2">Loky Doe</h4>
                                <h6 class="mb-4 text-primary">Cold &amp; Flue</h6>
                                <div class="d-flex justify-content-center justify-content-lg-start">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-4">Unassign</a>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger-light ">Imporvement</a>
                                </div>
                            </div>
                            <div id="chart" class="me-3"></div>
                            <div class="media-footer align-self-center">
                                <div class="up-sign text-success">
                                    <i class="fa fa-caret-up fs-38"></i>
                                    <h3 class="text-success">10%</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#staffform .actions ul li:last-child a").addClass("bg-success btn btn-success");
            let count = '{{ $availabilityCount }}';

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
            function loadStates(countryId, stateSelectElement, initialSelected) {
                if (countryId) {
                    $.ajax({
                        url: '{{ route('get.states', '') }}' + '/' + countryId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            stateSelectElement.empty();
                            stateSelectElement.append('<option value="">Select State</option>');
                            $.each(data, function(key, value) {
                                var selected = null;
                                if (key == initialSelected) {

                                    selected = "selected";

                                }
                                var state = '{{ $staffProfile->com_state_id }}';
                                stateSelectElement.append('<option value="' + key + '" ' +
                                    selected + '>' +
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
            function loadCities(stateId, citySelectElement, initialSelected) {
                if (stateId) {
                    $.ajax({
                        url: '{{ route('get.cities', '') }}' + '/' + stateId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            citySelectElement.empty();
                            citySelectElement.append('<option value="">Select City</option>');
                            $.each(data, function(key, value) {
                                var selected = null;
                                if (key == initialSelected) {

                                    selected = "selected";

                                }
                                citySelectElement.append('<option value="' + key + '" ' +
                                    selected + '>' +
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
            var initialSelectedStateId = '{{ $staffProfile->state_id }}';
            var initialSelectedComStateId = '{{ $staffProfile->com_state_id }}';
            var initialSelectedCityId = '{{ $staffProfile->city_id }}';
            var initialSelectedComCityId = '{{ $staffProfile->com_city_id }}';

            var initialCountryId = $('#country_id').val(); // Assuming India is selected initially
            loadStates(initialCountryId, $('#state_id'), initialSelectedStateId);

            // Handle change event for country dropdown
            $('#country_id').change(function() {
                var countryId = $(this).val();
                loadStates(countryId, $('#state_id'), null);
            });

            // Handle change event for state dropdown
            $('#state_id').change(function() {
                var stateId = $(this).val();
                loadCities(stateId, $('#city_id'), initialSelectedCityId);
            });

            // Same logic for communication address
            var com_initialCountryId = $('#com_country_id').val(); // Assuming India is selected initially
            loadStates(com_initialCountryId, $('#com_state_id'), initialSelectedComStateId);

            $('#com_country_id').change(function() {
                var countryId = $(this).val();
                loadStates(countryId, $('#com_state_id'), null);
            });

            $('#com_state_id').change(function() {
                var stateId = $(this).val();
                loadCities(stateId, $('#com_city_id'), initialSelectedComCityId);
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

        document.addEventListener("DOMContentLoaded", function() {
            var canvas = document.getElementById('profilePic');
            var ctx = canvas.getContext('2d');
            if ('{{ $staffProfile }}') {

                var profileUrl = '{{ $staffProfile->photo ?? '' }}';
                var photoUrl = '{{ asset('storage/') }}/' + profileUrl;
                if (profileUrl) {
                    var img = new Image();
                    img.onload = function() {
                        canvas.width = img.width;
                        canvas.height = img.height;
                        ctx.drawImage(img, 0, 0);
                    };
                    img.src = photoUrl;
                }
            }
        });
    </script>

@endsection
