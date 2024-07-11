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
                                    <label class="form-label" for="patient_id">Patient ID</label><span class="text-danger">
                                    *</span>
                                    <input class="form-control" type="text" id="patient_id" name="patient_id"
                                        placeholder="Patient ID" readonly>
                                        <div id="patientIdError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="patient_name">Patient Name</label><span class="text-danger">
                                    *</span>
                                    <input class="form-control" type="text" id="patient_name"
                                        name="patient_name" placeholder="Patient name" readonly>
                                        <div id="patientNameError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="clinic_branch_id">Branch</label><span class="text-danger">
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
                                    <label class="form-label" for="appdate">Booking Date & Time</label><span class="text-danger">*</span>
                                    <input class="form-control" type="datetime-local" id="appdate" name="appdate" required>
                                    <div id="appDateError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="doctor">Doctor</label><span class="text-danger">
                                    *</span>
                                    <select  class="form-select" id="doctor_id" name="doctor_id" required data-placeholder="Select a Doctor" style="width: 100%;">
                                        <option value="">Select a doctor</option>
                                        @foreach ($workingDoctors as $doctor)
                                            <?php $doctorName = str_replace("<br>", " ", $doctor->user->name); ?>
                                            <option value="{{ $doctor->user_id }}">{{ $doctorName }}</option>  
                                        @endforeach
                                    </select>
                                    <div id="doctorError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div style="display:none" id="existingAppointmentsError">
                                <span class="text-danger">Already exists appointment for the selected time!</span>
                            </div>
                        </div>
                        <div class="row">
                            <div style="display:none" id="existingAppointments">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="bp">Blood Pressure</label>
                                    <input type="text" class="form-control" id="bp" name="bp" placeholder="Enter Blood Pressure">
                                    <div id="bpError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="height">Height</label>
                                    <input type="text" class="form-control" id="height" name="height" placeholder="Enter Height">
                                    <div id="heightError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="weight">Weight</label>
                                    <input type="text" class="form-control" id="weight" name="weight" placeholder="Enter Weight">
                                    <div id="weightError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="rdoctor">Referrerd Doctor</label>
                                    <input type="text" class="form-control" id="rdoctor" name="rdoctor" placeholder="Enter doctor name">
                                    <div id="rdoctorError" class="invalid-feedback"></div>
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
        // Handle Save button click
        $('#newAppointmentBtn').click(function() {
            // Reset previous error messages
            $('#patientIdError').text('');
            $('#patientNameError').text('');
            $('#clinicError').text('');
            $('#doctorError').text('');
            $('#appDateError').text('');
            $('#bpError').text('');
            $('#heightError').text('');
            $('#weightError').text('');
            $('#rdoctorError').text('');
            
            // Validate form inputs
            var clinicId = $('#clinic_branch_id').val();
            var doctorId = $('#doctor_id').val();
            var appDate = $('#appdate').val();
            var patientId = $('#patientId').val();
            var patientName = $('#patient_name').val();
            var bp = $('#bp').val();
            var height = $('#height').val();
            var weight = $('#weight').val();
            var rdoctor = $('#rdoctor').val();
            
            
            // Basic client-side validation (you can add more as needed)
            if (clinicId.length === 0) {
                $('#clinic_branch_id').addClass('is-invalid');
                $('#clinicError').text('Clinic is required.');
                return; // Prevent further execution
            } else {
                $('#clinic_branch_id').removeClass('is-invalid');
                $('#clinicError').text('');
            }

            if (doctorId.length === 0) {
                $('#doctor_id').addClass('is-invalid');
                $('#doctorError').text('Doctor is required.');
                return; // Prevent further execution
            } else {
                $('#doctor_id').removeClass('is-invalid');
                $('#doctorError').text('');
            }
            if (appDate.length === 0) {
                $('#appdate').addClass('is-invalid');
                $('#appDateError').text('Appointment date is required.');
                return; // Prevent further execution
            } else {
                $('#appdate').removeClass('is-invalid');
                $('#appDateError').text('');
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
                    // If successful, hide modal and show success message
                    $('#existingAppointments').hide();
                    $('#existingAppointmentsError').hide();
                    $('#modal-booking').modal('hide');
                    $('#successMessage').text('New Appointment added successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                    // location.reload();
                    table.ajax.reload();
                },
                // error: function(xhr) {
                //     // Reset previous error messages
                //     $('#appDateError').text('');

                //     // Check if there are validation errors
                //     var errors = xhr.responseJSON.errors;
                //     $('#appDateError').text('');

                //     // Check if there are validation errors
                //     var errors = xhr.responseJSON.errors;
                //     if (errors && errors.hasOwnProperty('app_time')) {
                //         $('#appdate').addClass('is-invalid');
                //         $('#appDateError').text(errors.appdate[0]);
                //     } else {
                //         // Handle specific error from backend
                //         var errorMessage = xhr.responseJSON.error;
                //         if (errorMessage) {
                //             $('#existingAppointmentsError').show();
                //         }
                //     }
                // }
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
            // $('#doctor_id').removeClass('is-invalid');
            // $('#doctorError').text('');
            // $('#appDate').removeClass('is-invalid');
            // $('#appDateError').text('');
            // $('#clinic_branch_id').removeClass('is-invalid');
            // $('#clinicError').text('');
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
</script>
