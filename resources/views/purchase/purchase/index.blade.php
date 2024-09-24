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
                    <h3 class="page-title">Add Purchases</h3>
                </div>
            </div>

            <section class="content">
                <form id="purchaseItemsForm" method="post" action="{{ route('purchases.store') }}">
                    @csrf
                    <div class="box">
                        @include('purchase.purchase.supplierEntry')

                        @include('purchase.purchase.itemsTable')

                        <div class="box-footer text-end p-3">
                            <button type="submit" class="btn btn-success" id="savePurchaseButton">
                                <i class="fa fa-save"></i> Save Purchase
                            </button>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/purchase.js') }}"></script>
@endsection
