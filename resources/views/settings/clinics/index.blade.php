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
            <ul class="nav nav-tabs " role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#home7" role="tab" id="basic">
                        <span class="hidden-sm-up"><i class="fa-solid fa-house-chimney-medical"></i></span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-house-chimney-medical me-10"></i>Basic
                            Settings</span>
                    </a>
                </li>
                <?php 
                if ($clinicDetails) { ?>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#profile7" role="tab" id="branches">
                        <span class="hidden-sm-up"><i class="fa-solid fa-hospital"></i></span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-hospital me-10"></i>Branches</span>
                    </a>
                </li>
                <?php } ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="home7" role="tabpanel">
                    <div class="py-15">
                        <form method="post" action="{{ route('settings.clinic.create') }}" enctype="multipart/form-data">
                            @csrf
                            <!-- Main content -->
                            <section class="content ">
                                <div class="row d-flex justify-content-between">
                                    <div class="col-md-8 col-lg-6 col-12">
                                        <div class="box">
                                            <div class="box-body pb-0">
                                                <div class="form-group">
                                                    <label class="form-label" for="clinic_name">Clinic Name <span
                                                            class="text-danger">
                                                            *</span></label>
                                                    <input class="form-control @error('clinic_name') is-invalid @enderror"
                                                        type="text" id="clinic_name" name="clinic_name"
                                                        placeholder="Clinic Name" <?php if($clinicDetails) { ?>
                                                        value="{{ old('clinic_name', $clinicDetails->clinic_name) }}"
                                                        <?php }?>>
                                                    @error('clinic_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="clinic_website">Website</label>
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
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="yes">
                                                                Is Insurance available?<span class="text-danger">*</span>
                                                            </label>
                                                            <div>
                                                                <input name="insurance" type="radio"
                                                                    class="form-control with-gap" id="yes"
                                                                    value="Y"
                                                                    @if ($clinicDetails) @if ($clinicDetails->clinic_insurance_available == 'Y') checked @endif
                                                                    @endif
                                                                >
                                                                <label for="yes">Yes</label>

                                                                <input name="insurance" type="radio"
                                                                    class="form-control with-gap" id="no"
                                                                    value="N"
                                                                    @if ($clinicDetails) @if ($clinicDetails->clinic_insurance_available == 'N') checked @endif
                                                                    @endif
                                                                >
                                                                <label for="no">No</label>
                                                                <div id="insuranceError" class="invalid-feedback"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="tax">Tax (%)
                                                                <span class="text-danger">
                                                                    *</span></label>
                                                            <input class="form-control @error('tax') is-invalid @enderror"
                                                                type="text" id="tax" name="treatment_tax"
                                                                placeholder="Tax" <?php if($clinicDetails) { ?>
                                                                value="{{ old('tax', $clinicDetails->tax) }}"
                                                                <?php }?>>
                                                            @error('tax')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label"
                                                                for="treatment_tax_included">Treatment Tax Included
                                                                <span class="text-danger">
                                                                    *</span></label>
                                                            <select
                                                                class="form-control @error('treatment_tax_included') is-invalid @enderror"
                                                                type="text" id="treatment_tax_included"
                                                                name="treatment_tax_included">
                                                                <option value="Y" <?php if ($clinicDetails) {
                                                                    if ($clinicDetails->treatment_tax_included == 'Y') {
                                                                        echo 'selected';
                                                                    }
                                                                } ?>>Yes</option>
                                                                <option value="N" <?php if ($clinicDetails) {
                                                                    if ($clinicDetails->treatment_tax_included == 'N') {
                                                                        echo 'selected';
                                                                    }
                                                                } ?>>No</option>
                                                            </select>

                                                            @error('treatment_tax_included')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="currency">Currency<span
                                                                    class="text-danger">
                                                                    *</span></label>
                                                            <select
                                                                class="form-control @error('currency') is-invalid @enderror"
                                                                type="text" id="currency" name="currency">
                                                                <option value="₹" <?php if ($clinicDetails) {
                                                                    if ($clinicDetails->currency == '₹') {
                                                                        echo 'selected';
                                                                    }
                                                                } ?>>₹</option>
                                                                <option value="$" <?php if ($clinicDetails) {
                                                                    if ($clinicDetails->currency == '$') {
                                                                        echo 'selected';
                                                                    }
                                                                } ?>>$</option>
                                                                <option value="OMR" <?php if ($clinicDetails) {
                                                                    if ($clinicDetails->currency == 'OMR') {
                                                                        echo 'selected';
                                                                    }
                                                                } ?>>OMR</option>
                                                            </select>
                                                            @error('currency')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label"
                                                                for="patient_registration_fees">Patient Registration
                                                                Fees<span class="text-danger">
                                                                    *</span></label>
                                                            <input
                                                                class="form-control @error('patient_registration_fees') is-invalid @enderror"
                                                                type="text" id="patient_registration_fees"
                                                                name="patient_registration_fees"
                                                                placeholder="Registration Fees" <?php if($clinicDetails) { ?>
                                                                value="{{ old('patient_registration_fees', $clinicDetails->patient_registration_fees) }}"
                                                                <?php }?>>
                                                            @error('registration_fees')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="consultation_fees">Consultation
                                                                Fees<span class="text-danger">
                                                                    *</span></label>
                                                            <input
                                                                class="form-control @error('consultation_fees') is-invalid @enderror"
                                                                type="text" id="consultation_fees"
                                                                name="consultation_fees" placeholder="Consultation Fees"
                                                                <?php if($clinicDetails) { ?>
                                                                value="{{ old('consultation_fees', $clinicDetails->consultation_fees) }}"
                                                                <?php }?>>
                                                            @error('consultation_fees')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label"
                                                                for="consultation_fees_frequency">Frequency(Consult
                                                                fee)<span class="text-danger">
                                                                    *</span></label>
                                                            <input
                                                                class="form-control @error('consultation_fees_frequency') is-invalid @enderror"
                                                                type="text" id="consultation_fees_frequency"
                                                                name="consultation_fees_frequency"
                                                                placeholder="Fees Frequency" <?php if($clinicDetails) { ?>
                                                                value="{{ old('consultation_fees_frequency', $clinicDetails->consultation_fees_frequency) }}"
                                                                <?php }?>>
                                                            @error('collect_fees')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label"
                                                                for="financial_year_start">Financial Year starting Month
                                                                <span class="text-danger">
                                                                    *</span></label>
                                                            <select
                                                                class="form-control @error('financial_year_start') is-invalid @enderror"
                                                                type="text" id="financial_year_start"
                                                                name="financial_year_start">
                                                                <option value="">--Select--</option>
                                                                @foreach($months as $key => $month)
                                                                <option value="{{$key}}" <?php if ($clinicDetails) {
                                                                    if ($clinicDetails->financial_year_start == $key) {
                                                                        echo 'selected';
                                                                    }
                                                                } ?>>{{$month}}</option>
                                                                @endforeach
                                                            </select>

                                                            @error('financial_year_start')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label"
                                                                for="financial_year_end">Financial Year ending Month
                                                                <span class="text-danger">
                                                                    *</span></label>
                                                            <select
                                                                class="form-control @error('financial_year_end') is-invalid @enderror"
                                                                type="text" id="financial_year_end"
                                                                name="financial_year_end">
                                                                <option value="">--Select--</option>
                                                                @foreach($months as $key => $month)
                                                                <option value="{{$key}}" <?php if ($clinicDetails) {
                                                                    if ($clinicDetails->financial_year_end == $key) {
                                                                        echo 'selected';
                                                                    }
                                                                } ?>>{{$month}}</option>
                                                                @endforeach
                                                            </select>

                                                            @error('financial_year_end')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-10">
                                                        <div class="form-group ">
                                                            <label class="form-label" for="clinic_logo">Logo</label>
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
                                            </div>
                                            <div class="box-footer p-3 text-end">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa fa-save"></i> Save Changes
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-lg-6 col-12">
                                        @include('settings.clinics.branch')
                                    </div>
                                </div>
                            </section>
                        </form>
                    </div>
                </div>
                <div class="tab-pane" id="profile7" role="tabpanel">
                    <div class="py-15">
                        @include('settings.clinics.clinic_form')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- custom JavaScript file -->
    <script src="{{ asset('js/clinic.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // $('#cbranch_table').css('visibility', 'hidden');
            var canvas = document.getElementById('logoCanvas');
            var ctx = canvas.getContext('2d');
            if ('{{ $clinicDetails }}') {

                var clinicLogoUrl = '{{ $clinicDetails->clinic_logo ?? '' }}';
                var logoUrl = "{{ asset('storage/') }}/" + clinicLogoUrl;
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
