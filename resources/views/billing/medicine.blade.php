<div class="modal fade modal-right slideInRight" id="modal-medicine" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable h-p100">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-tablet"></i> Medicine
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
                                    <th style="width:50%;" class="text-start">Medicine</th>
                                    <th style="width:30%;">Duration</th>
                                    <th style="width:5%;" class="text-end">Status</th>
                                    <th style="width:15%;" class="text-end">Rate</th>
                                </tr>
                            </thead>
                            <tbody id="tablebody">
                                <tr>
                                    <td class="text-start"><input type="checkbox" id="medicine_checkbox1"
                                            name="medicine_checkbox1" class="filled-in chk-col-success" />
                                        <label for="medicine_checkbox1">
                                            medicine1</label>
                                    </td>
                                    <td>
                                        <div class="input-group col-12">
                                            <input type="number" class="form-control text-center" id="duration"
                                                name="prescriptions[][duration]" aria-describedby="basic-addon2"
                                                required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">days</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>in stock</td>
                                    <td class="text-end">&#8377; 10</td>
                                </tr>
                                <tr>
                                    <td class="text-start"><input type="checkbox" id="medicine_checkbox2"
                                            name="medicine_checkbox2" class="filled-in chk-col-success" />
                                        <label for="medicine_checkbox2">
                                            medicine2</label>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control text-center" id="duration"
                                                name="prescriptions[][duration]" aria-describedby="basic-addon2"
                                                required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">days</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>out off stock</td>
                                    <td class="text-end">&#8377; 1000</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th>&#8377; 1000</th>
                                </tr>
                            </tbody>
                        </table>
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
