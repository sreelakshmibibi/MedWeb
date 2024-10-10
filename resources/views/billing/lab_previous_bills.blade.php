@extends('layouts.dashboard')
@section('title', 'Lab Payments Details')
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
                    <h3 class="page-title">Lab Payment Details</h3>                   
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table width="100%" class="table table-bordered table-hover table-striped mb-0 data-table">
                                <thead class="bg-primary-light text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Bill Amount</th>
                                        <th>Previous Due</th>
                                        <th>Total Amount</th>
                                        <th>Payment Modes</th>
                                        <th>Amount Paid</th>
                                        <th>Balance Due</th>
                                        <th>Paid On</th>
                                        <th>Bill Status</th>
                                        <th>Action</th>
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
    
    @include('billing.lab_bill_cancel')
    {{-- @endsection

@section('scripts') --}}

    <script>
        var clinicBasicDetails = @json($clinicBasicDetails);
        var labPaymentUrl = "{{ route('labPayment.show') }}";
    </script>

    <!-- custom JavaScript file -->
    <script src="{{ asset('js/labPayment.js') }}"></script>

    <script type="text/javascript">
        // jQuery(function($) {
            $(document).on('click', '#btn-cancel-lab-bill', function() {
            var billId = $(this).data('id');
            $('#cancel_bill_id').val(billId);
            $('#modal-cancel-lab-bill').modal('show');
        });

        $('#btn-cancel-bill').click(function() {
            var billId = $('#cancel_bill_id').val();
            var reason = $('#bill_cancel_reason').val();

            if (reason.length === 0) {
                $('#reason').addClass('is-invalid');
                $('#reasonError').text('Reason is required.');
                return; // Stop further execution
            }

            var url = "{{ route('labPayment.destroy', ':billId') }}";
            url = url.replace(':billId', billId);

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "reason": reason
                },
                success: function(response) {
                    $('#modal-cancel-lab-bill').modal('hide'); // Close modal after success
                    table.draw(); // Refresh DataTable
                    $('#successMessage').text('Bill cancelled successfully');
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds

                },
                error: function(xhr) {
                    $('#modal-cancel-lab-bill').modal(
                        'hide'); // Close modal in case of error
                    console.log("Error!", xhr.responseJSON.message, "error");
                }
            });
        });       
        // });
    </script>
@endsection
