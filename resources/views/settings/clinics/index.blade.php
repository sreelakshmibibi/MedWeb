@extends('layouts.dashboard')
@section('title', 'Clinics')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Clinics</h3>
                </div>
            </div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs customtab2" role="tablist">
                <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#home7" role="tab"><span
                            class="hidden-sm-up"><i class="ion-home"></i></span> <span class="hidden-xs-down">Basic
                            Settings</span></a>
                </li>
                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#profile7" role="tab"><span
                            class="hidden-sm-up"><i class="ion-person"></i></span> <span
                            class="hidden-xs-down">Branches</span></a> </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="home7" role="tabpanel">
                    <div class="p-15">

                        <!-- Main content -->
                        <section class="content">
                            <div class="row">
                                <div class="col-md-6 col-lg-6 col-12">
                                    <div class="box">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label class="form-label" for="name">Clinic Name</label>
                                                <input class="form-control" type="text" id="clinic_name"
                                                    name="clinic_name" placeholder="Clinic Name">
                                                <div id="clinicNameError" class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="logo">Logo</label>
                                                <input class="form-control" type="file" id="clinic_logo"
                                                    name="clinic_logo" placeholder="logo">
                                                <div id="clinicLogoError" class="invalid-feedback"></div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-md-8">
                                                    <label class="form-label" for="logo">Logo</label>
                                                    <input class="form-control" type="file" id="clinic_logo"
                                                        name="clinic_logo" placeholder="logo">
                                                    <div id="clinicLogoError" class="invalid-feedback"></div>
                                                </div>

                                                <canvas id="logoCanvas" class="col-md-4" style=" height:64px;"></canvas>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="website">Website</label>
                                                <input class="form-control" type="url" id="clinic_website"
                                                    name="clinic_website" placeholder="http://">
                                                <div id="clinicWebsiteError" class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="box-footer text-end">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa fa-save"></i> Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="tab-pane" id="profile7" role="tabpanel">
                    <div class="p-15">
                        @include('settings.clinics.clinic_form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
