<form id="form-delivered" method="POST">
    @csrf
    <!-- delete modal-->
    <div class="modal fade" id="modal-deliver" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delivered</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure the order is delivered?</p>
                    <input type="hidden" id="order_id" name="order_id" value="">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger float-end"
                        id="btn-confirm-deliver">Yes, Delivered</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
