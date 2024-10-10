@php
    use App\Services\CommonService;
    $commonService = new CommonService();
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.dashboard')
@section('title', 'Visiting Doctor Payment')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success"></div>
                <div id="errorMessagecreate" style="display:none;" class="alert alert-danger"></div>

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
                    {{-- @if ($mode == 'create') --}}
                    <h3 class="page-title">
                        @if (Auth::user()->can('visiting doctor payment save'))
                            Visiting Doctor Payments
                        @endif
                        @if (!Auth::user()->can('visiting doctor payment save'))
                            Visiting Doctor Payment History
                        @endif
                    </h3>

                    <a type="button" class="waves-effect waves-light btn btn-primary" title="Back"
                        href="{{ route('doctorPayment') }}">
                        <span class="hidden-sm-up"><i class="fa-solid fa-angles-left"></i></span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-angles-left"></i> Back</span></a>
                    {{-- @endif --}}
                </div>
            </div>

            <section class="content">
                <form id="doctorPaymentForm" method="post" action="{{ route('doctorPayment.store') }}">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id" value="{{ isset($staff) ? $staff->user_id : '' }}">
                    <input type="hidden" id="with_effect_from" name="with_effect_from"
                        value="{{ isset($staff) ? $staff->date_of_joining : '' }}">

                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="doctor">Name: </label>
                                        <input type="text" id="doctor" name="doctor" class="form-control" readonly
                                            value="{{ str_replace('<br>', ' ', $staff->user->name) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="designation">Designation: </label>
                                        <input type="text" id="designation" name="designation" class="form-control"
                                            readonly value="{{ $staff->designation }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label" for="doj">Joined on: </label>
                                        <input type="text" id="doj" name="doj" class="form-control" readonly
                                            value="{{ date('d-m-Y', strtotime($staff->date_of_joining)) }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="totalpaid">Total Paid: </label>
                                        <input type="text" id="totalpaid" name="totalpaid" class="form-control" readonly
                                            value="{{ $totalPaid }}">
                                    </div>
                                </div>
                            </div>
                            @if (Auth::user()->can('visiting doctor payment save'))
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="amount">Amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="amount" name="amount">
                                            <div class="invalid-feedback" id="amountError"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label" for="paid_on">Paid On <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="paid_on" name="paid_on"
                                                value="{{ date('Y-m-d') }}" required>
                                            <div class="invalid-feedback" id="paidOnError"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label class="form-label" for="remarks">Remarks</label>
                                            <textarea rows="1" class="form-control" id="remarks" name="remarks" placeholder="Remarks (if any)"></textarea>
                                            <div id="remarksError" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <div class="box-footer text-end p-3">
                            <button type="submit" class="btn btn-success" id="savePayment">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                        @endif
                    </div>

                    <div class="box">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table width="100%"
                                    class="table table-bordered table-hover table-striped mb-0 data-table">
                                    <thead class="bg-primary-light text-center">
                                        <tr>
                                            <th width="10px">No</th>
                                            <th width="100px">Amount</th>
                                            <th width="150px">Paid On</th>
                                            <th>Remarks</th>
                                            <th width="60px">Status</th>
                                            <th width="150px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Populate table rows with department data -->
                                    </tbody>
                                </table>
                            </div>

                        </div>
                </form>
            </section>
        </div>
    </div>
    @include('payroll.doctorPayment.delete')
    <script>
        var table;
        var deleteurl = "{{ route('doctorPayment.destroy', ':historyId') }}";
        window.csrf = "{{ csrf_token() }}";
        var userId = '{{ $idEncrypted }}'; // Get user ID from Blade
        var doctorPaymentUrlCreate = "{{ route('doctorPayment.create', ':userId') }}".replace(':userId',
            userId); // Replace placeholder with encoded user ID   
    </script>

    <!-- custom JavaScript file -->
    <script src="{{ asset('js/doctorPaymentHistory.js') }}"></script>

@endsection
