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
    

    {{-- @endsection

@section('scripts') --}}

    <script>
        var labPaymentUrl = "{{ route('labPayment.show') }}";
    </script>

    <!-- custom JavaScript file -->
    <script src="{{ asset('js/labPayment.js') }}"></script>

    <script type="text/javascript">
        jQuery(function($) {
            
            
            
        });
    </script>
@endsection
