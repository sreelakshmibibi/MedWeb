@extends('layouts.dashboard')
@section('title', 'Patient')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Treatment</h3>
                    <a type="button" class="waves-effect waves-light btn btn-primary" href="{{ route('appointment') }}">
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
                    <div class="box-body wizard-content">
                        <form method="post" class="validation-wizard wizard-circle" id="treatmentform"
                            action="{{ route('patient.patient_list.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Step 1 -->
                            <h6 class="tabHeading">Personal Info</h6>
                            <section class="tabSection">
                                @include('appointment.personal_info')
                            </section>

                            <h6 class="tabHeading">Appointment History</h6>
                            <section class="tabSection">
                                @include('appointment.history')
                            </section>

                            <h6 class="tabHeading">Dental Chart</h6>
                            <section class="tabSection">
                                @include('appointment.dchart_images')
                            </section>

                            {{-- <h6 class="tabHeading">Examination</h6>
                            <section class="tabSection">
                                @include('appointment.examination')
                            </section> --}}

                            <h6 class="tabHeading">Dental Table</h6>
                            <section class="tabSection">
                                @include('appointment.dtable')
                            </section>

                            <h6 class="tabHeading">Prescription</h6>
                            <section class="tabSection">
                                @include('appointment.prescription')
                            </section>

                            <h6 class="tabHeading">Charge</h6>
                            <section class="tabSection">
                                @include('appointment.charge')
                            </section>

                            {{-- <h6 class="tabHeading">Chart</h6>
                            <section class="tabSection">
                                @include('appointment.dchart')
                            </section> --}}


                            <div id="updateRoute" data-url="{{ route('patient.patient_list.update') }}"
                                data-patientlist-route="{{ route('patient.patient_list') }}"></div>
                            <input type="hidden" name="edit_app_id" id="edit_app_id" value="{{ $appointment->id }}">
                            <input type="hidden" name="edit_patient_id" id="edit_patient_id"
                                value="{{ $patientProfile->id }}">
                        </form>
                    </div>

                    <!-- /.box-body -->
                </div>
            </section>
        </div>
    </div>

    @include('appointment.teeth')

    <script>
        $(document).ready(function() {

            $("#treatmentform .actions ul li:last-child a").addClass("bg-success btn btn-success");

            // Handle change event for dparts
            $('.dparts').click(function() {
                var partName = this.id;
                var partId = '#' + partName;
                var title = $(this).attr('title');
                var divId = '#' + title;

                if ($(partId).hasClass('red')) {
                    $(partId).css({
                        'background-color': 'white',
                    });
                    $(partId).removeClass('red');
                    $(divId).hide();
                } else {
                    $(partId).css({
                        'background-color': 'red',
                    });
                    $(partId).addClass('red');
                    $(divId).show();
                }
                $(partId).toggleClass('selected');
            });

            // $('#tooth_selected').change(function() {
            //     var selectedValue = $(this).val();

            //     // Hide all tooth divs
            //     $('.exam_toothdiv').hide();
            //     $('#incisors_canines').hide();
            //     $('#premolars_molars').hide();

            //     if (selectedValue === 'tooth_in') {
            //         $('.exam_toothdiv').show();
            //         $('#incisors_canines').show();

            //     } else if (selectedValue === 'tooth_mol') {
            //         $('.exam_toothdiv').show();
            //         $('#premolars_molars').show();

            //     }
            // });

            $('#table_info_btn').click(function() {
                if ($('#table_infodiv').css('display') == 'block') {
                    $('#table_infodiv').hide();
                } else {
                    $('#table_infodiv').show();
                }
            });

            $('#closeToothBtn').click(function() {
                var teethName = $('#tooth_no').val();
                var divId = '#div' + teethName;
                $(divId).css({
                    'border': 'none',
                    'border-radius': '5px',
                });
            })

            $('#newToothTreatmentBtn').click(function() {
                var teethName = $('#tooth_no').val();
                var divId = '#div' + teethName;
                $(divId).css({
                    'border': 'none',
                    'border-radius': '5px',
                    // 'background-color': 'rgba(0, 0, 255, 0.1)',
                });
                // $(divId).addClass('overlay');
            });


            $("#follow_checkbox").change(function() {
                if ($(this).is(':checked')) {
                    // $('#followupdiv').hide();
                    $('#followupdiv').show();
                } else {
                    // $('#followupdiv').show();
                    $('#followupdiv').hide();
                }
            });

            $("#presc_checkbox").change(function() {
                if ($(this).is(':checked')) {
                    $('#prescdiv').show();
                } else {
                    $('#prescdiv').hide();
                }
            });

            let count = 1;
            // Event listener for Add Row button click
            $(document).on('click', '#medicineAddRow', function() {
                count++;
                let newRow = `<tr>
                    <td>${count}</td>
                    <td>
                        <select class="select2" id="medicine_id${count}" name="medicine_id${count}" required
                            data-placeholder="Select a Medicine" style="width: 100%;">
                        </select>
                    </td>
                    <td>
                        <select class="select2" id="dosage${count}" name="dosage${count}" required
                            data-placeholder="Select a Dosage" style="width: 100%;">
                            <option value="1">1-0-0</option>
                            <option value="2">0-1-0</option>
                            <option value="3">0-0-1</option>
                            <option value="4">1-1-1</option>
                            <option value="5">1-0-1</option>
                            <option value="6">1-1-0</option>
                            <option value="7">0-1-1</option>
                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" class="form-control" id="duration${count}" name="duration${count}" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">days</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control" id="remarks${count}" name="remarks${count}" placeholder="remarks">
                    </td>
                    <td>
                        <button type="button" class="btnDelete waves-effect waves-light btn btn-danger btn-sm"
                                title="delete row"> <i class="fa fa-trash"></i></button>
                    </td>
                </tr>`;

                $('#presctablebody').append(newRow);
                // Reinitialize Select2 on the newly added select element
                $(`#medicine_id${count}`).select2({
                    width: '100%',
                    placeholder: 'Select a Medicine'
                });
                $(`#dosage${count}`).select2({
                    width: '100%',
                    placeholder: 'Select a Dosage'
                });
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

            let chargecount = 1;
            // Event listener for Add Row button click
            $(document).on('click', '#chargeAddRow', function() {
                chargecount++;
                let newRow = `<tr>
                    <td>${chargecount}</td>
                    <td>
                        <select class="select2" id="treatment_id${chargecount}" name="treatment_id${chargecount}"
                            data-placeholder="Select a Treatment" style="width: 100%;">
                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" class="form-control" id="quantity${chargecount}" name="quantity${chargecount}"
                                aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Tooth</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        1000
                    </td>
                    <td>
                        <button type="button" id="btnchargeDelete" title="delete row"
                            class="waves-effect waves-light btn btn-danger btn-sm btnchargeDelete"> <i class="fa fa-trash"></i></button>
                    </td>
                </tr>`;

                $('#chargetablebody').append(newRow);
                // Reinitialize Select2 on the newly added select element
                $(`#treatment_id${chargecount}`).select2({
                    width: '100%',
                    placeholder: 'Select a Treatment'
                });

                updateRowchargeCount();
            });

            // Event listener for Delete button click
            $(document).on('click', '.btnchargeDelete', function() {
                $(this).closest('tr').remove();
                updateRowchargeCount();
            });

            // Function to update row chargecount input field value
            function updateRowchargeCount() {
                $('#row_chargecount').val(chargecount);
            }


            // Initializations
            var initialSelectedStateId = '{{ $patientProfile->state_id }}';
            var initialSelectedCityId = '{{ $patientProfile->city_id }}';

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

            // Event listener for dropdown item click
            $(".dropdown-menu .dropdown-item").click(function() {
                // Get the selected salutation text
                let salutation = $(this).text().trim();

                // Update the button text with the selected salutation
                $(".input-group .dropdown-toggle").text(salutation);
            });

        });
    </script>
@endsection