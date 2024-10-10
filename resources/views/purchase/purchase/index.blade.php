@php
    use App\Services\CommonService;
    $commonService = new CommonService();
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.dashboard')
@section('title', 'Purchases')
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
                    @if ($mode == 'create')
                        <h3 class="page-title">Add Purchases</h3>

                        <a type="button" class="waves-effect waves-light btn btn-primary"
                            href="{{ route('purchases.get') }}"><i class="fa-solid fa-clock-rotate-left"> </i> Purchase
                            History</a>
                    @else
                        <h3 class="page-title">{{ $mode === 'view' ? 'View' : 'Edit' }} Purchases</h3>

                        <a type="button" class="waves-effect waves-light btn btn-primary" title="Back"
                            href="{{ route('purchases.get') }}">
                            <span class="hidden-sm-up"><i class="fa-solid fa-angles-left"></i></span>
                            <span class="hidden-xs-down"><i class="fa-solid fa-angles-left"></i> Back</span>
                        </a>
                    @endif
                </div>
            </div>

            <section class="content">
                <form id="purchaseItemsForm" method="post"
                    action="{{ $mode === 'create' ? route('purchases.store') : ($mode === 'edit' ? route('purchases.update') : '') }}">
                    @csrf
                    <input type="hidden" id="purchase_id" name="purchase_id"
                        value="{{ isset($purchase) ? $purchase->id : '' }}">
                    <div class="box">
                        @include('purchase.purchase.supplierEntry')

                        @include('purchase.purchase.itemsTable')

                        <div class="box-footer text-end p-3">
                            @if ($mode != 'view')
                                <button type="submit" class="btn btn-success" id="savePurchaseButton">
                                    <i class="fa fa-save"></i> {{ $mode === 'create' ? 'Save' : 'Update' }} Purchase
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/purchase.js') }}"></script>
    <script>
        // Optional: Script to handle the closing of alerts
        document.querySelectorAll('.closed').forEach(item => {
            item.addEventListener('click', event => {
                event.target.closest('.myadmin-alert').style.display = 'none';
            });
        });
        $(document).ready(function() {
            // Function to toggle input display
            function togglePaymentInput(checkbox, inputId) {
                const inputField = $('#' + inputId);
                if (checkbox.checked) {
                    inputField.show().css('display', 'inline');
                } else {
                    inputField.hide().val(''); // Clear the input if unchecked
                }
            }

            // Checkbox change event listeners
            $('#paymode_gpay').change(function() {
                togglePaymentInput(this, 'itemgpay');
            });

            $('#paymode_cash').change(function() {
                togglePaymentInput(this, 'itemcash');
            });

            $('#paymode_card').change(function() {
                togglePaymentInput(this, 'itemcard');
            });

            // Initial state for view mode
            if ("{{ $mode }}" != "create") {
                $('#uploadedBills').show();
                togglePaymentInput(document.getElementById('paymode_gpay'), 'itemgpay');
                togglePaymentInput(document.getElementById('paymode_cash'), 'itemcash');
                togglePaymentInput(document.getElementById('paymode_card'), 'itemcard');
                $("#category").trigger("change");
            }
        });

        // Download bills when button is clicked
        $('#uploadedBills').click(function() {
            var purchaseId = $("#purchase_id").val();
            window.location.href = '{{ url('purchases') }}' + "/" + purchaseId + "/download-bills";
        });
    </script>
@endsection
