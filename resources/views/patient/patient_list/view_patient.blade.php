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
    @include('appointment.pdf_option')
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

            $(document).on('click', '.btn-treatment-pdf-generate', function() {
                var appId = $(this).data('app-id');
                var parentId = $(this).data('parent-id');
                var patientId = $(this).data('patient-id');

                $('#pdf_appointment_id').val(appId);
                $('#pdf_patient_id').val(patientId);
                $('#pdf_app_parent_id').val(parentId);
                $('#pdfType').val('appointment'); // Default to 'appointment'
                $('#toothSelection').addClass('d-none'); // Hide tooth selection by default
                $('#modal-download').modal('show'); // Show the modal
            });
        });
        jQuery(function($) {
            var table;

            if (apphistoryStepAdded) {
                if ($.fn.DataTable.isDataTable("#apphistory_table")) {
                    $("#apphistory_table").DataTable().destroy();
                }

                table = $("#apphistory_table").DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "",
                        type: "GET",
                    },
                    columns: [{
                            data: "DT_RowIndex",
                            name: "DT_RowIndex",
                            className: "max-w-10",
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: "treat_date",
                            name: "treat_date",
                            className: "w-60",
                            render: function(data, type, row) {
                                return moment(data).format("DD-MM-YYYY");
                            },
                        },
                        {
                            data: "teeth",
                            name: "teeth",
                            className: 'text-start',
                        },
                        {
                            data: "problem",
                            name: "problem",
                            className: 'text-start',
                        },
                        {
                            data: "disease",
                            name: "disease",
                            className: 'text-start',
                        },
                        {
                            data: "treatment",
                            name: "treatment",
                            className: 'text-start',
                        },
                        {
                            data: "doctor",
                            name: "doctor",
                            className: "text-start min-w-100",
                        },
                        // {
                        //     data: "branch",
                        //     name: "branch",
                        //     className: "text-left w-120",
                        // },
                        {
                            data: "status",
                            name: "status",
                            className: "w-10 text-start",
                            orderable: false,
                            searchable: true,
                        },
                        {
                            data: "action",
                            name: "action",
                            className: "min-w-40",
                            orderable: false,
                            searchable: true,
                        },
                    ],
                });
            }
            if (billhistoryStepAdded) {
                var patientId = $('#pPatientId').val();
                if ($.fn.DataTable.isDataTable("#billHistoryTable")) {
                    $("#billHistoryTable").DataTable().destroy();
                }

                // Initialize DataTable
                table = $("#billHistoryTable").DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {

                        url: '{{ url('patient_list') }}' + "/" + patientId + "/bill",
                        type: "GET",

                        dataSrc: function(json) {
                            return json.data;
                        },
                    },
                    columns: [{
                            data: "DT_RowIndex",
                            name: "DT_RowIndex",
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: "billDate",
                            name: "billDate",
                            className: "min-w-60",
                            render: function(data, type, row) {
                                if (data) {
                                    var date = new Date(data);
                                    var day = ("0" + date.getDate()).slice(-2);
                                    var month = ("0" + (date.getMonth() + 1)).slice(-2);
                                    var year = date.getFullYear();

                                    return day + "-" + month + "-" + year;
                                } else {
                                    return "-";
                                }
                            },
                        },
                        {
                            data: "branch",
                            name: "branch",
                        },
                        {
                            data: "billType",
                            name: "billType",
                        },
                        {
                            data: "total",
                            name: "total",
                        },
                        {
                            data: "discount",
                            name: "discount",
                        },
                        {
                            data: "tax",
                            name: "tax",
                        },
                        {
                            data: "netAmount",
                            name: "netAmount",
                        },
                        {
                            data: "cash",
                            name: "cash",
                        },
                        {
                            data: "gpay",
                            name: "gpay",
                        },
                        {
                            data: "card",
                            name: "card",
                        },
                        {
                            data: "totalPaid",
                            name: "totalPaid",
                        },
                        {
                            data: "balanceGiven",
                            name: "balanceGiven",
                        },
                        {
                            data: "outstanding",
                            name: "outstanding",
                        },
                    ],
                });
            }
        });
    </script>
@endsection
