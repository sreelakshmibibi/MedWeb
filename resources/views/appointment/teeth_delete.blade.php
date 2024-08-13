<form id="form-delete" method="POST">
    @csrf
    @method('DELETE')
    <!-- delete modal-->
    <div class="modal fade" id="modal-delete" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the teeth details?</p>
                    <input type="hidden" id="delete_tooth_exam_id" name="delete_tooth_exam_id" value="">
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
<script>
    $('#btn-confirm-delete').click(function() {
        var tootExamId = $('#delete_tooth_exam_id').val();
        var url = "{{ route('treatment.destroy', [':toothExamId']) }}";
        url = url.replace(':toothExamId', tootExamId);
        $.ajax({
            type: 'DELETE',
            url: url,
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                if (historyStepAdded == true) {
                    getDentalTable(3);
                } else {
                    getDentalTable(2);
                }
                $('#successMessage').text('Teeth exam details deleted successfully');
                $('#successMessage').fadeIn().delay(3000)
                    .fadeOut(); // Show for 3 seconds
                //table.draw();

            },
            error: function(xhr) {
                $('#modal-delete').modal('hide');
                swal("Error!", xhr.responseJSON.message, "error");
            }
        });
    });
</script>
