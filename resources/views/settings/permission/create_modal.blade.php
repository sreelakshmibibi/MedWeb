<form action="{{ url('permissions') }}" method="POST">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-createpermission" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-user"></i> Create Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="form-group">
                            <label class="form-label" for="permissionname">Permission Name <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="permissionname" name="name"
                                placeholder="Permission Name">
                            <div id="permissionError" class="invalid-feedback"></div>
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
