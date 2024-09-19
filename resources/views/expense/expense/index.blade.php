@extends('layouts.dashboard')
@section('title', 'Expenses')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">
                </div>
                @if (session('success'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-success alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('success') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                @if (session('error'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('error') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Expense Details</h3>

                    <button type="button" class="waves-effect waves-light btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-right" title="Add New">
                        <span class="hidden-sm-up">Add New</span>
                        <span class="hidden-xs-down"><i class="fa fa-add"></i> Add New</span>
                    </button>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table width="100%" class="table table-bordered table-hover table-striped mb-0 data-table">
                                <thead class="bg-primary-light text-center">
                                    <tr>
                                        <th width="10px">No</th>
                                        <th width="120px">Bill Date</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Category</th>
                                        <th width="20px">Status</th>
                                        <th width="120px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populate table rows with department data -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->

    @include('expense.expense.create')
    @include('expense.expense.edit')
    @include('expense.expense.delete')

    <script type="text/javascript">
        var expenseUrl = "{{ route('clinicExpense') }}";
        var table; // Define table variable in the global scope

        jQuery(function($) {
            // Check if DataTable is already initialized
            if ($.fn.DataTable.isDataTable(".data-table")) {
                // Destroy existing DataTable instance
                $(".data-table").DataTable().destroy();
            }
            table = $(".data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: expenseUrl,
                    type: "GET",
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            // Return the row index (starts from 0)
                            return meta.row + 1; // Adding 1 to start counting from 1
                        },
                    },
                    {
                        data: "billdate",
                        name: "billdate",
                        className: "text-center",
                        render: function(data) {
                            return moment(data).format('DD-MM-YYYY'); // Format using Moment.js
                        }
                    },
                    {
                        data: "name",
                        name: "name",
                        className: "text-left",
                    },
                    {
                        data: "amount",
                        name: "amount",
                        className: "text-center",
                    },
                    {
                        data: "category_name",
                        name: "category_name",
                        className: "text-center",
                    },
                    {
                        data: "status",
                        name: "status",
                        className: "text-center",
                    },
                    {
                        data: "action",
                        name: "action",
                        className: "text-center",
                        orderable: false,
                        searchable: true,
                    },
                ],
            });

        });

        const resetError = (elementId) => {
            $(`#${elementId}`).removeClass('is-invalid').next('.invalid-feedback').text('');
        };

        const showError = (elementId, message) => {
            $(`#${elementId}`).addClass('is-invalid').next('.invalid-feedback').text(message);
        };

        // Download bills when button is clicked
        $(document).on('click', '.downloadBills', function() {
            var expenseId = $(this).data('id');
            alert(expenseId)
            window.location.href = '{{ url('clinicExpense') }}' + "/" + expenseId + "/download-bills";
        });

        $(document).on('click', '.btn-edit', function() {
            $('#uploadedBills').hide();
            var expenseId = $(this).data('id');
            $('#edit_expense_id').val(expenseId); // Set expense ID in the hidden input
            $.ajax({
                url: '{{ url('clinicExpense') }}' + "/" + expenseId + "/edit",
                method: 'GET',
                success: function(response) {
                    $('#edit_expense_id').val(response.id);
                    $('#edit_name').val(response.name);
                    $('#edit_category').val(response.category);
                    $('#edit_amount').val(response.amount);
                    $('#edit_billdate').val(response.billdate);

                    $('#edit_yes').prop('checked', response.status === 'Y');
                    $('#edit_no').prop('checked', response.status === 'N');

                    // Check if there are uploaded bills
                    if (response.billfile && response.billfile.length > 0) {
                        $('#uploadedBills').show();
                    } else {
                        $('#uploadedBills').hide();
                    }
                    $('#modal-edit').modal('show');
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        $(document).on('click', '.btn-del', function() {
            var expenseId = $(this).data('id');
            $('#delete_expense_id').val(expenseId); // Set expense ID in the hidden input
            $('#modal-delete').modal('show');
        });

        $('#btn-confirm-delete').click(function() {
            var expenseId = $('#delete_expense_id').val();
            var url = "{{ route('expense.expense.destroy', ':expense') }}";
            url = url.replace(':expense', expenseId);
            $.ajax({
                type: 'DELETE',
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    table.draw(); // Refresh DataTable
                    $('#successMessage').text('Expense deleted successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                },
                error: function(xhr) {
                    $('#modal-delete').modal('hide');
                    swal("Error!", xhr.responseJSON.message, "error");
                }
            });
        });
    </script>
@endsection
