<form id="createLeaveForm" method="post" action="{{ route('settings.department.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Leave Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessagecreate" style="display:none;" class="alert alert-danger">
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="staffid">Staff ID</label>
                                    <input class="form-control" type="text" id="staffid" name="staffid"
                                        placeholder="staffid" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="staff_name" class="form-label">Name <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" id="staff_name" name="staff_name">
                                        <option value="" disabled selected>Select type</option>
                                        <option value="1">Name1</option>
                                        <option value="2">Name2</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="leave_type">Leave Type <span class="text-danger">
                                    *</span></label>
                            <select class="form-control" id="leave_type" name="leave_type">
                                <option value="" disabled selected>Select type</option>
                                <option value="1">Casual Leave</option>
                                <option value="2">Medical Leave</option>
                                <option value="3">Loss of Pay</option>
                                <option value="4">Other</option>
                            </select>
                            <div id="leaveTypeError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_from" class="form-label">From <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="leave_from" name="leave_from"
                                        placeholder="Leave From Date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>">
                                    <div id="leaveFromError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_to" class="form-label">To <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="leave_to" name="leave_to"
                                        placeholder="Leave To Date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>">
                                    <div id="leaveToError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="total_days">Total Days</label>
                                    <input class="form-control" type="text" id="total_days" name="total_days"
                                        placeholder="number of days" readonly>
                                    <div id="daysError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="remaining">Remaining Leaves</label>
                                    <input class="form-control" type="text" id="remaining" name="remaining"
                                        placeholder="remaining leaves" readonly>
                                    <div id="remainingError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="reason">Reason</label>
                            <textarea class="form-control" id="reason" name="reason" placeholder="Leave Reason"></textarea>
                            <div id="reasonError" class="invalid-feedback"></div>
                        </div>

                        <!-- Status -->
                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <input name="status" type="radio" checked class="form-control with-gap"
                                id="yes" value="Y">
                            <label for="yes">Yes</label>
                            <input name="status" type="radio" class="form-control with-gap" id="no"
                                value="N">
                            <label for="no">No</label>
                            <div id="statusError" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="saveLeaveBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
