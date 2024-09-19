<form id="editExpenseForm" method="post" action="{{ route('expense.expense.update') }}">
    @csrf
    <input type="hidden" id="edit_expense_id" name="edit_expense_id" value="">
    <div class="modal fade modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Edit Expense Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessage" style="display:none;" class="alert alert-danger">
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- Name -->
                        <div class="form-group">
                            <label class="form-label" for="edit_name">Expense Name <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="edit_name" name="name"
                                placeholder="Expense Name">
                            <div id="edit_nameError" class="invalid-feedback"></div>
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label class="form-label" for="edit_category">Category <span class="text-danger">
                                    *</span></label>
                            <select class="form-select category_select" id="edit_category" name="category">
                                <option value="">Select a Category</option>
                                {{-- <option value="1">food</option>
                                <option value="2">water</option> --}}
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category }}</option>
                                @endforeach
                            </select>
                            <div id="edit_categoryError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 ">

                                <!-- Amount -->
                                <div class="form-group">
                                    <label class="form-label" for="edit_amount">Amount <span class="text-danger">
                                            *</span></label>
                                    <input class="form-control" type="text" id="edit_amount" name="amount"
                                        placeholder="amount">
                                    <div id="edit_amountError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <!-- bill date -->
                                <div class="form-group">
                                    <label class="form-label" for="edit_billdate">Bill Date <span class="text-danger">
                                            *</span></label>
                                    <input class="form-control" type="date" id="edit_billdate" name="billdate"
                                        placeholder="bill date" value="<?php echo date('Y-m-d'); ?>">
                                    <div id="edit_billdateError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label" for="edit_billfile">Attach Bill</label>
                                <button type="button" id="uploadedBills" style="display:none;"
                                    class="waves-effect waves-light btn btn-circle btn-secondary btn-xs"
                                    title="download bills"><i class="fa-solid fa-download"></i></button>
                            </div>
                            <input type="file" class="form-control" id="edit_billfile" type="file"
                                name="billfile[]" multiple>
                            <div id="edit_billfileError" class="invalid-feedback"></div>
                        </div>

                        <!-- Status -->
                        <div class="form-group mt-3">
                            <label class="form-label col-md-6">Active</label>
                            <input name="status" type="radio" checked class="form-control with-gap" id="edit_yes"
                                value="Y">
                            <label for="edit_yes">Yes</label>
                            <input name="status" type="radio" class="form-control with-gap" id="edit_no"
                                value="N">
                            <label for="edit_no">No</label>
                            <div id="edit_statusError" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="updateExpenseBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        $('#updateExpenseBtn').click(function() {
            $('#errorMessage').hide();

            const formValues = {
                name: $('#edit_name').val(),
                amount: $('#edit_amount').val(),
                category: $('#edit_category').val(),
                date: $('#edit_billdate').val(),
                status: $('input[name="status"]:checked').val()
            };

            let isValid = true;

            // Validate inputs
            for (const [key, value] of Object.entries(formValues)) {
                if (!value) {
                    showError(`edit_${key}`,
                        `${key.charAt(0).toUpperCase() + key.slice(1)} is required.`);
                    isValid = false;
                } else {
                    resetError(`edit_${key}`);
                }
            }

            if (!isValid) return;

            // Submit the form via AJAX
            const formData = new FormData($('#editExpenseForm')[0]);

            $.ajax({
                type: 'POST',
                url: $(this).closest('form').attr('action'),
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    $('#modal-edit').modal('hide');
                    if (response.success) {
                        $('#successMessage').text(response.success).fadeIn().delay(3000)
                            .fadeOut();
                        table.ajax.reload();
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        for (const key in errors) {
                            showError(`edit_${key}`, errors[key][0]);
                        }
                    }
                }
            });
        });

        // Download bills when button is clicked
        $('#uploadedBills').click(function() {
            var expenseId = $('#edit_expense_id').val();
            window.location.href = '{{ url('clinicExpense') }}' + "/" + expenseId + "/download-bills";
        });

        // Reset form and errors on modal close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $('#editExpenseForm').trigger('reset');
            ['edit_name', 'edit_amount', 'edit_category', 'edit_billdate', 'edit_billfile'].forEach(
                resetError);
            $('#errorMessage').hide();
        });
    });
</script>
