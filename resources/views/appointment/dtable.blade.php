<?php

use Illuminate\Support\Facades\Session;
?>
<div class="d-flex align-items-center justify-content-between">
    <h5 class="box-title text-info mb-0 mt-2 "><i class="fa fa-medkit me-15"></i>
        Treatment Chart
    </h5>

    <button type="button" class="waves-effect waves-light btn btn-sm btn-outline-primary" id="table_info_btn">
        <i class="fa fa-table"></i>
        Info</button>
</div>
<hr class="my-15 ">

<div class="table-responsive mb-4">
    <table id="dentalTable" class="table table-bordered table-hover table-striped mb-0 text-center">

        <thead>
            <tr class="bg-primary-light">
                <th>No</th>
                <th>Tooth No</th>
                <th>Chief Complaint</th>
                <th>Disease</th>
                <th>HPI</th>
                <th>Dental Examination</th>
                <th>Diagnosis</th>
                <th>X-Ray</th>
                <th>Treatment</th>
                {{-- <th>Buccal</th>
                <th>Palatal</th>
                <th>Mesial</th>
                <th>Distal</th>
                <th>Occulusal</th>
                <th>Lingual</th>
                <th>Labial</th> --}}
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="tablebody">

        </tbody>
    </table>
</div>

<div class="table-responsive" id="table_infodiv" style="display: none;">
    <table id="toothScoreTable"
        class="table table-sm table-bordered table-hover table-striped mb-0 text-center b-1 border-dark">

        <thead class="bg-dark">
            {{-- <tr class="bg-primary-light"> --}}
            <tr>
                <th>Tooth Score</th>
                <th>Code</th>
                <th>Tooth Score</th>
                <th>Code</th>
                <th>Tooth Surface</th>
                <th>Code</th>
            </tr>
        </thead>
        <tbody id="tablebody">
            <tr>
                <td>Sound</td>
                <td>0</td>
                <td>For Extraction- X(x)</td>
                <td>4</td>
                <td>Decayed</td>
                <td>7</td>
            </tr>
            <tr>
                <td>Decayed- D(d)</td>
                <td>1</td>
                <td>Impacted</td>
                <td>5</td>
                <td>Filled</td>
                <td>8</td>
            </tr>
            <tr>
                <td>Missing- M</td>
                <td>2</td>
                <td>Unerupted</td>
                <td>6</td>
                <td>Have Fissure Sealant (HFS)</td>
                <td>9</td>
            </tr>
            <tr>
                <td>Filled- F</td>
                <td>3</td>
                <td>Need Fissure Sealant (NFS)</td>
                <td>10</td>
            </tr>

        </tbody>

    </table>
</div>

<div class="d-flex align-items-center justify-content-between">
    <h5 class="box-title text-info mb-0 mt-2 "><i class="fa fa-clock me-15"></i>
        Follow up
    </h5>
    <input type="checkbox" id="follow_checkbox" name="follow_checkbox" class="filled-in chk-col-success" />
    <label for="follow_checkbox"></label>
</div>
<hr class="my-15 ">

