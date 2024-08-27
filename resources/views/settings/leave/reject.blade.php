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
                    <textarea class="form-control" id="reject_reason" name="reject_reason" placeholder="Reason for leave rejection"
                        required></textarea>
                    <div id="rejectionError" class="invalid-feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger float-end" id="btn-confirm-reject">Reject</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>

<script>
    $(function() {
        // Reset form and errors on modal close
        $('#modal-reject').on('hidden.bs.modal', function() {
            $('#form-approve')[0].reset();
            $('#reject_reason').val('');
            $('#reject_reason').removeClass('is-invalid');
            $('#rejectionError').text('');
        });
    });
</script>
