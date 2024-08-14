@extends('layouts.dashboard')
@section('title', 'Report')
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
                                <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#home4"
                                        role="tab"><span class="hidden-sm-up"><i class="ion-home"></i></span> <span
                                            class="hidden-xs-down">Collection Report</span> </a> </li>
                                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#profile4"
                                        role="tab"><span class="hidden-sm-up"><i class="ion-person"></i></span> <span
                                            class="hidden-xs-down">Annual Report</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#messages4"
                                        role="tab"><span class="hidden-sm-up"><i class="ion-email"></i></span> <span
                                            class="hidden-xs-down">Income Report</span></a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="home4" role="tabpanel">
                                    <div class="p-15">
                                        @include('report.collection')
                                    </div>
                                </div>
                                <div class="tab-pane" id="profile4" role="tabpanel">
                                    <div class="p-15">
                                        <h4>Fusce porta eros a nisl varius, non molestie metus mollis. Pellentesque
                                            tincidunt ante sit amet ornare lacinia.</h4>
                                        <p>Duis cursus eros lorem, pretium ornare purus tincidunt eleifend. Etiam quis justo
                                            vitae erat faucibus pharetra. Morbi in ullamcorper diam. Morbi lacinia, sem
                                            vitae dignissim cursus, massa nibh semper magna, nec pellentesque lorem nisl
                                            quis ex.</p>
                                        <h3>Donec vitae laoreet neque, id convallis ante.</h3>
                                    </div>
                                </div>
                                <div class="tab-pane" id="messages4" role="tabpanel">
                                    <div class="p-15">
                                        <h3>Donec vitae laoreet neque, id convallis ante.</h3>
                                        <p>Duis cursus eros lorem, pretium ornare purus tincidunt eleifend. Etiam quis justo
                                            vitae erat faucibus pharetra. Morbi in ullamcorper diam. Morbi lacinia, sem
                                            vitae dignissim cursus, massa nibh semper magna, nec pellentesque lorem nisl
                                            quis ex.</p>
                                        <h4>Fusce porta eros a nisl varius, non molestie metus mollis. Pellentesque
                                            tincidunt ante sit amet ornare lacinia.</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-none box-body">
                        <!-- Nav tabs -->
                        <div class="vtabs">
                            <ul class="nav nav-tabs tabs-vertical" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#home9"
                                        role="tab"><span><i class="fa fa-home me-15"></i>Home</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#profile9"
                                        role="tab"><span><i class="ion-person me-15"></i>Person</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#messages9"
                                        role="tab"><span><i class="ion-email me-15"></i>Email</span></a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="home9" role="tabpanel">
                                    <div class="p-15">
                                        <h3>Donec vitae laoreet neque, id convallis ante.</h3>
                                        <h4>Fusce porta eros a nisl varius, non molestie metus mollis. Pellentesque
                                            tincidunt ante sit amet ornare lacinia.</h4>
                                        <p>Duis cursus eros lorem, pretium ornare purus tincidunt eleifend. Etiam quis justo
                                            vitae erat faucibus pharetra. Morbi in ullamcorper diam. Morbi lacinia, sem
                                            vitae dignissim cursus, massa nibh semper magna, nec pellentesque lorem nisl
                                            quis ex.</p>
                                    </div>
                                </div>
                                <div class="tab-pane" id="profile9" role="tabpanel">
                                    <div class="p-15">
                                        <h3>Donec vitae laoreet neque, id convallis ante.</h3>
                                        <p>Duis cursus eros lorem, pretium ornare purus tincidunt eleifend. Etiam quis justo
                                            vitae erat faucibus pharetra. Morbi in ullamcorper diam. Morbi lacinia, sem
                                            vitae dignissim cursus, massa nibh semper magna, nec pellentesque lorem nisl
                                            quis ex.</p>
                                        <h4>Fusce porta eros a nisl varius, non molestie metus mollis. Pellentesque
                                            tincidunt ante sit amet ornare lacinia.</h4>
                                    </div>
                                </div>
                                <div class="tab-pane" id="messages9" role="tabpanel">
                                    <div class="p-15">
                                        <p>Duis cursus eros lorem, pretium ornare purus tincidunt eleifend. Etiam quis justo
                                            vitae erat faucibus pharetra. Morbi in ullamcorper diam. Morbi lacinia, sem
                                            vitae dignissim cursus, massa nibh semper magna, nec pellentesque lorem nisl
                                            quis ex.</p>
                                        <h3>Donec vitae laoreet neque, id convallis ante.</h3>
                                        <h4>Fusce porta eros a nisl varius, non molestie metus mollis. Pellentesque
                                            tincidunt ante sit amet ornare lacinia.</h4>
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
