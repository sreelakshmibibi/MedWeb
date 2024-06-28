<form id="form-delete" method="POST">
    @csrf
    <!-- delete modal-->
    <div class="modal fade" id="modal-delete-clinic" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="statusChange"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmText"></p>
                    <input type="hidden" id="delete_clinic_id" name="delete_clinic_id" value="">
                    <input type="hidden" id="delete_clinic_status" name="delete_clinic_status" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger float-end" data-bs-dismiss="modal"
                        id="btn-confirm-delete">Confirm</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
