<form id="editExpenseCategoryForm" method="post" action="{{ route('expenseCategory.update') }}">
    @csrf
    <input type="hidden" id="edit_category_id" name="edit_category_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Edit Expense Category Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessage" style="display:none;" class="alert alert-danger">
                </div>
                
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="edit_category">Expense Category Name <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="edit_category" name="category" required
                                minlength="3" placeholder="Expense Category Name" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <div>
                                <input name="status" type="radio" class="form-control with-gap" id="edit_yes"
                                    value="Y">
                                <label class="form-check-label" for="edit_yes">Yes</label>
                                <input name="status" type="radio" class="form-control with-gap" id="edit_no"
                                    value="N">
                                <label class="form-check-label" for="edit_no">No</label>
                            </div>
                            <div class="text-danger" id="statusError"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="updateExpenseCategoryBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        // Handle Update button click
        $('#updateExpenseCategoryBtn').click(function() {
            // Reset previous error messages
            $('#edit_category').removeClass('is-invalid');
            $('#edit_category').next('.invalid-feedback').text('');
            $('#statusError').text('');

            var category = $('#edit_category').val();
            var status = $('input[name="status"]:checked').val();
            if (category.length === 0) {
                $('#edit_category').addClass('is-invalid');
                $('#edit_category').next('.invalid-feedback').text('Category name is required.');
                return; 
            }

            // If validation passed, submit the form via AJAX
            var form = $('#editExpenseCategoryForm');
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // If successful, hide modal and show success message
                    $('#modal-edit').modal('hide');
                     if (response.success) {
                        $('#successMessage').text(response.success);
                        $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                    }
                    
                    // location.reload(); // Refresh the page or update the table as needed
                    table.ajax.reload();
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON.error);
                    if (xhr.responseJSON.error) {
                        $('#errorMessage').text(xhr.responseJSON.error);
                        $('#errorMessage').fadeIn().delay(3000)
                        .fadeOut(); 
                    }
                    // If error, update modal to show errors
                    var errors = xhr.responseJSON.errors;
                   
                    if (errors.hasOwnProperty('category')) {
                        $('#edit_category').addClass('is-invalid');
                        $('#edit_category').next('.invalid-feedback').text(errors
                            .department[0]);
                    }

                    if (errors.hasOwnProperty('status')) {
                        $('#statusError').text(errors.status[0]);
                    }
                }
            });
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editExpenseCategoryForm').trigger('reset');
            $('#edit_category').removeClass('is-invalid');
            $('#edit_category').next('.invalid-feedback').text('');
            $('#statusError').text('');
        });

        // Pre-populate form fields when modal opens for editing
        $('#modal-edit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); 
            var categoryId = button.data('id'); 

            // Fetch department details via AJAX
            $.ajax({
                url: '{{ url('expenseCategory') }}' + "/" + categoryId + "/edit",
                method: 'GET',
                success: function(response) {
                    // Populate form fields
                    $('#edit_category_id').val(response.id);
                    $('#edit_category').val(response.category);

                    // Set radio button status
                    if (response.status === 'Y') {
                        $('#edit_yes').prop('checked', true);
                    } else {
                        $('#edit_no').prop('checked', true);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>
