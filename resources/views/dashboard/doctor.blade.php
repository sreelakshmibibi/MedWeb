<?php

use App\Models\AppointmentStatus;
use App\Models\PatientTreatmentBilling;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
?>
@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('loader')
    <div id="loader"></div>
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    @php
                        $woman = 0;
                        $man = 0;
                        $new = 0;
                        $followup = 0;
                        if ($totalUniquePatients != 0) {
                            $woman = number_format(($femalePatientsCount / $totalUniquePatients) * 100, 2);
                            $man = number_format(($malePatientsCount / $totalUniquePatients) * 100, 2);
                            $new = number_format(($newPatientsCount / $totalUniquePatients) * 100, 2);
                            $followup = number_format(($followupPatientsCount / $totalUniquePatients) * 100, 2);
                        }
                    @endphp
                    <div class=" col-xl-4 col-12">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="box">
                                    <div class="box-body px-0 text-center">
                                        <div style="min-height: 156px;">
                                            <div id="dashboard_patientschart"></div>
                                        </div>
                                        <div class="mt-15 d-inline-block">
                                            <div class="text-start mb-10">
                                                <span class="badge badge-xl badge-dot badge-primary me-15"></span>
                                                Woman {{ $woman ? $woman : '-' }}%
                                            </div>
                                            <div class="text-start">
                                                <span class="badge badge-xl badge-dot badge-primary-light me-15"></span>
                                                Man {{ $man ? $man : '-' }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <h4>New Patients</h4>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="fs-40 my-0 countnm">{{ $newPatientsCount }}</h2>
                                            <div>
                                                <span
                                                    class="badge badge-pill
                                                {{ $newPatientsCount >= $followupPatientsCount ? 'badge-success-light' : 'badge-danger-light' }}">
                                                    <i
                                                        class="fa me-10 {{ $newPatientsCount >= $followupPatientsCount ? 'fa-angle-up' : 'fa-angle-down' }}"></i>
                                                    {{ $new ? $new : '-' }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box">
                                    <div class="box-body">
                                        <h4>Old Patients</h4>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="fs-40 my-0 countnm">{{ $followupPatientsCount }}</h2>
                                            <div>
                                                <span
                                                    class="badge badge-pill {{ $followupPatientsCount >= $newPatientsCount ? 'badge-success-light' : 'badge-danger-light' }}">
                                                    <i
                                                        class="fa me-10 {{ $followupPatientsCount >= $newPatientsCount ? 'fa-angle-up' : 'fa-angle-down' }}"></i>
                                                    {{ $followup ? $followup : '-' }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-12">
                        <div class="box b-0 bg-transparent">
                            <div class="box-body pt-5 pb-0 px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">Your Patients Today</h4>
                                    <a href="{{ route('patient.patient_list') }}" class="">All Patients <i
                                            class="ms-10 fa fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-body p-15 dashboardpatients">
                                <div class="inner-user-div22">
                                    @if ($currentappointments->isNotEmpty())
                                        @foreach ($currentappointments as $currentappointment)
                                            <div class="mb-10 d-flex justify-content-between align-items-center">
                                                <div class="fw-600 min-w-120">
                                                    <?= date('g:i A', strtotime($currentappointment->app_time)) ?>
                                                </div>
                                                <div title="{{ $currentappointment->app_status == '2' ? 'patient is waiting' : '' }}"
                                                    class="w-p100 p-10 rounded10 justify-content-between align-items-center d-flex doctodaydash {{ $currentappointment->app_status == '2' ? 'bg-lighter b-1 border-warning' : 'bg-lightest' }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span title="Token No"
                                                            class="me-10 avatar bg-primary-light rounded-circle b-1 text-bold">
                                                            <?= $currentappointment->token_no ?>
                                                        </span>
                                                        <div>
                                                            <h6 class="mb-0">
                                                                <?= str_replace('<br>', ' ', $currentappointment->patient->first_name . ' ' . $currentappointment->patient->last_name) ?>
                                                            </h6>
                                                            <p class="mb-0 fs-12 text-mute">Patient ID:
                                                                <?= $currentappointment->app_id ?></p>
                                                        </div>
                                                    </div>
                                                    @php
                                                        $parent_id = $currentappointment->app_parent_id
                                                            ? $currentappointment->app_parent_id
                                                            : $currentappointment->id;
                                                        $base64Id = base64_encode($currentappointment->id);
                                                        $idEncrypted = Crypt::encrypt($base64Id);
                                                    @endphp
                                                    <div class="d-flex dashboardbtnwrapper">
                                                        <?php 
                                                        if (Auth::user()->can('treatments') && (Auth::user()->id == $currentappointment->doctor_id || Auth::user()->is_admin)) { 
                                                            if ($currentappointment->app_date == date('Y-m-d') && $currentappointment->app_status != AppointmentStatus::MISSED) {
                                                                    $bills = PatientTreatmentBilling::where('appointment_id', $currentappointment->id)->where('status', 'Y')->get();
                                                                    if ($bills->isEmpty()) {?>
                                                        <a href='{{ route('treatment', $idEncrypted) }}'
                                                            class='waves-effect waves-light btn btn-circle btn-primary btn-xs me-1'
                                                            title='treatment'><i class='fa-solid fa-stethoscope'></i></a>
                                                        <?php } 
                                                            }
                                                        } ?>
                                                        {{-- <a href=''
                                                            class='waves-effect waves-light btn btn-circle btn-info btn-xs me-1'
                                                            title='view'><i class='fa-solid fa-eye'></i></a> --}}
                                                        <!-- <button type='button'
                                                                                                class='waves-effect waves-light btn btn-circle btn-success btn-add btn-xs me-1'
                                                                                                title='follow up' data-bs-toggle='modal'
                                                                                                data-id='{{ $currentappointment->id }}'
                                                                                                data-parent-id='{{ $parent_id }}'
                                                                                                data-patient-id='{{ $currentappointment->patient->patient_id }}'
                                                                                                data-patient-name='{{ str_replace('<br>', ' ', $currentappointment->patient->first_name . ' ' . $currentappointment->patient->last_name) }}'
                                                                                                data-bs-target='#modal-booking'><i
                                                                                                    class='fa fa-plus'></i></button>

                                                                                            <button type='button'
                                                                                                class='waves-effect waves-light btn btn-circle btn-warning btn-reschedule btn-xs me-1'
                                                                                                title='reschedule' data-bs-toggle='modal'
                                                                                                data-id='{{ $currentappointment->id }}'
                                                                                                data-parent-id='{{ $parent_id }}'
                                                                                                data-patient-id='{{ $currentappointment->patient->patient_id }}'
                                                                                                data-patient-name='{{ str_replace('<br>', ' ', $currentappointment->patient->first_name . ' ' . $currentappointment->patient->last_name) }}'
                                                                                                data-bs-target='#modal-reschedule'><i
                                                                                                    class='fa-solid fa-calendar-days'></i></button>

                                                                                            <button type='button' id="cancelbtn"
                                                                                                class='waves-effect waves-light btn btn-circle btn-danger btn-xs'
                                                                                                title='cancel' data-bs-toggle='modal'
                                                                                                data-bs-target='#modal-cancel'
                                                                                                data-id='{{ $currentappointment->id }}'><i
                                                                                                    class='fa fa-times'></i></button>
                        -->
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="mb-10 d-flex justify-content-between align-items-center">
                                            <h6 class="text-muted mb-1 fs-16">No patients remaining today!</h6>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" col-xl-4 col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title">Analytics</h4>
                            </div>
                            <div class="box-body ps-0 dashboardchart">
                                <div id="dashboardoverview_trend"></div>
                            </div>
                        </div>
                    </div>
                    <div class=" col-xl-4 col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title">Appointments Overview</h4>
                            </div>
                            <div class="box-body dashboardchart">
                                <div id="dashboardchartoverview"></div>
                            </div>
                        </div>
                    </div>
                    <div class=" col-xl-4 col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title">Overall Appointment</h4>
                            </div>
                            <div class="box-body dashboardchart">
                                <div id="dashboardappointment_overview"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->
    <!-- ./wrapper -->
    @include('appointment.booking')
    @include('appointment.reschedule')
    @include('appointment.cancel')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        });
        $(document).ready(function() {

            var totalUniquePatients = @json($totalUniquePatients);
            var malePatientsCount = @json($malePatientsCount);
            var femalePatientsCount = @json($femalePatientsCount);
            var childrenCount = @json($childrenCount);
            var otherCount = @json($otherCount);

            var options = {
                // series: [400, 500],
                series: [femalePatientsCount, malePatientsCount],
                chart: {
                    type: 'donut',
                    width: 186,
                },
                dataLabels: {
                    enabled: false,
                },
                colors: ['#5156be', '#c8c9ee'],
                legend: {
                    show: false,
                },

                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                total: {
                                    showAlways: true,
                                    show: true
                                }
                            },
                        }
                    }
                },
                labels: ["Woman", "Man"],
                responsive: [{
                    breakpoint: 1600,
                    options: {
                        chart: {
                            width: 175,
                        }
                    }
                }, {
                    breakpoint: 500,
                    options: {
                        chart: {
                            width: 200,
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#dashboard_patientschart"), options);
            chart.render();

            var options = {
                series: [malePatientsCount, femalePatientsCount, childrenCount, otherCount],
                chart: {
                    height: 200,
                    type: "polarArea",
                },
                labels: ["Male", "Female", "Child", "Other"],
                fill: {
                    opacity: 1,
                },
                stroke: {
                    width: 1,
                    colors: undefined,
                },
                yaxis: {
                    show: false,
                },
                legend: {
                    position: "right",
                },
                colors: ["#5156be", "#3596f7", "#05825f", "#ee3158"],
                plotOptions: {
                    polarArea: {
                        rings: {
                            strokeWidth: 0,
                        },
                        spokes: {
                            strokeWidth: 0,
                        },
                    },
                },
            };

            var chart = new ApexCharts(document.querySelector("#dashboardchartoverview"), options);
            chart.render();

            function appointmentsbyhour() {
                fetch('/appointments-by-hour')
                    .then(response => response.json())
                    .then(data => {
                        // Assuming data is an array of objects with 'hour' and 'count' fields
                        const hours = [];
                        const counts = [];

                        data.forEach(item => {
                            hours.push(`${item.hour}:00`); // Format hours to "HH:00"
                            counts.push(item.count); // Appointment count
                        });

                        // Initialize the chart with the data
                        var options = {
                            series: [{
                                name: "Total",
                                data: counts,
                            }, ],
                            chart: {
                                type: "bar",
                                height: 205,
                                toolbar: {
                                    show: false,
                                },
                            },
                            colors: ["#5156be"],
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: "20%",
                                    endingShape: "rounded",
                                },
                            },
                            dataLabels: {
                                enabled: false,
                            },
                            grid: {
                                show: false,
                            },
                            stroke: {
                                show: false,
                                width: 0,
                                colors: ["transparent"],
                            },
                            xaxis: {
                                categories: hours,
                            },
                            yaxis: {
                                axisBorder: {
                                    show: false,
                                },
                                axisTicks: {
                                    show: false,
                                },
                                labels: {
                                    show: false,
                                },
                            },
                            fill: {
                                opacity: 1,
                            },
                            tooltip: {
                                y: {
                                    formatter: function(val) {
                                        return val + " Appointment";
                                    },
                                },
                            },
                        };

                        var chart = new ApexCharts(
                            document.querySelector("#dashboardappointment_overview"),
                            options
                        );
                        chart.render();
                    });

            }

            function appointmentsbymonth() {
                fetch('/appointments-by-month')
                    .then(response => response.json())
                    .then(data => {

                        // Get the current month and year
                        const now = new Date();
                        const currentMonth = now.getMonth(); // 0-based index (0 for January)
                        const months = [];
                        const counts = [];

                        // Initialize months and counts for 6 months before and including the current month
                        for (let i = -6; i <= 0; i++) {
                            const monthIndex = (currentMonth + i + 12) % 12; // Calculate month index
                            const monthName = new Date(2020, monthIndex).toLocaleString('default', {
                                month: 'short'
                            }); // Get month name
                            months.push(monthName);
                            counts.push(0); // Initialize count to 0
                        }

                        // Populate counts array based on the fetched data
                        data.forEach(item => {
                            const monthIndex = item.month - 1; // Convert month to 0-based index
                            const relativeIndex = (monthIndex - currentMonth + 12) %
                                12; // Calculate relative index
                            if (relativeIndex >= -6 && relativeIndex <= 0) {
                                counts[relativeIndex + 6] = item.count; // Set count for the month
                            }
                        });

                        // Chart options
                        var options = {
                            series: [{
                                name: "Growth",
                                data: counts,
                            }],
                            chart: {
                                height: 205,
                                type: "area",
                                toolbar: {
                                    show: false,
                                },
                            },
                            colors: ["#05825f"],
                            dataLabels: {
                                enabled: false,
                            },
                            stroke: {
                                curve: "smooth",
                            },
                            grid: {
                                borderColor: "#e7e7e7",
                            },
                            xaxis: {
                                categories: months,
                            },
                            legend: {
                                show: false,
                            },
                        };

                        // Render the chart
                        var chart = new ApexCharts(
                            document.querySelector("#dashboardoverview_trend"),
                            options
                        );
                        chart.render();
                    })
                    .catch(error => console.error('Error fetching data:', error));

            }

            appointmentsbyhour();
            appointmentsbymonth();

            $(document).on('click', '.btn-add', function() {
                var app_parent_id = $(this).data('parent-id');
                var patientId = $(this).data('patient-id');
                var patientName = $(this).data('patient-name');
                $('#patient_id').val(patientId); // Set app ID in the hidden input
                $('#patient_name').val(patientName); // Set app ID in the hidden input
                $('#app_parent_id').val(app_parent_id);

                // var now = new Date();
                // var year = now.getFullYear();
                // var month = (now.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
                // var day = now.getDate().toString().padStart(2, '0');
                // var hours = now.getHours().toString().padStart(2, '0');
                // var minutes = now.getMinutes().toString().padStart(2, '0');
                // var datetime = `${year}-${month}-${day}T${hours}:${minutes}`;

                var now = new Date();
                var datetime = now.toISOString().slice(0, 16);

                document.getElementById('appdate').value = datetime;
                $('#modal-booking').modal('show');
            });

            $(document).on('click', '.btn-reschedule', function() {
                var appId = $(this).data('id');
                var patientId = $(this).data('patient-id');
                var patientName = $(this).data('patient-name');

                $('#reschedule_app_id').val(appId); // Set app ID in the hidden input
                $.ajax({
                    url: '{{ url('appointment', '') }}' + "/" + appId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_app_id').val(response.id);
                        $('#edit_patient_id').val(response
                            .patient_id); // Set app ID in the hidden input
                        $('#edit_patient_name').val(patientName);
                        var doctorName = response.doctor.name;
                        var formattedDoctorName = doctorName.replace(/<br>/g, ' ');
                        $('#edit_doctor').val(formattedDoctorName);

                        $('#edit_clinic_branch').val(response.clinic_branch);
                        $('#edit_doctor_id').val(response.doctor_id);

                        $('#edit_clinic_branch_id').val(response.app_branch);
                        // $('#edit_staff').val(response.staff);
                        var app_date = response.app_date;
                        var app_time = response.app_time;
                        $('#scheduled_appdate').val(app_date + ' ' + app_time);
                        $('#modal-reschedule').modal('show');
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });
            });

            $(document).on('click', '#cancelbtn', function() {
                var appId = $(this).data('id');
                $('#delete_app_id').val(appId); // Set staff ID in the hidden input
                $('#modal-cancel').modal('show');
            });

        });
    </script>
@endsection
