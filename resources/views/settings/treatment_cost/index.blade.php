@extends('layouts.dashboard')
@section('title', 'Treatment Cost')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">Treatment cost created successfully
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
                    <h3 class="page-title">Treatment Cost Details</h3>
                    <button type="button" class="waves-effect waves-light btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-right"> <i class="fa fa-add"></i> Add New</button>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-hover table-striped mb-0 border-2 data-table text-center">
                                <thead class="bg-primary-light">
                                    <tr>
                                        <th width="10px">No</th>
                                        <th>Treatment Name</th>
                                        <th width="100px">Cost</th>
                                        <th width="100px">Discount (%)</th>
                                        <th width="80px">From</th>
                                        <th width="80px">To</th>
                                        <th width="20px">Status</th>
                                        <th width="80px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populate table rows with Treatment type data -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->

    @include('settings.treatment_cost.create')
    @include('settings.treatment_cost.edit')
    @include('settings.treatment_cost.delete')
    {{-- </div> --}}

    <!-- ./wrapper -->
    {{-- <script type="module"> --}}
    <script type="text/javascript">
        var table;
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('discount_from').setAttribute('min', today);
        document.getElementById('discount_to').setAttribute('min', today);
        document.getElementById('edit_discount_from').setAttribute('min', today);
        document.getElementById('edit_discount_to').setAttribute('min', today);

        jQuery(function($) {

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('settings.treatment_cost') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            // Return the row index (starts from 0)
                            return meta.row + 1; // Adding 1 to start counting from 1
                        }
                    },
                    {
                        data: 'treat_name',
                        name: 'treat_name'
                    },
                    {
                        data: 'treat_cost',
                        name: 'treat_cost',
                        render: function(data, type, row) {
                            return parseFloat(data).toFixed(2); // Format to 2 decimal points
                        }
                    },
                    {
                        data: 'discount_percentage',
                        name: 'discount_percentage',
                        render: function(data, type, row) {
                            if (!data) {
                                data = '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'discount_from',
                        name: 'discount_from',
                        render: function(data, type, row) {
                            if (data) {
                                var date = new Date(data);
                                var day = ("0" + date.getDate()).slice(-2);
                                var month = ("0" + (date.getMonth() + 1)).slice(-2);
                                var year = date.getFullYear();
                                return day + '-' + month + '-' + year;
                            } else {
                                data = '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'discount_to',
                        name: 'discount_to',
                        render: function(data, type, row) {
                            if (data) {
                                var date = new Date(data);
                                var day = ("0" + date.getDate()).slice(-2);
                                var month = ("0" + (date.getMonth() + 1)).slice(-2);
                                var year = date.getFullYear();
                                return day + '-' + month + '-' + year;
                            } else {
                                data = '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: true
                    },
                ]

            });

            $(document).on('click', '.btn-edit', function() {
                var treatmentId = $(this).data('id');
                $('#edit_treatment_cost_id').val(treatmentId); // Set treatment ID in the hidden input
                $.ajax({
                    url: '{{ url('treatment_cost') }}' + "/" + treatmentId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_treatment_cost_id').val(response.id);
                        $('#edit_treatment_name').val(response.treat_name);
                        // Format the treat_cost to two decimal points
                        var formattedCost = parseFloat(response.treat_cost).toFixed(2);
                        $('#edit_treatment_cost').val(formattedCost);
                        $('#edit_yes').prop('checked', response.status === 'Y');
                        $('#edit_no').prop('checked', response.status === 'N');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            $(document).on('click', '.btn-danger', function() {
                var treatmentId = $(this).data('id');
                $('#delete_treatment_id').val(treatmentId); // Set treatment ID in the hidden input
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var treatmentId = $('#delete_treatment_id').val();
                var url = "{{ route('settings.treatment_cost.destroy', ':treatment') }}";
                url = url.replace(':treatment', treatmentId);

                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.draw(); // Refresh DataTable
                        $('#successMessage').text('Department deleted successfully');
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                    },
                    error: function(xhr) {
                        $('#modal-delete').modal('hide');
                        swal("Error!", xhr.responseJSON.message, "error");
                    }
                });
            });

        });
    </script>
@endsection
