<form id="form-reorder" method="POST">
    @csrf
    <!-- delete modal-->
    <div class="modal fade modal-right slideInRight" id="modal-reorder" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Repeat Order</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="patient_id">Patient ID</label><span
                                    class="text-danger">
                                    *</span>
                                <input class="form-control" type="text" id="patient_id" name="patient_id"
                                    placeholder="Patient ID" readonly>
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="patient_name">Patient Name</label><span
                                    class="text-danger">
                                    *</span>
                                <input class="form-control" type="text" id="patient_name" name="patient_name"
                                    placeholder="Patient name" readonly>
                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="treatment_plan_id">Treatment Plan</label><span
                                    class="text-danger" readonly>
                                    *</span>
                                <select class="form-select" id="treatment_plan_id" name="treatment_plan_id">
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}">{{ $plan->plan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="shade_id">Shade</label><span
                                    class="text-danger">
                                    *</span>
                                    <select class="form-select" id="shade_id"
                                        name="shade_id" readonly>
                                        @foreach ($shades as $shade)
                                            <option value="<?= $shade->id ?>"><?= $shade->shade_name ?>
                                            </option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="upper_shade">Upper Shade</label>
                                <input type="text" class="form-control"  id="upper_shade"
                                    name="upper_shade">
                                <div id="upperShadeError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="middle_shade">Middle Shade</label>
                                <input type="text" class="form-control"  id="middle_shade"
                                    name="middle_shade">
                                <div id="middleShadeError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="lower_shade">Lower Shade</label>
                                <input type="text" class="form-control" id="lower_shade"
                                    name="lower_shade">
                                <div id="lowerShadeError" class="invalid-feedback"></div>
                            </div>
                        </div>
                            
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="metal_trial">Metal Trial</label>
                                <input type="text" class="form-control" id="metal_trial"
                                    name="metal_trial">
                                <div id="metalTrialError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="bisq_trial">Bisq trial</label>
                                <input type="text" class="form-control" id="bisq_trial"
                                    name="bisq_trial">
                                <div id="bisqTrialError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="finish">Finish</label>
                                <input type="text" class="form-control" id="finish"
                                    name="finish">
                                <div id="finishError" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="order_date">Repeat Order Placed On</label><span
                                    class="text-danger">
                                    *</span>
                                <input type="datetime-local" class="form-control" id="order_date" name="order_date"
                                                value="{{ date('Y-m-d') }}" required> 
                                <div id="orderDateError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="delivery_expected">Delivery Expected</label><span
                                    class="text-danger">
                                    *</span>
                                <input type="datetime-local" class="form-control" id="delivery_expected" name="delivery_expected"
                                                value="{{ date('Y-m-d') }}" required>   
                                <div id="deliveryExpectedError" class="invalid-feedback"></div> 
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group mt-2">
                        <label class="form-label col-md-6">Instructions</label>
                        <textarea  class="form-control" id="instructions" name="instructions" placeholder="Instructions" required readonly></textarea>
                    </div>
                    
                    <div class="form-group mt-2">
                        <label class="form-label col-md-6">Reason</label>
                        <input type="hidden" id="repeat_order_id" name="repeat_order_id" value="">
                        <textarea  class="form-control" id="repeat_reason" name="repeat_reason" placeholder="Reason for repeat" required></textarea>
                        <div id="repeatReasonError" class="invalid-feedback"></div>
                    </div>
                    
                    
                    
                    <div class="form-group mt-2">
                        <label class="form-label col-md-6">Is Billable?</label>
                        <input name="billable" type="radio" id="billable" class="form-control with-gap" id="yes"
                            value="Y">
                        <label for="yes">Yes</label>
                        <input name="billable" id="billable" type="radio"checked class="form-control with-gap" id="no"
                            value="N">
                        <label for="no">No</label>
                        <div id="billableError" class="invalid-feedback"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger float-end"
                        id="btn-confirm-reorder">Repeat</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
