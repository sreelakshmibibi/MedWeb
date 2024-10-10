<form id="form-cancell" method="POST">
    @csrf
    <!-- delete modal-->
    <div class="modal fade" id="modal-cancell" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cancel</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel the order?</p>
                    <input type="hidden" id="cancel_order_id" name="cancel_order_id" value="">
                    <textarea  class="form-control" id="order_cancel_reason" name="order_cancel_reason" placeholder="Reason for cancellation" required></textarea>
                    <div id="reasonError" class="invalid-feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger float-end"
                        id="btn-cancel-order">Yes, Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