<div class="row mb-4" id="followupdiv" style="display: none;">
    <div class="table-responsive">
        <table id="myTable" class="table table-bordered table-hover table-striped mb-0 text-center">

            <thead>
                <tr class="bg-primary-light">
                    <th>No</th>
                    <!-- <th>Treatment</th> -->
                    <th>Branch</th>
                    <th>Appointment Date & Time</th>
                    <th>Consulting Doctor</th>
                    <th>Appointment Type</th>
                    <th>Remarks</th>
                    <!-- <th><button type="button" class="waves-effect waves-light btn btn-sm btn-primary">
                            <i class="fa fa-add"></i>
                            Add</button></th> -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <!-- <td>
                        <select class="select2" id="treatment_id1" name="treatment_id1"
                            data-placeholder="Select a Treatment" style="width: 100%;">

                        </select>
                    </td> -->

                    <td>
                        <select class="select2" id="clinic_branch_id" name="clinic_branch_id"
                            data-placeholder="Select a Branch" style="width: 100%;">
                            @foreach ($clinicBranches as $clinicBranch)
                                <?php
                                $clinicAddress = $clinicBranch->clinic_address;
                                $clinicAddress = explode('<br>', $clinicBranch->clinic_address);
                                $clinicAddress = implode(', ', $clinicAddress);
                                $branch = $clinicAddress . ', ' . $clinicBranch->city->city . ', ' . $clinicBranch->state->state;
                                ?>
                                <option value="{{ $clinicBranch->id }}"
                                    @if ($appointment->app_branch == $clinicBranch->id) selected @endif>
                                    {{ $branch }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="form-control" type="datetime-local" id="appdate" name="appdate"
                            value="{{ now()->setTimezone('Asia/Kolkata')->format('Y-m-d\TH:i') }}">
                    </td>

                    <td>
                        <select class="select2" id="doctor_id" name="doctor_id" data-placeholder="Select a Doctor"
                            style="width: 100%;">
                            @foreach ($workingDoctors as $doctor)
                                <?php $doctorName = str_replace('<br>', ' ', $doctor->user->name); ?>
                                <option value="{{ $doctor->user_id }}"
                                    @if ($doctor->user_id == $appointment->doctor_id) selected @endif>
                                    {{ $doctorName }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <select class="form-select" id="apptype" name="apptype">
                            @foreach ($appointmentTypes as $appointmentType)
                                <option value="{{ $appointmentType->id }}">
                                    {{ $appointmentType->type }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" id="remarks_followup" name="remarks_followup"
                            placeholder="remarks">
                    </td>
                    <!-- <td>
                        <button type="button" id="btnDelete" title="delete row"
                            class="waves-effect waves-light btn btn-danger btn-sm"> <i class="fa fa-trash"></i></button>
                    </td> -->
                </tr>
            </tbody>
        </table>
        <div class="row">
            <div style="display:none" id="existingAppointmentsError">
                <span class="text-danger">Already exists appointment for the selected time!</span>
            </div>
        </div>
        <div class="row">
            <div style="display:none" id="existingAppointments">
            </div>
        </div>
    </div>
</div>

<div class="d-flex align-items-center justify-content-between">
    <h5 class="box-title text-info mb-0 mt-2 "><i class="fa-solid fa-prescription me-15"></i>
        Prescription
    </h5>
    <input type="checkbox" id="presc_checkbox" name="presc_checkbox" class="filled-in chk-col-success" />
    <label for="presc_checkbox"></label>
</div>
<hr class="my-15 ">
<script>
    $(document).on('click', '.btn-treat-view', function() {
        var teethName = $(this).data('id');
        var appId = '<?= Session::get('appId') ?>';
        var patientId = '<?= Session::get('patientId') ?>';
        // console.log('Hover in T' + teethName);
        var divId = '#div' + teethName;
        $(divId).css({
            'border': '2px solid blue',
            'border-radius': '5px',
        });

        // $(this).toggleClass('selected');
        $('#tooth_id').val(teethName);
        $('#app_id').val(appId);
        $('#patient_id').val(patientId);
        if ($(divId).hasClass('molar')) {
            $('#premolars_molars').show();
            $('#incisors_canines').hide();
        } else if ($(divId).hasClass('inccan')) {
            $('#incisors_canines').show();
            $('#premolars_molars').hide();
        }
        $.ajax({
            url: '{{ route('get.toothExamination', ['toothId' => ':toothId', 'appId' => ':appId', 'patientId' => ':patientId']) }}'
                .replace(':toothId', teethName)
                .replace(':appId', appId)
                .replace(':patientId', patientId),
            type: "GET",
            dataType: "json",

            success: function(response) {
                var examination = response
                    .examination; // Assuming there's only one item in the array

                // Set the value of tooth_score_id field
                var toothScoreId = examination.tooth_score_id;
                $('#tooth_score_id').val(toothScoreId);

                // Loop through options to find the corresponding text and select it
                $('#tooth_score_id option').each(function() {
                    if ($(this).val() == toothScoreId) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });
                $('#tooth_score_id').trigger('change');
                $('#chief_complaint').val(examination.chief_complaint);
                $('#hpi').val(examination.hpi);
                $('#diagnosis').val(examination.diagnosis);
                $('#dental_examination').val(examination
                    .dental_examination);
                $('#remarks').val(examination.remarks);
                var disease_id = examination.disease_id;
                $('#disease_id').val(disease_id);

                // Loop through options to find the corresponding text and select it
                $('#disease_id option').each(function() {
                    if ($(this).val() == disease_id) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var treatment_id = examination.treatment_id;
                $('#treatment_id').val(treatment_id);

                // Loop through options to find the corresponding text and select it
                $('#treatment_id option').each(function() {
                    if ($(this).val() == treatment_id) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var treatment_status = examination.treatment_status;
                $('#treatment_status').val(treatment_status);

                // Loop through options to find the corresponding text and select it
                $('#treatment_status option').each(function() {
                    if ($(this).val() == treatment_status) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var palatal_condn = examination.palatal_condn;
                $('#palatal_condn').val(palatal_condn);

                if (palatal_condn !== null) {
                    $("#Palatal").show();
                    var dpartId = '#' + $('.dparts[title="Palatal"]').attr(
                        'id');
                    $(dpartId).css({
                        'background-color': 'red',
                    });
                    $(dpartId).addClass('red');
                }

                // Loop through options to find the corresponding text and select it
                $('#palatal_condn option').each(function() {
                    if ($(this).val() == palatal_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var mesial_condn = examination.mesial_condn;
                $('#mesial_condn').val(mesial_condn);
                if (mesial_condn !== null) {
                    $("#Mesial").show();
                    var dpartId = '#' + $('.dparts[title="Mesial"]').attr(
                        'id');
                    $(dpartId).css({
                        'background-color': 'red',
                    });
                    $(dpartId).addClass('red');
                }

                // Loop through options to find the corresponding text and select it
                $('#mesial_condn option').each(function() {
                    if ($(this).val() == mesial_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var distal_condn = examination.distal_condn;
                $('#distal_condn').val(distal_condn);

                if (distal_condn !== null) {
                    $("#Distal").show();
                    var dpartId = '#' + $('.dparts[title="Distal"]').attr(
                        'id');
                    $(dpartId).css({
                        'background-color': 'red',
                    });
                    $(dpartId).addClass('red');
                }

                // Loop through options to find the corresponding text and select it
                $('#distal_condn option').each(function() {
                    if ($(this).val() == distal_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var buccal_condn = examination.buccal_condn;
                $('#buccal_condn').val(buccal_condn);

                if (buccal_condn !== null) {
                    $("#Buccal").show();
                    var dpartId = '#' + $('.dparts[title="Buccal"]').attr(
                        'id');
                    $(dpartId).css({
                        'background-color': 'red',
                    });
                    $(dpartId).addClass('red');
                }

                // Loop through options to find the corresponding text and select it
                $('#buccal_condn option').each(function() {
                    if ($(this).val() == buccal_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var occulusal_condn = examination.occulusal_condn;
                $('#occulusal_condn').val(occulusal_condn);

                if (occulusal_condn !== null) {
                    $("#Occulusal").show();
                    var dpartId = '#' + $('.dparts[title="Occulusal"]')
                        .attr(
                            'id');
                    $(dpartId).css({
                        'background-color': 'red',
                    });
                    $(dpartId).addClass('red');
                }

                // Loop through options to find the corresponding text and select it
                $('#occulusal_condn option').each(function() {
                    if ($(this).val() == occulusal_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var labial_condn = examination.labial_condn;
                $('#labial_condn').val(labial_condn);

                // Loop through options to find the corresponding text and select it
                $('#labial_condn option').each(function() {
                    if ($(this).val() == labial_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var lingual_condn = examination.lingual_condn;
                $('#lingual_condn').val(lingual_condn);

                // Loop through options to find the corresponding text and select it
                $('#lingual_condn option').each(function() {
                    if ($(this).val() == lingual_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var xrays = response.xrays;
                if (Array.isArray(xrays) && xrays.length > 0) {
                    // Show the link
                    $('#uploadedXrays').show();
                } else {
                    // Hide the link if no xrays or not an array
                    $('#uploadedXrays').hide();
                }
            },

        });
        $('#newTreatmentBtn').hide();
        $('#editTreatmentBtn').hide();
        $('#modal-teeth').modal('show');


    });
    $(document).on('click', '.btn-treat-edit', function() {
        var teethName = $(this).data('id');
        var appId = '<?= Session::get('appId') ?>';
        var patientId = '<?= Session::get('patientId') ?>';
        // console.log('Hover in T' + teethName);
        var divId = '#div' + teethName;
        $(divId).css({
            'border': '2px solid blue',
            'border-radius': '5px',
        });

        // $(this).toggleClass('selected');
        $('#tooth_id').val(teethName);
        $('#app_id').val(appId);
        $('#patient_id').val(patientId);
        if ($(divId).hasClass('molar')) {
            $('#premolars_molars').show();
            $('#incisors_canines').hide();
        } else if ($(divId).hasClass('inccan')) {
            $('#incisors_canines').show();
            $('#premolars_molars').hide();
        }
        $.ajax({
            url: '{{ route('get.toothExamination', ['toothId' => ':toothId', 'appId' => ':appId', 'patientId' => ':patientId']) }}'
                .replace(':toothId', teethName)
                .replace(':appId', appId)
                .replace(':patientId', patientId),
            type: "GET",
            dataType: "json",

            success: function(response) {
                var examination = response
                    .examination; // Assuming there's only one item in the array

                // Set the value of tooth_score_id field
                var toothScoreId = examination.tooth_score_id;
                $('#tooth_score_id').val(toothScoreId);

                // Loop through options to find the corresponding text and select it
                $('#tooth_score_id option').each(function() {
                    if ($(this).val() == toothScoreId) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });
                $('#tooth_score_id').trigger('change');
                $('#chief_complaint').val(examination.chief_complaint);
                $('#hpi').val(examination.hpi);
                $('#diagnosis').val(examination.diagnosis);
                $('#dental_examination').val(examination
                    .dental_examination);
                $('#remarks').val(examination.remarks);
                var disease_id = examination.disease_id;
                $('#disease_id').val(disease_id);

                // Loop through options to find the corresponding text and select it
                $('#disease_id option').each(function() {
                    if ($(this).val() == disease_id) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var treatment_id = examination.treatment_id;
                $('#treatment_id').val(treatment_id);

                // Loop through options to find the corresponding text and select it
                $('#treatment_id option').each(function() {
                    if ($(this).val() == treatment_id) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var treatment_status = examination.treatment_status;
                $('#treatment_status').val(treatment_status);

                // Loop through options to find the corresponding text and select it
                $('#treatment_status option').each(function() {
                    if ($(this).val() == treatment_status) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var palatal_condn = examination.palatal_condn;
                $('#palatal_condn').val(palatal_condn);

                if (palatal_condn !== null) {
                    $("#Palatal").show();
                    var dpartId = '#' + $('.dparts[title="Palatal"]').attr(
                        'id');
                    $(dpartId).css({
                        'background-color': 'red',
                    });
                    $(dpartId).addClass('red');
                }

                // Loop through options to find the corresponding text and select it
                $('#palatal_condn option').each(function() {
                    if ($(this).val() == palatal_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var mesial_condn = examination.mesial_condn;
                $('#mesial_condn').val(mesial_condn);
                if (mesial_condn !== null) {
                    $("#Mesial").show();
                    var dpartId = '#' + $('.dparts[title="Mesial"]').attr(
                        'id');
                    $(dpartId).css({
                        'background-color': 'red',
                    });
                    $(dpartId).addClass('red');
                }

                // Loop through options to find the corresponding text and select it
                $('#mesial_condn option').each(function() {
                    if ($(this).val() == mesial_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var distal_condn = examination.distal_condn;
                $('#distal_condn').val(distal_condn);

                if (distal_condn !== null) {
                    $("#Distal").show();
                    var dpartId = '#' + $('.dparts[title="Distal"]').attr(
                        'id');
                    $(dpartId).css({
                        'background-color': 'red',
                    });
                    $(dpartId).addClass('red');
                }

                // Loop through options to find the corresponding text and select it
                $('#distal_condn option').each(function() {
                    if ($(this).val() == distal_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var buccal_condn = examination.buccal_condn;
                $('#buccal_condn').val(buccal_condn);

                if (buccal_condn !== null) {
                    $("#Buccal").show();
                    var dpartId = '#' + $('.dparts[title="Buccal"]').attr(
                        'id');
                    $(dpartId).css({
                        'background-color': 'red',
                    });
                    $(dpartId).addClass('red');
                }

                // Loop through options to find the corresponding text and select it
                $('#buccal_condn option').each(function() {
                    if ($(this).val() == buccal_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var occulusal_condn = examination.occulusal_condn;
                $('#occulusal_condn').val(occulusal_condn);

                if (occulusal_condn !== null) {
                    $("#Occulusal").show();
                    var dpartId = '#' + $('.dparts[title="Occulusal"]')
                        .attr(
                            'id');
                    $(dpartId).css({
                        'background-color': 'red',
                    });
                    $(dpartId).addClass('red');
                }

                // Loop through options to find the corresponding text and select it
                $('#occulusal_condn option').each(function() {
                    if ($(this).val() == occulusal_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var labial_condn = examination.labial_condn;
                $('#labial_condn').val(labial_condn);

                // Loop through options to find the corresponding text and select it
                $('#labial_condn option').each(function() {
                    if ($(this).val() == labial_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var lingual_condn = examination.lingual_condn;
                $('#lingual_condn').val(lingual_condn);

                // Loop through options to find the corresponding text and select it
                $('#lingual_condn option').each(function() {
                    if ($(this).val() == lingual_condn) {
                        $(this).prop('selected', true);
                        return false; // Exit the loop once found
                    }
                });

                var xrays = response.xrays;
                if (Array.isArray(xrays) && xrays.length > 0) {
                    // Show the link
                    $('#uploadedXrays').show();
                } else {
                    // Hide the link if no xrays or not an array
                    $('#uploadedXrays').hide();
                }
            },

        });
        $('#newTreatmentBtn').show();
        $('#modal-teeth').modal('show');


    });

    $('#clinic_branch_id, #appdate').change(function() {
        var branchId = $('#clinic_branch_id').val();
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
        $('#existingAppointmentsError').hide();
        showExistingAppointments(branchId, appDate, doctorId);

    });

    function showExistingAppointments(branchId, appDate, doctorId) {
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
                    if (data.existingAppointments.length > 0) {

                        // Clear existing content

                        $('#existingAppointments').empty();
                        // Create a table element
                        var table = $('<table class="table table-striped">').addClass('appointment-table');

                        // Create header row
                        var headerRow = $('<tr>');
                        headerRow.append($('<th>').text('Scheduled Appointments'));
                        table.append(headerRow);

                        // Calculate number of rows needed
                        var numRows = Math.ceil(data.existingAppointments.length / 3);

                        // Loop to create rows and populate cells
                        for (var i = 0; i < numRows; i++) {
                            var row = $('<tr>');

                            // Create 3 cells for each row
                            for (var j = 0; j < 3; j++) {
                                var dataIndex = i * 3 + j;
                                if (dataIndex < data.existingAppointments.length) {
                                    var cell = $('<td>').text(data.existingAppointments[dataIndex]
                                        .app_time);
                                    row.append(cell);
                                } else {
                                    var cell = $('<td>'); // Create empty cell if no more data
                                    row.append(cell);
                                }
                            }

                            table.append(row);
                        }
                        // Append table to existingAppointments div
                        $('#existingAppointments').append(table);

                        // Show the div
                        $('#existingAppointments').show();

                    } else {
                        $('#existingAppointments').html('No existing appointments found.');
                        $('#existingAppointments').show();

                    }
                },

                error: function(xhr, status, error) {
                    console.error('Error fetching existing appointments:', error);
                    $('#existingAppointments').html(
                        'Error fetching existing appointments. Please try again later.');
                    $('#existingAppointments').show();

                }
            });
        } else {
            $('#existingAppointments').html('No existing appointments found.');
            $('#existingAppointments').show();
        }
    }
</script>
