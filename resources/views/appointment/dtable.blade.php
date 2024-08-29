<?php

use Illuminate\Support\Facades\Session;
?>
<div class="d-flex align-items-center justify-content-between">
    <h5 class="box-title text-info mb-0 mt-2 "><i class="fa fa-medkit me-15"></i>
        Treatment Chart
    </h5>

    {{-- <button type="button" class="waves-effect waves-light btn btn-sm btn-outline-primary" id="table_info_btn">
        <i class="fa fa-table"></i>
        Info</button> --}}

    <button type='button'
        class='waves-effect waves-light btn btn-circle btn-secondary btn-treatment-pdf-generate btn-xs me-1'
        title='Download & Print Treatment Summary' data-bs-toggle='modal' data-app-id='{{ session('appId') }}'
        data-parent-id='{$parent_id}' data-patient-id='{{ session('patientId') }}' data-bs-target='#modal-download'><i
            class='fa fa-download'></i></button>
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
    {{-- <input type="checkbox" id="follow_checkbox" name="follow_checkbox" class="filled-in chk-col-success" /> --}}
    <input type="checkbox" id="follow_checkbox" name="follow_checkbox" class="filled-in chk-col-success"
        @if (isset($latestFollowup)) checked @endif />
    <label for="follow_checkbox"></label>
</div>
<hr class="my-15 ">

