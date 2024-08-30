<?php

use App\Services\CommonService;
$commonService = new CommonService();
use Illuminate\Support\Facades\Session;

?>
@extends('layouts.dashboard')
@section('title', 'Patients')
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
                    <h3 class="page-title">Patient Info</h3>

                    {{-- <a type="button" class="waves-effect waves-light btn btn-primary"
                        href="{{ route('patient.patient_list') }}">
                        <i class="fa-solid fa-angles-left"></i> Back</a> --}}

                    <div>
                        <a href='#'
                            class='waves-effect waves-light btn btn-circle btn-patientidcard-pdf-generate btn-secondary btn-xs me-1 text-dark'
                            title='Download & Print Patient ID Card' data-app-id='{{ session('appId') }}'
                            data-patient-id='{{ session('patientId') }}'><i class='fa fa-download'></i></a>
                        <a type="button" class="waves-effect waves-light btn btn-circle btn-primary btn-xs" title="back"
                            href="{{ route('patient.patient_list') }}">
                            <i class="fa-solid fa-angles-left"></i></a>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body wizard-content px-2 pb-0">
                        <form method="post" class="validation-wizard wizard-circle" id="patientviewform">
                            @csrf
                            <!-- Step 1 -->
                            <h6 class="tabHeading">Personal Info</h6>
                            <section class="tabSection">
                                @include('patient.patient_list.view_personal_info')
                            </section>
                        </form>
                    </div>

                    <div class="appointmenthistorydiv" style="display: none;">
                        @include('patient.patient_list.view_history')
                    </div>

                    <div class="billhistorydiv" style="display: none;">
                        @include('patient.patient_list.view_billhistory')
                    </div>
                    <!-- /.box-body -->
                </div>

            </section>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#patientviewform .actions ul li:last-child").addClass("disabled").attr("aria-hidden", "true").attr(
                "aria-disabled", "true").hide();
            $("#patientviewform .actions ul li:last-child a").attr("href", "#").hide();

            appvisitcount = $("#pvisitcount").val();
            handleRemainingSteps(appvisitcount);

            $(document).on('click', '.btn-patientidcard-pdf-generate', function() {
                var appId = $(this).data('app-id');
                var patientId = $(this).data('patient-id');
                const url = '{{ route('download.patientidcard') }}';

                // Make the AJAX request
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        app_id: appId,
                        patient_id: patientId,
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    xhrFields: {
                        responseType: 'blob' // Important for handling binary data like PDFs
                    },
                    success: function(response) {
                        var blob = new Blob([response], {
                            type: 'application/pdf'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = 'patientidcard.pdf';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        // For printing, open the PDF in a new window or iframe and call print
                        var printWindow = window.open(link.href, '_blank');
                        printWindow.onload = function() {
                            printWindow.print();
                        };
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
@endsection
