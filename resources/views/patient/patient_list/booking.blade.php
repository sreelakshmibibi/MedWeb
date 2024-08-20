<form id="bookingForm" method="post" action="{{ route('patient.patient_list.booking') }}">
    @csrf
    <input type="hidden" id="reschedule_app_id" name="reschedule_app_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-booking" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-calendar-days"></i> Booking Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <input class="form-control" type="hidden" id="app_parent_id" name="app_parent_id">

                                <div class="form-group">
                                    <label class="form-label" for="patient_id">Patient ID</label><span
                                        class="text-danger">
                                        *</span>
                                    <input class="form-control" type="text" id="patient_id" name="patient_id"
                                        placeholder="Patient ID" readonly>
                                    <div id="patientIdError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="patient_name">Patient Name</label><span
                                        class="text-danger">
                                        *</span>
                                    <input class="form-control" type="text" id="patient_name" name="patient_name"
                                        placeholder="Patient name" readonly>
                                    <div id="patientNameError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="clinic_branch_id">Branch</label><span
                                        class="text-danger">
                                        *</span>
                                    <select class="form-select" id="clinic_branch_id" name="clinic_branch_id" required
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
                                    <div id="clinicError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="appdate">Booking Date & Time</label><span
                                        class="text-danger">*</span>
                                    <input class="form-control" type="datetime-local" id="appdate" name="appdate"
                                        required>
                                    <div id="appDateError" class="invalid-feedback"></div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="doctor_id">Doctor</label><span class="text-danger">
                                        *</span>
                                    <select class="form-select" id="doctor_id" name="doctor_id" required
                                        data-placeholder="Select a Doctor" style="width: 100%;">
                                        <option value="">Select a doctor</option>
                                        @foreach ($workingDoctors as $doctor)
                                            <?php $doctorName = str_replace('<br>', ' ', $doctor->user->name); ?>
                                            <option value="{{ $doctor->user_id }}">{{ $doctorName }}</option>
                                        @endforeach
                                    </select>
                                    <div id="doctorError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="rdoctor">Referrerd Doctor</label>
                                    <input type="text" class="form-control" id="rdoctor" name="rdoctor"
                                        placeholder="Enter doctor name">
                                    <div id="rdoctorError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div style="display:none" id="doctorNotAvailable">
                                <span class="text-danger">Sorry, the doctor is not available at the selected time.
                                    Please choose another time.</span>
                            </div>
                        </div>
                        <div class="row">
                            <div style="display:none" id="alreadyExistsPatient">
                                <span class="text-danger">Already exists appointment for the selected date!</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div style="display:none" id="existingAppointmentsError" class="text-danger">
                                <span class="text-danger">Appointments already exists for the selected time!</span>
                            </div>
                        </div>

                        <div class="row" style="display:none" id="existAppContainer">
                            <hr />
                            <div class="mb-3" style="display:none" id="existingAppointments">
                            </div>

                        </div>

                        <div class="row">
                            <hr />
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="bp"
                                        class="col-md-4 col-form-label py-0 align-content-center">B.P.</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="bp" name="bp"
                                            placeholder="Blood Pressure">
                                    </div>
                                    <div id="bpError" class="invalid-feedback"></div>
                                </div>

                                <div class="form-group row">
                                    <label for="height"
                                        class="col-md-4 col-form-label py-0 align-content-center">Height</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="height" name="height"
                                            placeholder="Enter Height">
                                    </div>
                                    <div id="heightError" class="invalid-feedback"></div>
                                </div>

                                <div class="form-group row">
                                    <label for="weight"
                                        class="col-md-4 col-form-label py-0 align-content-center">Weight</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="weight" name="weight"
                                            placeholder="Enter Weight">
                                    </div>
                                    <div id="weightError" class="invalid-feedback"></div>
                                </div>

                                <div class="form-group row">
                                    <label for="temperature"
                                        class="col-md-4 col-form-label py-0 align-content-center">Temperature</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="temperature"
                                            name="temperature" placeholder="Temperature in °F">
                                    </div>
                                    <div id="temperatureError" class="invalid-feedback"></div>
                                </div>

                                <div class="form-group row">
                                    <label for="allergies"
                                        class="col-md-4 col-form-label py-0 align-content-center">Allergies</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" id="allergies" name="allergies" placeholder="List any allergies" rows="1"></textarea>
                                    </div>
                                    <div id="allergiesError" class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="input-group mb-0">
                                        <select class="form-select" id="smoking_status" name="smoking_status">
                                            <option value="">Select Smoking Status</option>
                                            <option value="Non-smoker">Non-smoker</option>
                                            <option value="Former smoker">Former smoker</option>
                                            <option value="Current smoker">Current smoker</option>
                                        </select>
                                    </div>
                                    <div id="smokingStatusError" class="invalid-feedback"></div>
                                </div>

                                <div class="form-group row">
                                    <div class="input-group mb-0">
                                        <select class="form-select" id="alcoholic_status" name="alcoholic_status">
                                            <option value="">Select Alcoholic Status</option>
                                            <option value="Non-drinker">Non-drinker</option>
                                            <option value="Former drinker">Former drinker</option>
                                            <option value="Current drinker">Current drinker</option>
                                        </select>
                                    </div>
                                    <div id="alcoholicStatusError" class="invalid-feedback"></div>
                                </div>

                                <div class="form-group row">
                                    <div class="input-group mb-0">
                                        <select class="form-select" id="diet" name="diet">
                                            <option value="">Select Diet</option>
                                            <option value="Vegetarian">Vegetarian</option>
                                            <option value="Non-Vegetarian">Non-Vegetarian</option>
                                            <option value="Vegan">Vegan</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div id="dietError" class="invalid-feedback"></div>
                                </div>

                                <div class="form-group row">
                                    <div class="input-group mb-0">
                                        <select class="form-select" id="pregnant_status" name="pregnant">
                                            <option value="">Are you pregnant?</option>
                                            <option value="Y">Yes</option>
                                            <option value="N">No</option>
                                        </select>
                                    </div>
                                    <div id="pregnantError" class="invalid-feedback"></div>
                                </div>

                                <div class="form-group row" id="medical-conditions-wrapper">
                                    <div class="input-group mb-0">
                                        <input type="text" class="form-control" name="medical_conditions[]"
                                            placeholder="Medical Condition">
                                        <button class="btn-sm btn-success" type="button"
                                            onclick="addPatientMedicalCondition()">+</button>
                                    </div>
                                    {{-- <div id="pregnantError" class="invalid-feedback"></div> --}}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="newAppointmentBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        var now = new Date().toISOString().slice(0, 16);
        document.getElementById('appdate').setAttribute('min', now);

        $('#newAppointmentBtn').click(function() {
            // Reset previous error messages
            $('.invalid-feedback').text('');
            $('.form-control').removeClass('is-invalid');

            // Validate form inputs
            var clinicId = $('#clinic_branch_id').val();
            var doctorId = $('#doctor_id').val();
            var appDate = $('#appdate').val();
            var patientId = $('#patient_id').val(); // Corrected from patientId to patient_id
            var patientName = $('#patient_name').val();
            var bp = $('#bp').val();
            var height = $('#height').val();
            var weight = $('#weight').val();
            var rdoctor = $('#rdoctor').val();

            // Basic client-side validation
            if (!clinicId) {
                $('#clinic_branch_id').addClass('is-invalid');
                $('#clinicError').text('Clinic is required.');
                return;
            }

            if (!doctorId) {
                $('#doctor_id').addClass('is-invalid');
                $('#doctorError').text('Doctor is required.');
                return;
            }

            if (!appDate) {
                $('#appdate').addClass('is-invalid');
                $('#appDateError').text('Appointment date is required.');
                return;
            }

            // If validation passed, submit the form via AJAX
            var form = $('#bookingForm');
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('#alreadyExistsPatient').hide();
                    $('#existingAppointments').hide();
                    $('#existingAppointmentsError').hide();
                    $('#modal-booking').modal('hide');
                    $('#successMessage').text('New Appointment added successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                    location.reload(); // Reload the page
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        if (errors.clinic_branch_id) {
                            $('#clinic_branch_id').addClass('is-invalid');
                            $('#clinicError').text(errors.clinic_branch_id[0]);
                        }
                        if (errors.doctor_id) {
                            $('#doctor_id').addClass('is-invalid');
                            $('#doctorError').text(errors.doctor_id[0]);
                        }
                        if (errors.appdate) {
                            $('#appdate').addClass('is-invalid');
                            $('#appDateError').text(errors.appdate[0]);
                        }
                        if (errors.bp) {
                            $('#bp').addClass('is-invalid');
                            $('#bpError').text(errors.bp[0]);
                        }
                        if (errors.height) {
                            $('#height').addClass('is-invalid');
                            $('#heightError').text(errors.height[0]);
                        }
                        if (errors.weight) {
                            $('#weight').addClass('is-invalid');
                            $('#weightError').text(errors.weight[0]);
                        }
                        if (errors.rdoctor) {
                            $('#rdoctor').addClass('is-invalid');
                            $('#rdoctorError').text(errors.rdoctor[0]);
                        }
                    } else {
                        var errorPatient = xhr.responseJSON.errorPatient;
                        if (errorPatient) {
                            $('#alreadyExistsPatient').show();
                        }
                        var errorMessage = xhr.responseJSON.error;
                        if (errorMessage) {
                            $('#existingAppointmentsError').show();
                        }
                    }
                }
            });
        });


        // Reset form and errors on modal close
        $('#modal-booking').on('hidden.bs.modal', function() {
            $('#bookingForm').trigger('reset');
            $('#existAppContainer').hide();
            $('#existingAppointments').empty();
            $('#existingAppointments').hide();
            $('#alreadyExistsPatient').hide();
            $('#doctorNotAvailable').hide();
            $('#clinic_branch_id').removeClass('is-invalid');
            $('#clinicError').text('');
            $('#doctor_id').removeClass('is-invalid');
            $('#doctorError').text('');
            $('#appdate').removeClass('is-invalid');
            $('#appDateError').text('');
            $('#bp').removeClass('is-invalid');
            $('#bpError').text('');
            $('#height').removeClass('is-invalid');
            $('#heightError').text('');
            $('#weight').removeClass('is-invalid');
            $('#weightError').text('');
            $('#rdoctor').removeClass('is-invalid');
            $('#rdoctorError').text('');
            $('#medical-conditions-wrapper').empty();
            // Add back the initial input field for medical conditions
            var initialMedicalConditionInput = `
        <div class="input-group mb-0">
            <input type="text" class="form-control" name="medical_conditions[]" placeholder="Medical Condition">
            <button class="btn-sm btn-success" type="button" onclick="addPatientMedicalCondition()">+</button>
        </div>`;
            $('#medical-conditions-wrapper').append(initialMedicalConditionInput);

        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var now = new Date();
        var year = now.getFullYear();
        var month = (now.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
        var day = now.getDate().toString().padStart(2, '0');
        var hours = now.getHours().toString().padStart(2, '0');
        var minutes = now.getMinutes().toString().padStart(2, '0');
        var datetime = `${year}-${month}-${day}T${hours}:${minutes}`;

        document.getElementById('appdate').value = datetime;
    });

    $('#clinic_branch_id, #appdate').change(function() {
        var branchId = $('#clinic_branch_id').val();
        var appDate = $('#appdate').val();
        $('#existAppContainer').hide();
        $('#existingAppointments').empty();
        $('#existingAppointments').hide();
        $('#alreadyExistsPatient').hide();
        $('#doctorNotAvailable').hide();
        loadDoctors(branchId, appDate);


    });

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

                    $('#doctor_id').empty();
                    $('#doctor_id').append('<option value="">Select a doctor</option>');
                    $.each(data, function(key, value) {
                        var doctorName = value.user.name.replace(/<br>/g, ' ');
                        $('#doctor_id').append('<option value="' + value.user_id + '">' +
                            doctorName + '</option>');
                    });

                }
            });
        } else {
            $('#doctor_id').empty();
        }
    }

    $('#clinic_branch_id, #appdate, #doctor_id').change(function() {
        var branchId = $('#clinic_branch_id').val();
        var appDate = $('#appdate').val();
        var doctorId = $('#doctor_id').val();
        $('#alreadyExistsPatient').hide();
        $('#existingAppointmentsError').hide();
        $('#existAppContainer').hide();
        $('#existingAppointments').empty();
        $('#doctorNotAvailable').hide();
        showExistingAppointments(branchId, appDate, doctorId, 'store');

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

    function showExistingAppointments(branchId, appDate, doctorId, methodType) {
        if (branchId && appDate && doctorId) {

            $.ajax({
                url: '{{ route('get.exisitingAppointments', '') }}' + '/' + branchId,
                type: "GET",
                data: {
                    appdate: appDate,
                    doctorId: doctorId
                },
                dataType: "json",
                success: function(data) {
                    $('#existingAppointmentsError').hide();
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
                    if (data.existingAppointments.length > 0) {

                        // Clear existing content
                        $('#existAppContainer').hide();
                        $('#existingAppointments').empty();
                        $('#rescheduleExistingAppointments').empty();
                        // var heading = $('<h4>').text('Scheduled Appointments');
                        // alert(heading)
                        // Create a table element
                        var table = $('<table class="table table-striped mb-0">').addClass(
                            'appointment-table').css({
                            'border-collapse': 'separate',
                            'border-spacing': '0.5rem'
                        });
                        //caption
                        // table.append($('<caption>').text('Scheduled Appointments'));
                        // Create header row
                        // var headerRow = $('<tr>');
                        // headerRow.append($('<th>').text('Scheduled Appointments'));
                        // table.append(headerRow);


                        // Calculate number of rows needed
                        var numRows = Math.ceil(data.existingAppointments.length / 5);

                        // Loop to create rows and populate cells
                        for (var i = 0; i < numRows; i++) {
                            var row = $('<tr>');

                            // Create 5 cells for each row
                            for (var j = 0; j < 5; j++) {
                                var dataIndex = i * 5 + j;
                                if (dataIndex < data.existingAppointments.length) {
                                    var app_time = data
                                        .existingAppointments[
                                            dataIndex]
                                        .app_time;
                                    var formattedTime = convertTo12HourFormat(app_time);
                                    var cell = $('<td class="b-1 w-100 text-center">').text(formattedTime);
                                    row.append(cell);
                                } else {
                                    var cell = $('<td>'); // Create empty cell if no more data
                                    row.append(cell);
                                }
                            }

                            table.append(row);
                        }
                        if (methodType == 'store') {
                            $('#existingAppointments').append($('<h6 class="text-warning mb-1">').text(
                                'Scheduled Appointments'));
                            // Append table to existingAppointments div
                            $('#existingAppointments').append(table);
                            $('#existAppContainer').show();
                            // Show the div
                            $('#existingAppointments').show();
                        } else if (methodType == 'edit') {
                            $('#rescheduleExistingAppointments').append($('<h6 class="text-warning mb-1">')
                                .text(
                                    'Scheduled Appointments'));
                            // Append table to existingAppointments div
                            $('#rescheduleExistingAppointments').append(table);

                            // Show the div
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
            console.log('hi no exisiting');

        }
    }


    function addPatientMedicalCondition() {
        const wrapper = document.getElementById('medical-conditions-wrapper');
        const div = document.createElement('div');
        div.className = 'input-group mb-0';
        div.innerHTML = `
                <input type="text" class="form-control" name="medical_conditions[]" placeholder="Medical Condition">
                <button class="btn-sm btn-danger" type="button" onclick="removePatientMedicalCondition(this)">-</button>
            `;
        wrapper.appendChild(div);
    }

    function removePatientMedicalCondition(button) {
        const div = button.parentElement;
        div.remove();
    }
</script>