{{-- <div class="row mb-4" id="followupdiv" style="display: none;"> --}}
<div class="row mb-4" id="followupdiv" @if (!isset($latestFollowup)) style="display: none;" @endif>
    <div class="table-responsive">
        <table id="myTable" class="table table-bordered table-hover table-striped mb-0 text-center">

            <thead>
                <tr class="bg-primary-light">
                    <th>No</th>
                    <!-- <th>Treatment</th> -->
                    <th>Branch</th>
                    <th>Appointment Date & Time</th>
                    <th>Consulting Doctor</th>
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
                        <input type="hidden" id="followupAppId" name="followupAppId"
                            @if (isset($latestFollowup)) value="{{ $latestFollowup->id }}" @endif />
                        <select class="select2" id="clinic_branch_id" name="clinic_branch_id"
                            data-placeholder="Select a Branch" style="width: 100%;">
                            @foreach ($clinicBranches as $clinicBranch)
                                <?php
                                $clinicAddress = $clinicBranch->clinic_address;
                                $clinicAddress = explode('<br>', $clinicBranch->clinic_address);
                                $clinicAddress = implode(', ', $clinicAddress);
                                $branch = $clinicAddress . ', ' . $clinicBranch->city->city . ', ' . $clinicBranch->state->state;
                                ?>
                                {{-- <option value="{{ $clinicBranch->id }}"
                                    @if ($appointment->app_branch == $clinicBranch->id) selected @endif>
                                    {{ $branch }}</option> --}}
                                <option value="{{ $clinicBranch->id }}"
                                    @if (isset($latestFollowup) && $latestFollowup->app_branch == $clinicBranch->id) selected 
                                        @elseif (!isset($latestFollowup) && $appointment->app_branch == $clinicBranch->id) 
                                            selected @endif>
                                    {{ $branch }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        {{-- <input class="form-control" type="datetime-local" id="appdate" name="appdate"
                            value="{{ now()->setTimezone('Asia/Kolkata')->format('Y-m-d\TH:i') }}"> --}}
                        <input class="form-control" type="datetime-local" id="appdate" name="appdate"
                            @if (isset($latestFollowup)) value="{{ \Carbon\Carbon::parse($latestFollowup->app_date . ' ' . $latestFollowup->app_time)->format('Y-m-d\TH:i') }}"
                            @else value="{{ now()->setTimezone('Asia/Kolkata')->format('Y-m-d\TH:i') }}" @endif>
                    </td>

                    <td>
                        <select class="select2" id="doctor_id" name="doctor_id" data-placeholder="Select a Doctor"
                            style="width: 100%;">
                            @foreach ($workingDoctors as $doctor)
                                <?php $doctorName = str_replace('<br>', ' ', $doctor->user->name); ?>
                                {{-- <option value="{{ $doctor->user_id }}"
                                    @if ($doctor->user_id == $appointment->doctor_id) selected @endif>
                                    {{ $doctorName }}
                                </option> --}}
                                <option value="{{ $doctor->user_id }}"
                                    @if (isset($latestFollowup) && $latestFollowup->doctor_id == $doctor->user_id) selected
                                        @elseif (!isset($latestFollowup) && $doctor->user_id == $appointment->doctor_id) selected @endif>
                                    {{ $doctorName }}
                                </option>
                            @endforeach
                        </select>
                    </td>


                    <td>
                        {{-- <input type="text" class="form-control" id="remarks_followup" name="remarks_followup"
                            placeholder="remarks"> --}}
                        <input type="text" class="form-control" id="remarks_followup" name="remarks_followup"
                            @if (isset($latestFollowup)) value="{{ $latestFollowup->remarks }}" @endif
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
            <div style="display:none" id="doctorNotAvailable">
                <span class="text-danger">Sorry, the doctor is not available at the selected time.
                    Please choose another time.</span>
            </div>
        </div>
        <div class="row mt-2">
            <div style="display:none" id="existingAppointmentsError">
                <span class="text-danger">Already exists appointment for the selected time!</span>
            </div>
        </div>
        <div class="row mt-2" style="display:none" id="existAppContainer">
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
    {{-- <input type="checkbox" id="presc_checkbox" name="presc_checkbox" class="filled-in chk-col-success"
        @if (isset($patientPrescriptions) && $patientPrescriptions->isNotEmpty()) checked @endif /> --}}
</div>
<hr class="my-15 ">
<script>
    $(function() {

        let presc = {{ count($patientPrescriptions) }};
        if (presc >= 1) {
            $("#presc_checkbox").prop('checked', true).trigger('change');
        }

    });

    $(document).on('click', '.btn-treat-view', function() {
        var teethName = $(this).data('id');
        var teethType = $(this).data('teeth-type');
        var appId = '<?= Session::get('appId') ?>';
        var patientId = '<?= Session::get('patientId') ?>';
        var divId = '#div' + teethName;
        $(divId).addClass('blue'); //new
        if (teethType == 'Row') {
            $('.exam_chiefComplaint').show();
            $('.exam_toothdiv').hide();
        } else {
            $('.exam_chiefComplaint').hide();
            $('.exam_toothdiv').show();
        }

        $('#tooth_id').val(teethName);
        $('#xteeth_id').val(teethName); //new
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
                if (examination != null) {
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
                    // $('#disease_id option').each(function() {
                    //     if ($(this).val() == disease_id) {
                    //         $(this).prop('selected', true);
                    //         return false; // Exit the loop once found
                    //     }
                    // });
                    var diseaseName = response
                        .diseaseName.name;
                    var found = false;
                    // Loop through options to find the corresponding text and select it
                    $('#disease_id option').each(function() {
                        if ($(this).val() == disease_id) {
                            $(this).prop('selected', true);
                            found = true; // Set the flag to true
                            return false; // Exit the loop once found
                        }
                    });

                    if (!found) {
                        $('.disease_id_select').empty();
                        var diseaseSelect = $('.disease_id_select');
                        var option = new Option(diseaseName, disease_id,
                            true, true);
                        diseaseSelect.append(option).trigger(
                            'change');
                        $('.disease_id_select').val(disease_id);
                        // manually trigger the `select2:select` event
                        // diseaseSelect.trigger({
                        //     type: 'select2:select',
                        //     params: {
                        //         data: data
                        //     }
                        // });
                    }

                    $(".disease_id_select").select2("destroy");
                    $(".disease_id_select").select2({
                        dropdownParent: $('#modal-teeth'),
                        width: "100%",
                        placeholder: "Select a Disease",
                        tags: true,
                        tokenSeparators: [","],
                        createTag: function(params) {
                            var term = $.trim(params.term);
                            if (term === "") {
                                return null;
                            }
                            // Check if the term already exists as an option
                            var found = false;
                            $(".treatment_id_select option")
                                .each(function() {
                                    if ($.trim($(this)
                                            .text()) ===
                                        term) {
                                        found = true;
                                        return false; // Exit the loop early
                                    }
                                });
                            if (!found) {
                                return {
                                    id: term,
                                    text: term,
                                    newTag: true
                                };
                            }
                            return null;
                        },
                        insertTag: function(data, tag) {
                            data.push(tag);
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
                    var treatment_plan_id = examination.treatment_plan_id;
                    $('#treatment_plan_id').val(treatment_plan_id);

                    // Loop through options to find the corresponding text and select it
                    $('#treatment_plan_id option').each(function() {
                        if ($(this).val() == treatment_plan_id) {
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
                        var dpartId = $('.dparts[title="Palatal"]');
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
                        var dpartId = $('.dparts[title="Mesial"]');
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
                        var dpartId = $('.dparts[title="Distal"]');
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
                        var dpartId = $('.dparts[title="Buccal"]');
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
                } else {
                    toothData.forEach(function(tooth) {
                        var toothId = tooth.tooth_id;
                        if (toothId == teethName) {
                            var palatal_condn = tooth.palatal_condn;
                            $('#palatal_condn').val(palatal_condn);

                            if (palatal_condn !== null) {
                                $("#Palatal").show();
                                var dpartId = $(
                                    '.dparts[title="Palatal"]');
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            var mesial_condn = tooth.mesial_condn;
                            $('#mesial_condn').val(mesial_condn);
                            if (mesial_condn !== null) {
                                $("#Mesial").show();
                                var dpartId = $(
                                    '.dparts[title="Mesial"]');
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            var distal_condn = tooth.distal_condn;
                            $('#distal_condn').val(distal_condn);

                            if (distal_condn !== null) {
                                $("#Distal").show();
                                var dpartId = $(
                                    '.dparts[title="Distal"]');
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            var buccal_condn = tooth.buccal_condn;
                            $('#buccal_condn').val(buccal_condn);

                            if (buccal_condn !== null) {
                                $("#Buccal").show();
                                var dpartId = $(
                                    '.dparts[title="Buccal"]');
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            var occulusal_condn = tooth
                                .occulusal_condn;
                            $('#occulusal_condn').val(
                                occulusal_condn);

                            if (occulusal_condn !== null) {
                                $("#Occulusal").show();
                                var dpartId = $(
                                    '.dparts[title="Occulusal"]'
                                );
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            var labial_condn = tooth.labial_condn;
                            $('#labial_condn').val(labial_condn);

                            var lingual_condn = tooth.lingual_condn;
                            $('#lingual_condn').val(lingual_condn);
                        }
                    });

                }


                $('#teethXrayDiv').hide();
            },

        });
        $('#newTreatmentBtn').hide();
        $('#modal-teeth').modal('show');
    });

    $(document).on('click', '.btn-treat-edit', function() {

        var teethName = $(this).data('id');
        var teethType = $(this).data('teeth-type');
        var appId = '<?= Session::get('appId') ?>';
        var patientId = '<?= Session::get('patientId') ?>';
        var divId = '#div' + teethName;
        $(divId).addClass('blue'); //new
        if (teethType == 'Row') {
            $('#row_id').val(teethName);
            $('#tooth_id').val('');
            $('.exam_chiefComplaint').show();
            $('.exam_toothdiv').hide();
        } else {
            $('#tooth_id').val(teethName);
            $('#xteeth_id').val(teethName);
            $('#row_id').val('');
            $('.exam_chiefComplaint').hide();
            $('.exam_toothdiv').show();
        }

        //new
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

                if (examination != null) {
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
                    // $('#disease_id option').each(function() {
                    //     if ($(this).val() == disease_id) {
                    //         $(this).prop('selected', true);
                    //         return false; // Exit the loop once found
                    //     }
                    // });
                    var diseaseName = response
                        .diseaseName.name;
                    var found = false;
                    // Loop through options to find the corresponding text and select it
                    $('#disease_id option').each(function() {
                        if ($(this).val() == disease_id) {
                            $(this).prop('selected', true);
                            found = true; // Set the flag to true
                            return false; // Exit the loop once found
                        }
                    });

                    if (!found) {
                        $('.disease_id_select').empty();
                        var diseaseSelect = $('.disease_id_select');
                        var option = new Option(diseaseName, disease_id,
                            true, true);
                        diseaseSelect.append(option).trigger(
                            'change');
                        $('.disease_id_select').val(disease_id);
                        // manually trigger the `select2:select` event
                        // diseaseSelect.trigger({
                        //     type: 'select2:select',
                        //     params: {
                        //         data: data
                        //     }
                        // });
                    }

                    $(".disease_id_select").select2("destroy");
                    $(".disease_id_select").select2({
                        dropdownParent: $('#modal-teeth'),
                        width: "100%",
                        placeholder: "Select a Disease",
                        tags: true,
                        tokenSeparators: [","],
                        createTag: function(params) {
                            var term = $.trim(params.term);
                            if (term === "") {
                                return null;
                            }
                            // Check if the term already exists as an option
                            var found = false;
                            $(".treatment_id_select option")
                                .each(function() {
                                    if ($.trim($(this)
                                            .text()) ===
                                        term) {
                                        found = true;
                                        return false; // Exit the loop early
                                    }
                                });
                            if (!found) {
                                return {
                                    id: term,
                                    text: term,
                                    newTag: true
                                };
                            }
                            return null;
                        },
                        insertTag: function(data, tag) {
                            data.push(tag);
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
                    var treatment_plan_id = examination.treatment_plan_id;
                    $('#treatment_plan_id').val(treatment_plan_id);

                    // Loop through options to find the corresponding text and select it
                    $('#treatment_plan_id option').each(function() {
                        if ($(this).val() == treatment_plan_id) {
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
                        var dpartId = $('.dparts[title="Palatal"]');
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
                        var dpartId = $('.dparts[title="Mesial"]');
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
                        var dpartId = $('.dparts[title="Distal"]');
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
                        var dpartId = $('.dparts[title="Buccal"]');
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
                } else {
                    toothData.forEach(function(tooth) {
                        var toothId = tooth.tooth_id;
                        if (toothId == teethName) {
                            var palatal_condn = tooth.palatal_condn;
                            $('#palatal_condn').val(palatal_condn);

                            if (palatal_condn !== null) {
                                $("#Palatal").show();
                                var dpartId = $(
                                    '.dparts[title="Palatal"]');
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            var mesial_condn = tooth.mesial_condn;
                            $('#mesial_condn').val(mesial_condn);
                            if (mesial_condn !== null) {
                                $("#Mesial").show();
                                var dpartId = $(
                                    '.dparts[title="Mesial"]');
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            var distal_condn = tooth.distal_condn;
                            $('#distal_condn').val(distal_condn);

                            if (distal_condn !== null) {
                                $("#Distal").show();
                                var dpartId = $(
                                    '.dparts[title="Distal"]');
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            var buccal_condn = tooth.buccal_condn;
                            $('#buccal_condn').val(buccal_condn);

                            if (buccal_condn !== null) {
                                $("#Buccal").show();
                                var dpartId = $(
                                    '.dparts[title="Buccal"]');
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            var occulusal_condn = tooth
                                .occulusal_condn;
                            $('#occulusal_condn').val(
                                occulusal_condn);

                            if (occulusal_condn !== null) {
                                $("#Occulusal").show();
                                var dpartId = $(
                                    '.dparts[title="Occulusal"]'
                                );
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            var labial_condn = tooth.labial_condn;
                            $('#labial_condn').val(labial_condn);

                            var lingual_condn = tooth.lingual_condn;
                            $('#lingual_condn').val(lingual_condn);
                        }
                    });

                }

                var xrays = response.xrays;
                if (Array.isArray(xrays) && xrays.length > 0) {
                    // Show the link
                    $('#uploadedXrays').show();
                    $('#uploadedXrays').attr('data-id', examination.id);
                    $('#xtooth_exam_id').val(examination.id);
                } else {
                    // Hide the link if no xrays or not an array
                    $('#uploadedXrays').hide();
                    $('#uploadedXrays').attr('data-id', null);
                    $('#xtooth_exam_id').val('');
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
        $('#doctorNotAvailable').hide();
        showExistingAppointments(branchId, appDate, doctorId);

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
                    if (data.doctorAvailable == true) {
                        $('#doctorNotAvailable').hide();
                    } else {
                        $('#doctorNotAvailable').show();
                    }
                    if (data.existingAppointments.length > 0) {

                        // Clear existing content
                        $('#existAppContainer').hide();
                        $('#existingAppointments').empty();
                        // Create a table element
                        // var table = $('<table class="table table-striped">').addClass('appointment-table');
                        var table = $('<table class="table table-striped mb-0">').addClass(
                            'appointment-table').css({
                            'border-collapse': 'separate',
                            'border-spacing': '0.5rem'
                        });

                        var numRows = Math.ceil(data.existingAppointments.length / 10);

                        for (var i = 0; i < numRows; i++) {
                            var row = $('<tr>');

                            for (var j = 0; j < 10; j++) {
                                var dataIndex = i * 10 + j;
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
                        $('#existingAppointments').append($('<h6 class="text-warning mb-1">').text(
                            'Scheduled Appointments'));
                        // Append table to existingAppointments div
                        $('#existingAppointments').append(table);
                        $('#existAppContainer').show();
                        // Show the div
                        $('#existingAppointments').show();

                    } else {
                        $('#existingAppointments').html('No existing appointments found.');
                        $('#existAppContainer').show();
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
            $('#existingAppointments').html('No existing appointments found.');
            $('#existAppContainer').show();
            $('#existingAppointments').show();
        }
    }
    $(document).on('click', '#xraybtn', function() {
        var appointmentId = $(this).data('appointment-id');
        var teethName = $(this).data('teeth-name');
        var patientId = $(this).data('patient-id');
        var toothExamId = $(this).data('id');


        $('#xapp_id').val(appointmentId);
        $('#xpatient_id').val(patientId);
        $('#xteeth_id').val(teethName);
        $('#xtooth_exam_id').val(toothExamId);
    });
</script>
