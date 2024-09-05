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
                        <input name="billable" type="radio"  class="form-control with-gap" id="yes"
                            value="Y">
                        <label for="yes">Yes</label>
                        <input name="billable" type="radio"checked class="form-control with-gap" id="no"
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
