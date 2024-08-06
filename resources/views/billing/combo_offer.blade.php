<div class="modal fade modal-right slideInRight" id="modal-combo" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable h-p100">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-tags"></i> Combo Offers
                </h5>
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
                            <tbody id="tablebody">
    @foreach ($combOffers as $id => $details)
        <tr>
            <td class="text-start">
                <input type="checkbox" id="combo_checkbox_{{ $id }}"
                       name="combo_checkbox[]" class="filled-in chk-col-success"
                       value="{{ $id }}" />
                <label for="combo_checkbox_{{ $id }}">{{ $details['treatment'] }}</label>
            </td>
            <td class="text-center">{{ $clinicBasicDetails->currency}}{{ $details['cost'] }}</td>
            <td class="text-center">{{ $clinicBasicDetails->currency}}{{ $details['offer'] }}</td>
        </tr>
    @endforeach
</tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer modal-footer-uniform">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success float-end" id="comboBtn">Save</button>
            </div>
        </div>
    </div>
</div>
