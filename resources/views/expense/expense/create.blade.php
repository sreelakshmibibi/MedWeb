<form id="createExpenseForm" method="post" action="{{ route('expense.expense.store') }}">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"></i> Expense Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessagecreate" style="display:none;" class="alert alert-danger">
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- Name -->
                        <div class="form-group">
                            <label class="form-label" for="name">Expense Name <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="name" name="name"
                                placeholder="Expense Name">
                            <div id="nameError" class="invalid-feedback"></div>
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label class="form-label" for="category">Category <span class="text-danger">
                                    *</span></label>
                            <select class="form-select category_select" id="category" name="category">
                                <option value="">Select a Category</option>
                                {{-- <option value="1">food</option>
                                <option value="2">water</option> --}}
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category }}</option>
                                @endforeach
                            </select>
                            <div id="categoryError" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 ">
                                <!-- Amount -->
                                <div class="form-group">
                                    <label class="form-label" for="amount">Amount <span class="text-danger">
                                            *</span></label>
                                    <input class="form-control" type="text" id="amount" name="amount"
                                        placeholder="amount">
                                    <div id="amountError" class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col-md-6 ">
                                <!-- bill date -->
                                <div class="form-group">
                                    <label class="form-label" for="billdate">Bill Date <span class="text-danger">
                                            *</span></label>
                                    <input class="form-control" type="date" id="billdate" name="billdate"
                                        placeholder="bill date" value="<?php echo date('Y-m-d'); ?>">
                                    <div id="billdateError" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label" for="billfile">Attach Bill</label>
                            </div>
                            <input type="file" class="form-control" id="billfile" type="file" name="billfile[]"
                                multiple>
                            <div id="billfileError" class="invalid-feedback"></div>
                        </div>

                        <!-- Status -->
                        <div class="form-group mt-3">
                            <label class="form-label col-md-6">Active</label>
                            <input name="status" type="radio" checked class="form-control with-gap" id="yes"
                                value="Y">
                            <label for="yes">Yes</label>
                            <input name="status" type="radio" class="form-control with-gap" id="no"
                                value="N">
                            <label for="no">No</label>
                            <div id="statusError" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end" id="saveExpenseBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        $('#saveExpenseBtn').click(function() {
            $('#errorMessagecreate').hide();

            const formValues = {
                name: $('#name').val(),
                amount: $('#amount').val(),
                category: $('#category').val(),
                date: $('#billdate').val(),
                status: $('input[name="status"]:checked').val()
            };

            let isValid = true;

            // Validate inputs
            for (const [key, value] of Object.entries(formValues)) {
                if (!value) {
                    showError(key, `${key.charAt(0).toUpperCase() + key.slice(1)} is required.`);
                    isValid = false;
                } else {
                    resetError(key);
                }
            }

            if (!isValid) return;

            // Submit the form via AJAX
            const formData = new FormData($('#createExpenseForm')[0]);

            $.ajax({
                type: 'POST',
                url: $(this).closest('form').attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#successMessage').text(response.success).fadeIn().delay(3000)
                            .fadeOut();
                        $('#modal-right').modal('hide');
                        table.ajax.reload();
                    } else if (response.error) {
                        $('#errorMessagecreate').text(response.error).fadeIn().delay(3000)
                            .fadeOut();
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        for (const key in errors) {
                            showError(key, errors[key][0]);
                        }
                    }
                }
            });
        });

        $('#modal-right').on('hidden.bs.modal', function() {
            $('#createExpenseForm').trigger('reset');
            ['name', 'amount', 'category', 'billdate', 'billfile'].forEach(resetError);
            $('#errorMessagecreate').hide();
        });
    });
</script>
