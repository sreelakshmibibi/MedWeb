<form action="{{ url('roles') }}" method="POST">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-createrole" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-user"></i> Create Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="form-group">
                            <label class="form-label" for="rolename">Role Name <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="rolename" name="name"
                                placeholder="Role Name">
                            <div id="roleError" class="invalid-feedback"></div>
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
