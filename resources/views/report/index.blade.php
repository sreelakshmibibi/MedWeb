@extends('layouts.dashboard')
@section('title', 'Reports')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">Appointment created successfully
                </div>
                @if (session('success'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-success alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('success') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                @if (session('error'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fade fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('error') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Reports</h3>
                </div>
            </div>

            <section class="content">
                <div class="box b-0" style="max-height: 100%; min-height: 100vh;">
                    <div class="box-body p-0">
                        <!-- Nav tabs -->
                        <div class="vtabs" style="width: 100%;">
                            <ul class="nav nav-tabs tabs-vertical" role="tablist" style="max-height: 100%; height: 100vh;">
                                <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#collectiontab"
                                        role="tab"><span class="hidden-sm-up">Collection</span> <span
                                            class="hidden-xs-down">Collection Report</span> </a> </li>
                                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#profile4"
                                        role="tab"><span class="hidden-sm-up"><i class="ion-person"></i></span> <span
                                            class="hidden-xs-down">Annual Report</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#incometab"
                                        role="tab"><span class="hidden-sm-up">Income</span> <span
                                            class="hidden-xs-down">Income Report</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#servicetab"
                                        role="tab"><span class="hidden-sm-up">Service</span> <span
                                            class="hidden-xs-down">Service Report</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#patientstab"
                                        role="tab"><span class="hidden-sm-up">Patients</span> <span
                                            class="hidden-xs-down">Patients Report</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#diseasetab"
                                        role="tab"><span class="hidden-sm-up">Disease</span> <span
                                            class="hidden-xs-down">Disease Report</span></a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="collectiontab" role="tabpanel">
                                    <div class="p-15">
                                        @include('report.collection')
                                    </div>
                                </div>
                                <div class="tab-pane" id="profile4" role="tabpanel">
                                    <div class="p-15">

                                    </div>
                                </div>
                                <div class="tab-pane" id="incometab" role="tabpanel">
                                    <div class="p-15">
                                        @include('report.income')
                                    </div>
                                </div>
                                <div class="tab-pane" id="servicetab" role="tabpanel">
                                    <div class="p-15">
                                        @include('report.service')
                                    </div>
                                </div>
                                <div class="tab-pane" id="patientstab" role="tabpanel">
                                    <div class="p-15">
                                        @include('report.patients')
                                    </div>
                                </div>
                                <div class="tab-pane" id="diseasetab" role="tabpanel">
                                    <div class="p-15">
                                        @include('report.disease')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->


    <!-- ./wrapper -->
    <script src="{{ asset('js/reports.js') }}"></script>
@endsection