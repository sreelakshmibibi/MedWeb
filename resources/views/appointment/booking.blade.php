<form id="bookingForm" method="post" action="{{ route('appointment.update') }}">
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
                                    <label class="form-label" for="patient_id">Patient ID</label>
                                    <input class="form-control" type="text" id="edit_patient_id" name="patient_id"
                                        placeholder="Patient ID" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="patient_name">Patient Name</label>
                                    <input class="form-control" type="text" id="edit_patient_name"
                                        name="patient_name" placeholder="Patient name" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="doctor">Doctor</label>
                                    <select class="select2" id="doctor_id" name="doctor_id" required
                                        data-placeholder="Select a Doctor" style="width: 100%;">
                                        <option>select a doctor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="clinic_branch_id">Branch</label>
                                    <select class="select2" id="clinic_branch_id" name="clinic_branch_id" required
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
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="appdate">Booking Date & Time</label>
                                    <input class="form-control" type="datetime-local" id="appdate" name="appdate"
                                        required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="appstatus">Appointment Status</label>
                                    <select class="form-select" id="appstatus" name="appstatus" required>
                                        <option value="">Select Status</option>
                                        <option value="W">Waiting</option>
                                        <option value="S">Success</option>
                                    </select>
                                </div>
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
        $('#modal-reschedule').on('hidden.bs.modal', function() {
            $('#rescheduleAppointmentForm').trigger('reset');
            $('#reschedule_app').removeClass('is-invalid');
            $('#reschedule_app').next('.invalid-feedback').text('');
            $('#statusError').text('');
        });

        // Pre-populate form fields when modal opens for editing
        $('#modal-reschedule').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var appId = button.data('id'); // Extract app ID from data-id attribute

            // Fetch app details via AJAX
            $.ajax({
                url: '{{ url("app") }}' + "/" + appId + "/edit",
                method: 'GET',
                success: function(response) {
                    // Populate form fields
                    $('#reschedule_app_id').val(response.id);
                    $('#reschedule_app').val(response.app);

                    
                },
                error: function(error) {
                    console.log(error);
                }
            });
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
