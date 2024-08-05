@extends('layouts.dashboard')
@section('title', 'Billing')
@section('content')

    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">
                    Bill created successfully
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Billing List</h3>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <div id="billing_paginator"></div>
                        <br />
                        <div class="table-responsive">

                            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                                id="billing_table" width="100%">
                                <thead class="bg-primary-light">
                                    <tr>
                                        <th width="10px">Token No</th>
                                        <th width="60px">Patient ID</th>
                                        <th>Patient Name</th>
                                        <th width="60px">Phone number</th>
                                        <th width="180px">Branch</th>
                                        <th>Consulted Doctor</th>
                                        <th>Status</th>
                                        <th width="144px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script type="text/javascript">
        var selectedDate;
        var table;

        $(document).ready(function() {

            $("#billing_paginator").datepaginator({
                onSelectedDateChanged: function(a, t) {
                    selectedDate = moment(t).format("YYYY-MM-DD");
                    table.ajax.reload();
                },
            });
            var initialDate = $("#billing_paginator").datepaginator("getDate");
            selectedDate = moment(initialDate).format("YYYY-MM-DD")
        });

        jQuery(function($) {
            table = $('#billing_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('billing') }}",
                    type: 'GET',
                    data: function(d) {
                        d.selectedDate = selectedDate;
                    }
                },
                columns: [{
                        data: 'token_no',
                        name: 'token_no'
                    },
                    {
                        data: 'patient_id',
                        name: 'patient_id'
                    },

                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'branch',
                        name: 'branch'
                    },

                    {
                        data: 'doctor',
                        name: 'doctor'
                    },

                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: true
                    },
                ]
            });
        });

        $(document).on('click', '.btn-pdf-generate', function() {
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
    </script>
@endsection
