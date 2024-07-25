<form id="form-teeth" method="POST" action="{{ route('treatment.store') }}">
    @csrf
    <input type="hidden" id="app_id" name="app_id" value="">
    <input type="hidden" id="patient_id" name="patient_id" value="">
    <input type="hidden" id="row_id" name="row_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-teeth" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-tooth"></i> Tooth Examination
                    </h5>
                    <button type="button" class="btn-close closeToothBtn" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="row exam_toothdiv" style="display: none;">
                            <div class="col-md-3 ">
                                <div class="dparts-wrapper" id="incisors_canines" style="display: none;">
                                    <div class="dparts part-left" title="Mesial" id="part-left"></div>
                                    <div class="dparts part-top" title="Buccal" id="part-top"></div>
                                    <div class="dparts part-right" title="Distal" id="part-right"></div>
                                    <div class="dparts part-bottom" title="Palatal" id="part-bottom"></div>
                                </div>

                                <div class="dparts-wrapper" id="premolars_molars" style="display: none;">
                                    <div class="dparts dpart-left" title="Mesial" id="dpart-left"></div>
                                    <div class="dparts dpart-top" title="Buccal" id="dpart-top"></div>
                                    <div class="dparts dpart-right" title="Distal" id="dpart-right"></div>
                                    <div class="dparts dpart-bottom" title="Palatal" id="dpart-bottom"></div>
                                    <div class="dparts dpart-center" title="Occulusal" id="dpart-center"></div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4 ps-2">
                                        <div class="form-group">
                                            <label class="form-label" for="tooth_id">Tooth No</label>
                                            <input type="text" class="form-control" id="tooth_id" name="tooth_id"
                                                placeholder="tooth no" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-8 ">
                                        <div class="form-group">
                                            <label class="form-label" for="tooth_score_id">Tooth Score <span
                                                    class="text-danger">
                                                    *</span></label>
                                            <select class="form-select" id="tooth_score_id" name="tooth_score_id">
                                                <option value="">Select Score</option>
                                                @foreach ($toothScores as $toothScore)
                                                    <option value="<?= $toothScore->id ?>"> <?= $toothScore->score ?>
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div id="toothScoreError" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 ">
                                        <div class="form-group">
                                            <label class="form-label" for="chief_complaint">Chief Complaint <span
                                                    class="text-danger">
                                                    *</span></label>
                                            <input type="text" class="form-control" id="chief_complaint"
                                                name="chief_complaint" placeholder="Chief Complaint">
                                            <div id="complaintError" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row exam_chiefComplaint">
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <label class="form-label" for="chief_complaint1">Chief Complaint <span
                                            class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" id="chief_complaint1"
                                        name="chief_complaint1" placeholder="Chief Complaint">
                                    <div id="complaintError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                       
                            {{-- <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="form-label" for="complaint">Chief Complaint <span class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" id="complaint" name="complaint"
                                        placeholder="Chief Complaint">
                                </div>
                            </div>--}}
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="form-label" for="disease_id">Disease <span class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="disease_id" name="disease_id">
                                        <option value="">Select disease</option>
                                        @foreach ($diseases as $disease)
                                            <option value="<?= $disease->id ?>"><?= $disease->name ?></option>
                                        @endforeach
                                    </select>
                                    <div id="diseaseError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="hpi">HPI <span class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" id="hpi" name="hpi"
                                        placeholder="HPI">
                                    <div id="hpiError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="form-label" for="dental_examination">Dental Examination <span
                                            class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" id="dental_examination"
                                        name="dental_examination" placeholder="Dental Examination">
                                    <div id="dexamError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="form-label" for="diagnosis">Diagnosis <span class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" id="diagnosis" name="diagnosis"
                                        placeholder="diagnosis">
                                    <div id="diagnosisError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="form-label" for="treatment_id">Treatment <span class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="treatment_id" name="treatment_id">
                                        <option value="">Select a Treatment</option>
                                        @foreach ($treatments as $treatment)
                                            <option value="<?= $treatment->id ?>"><?= $treatment->treat_name ?>
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="treatmentError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="treatment_status">Treatment Status <span
                                            class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="treatment_status" name="treatment_status">
                                        <option value="">Select Status</option>
                                        @foreach ($treatmentStatus as $status)
                                            <option value="<?= $status->id ?>"><?= $status->status ?></option>
                                        @endforeach
                                    </select>
                                    <div id="treatmentStatusError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" id="teethXrayDiv">
                                <div class="form-group">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label" for="xray">X-Ray</label>
                                        <button type="button" id="uploadedXrays" style="display:none;"
                                            class="waves-effect waves-light btn btn-circle btn-info btn-xs"
                                            data-bs-toggle="modal" data-bs-target="#modal-documents"
                                            title="view documents"><i class="fa-solid fa-file-archive"></i></button>
                                    </div>
                                    <input type="file" class="form-control" id="xray" type="file"
                                        name="xray[]" multiple>
                                    <div id="xrayError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="form-label" for="remarks">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="1" placeholder="Remarks if any"></textarea>
                                    <div id="remarksError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="d-none row">
                            {{-- <div class="col-md-6">
                                <label class="form-label" for="xray">X-Ray <span class="text-danger">
                                        <input type="file" class="form-control" id="xray" type="file"
                                            name="xray[]" multiple>
                            </div> --}}
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <label class="form-label" for="remarks">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks">remarks if any</textarea>
                                    <div id="remarksError" class="invalid-feedback"></div>
                                    {{-- <input type="text" class="form-control" id="remarks" name="remarks"
                                        placeholder="remarks"> --}}
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class=" row exam_toothdiv" style="display: none;">
                            <div class="col-md-3 tooth_partsdiv" id="Buccal" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label" for="buccal_condn">Buccal <span class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="buccal_condn" name="buccal_condn">
                                        <option value=""> Select</option>
                                        @foreach ($surfaceConditions as $surfaceCondition)
                                            <option value="<?= $surfaceCondition->id ?>">
                                                <?= $surfaceCondition->condition ?></option>
                                        @endforeach

                                    </select>
                                    <div id="buccal_condnError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-3 tooth_partsdiv" id="Palatal" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label" for="palatal_condn">Palatal <span class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="palatal_condn" name="palatal_condn">
                                        <option value=""> Select</option>
                                        @foreach ($surfaceConditions as $surfaceCondition)
                                            <option value="<?= $surfaceCondition->id ?>">
                                                <?= $surfaceCondition->condition ?></option>
                                        @endforeach

                                    </select>
                                    <div id="palatal_condnError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-3 tooth_partsdiv" id="Mesial" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label" for="mesial_condn">Mesial <span class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="mesial_condn" name="mesial_condn">
                                        <option value=""> Select</option>
                                        @foreach ($surfaceConditions as $surfaceCondition)
                                            <option value="<?= $surfaceCondition->id ?>">
                                                <?= $surfaceCondition->condition ?></option>
                                        @endforeach
                                    </select>
                                    <div id="mesial_condnError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-3 tooth_partsdiv" id="Distal" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label" for="distal_condn">Distal <span class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="distal_condn" name="distal_condn">
                                        <option value=""> Select</option>
                                        @foreach ($surfaceConditions as $surfaceCondition)
                                            <option value="<?= $surfaceCondition->id ?>">
                                                <?= $surfaceCondition->condition ?></option>
                                        @endforeach
                                    </select>
                                    <div id="distal_condnError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-3 tooth_partsdiv" id="Occulusal" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label" for="occulusal_condn">Occulusal <span
                                            class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="occulusal_condn" name="occulusal_condn">
                                        <option value=""> Select</option>
                                        @foreach ($surfaceConditions as $surfaceCondition)
                                            <option value="<?= $surfaceCondition->id ?>">
                                                <?= $surfaceCondition->condition ?></option>
                                        @endforeach
                                    </select>
                                    <div id="occulusal_condnError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <label class="form-label" for="lingual_condn">Lingual</label>
                                    <select class="form-select" id="lingual_condn" name="lingual_condn">
                                        <option value=""> Select</option>
                                        @foreach ($surfaceConditions as $surfaceCondition)
                                            <option value="<?= $surfaceCondition->id ?>">
                                                <?= $surfaceCondition->condition ?></option>
                                        @endforeach

                                    </select>
                                    <div id="lingual_condnError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <label class="form-label" for="labial_condn">Labial</label>
                                    <select class="form-select" id="labial_condn" name="labial_condn">
                                        <option value=""> Select</option>
                                        @foreach ($surfaceConditions as $surfaceCondition)
                                            <option value="<?= $surfaceCondition->id ?>">
                                                <?= $surfaceCondition->condition ?></option>
                                        @endforeach

                                    </select>
                                    <div id="labial_condnError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            {{-- <div class="col-md-3 ">
                                <div class="form-group">
                                    <label class="form-label" for="treatment_status">Treatment Status <span
                                            class="text-danger">
                                            *</span></label>
                                    <select class="form-select" id="treatment_status" name="treatment_status">
                                        <option value="">Select Status</option>
                                        @foreach ($treatmentStatus as $status)
                                            <option value="<?= $status->id ?>"><?= $status->status ?></option>
                                        @endforeach
                                    </select>
                                    <div id="treatmentStatusError" class="invalid-feedback"></div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger closeToothBtn" id="closeToothBtn"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="newTreatmentBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {

        // Handle Save button click
        $('#newTreatmentBtn').click(function() {
            // Reset previous error messages
            resetErrors();

            // Validate form inputs
            var toothScore = $('#tooth_score_id').val();
            var complaint = $('#chief_complaint').val();
            var complaint = $('#chief_complaint1').val();
            var disease = $('#disease_id').val();
            var hpi = $('#hpi').val();
            var dexam = $('#dental_examination').val();
            var diagnosis = $('#diagnosis').val();
            var xray = $('#xray').prop('files');
            var treatment = $('#treatment_id').val();
            var remarks = $('#remarks').val();

            // Basic client-side validation
            if (!toothScore) {
                $('#tooth_score_id').addClass('is-invalid');
                $('#toothScoreError').text('Tooth Score is required.');
            }

            if (!complaint) {
                $('#chief_complaint').addClass('is-invalid');
                $('#chief_complaint1').addClass('is-invalid');
                $('#complaintError').text('Chief Complaint is required.');
            }

            if (!disease) {
                $('#disease_id').addClass('is-invalid');
                $('#diseaseError').text('Disease is required.');
            }

            if (!hpi) {
                $('#hpi').addClass('is-invalid');
                $('#hpiError').text('HPI is required.');
            }

            if (!dexam) {
                $('#dental_examination').addClass('is-invalid');
                $('#dexamError').text('Dental Examination is required.');
            }

            if (!diagnosis) {
                $('#diagnosis').addClass('is-invalid');
                $('#diagnosisError').text('Diagnosis is required.');
            }



            if (!treatment) {
                $('#treatment_id').addClass('is-invalid');
                $('#treatmentError').text('Treatment is required.');
            }

            // If all validations pass, submit the form via AJAX
            var form = $('#form-teeth');
            var url = form.attr('action');
            var formData = new FormData(form[0]);

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    // If successful, hide modal and show success message
                    $('#modal-teeth').modal('hide');
                    $('#successMessage').text(response.success);
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut();

                },
                error: function(xhr) {
                    // Handle specific error messages from backend if needed
                    console.log(xhr.responseText);
                    alert(
                        'Error saving treatment. Please try again.'
                    ); // You can customize this as per your UI needs
                }
            });
        });


        // Function to reset all form errors
        function resetErrors() {
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }

        // Reset form and errors on modal close
        $('#modal-teeth').on('hidden.bs.modal', function() {
            $('.tooth').css({
                'border': 'none',
            });
            $('.dparts').css({
                'background-color': 'white',
            });
            $('.dparts').removeClass('red');

            if ($('#checkbox_all').is(':checked')) {
                $('#checkbox_all').prop('checked', false);
                $('.exam_chiefComplaint').hide();
                $('.exam_toothdiv').show();
            }
            else if ($('#checkbox_row1').is(':checked')) {
                $('#checkbox_row1').prop('checked', false);
                $('.exam_chiefComplaint').hide();
                $('.exam_toothdiv').show();
                $('#trow1').removeClass('rowbordered');
            }
            else if ($('#checkbox_row2').is(':checked')) {
                $('#checkbox_row2').prop('checked', false);
                $('.exam_chiefComplaint').hide();
                $('.exam_toothdiv').show();
                $('#trow2').removeClass('rowbordered');
            }
            else if ($('#checkbox_row3').is(':checked')) {
                $('#checkbox_row3').prop('checked', false);
                $('.exam_chiefComplaint').hide();
                $('.exam_toothdiv').show();
                $('#trow3').removeClass('rowbordered');
            }
            else if ($('#checkbox_row4').is(':checked')) {
                $('#checkbox_row4').prop('checked', false);
                $('.exam_chiefComplaint').hide();
                $('.exam_toothdiv').show();
                $('#trow4').removeClass('rowbordered');
            } else {
                $('.exam_chiefComplaint').show();
            }

            $('.tooth_partsdiv').hide();
            $('#form-teeth').trigger('reset');
            resetErrors();

            $('#newTreatmentBtn').show();
            $('#uploadedXrays').hide();
            $('#teethXrayDiv').show();

        });
        $('.form-control, .form-select').on('input', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        });

    });
</script>
