<form id="rescheduleAppointmentForm" method="post" action="{{ route('appointment.update') }}">
    @csrf
    <input type="hidden" id="edit_app_id" name="edit_app_id" value="">
    <input type="hidden" id="edit_clinic_branch_id" name="edit_clinic_branch_id" value="">
    <!-- <input type="hidden" id="edit_doctor_id" name="edit_doctor_id" value=""> -->
    <div class="modal fade modal-right slideInRight" id="modal-reschedule" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-calendar-days"></i> Reschedule Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="patient_id">Patient ID</label>
                                    <input class="form-control" type="text" id="edit_patient_id"
                                        name="edit_patient_id" placeholder="Patient ID" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="patient_name">Patient Name</label>
                                    <input class="form-control" type="text" id="edit_patient_name"
                                        name="edit_patient_name" placeholder="Patient name" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="clinic_branch_id">Branch</label>
                                    <input type="text" class="form-control" id="edit_clinic_branch"
                                        name="edit_clinic_branch" required style="width: 100%;" readonly>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="scheduled_appdate">Scheduled Date & Time</label>
                                    <input class="form-control" type="text" id="scheduled_appdate"
                                        name="scheduled_appdate" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="rescheduledAppdate">Reschedule Date &
                                        Time</label><span class="text-danger">*</span>
                                    <input class="form-control" type="datetime-local" id="rescheduledAppdate"
                                        name="rescheduledAppdate" required>
                                    <div id="rescheduledAppdateError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_doctor">Doctor</label>
                                    <!-- <input type="text" class="form-control" id="edit_doctor" name="edit_doctor"
                                        required style="width: 100%;" readonly> -->
                                        <?php

                                            use App\Services\DoctorAvaialbilityService;

                                            $doctorAvailabilityService = new DoctorAvaialbilityService();
                                            $allDoctors = $doctorAvailabilityService->getAllDoctors();?>
                                            <select class="form-select" id="edit_doctor" name="edit_doctor" required
                                            data-placeholder="Select a Doctor" style="width: 100%;">
                                                <option value="">Select a doctor</option>
                                                @foreach ($allDoctors as $doctor)
                                                    <?php $doctorName = str_replace('<br>', ' ', $doctor->user->name); ?>
                                                    <option value="{{ $doctor->user_id }}">{{ $doctorName }}</option>
                                                @endforeach
                                            </select>
                                    <div id="editdoctorError" class="invalid-feedback"></div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="form-label" for="appdate">Reason for Rescheduling</label><span
                                    class="text-danger">*</span>
                                <textarea class="form-control" id="reschedule_reason" name="reschedule_reason" required></textarea>
                                <div id="rescheduleReasonError" class="invalid-feedback"></div>
                            </div>

                        </div>


                        <div class="row">
                            <div style="display:none" id="rescheduleExistingAppointmentsError">
                                <span class="text-danger">Already exists appointment for the selected time!</span>
                            </div>
                        </div>
                        <div class="row">
                            <div style="display:none" id="rescheduleExistingAppointments">
                            </div>
                        </div>
                        <div class="row">
                            <div style="display:none" id="rescheduleDoctorNotAvailable">
                                <span class="text-danger">Sorry, the doctor is not available at the selected time.
                                    Please choose another time.</span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="rescheduleBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    $(function() {
        var now = new Date().toISOString().slice(0, 16);
        document.getElementById('rescheduledAppdate').setAttribute('min', now);
        // Reset form and errors on modal close
        $('#modal-reschedule').on('hidden.bs.modal', function() {
            $('#rescheduleAppointmentForm').trigger('reset');
            $('#rescheduledAppdate').removeClass('is-invalid');
            $('#rescheduledAppdateError').text('');
            $('#reschedule_reason').removeClass('is-invalid');
            $('#rescheduleReasonError').text('');
            $('#editdoctorError').text('');
            $('#rescheduleDoctorNotAvailable').hide();
        });

        // Pre-populate form fields when modal opens for editing
        $('#rescheduleBtn').click(function() {
            // Reset previous error messages
            $('#rescheduledAppdateError').text('');
            $('#rescheduleReasonError').text('');
            // Validate form inputs

            var rescheduledAppdate = $('#rescheduledAppdate').val();
            var rescheduleReason = $('#reschedule_reason').val();
            var editDoctor = $('#edit_doctor').val();
            // Basic client-side validation (you can add more as needed)
            var isValid = true;
            if (rescheduledAppdate.length === 0) {
                $('#rescheduledAppdate').addClass('is-invalid');
                $('#rescheduledAppdateError').text('Rescheduled Appointment date is required.');
                // Prevent further execution
                isValid = false;
            } else {
                $('#rescheduledAppdate').removeClass('is-invalid');
                $('#rescheduledAppdateError').text('');
            }
            if (editDoctor.length === 0) {
                $('#edit_doctor').addClass('is-invalid');
                $('#editdoctorError').text('Doctor is required.');
                // Prevent further execution
                isValid = false;
            } else {
                $('#edit_doctor').removeClass('is-invalid');
                $('#editdoctorError').text('');
            }

            if (rescheduleReason.length === 0) {
                $('#reschedule_reason').addClass('is-invalid');
                $('#rescheduleReasonError').text('Reason for rescheduling is required.');
                // Prevent further execution
                isValid = false;
            } else {
                $('#reschedule_reason').removeClass('is-invalid');
                $('#rescheduleReasonError').text('');
            }

            if (isValid) {
                // If validation passed, submit the form via AJAX
                var form = $('#rescheduleAppointmentForm');
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
                        $('#modal-reschedule').modal('hide');
                        $('#successMessage').text('Appointment rescheduled successfully');
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                        // location.reload();
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        // Reset previous error messages
                        $('#rescheduledAppdateError').text('');

                        // Check if there are validation errors
                        var errors = xhr.responseJSON.errors;

                        // Check if there are validation errors
                        var errors = xhr.responseJSON.errors;
                        if (errors && errors.hasOwnProperty('app_date')) {
                            $('#rescheduledAppdate').addClass('is-invalid');
                            $('#rescheduledAppdateError').text(errors.appdate[0]);
                        } else {
                            // Handle specific error from backend
                            var errorMessage = xhr.responseJSON.error;
                            if (errorMessage) {
                                $('#rescheduleExistingAppointmentsError').show();
                            }
                        }
                    }
                });
            }
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
