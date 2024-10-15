@php
    use App\Services\CommonService;
    $commonService = new CommonService();
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.dashboard')
@section('title', 'Salary')
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
                    <h3 class="page-title">{{ $mode === 'create' ? 'Add' : ($mode === 'edit' ? 'Edit' : 'View') }}
                        Monthly Salary</h3>

                    <a type="button" class="waves-effect waves-light btn btn-primary" title="Back"
                        href="{{ route('employeeMonthlySalary') }}">
                        <span class="hidden-sm-up"><i class="fa-solid fa-angles-left"></i></span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-angles-left"></i> Back</span></a>
                    {{-- @endif --}}
                </div>
            </div>

            <section class="content">
                <form id="salaryForm" method="post"
                    action="{{ $mode === 'create' ? route('salary.monthly.store') : ($mode === 'edit' ? route('salary.monthly.update') : '') }}">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id" value="{{ isset($staff) ? $staff->user_id : '' }}">
                    <input type="hidden" id="with_effect_from" name="with_effect_from"
                        value="{{ isset($staff) ? $staff->date_of_joining : '' }}">
                    <div class="box">
                        @include('payroll.monthlySalary.entry')

                        <div class="box-footer text-end p-3">
                            @if ($mode != 'view')
                                <button type="submit" class="btn btn-success" id="saveSalaryButton">
                                    <i class="fa fa-save"></i> {{ $mode === 'create' ? 'Save' : 'Update' }} Salary
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
    <script src="{{ asset('js/monthlysalary.js') }}"></script>
    </script>
    <script>
        // Optional: Script to handle the closing of alerts
        document.querySelectorAll('.closed').forEach(item => {
            item.addEventListener('click', event => {
                event.target.closest('.myadmin-alert').style.display = 'none';
            });
        });
    </script>
@endsection
