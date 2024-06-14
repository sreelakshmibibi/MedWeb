 <!-- modal -->
 <div class="modal center-modal fade" id="modal-center" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Department Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form" method="post" action="{{ route('settings.department.store')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="box">
                                <!-- /.box-header -->
                                
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label class="form-label" for="name">Department</label>
                                            <input class="form-control" type="text" id="name" name="name"
                                                placeholder="Department Name">
                                        </div>

                                        <div class="form-group mt-2">
                                            <label class="form-label col-md-6" for="branch">Active</label>
                                            <input name="status" type="radio" checked class="form-control with-gap" id="yes">
                                            <label for="yes">Yes</label>
                                            <input name="status" type="radio" class="form-control with-gap" id="no">
                                            <label for="no">No</label>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                            
                            </div>
                        </div>
                    </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success float-end">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
