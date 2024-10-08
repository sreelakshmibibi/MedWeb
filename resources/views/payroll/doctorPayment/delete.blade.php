<form id="form-delete" method="POST">
    @csrf
    @method('DELETE')
    <!-- delete modal-->
    <div class="modal fade" id="modal-doctorPayment-delete" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel the Payment Done Record?</p>
                    <input type="hidden" id="delete_doctorPayment_id" name="delete_doctorPayment_id" value="">
                    <textarea  class="form-control" id="deleteReason" name="deleteReason" placeholder="Reason for delete?" required></textarea>
                    <div id="deleteReasonError" class="invalid-feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger float-end" data-bs-dismiss="modal"
                        id="btn-confirm-delete">Delete</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
