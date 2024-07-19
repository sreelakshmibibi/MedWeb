@extends('layouts.dashboard')
@section('title', 'Combo Offers')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">Combo offer created successfully
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
                    <h3 class="page-title">Combo Offers</h3>
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
                                        <th>No</th>
                                        <th>Treatments</th>
                                        <th>Total Treatment Amount</th>
                                        <th>Offer Amount</th>
                                        <th>Offer From</th>
                                        <th>Offer To</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populate table rows with combo offer data -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->

    @include('settings.combo_offer.create')
    @include('settings.combo_offer.edit')
    @include('settings.combo_offer.delete')

    <script type="text/javascript">
        var table;
        jQuery(function($) {
            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('settings.combo_offer') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'treatments',
                        name: 'treatments',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total_treatment_amount',
                        name: 'total_treatment_amount',
                        // render: function(data, type, row) {
                        //     return parseFloat(data).toFixed(2);
                        // }
                    },

                    {
                        data: 'offer_amount',
                        name: 'offer_amount',
                        // render: function(data, type, row) {
                        //     return parseFloat(data).toFixed(2);
                        // }
                    },
                    {
                        data: 'offer_from',
                        name: 'offer_from'
                    },
                    {
                        data: 'offer_to',
                        name: 'offer_to'
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
                    }
                ]
            });

            $(document).on('click', '.btn-edit', function() {
                var comboOfferId = $(this).data('id');

                $.ajax({
                    url: '{{ url('combo_offer') }}' + "/" + comboOfferId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_combo_offer_id').val(response.id);
                        $('#edit_offer_amount').val(response.offer_amount);
                        $('#edit_offer_from').val(response.offer_from);
                        $('#edit_offer_to').val(response.offer_to);
                        $('input[name="status"][value="' + response.status + '"]').prop(
                            'checked', true);

                        // Clear previous selections
                        $('#edit_treatments').empty();

                        // Populate the treatments select box
                        response.treatments.forEach(function(treatment) {
                            var option = $('<option></option>')
                                .attr('value', treatment.id)
                                .attr('data-cost', treatment
                                    .treat_cost)
                                .text(treatment.treat_name);

                            // Pre-select treatments
                            if (response.comboOffer_treatments.includes(treatment.id)) {
                                option.prop('selected', true);
                            }

                            $('#edit_treatments').append(option);
                        });

                        $('#edit_treatments').trigger('change');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

                // $('#modal-edit').modal('show'); // Show the edit modal if needed
            });

            $(document).on('click', '.btn-danger', function() {
                var comboOfferId = $(this).data('id');
                $('#delete_combo_offer_id').val(comboOfferId);
                $('#modal-delete').modal('show');
            });

            $('#btn-confirm-delete').click(function() {
                var comboOfferId = $('#delete_combo_offer_id').val();
                var url = "{{ route('settings.combo_offer.destroy', ':comboOffer') }}";
                url = url.replace(':comboOffer', comboOfferId);

                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.draw();
                        $('#successMessage').text('Combo offer deleted successfully');
                        $('#successMessage').fadeIn().delay(3000).fadeOut();
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
