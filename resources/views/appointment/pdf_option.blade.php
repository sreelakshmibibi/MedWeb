<!-- Modal -->
<div class="modal fade" id="modal-download" tabindex="-1" aria-labelledby="modalDownloadLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDownloadLabel">Request PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="pdfRequestForm" method="POST" action="{{ route('generate.pdf') }}">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="pdf_appointment_id" id="pdf_appointment_id">
                    <input type="hidden" name="pdf_app_parent_id" id="pdf_app_parent_id">
                    <input type="hidden" name="pdf_patient_id" id="pdf_patient_id">
                    <input type="hidden" name="pdf_tooth_id" id="pdf_tooth_id">

                    <div class="mb-3">
                        <label for="pdfType" class="form-label">Select PDF Type</label>
                        <select class="form-select" id="pdfType" name="pdf_type" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="appointment">Entire Appointment</option>
                            <option value="tooth">Specific Tooth</option>
                        </select>
                    </div>

                    <div id="toothSelection" class="mb-3 d-none">
                        <label for="toothIdSelect" class="form-label">Select Tooth(s)</label>
                        <select class="form-select" id="toothIdSelect" name="tooth_id[]" multiple>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="treatmentDownloadBtn">Generate PDF</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var pdfTeethRoute = "{{ route('fetch.teeth.details', ['patientId' => ':patientId', 'appId' => ':appId']) }}";
    // Show or hide the tooth selection based on the PDF type
    document.getElementById('pdfType').addEventListener('change', function() {
        var toothSelection = document.getElementById('toothSelection');
        if (this.value === 'tooth') {
            toothSelection.classList.remove('d-none');
            var patientId = document.getElementById('pdf_patient_id').value;
            var appId = document.getElementById('pdf_appointment_id').value;

            fetchTeethDetails(patientId, appId);
        } else {
            toothSelection.classList.add('d-none');
        }
    });

    // Function to fetch teeth details
    function fetchTeethDetails(patientId, appId) {

        $.ajax({
            url: pdfTeethRoute.replace(':patientId', patientId).replace(':appId', appId),
            type: "GET",
            dataType: "json",
            success: function(response) {
                var toothSelect = document.getElementById('toothIdSelect');
                toothSelect.innerHTML = ''; // Clear existing options

                // Check if response is an array and contains data
                if (Array.isArray(response) && response.length) {
                    response.forEach(teeth => {
                        var option = document.createElement('option');
                        option.value = teeth.teeth_id;
                        option.text = teeth.teeth_name;
                        toothSelect.appendChild(option);
                    });
                } else {
                    var option = document.createElement('option');
                    option.value = '';
                    option.text = 'No teeth details available';
                    toothSelect.appendChild(option);
                }
            },
            error: function(xhr) {
                console.error("Error fetching teeth details:", xhr);
            },
        });
    }

    $('#pdfRequestForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var form = $(this);
        var formData = form.serialize(); // Serialize form data

        $.ajax({
            url: '{{ route('generate.pdf') }}',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            xhrFields: {
                responseType: 'blob' // Specify response type as blob for file download
            },
            success: function(response) {
                var url = window.URL.createObjectURL(response);
                var a = document.createElement('a');
                a.href = url;
                a.download = 'appointment_details.pdf'; // Default file name
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            },
            error: function(xhr) {
                console.error('Error generating PDF:', xhr);
            }
        });
    });
</script>
