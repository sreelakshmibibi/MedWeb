<form method="post" action="{{ route('settings.treatment_cost.store') }}">
    @csrf
    <div class="modal modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog" style="width:40%; max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"> </i> Treatment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="form-group">
                            <label class="form-label" for="treatment">Treatment name</label>
                            <input class="form-control" type="text" id="edit_treatment" name="treatment" required
                                minlength="3" placeholder="Treatment Name">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cost">Cost</label>
                            <input class="form-control" type="text" id="cost" name="cost" required
                                minlength="3" placeholder="Treatment Cost">
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <input name="status" type="radio" checked class="form-control with-gap" id="yes"
                                value="Y">
                            <label for="yes">Yes</label>
                            <input name="status" type="radio" class="form-control with-gap" id="no"
                                value="N">
                            <label for="no">No</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success float-end" id="buttonalert">Save</button>
                </div>

            </div>

        </div>
    </div>
</form>
