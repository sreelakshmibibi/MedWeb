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
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-error" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <!-- Nav tabs -->
            <ul class="nav nav-tabs customtab2" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#home7" role="tab" id="basic">
                        <span class="hidden-sm-up"><i class="ion-home"></i></span>
                        <span class="hidden-xs-down">Basic Settings</span>
                    </a>
                </li>
                <?php 
                if ($clinicDetails) { ?>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#profile7" role="tab" id="branches">
                        <span class="hidden-sm-up"><i class="ion-person"></i></span>
                        <span class="hidden-xs-down">Branches</span>
                    </a>
                </li>
                <?php } ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="home7" role="tabpanel">
                    <div class="p-15">
                        <form method="post" action="{{ route('settings.clinic.create') }}" enctype="multipart/form-data">
                            @csrf
                            <!-- Main content -->
                            <section class="content ">
                                <div class="row d-flex justify-content-between">
                                    <div class="col-md-6 col-lg-6 col-12">
                                        <div class="box">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label class="form-label" for="name">Clinic Name</label>
                                                    <input class="form-control @error('clinic_name') is-invalid @enderror"
                                                        type="text" id="clinic_name" name="clinic_name"
                                                        placeholder="Clinic Name" <?php if($clinicDetails) { ?>
                                                        value="{{ old('clinic_name', $clinicDetails->clinic_name) }}"
                                                        <?php }?>>
                                                    @error('clinic_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label" for="website">Website</label>
                                                    <input
                                                        class="form-control @error('clinic_website') is-invalid @enderror"
                                                        type="url" id="clinic_website" name="clinic_website"
                                                        placeholder="http://" <?php if($clinicDetails) { ?>
                                                        value="{{ old('clinic_website', $clinicDetails->clinic_website) }}"
                                                        <?php } ?>>
                                                    @error('clinic_website')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-10">
                                                        <div class="form-group ">
                                                            <label class="form-label" for="logo">Logo</label>
                                                            <input
                                                                class="form-control @error('clinic_logo') is-invalid @enderror"
                                                                type="file" id="clinic_logo" name="clinic_logo"
                                                                placeholder="logo">
                                                            @error('clinic_logo')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <canvas id="logoCanvas" style="height: 64px;"></canvas>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div class="row form-group">
                                                    <canvas id="logoCanvas" class="col-md-2" style="height: 64px;"></canvas>
                                                </div> --}}
                                                {{-- <div class="form-group">
                                                    <label class="form-label" for="website">Website</label>
                                                    <input
                                                        class="form-control @error('clinic_website') is-invalid @enderror"
                                                        type="url" id="clinic_website" name="clinic_website"
                                                        placeholder="http://" <?php if($clinicDetails) { ?>
                                                        value="{{ old('clinic_website', $clinicDetails->clinic_website) }}"
                                                        <?php } ?>>
                                                    @error('clinic_website')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div> --}}
                                            </div>
                                            <div class="box-footer text-end">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa fa-save"></i> Save Changes
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        @include('settings.clinics.branch')
                                    </div>
                                </div>
                            </section>
                        </form>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var canvas = document.getElementById('logoCanvas');
            var ctx = canvas.getContext('2d');
            if ('{{ $clinicDetails }}') {

                var clinicLogoUrl = '{{ $clinicDetails->clinic_logo ?? "" }}';
                var logoUrl = '{{ asset("storage/") }}/' + clinicLogoUrl;
                if (clinicLogoUrl) {
                    var img = new Image();
                    img.onload = function() {
                        canvas.width = img.width;
                        canvas.height = img.height;
                        ctx.drawImage(img, 0, 0);
                    };
                    img.src = logoUrl;
                }
            }

            var input = document.getElementById('clinic_logo');
            var canvas = document.getElementById('logoCanvas');
            var ctx = canvas.getContext('2d');

            input.addEventListener('change', function(event) {
                var file = event.target.files[0];
                var reader = new FileReader();

                reader.onload = function(e) {
                    var img = new Image();
                    img.onload = function() {
                        // canvas.width = img.width;
                        // canvas.height = img.height;
                        // ctx.drawImage(img, 0, 0, img.width, img.height);
                        canvas.width = img.height;
                        canvas.height = img.height;
                        ctx.drawImage(img, 0, 0, img.height, img.height);
                    };
                    img.src = e.target.result;
                };

                if (file) {
                    reader.readAsDataURL(file);
                }
            });

        });
    </script>


@endsection
