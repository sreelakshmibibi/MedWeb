<form id="form-approve" method="POST">
    @csrf
    <!-- status modal-->
    <div class="modal fade" id="modal-reject" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Leave Rejection</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Reject leave request?</p>
                    <input type="hidden" id="reject_leave_id" name="reject_leave_id" value="">
                    <textarea  class="form-control" id="reject_reason" name="reject_reason" placeholder="Reason for leave rejection" required></textarea>
                    <div id="rejectionError" class="invalid-feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger float-end" data-bs-dismiss="modal"
                        id="btn-confirm-reject">Reject</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
