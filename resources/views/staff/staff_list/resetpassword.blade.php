<form action="{{ url('') }}" method="POST">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-resetpassword" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-key"></i> Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="form-group">
                            <label class="form-label" for="email">Email ID</label>
                            <input class="form-control" type="text" id="email" name="email"
                                placeholder="Email ID">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cpassword">Current Password <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="password" id="cpassword" name="cpassword"
                                placeholder="Current Password">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="newpassword">New Password <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="password" id="newpassword" name="newpassword"
                                placeholder="New Password">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="retypepassword">Retype New Password <span
                                    class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="retypepassword" name="retypepassword"
                                placeholder="Retype New Password">
                        </div>

                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success float-end">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
