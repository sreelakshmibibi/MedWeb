<form id="rescheduleAppointmentForm" method="post" action="{{ route('appointment.update') }}">
    @csrf
    <input type="hidden" id="reschedule_app_id" name="reschedule_app_id" value="">
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
                                    <input class="form-control" type="text" id="edit_patient_id" name="edit_patient_id"
                                           placeholder="Patient ID" readonly>
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
                                    <label class="form-label" for="doctor">Doctor</label>
                                    <input type="text" class="form-control" id="edit_doctor_id" name="edit_doctor_id" required
                                             style="width: 100%;" readonly>
                                        
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="clinic_branch_id">Branch</label>
                                    <input type="text" class="form-control" id="edit_clinic_branch_id" name="edit_clinic_branch_id" required
                                            style="width: 100%;" readonly>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="scheduled_appdate">Scheduled Date & Time</label>
                                    <input class="form-control" type="text" id="scheduled_appdate" name="scheduled_appdate"
                                           required readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="rescheduledAppdate">Reschedule Date & Time</label><span class="text-danger">*</span>
                                    <input class="form-control" type="datetime-local" id="rescheduledAppdate" name="rescheduledAppdate"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="form-label" for="appdate">Reason for Rescheduling</label><span class="text-danger">*</span>
                                <textarea class="form-control" type="datetime-local" id="reschedule_status_change_reason" name="reschedule_status_change_reason"
                                        required></textarea>
                                <div id="rescheduleReasonError" class="invalid-feedback"></div>
                            </div>
                            
                        </div>

                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="updateDepartmentBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    $(function() {


        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#rescheduleAppointmentForm').trigger('reset');
            $('#reschedule_app').removeClass('is-invalid');
            $('#reschedule_app').next('.invalid-feedback').text('');
            $('#statusError').text('');
        });

        // Pre-populate form fields when modal opens for editing

       
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
