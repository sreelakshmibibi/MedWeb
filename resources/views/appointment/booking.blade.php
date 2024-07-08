<form id="bookingForm" method="post" action="{{ route('appointment.store') }}">
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
                                    <label class="form-label" for="appdate">Booking Date & Time</label><span class="text-danger">
                                    *</span>
                                    <input class="form-control" type="datetime-local" id="appdate" name="appdate"
                                        required>
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
                            <div class="col-md-6" style="display:none" id="existingAppointments">
                               
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
            
            // Validate form inputs
            var clinicId = $('#clinic_branch_id').val();
            var doctorId = $('#doctor_id').val();
            var appDate = $('#appdate').val();
            var patientId = $('#patientId').val();
            var patientName = $('#patient_name').val();
            
            
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
                $('#appdateError').text('Appointment date is required.');
                return; // Prevent further execution
            } else {
                $('#appdate').removeClass('is-invalid');
                $('#appdateError').text('');
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
                    $('#modal-booking').modal('hide');
                    $('#successMessage').text('New Appointment added successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                    // location.reload();
                    table.ajax.reload();
                },
                error: function(xhr) {
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;

                    if (errors.hasOwnProperty('department')) {
                        $('#department').addClass('is-invalid');
                        $('#departmentError').text(errors.department[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#statusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-booking').on('hidden.bs.modal', function() {
            $('#createDepartmentForm').trigger('reset');
            $('#department').removeClass('is-invalid');
            $('#departmentError').text('');
            $('#statusError').text('');
        });
    });


        // Pre-populate form fields when modal opens for editing
       

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
