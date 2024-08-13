<div class="modal fade modal-right slideInRight" id="modal-combo" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable h-p100">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-tags"></i> Combo Offers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table
                            class="table table-sm table-bordered table-hover table-striped mb-0 text-center b-1 border-dark">
                            <thead class="bg-dark">
                                <tr>
                                    <th class="text-start">Combo</th>
                                    <th class="text-center">Cost</th>
                                    <th class="text-end">Offer Rate</th>
                                </tr>
                            </thead>
                            <tbody id="combotablebody">
                                @foreach ($combOffers as $id => $details)
                                    <tr>
                                        <td class="text-start">
                                            <input type="checkbox" id="combo_offer_{{ $id }}"
                                                name="combo_offer" class="filled-in chk-col-success"
                                                value="{{ $id }}" data-offer="{{ $details['offer'] }}"
                                                @if ($details['selected'] == 1) checked @endif>
                                            <label
                                                for="combo_offer_{{ $id }}">{{ $details['treatment'] }}</label>
                                        </td>
                                        <td class="text-center">
                                            {{ $clinicBasicDetails->currency }}{{ $details['cost'] }}</td>
                                        <td class="text-center">
                                            {{ $clinicBasicDetails->currency }}{{ $details['offer'] }}

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer modal-footer-uniform">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success float-end" id="comboBtn">Apply</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('input[name="combo_offer"]');
        const comboBtn = document.getElementById('comboBtn');
        const appointmentIdInput = document.getElementById('appointmentId');

        // Handle apply button click
        comboBtn.addEventListener('click', function() {
            let selectedCombos = null;

            // Collect selected combo offers
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedCombos = checkbox.value;

                }
            });

            // Get the appointment ID
            const appointmentId = appointmentIdInput.value;

            // Make AJAX request
            fetch(`{{ route('billing.combo', ['appointmentId' => '__appointmentId__']) }}`.replace(
                    '__appointmentId__', appointmentId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content') // For Laravel CSRF protection
                    },
                    body: JSON.stringify({
                        combos: selectedCombos
                    })
                })
                .then(response => {
                    if (response) {

                        // Reload the page upon successful request
                        location.reload();
                    } else {
                        alert('Failed to update combo offers.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating combo offers.');
                });

        });
    });
</script>
