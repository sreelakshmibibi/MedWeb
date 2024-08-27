<?php
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
                        <div class="box">
                            <div class="box-header with-border pb-2">
                                <h4 class="box-title">Doctors List</h4>
                                <p class="mb-0 pull-right">Today</p>
                            </div>
                            <div class="box-body" style="height:194px;">
                                <div class="inner-user-div2">

                                    @if ($workingDoctors->isNotEmpty())
                                        @foreach ($workingDoctors as $workingDoctor)
                                            <div class="d-flex align-items-center mb-30 dashboarddoclistdiv">
                                                <div class="me-15">
                                                    @if ($workingDoctor->user->staffProfile->photo != '')
                                                        <img src="{{ asset('storage/' . $workingDoctor->user->staffProfile->photo) }}"
                                                            class="avatar avatar-lg rounded10 bg-primary-light"
                                                            alt="" />
                                                    @else
                                                        <img src="{{ asset('images/svg-icon/user.svg') }}" alt="photo"
                                                            class="avatar avatar-lg rounded10 bg-primary-light">
                                                    @endif
                                                </div>

                                                <div class="d-flex flex-column flex-grow-1 fw-500">
                                                    <a href="#" class="text-dark hover-primary mb-1 fs-16">
                                                        <?= str_replace('<br>', ' ', $workingDoctor->user->name) ?>
                                                    </a>
                                                    <span
                                                        class="text-fade"><?= $workingDoctor->user->staffProfile->designation ?>
                                                    </span>
                                                </div>
                                                @php
                                                    $totaltokens = $workingDoctor->appointments_count;
                                                    $completedtokens = $workingDoctor->appointments_completed_count;
                                                    $remainingtokens = $totaltokens - $completedtokens;
                                                    $tokenpecent = 0;
                                                    if ($totaltokens != 0) {
                                                        $tokenpecent = ($completedtokens / $totaltokens) * 100;
                                                    }
                                                @endphp

                                                <div class="col-xl-4 col-md-4 col-12">
                                                    <div class="box box-body no-border p-0 mb-0">
                                                        <div class="fs-14 flexbox align-items-center">
                                                            <span>Tokens</span>
                                                            <span>{{ $totaltokens ?? 0 }}</span>
                                                        </div>

                                                        <div class="progress progress-xxs mt-1 mb-1">
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{ $tokenpecent }}%; height: 4px;"
                                                                aria-valuenow="{{ $tokenpecent }}" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>

                                                        <div class="d-flex justify-content-between">
                                                            <div class="text-start"><span
                                                                    class="text-muted me-1">Completed:</span>
                                                                <span class="text-dark">{{ $completedtokens ?? 0 }}</span>
                                                            </div>
                                                            <div class="text-end"><span
                                                                    class="text-muted me-1">Remaining:</span>
                                                                <span
                                                                    class="text-warning">{{ $remainingtokens ?? 0 }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="d-flex align-items-center mb-30">
                                            <h6 class="text-muted mb-1 fs-16">No doctors available today!</h6>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" col-xl-12 col-12">
                        @include('patient.today.index')
                    </div>

                </div>
            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->
    <!-- ./wrapper -->
    <script>
        $(document).ready(function() {

            var totalUniquePatients = @json($totalUniquePatients);
            var malePatientsCount = @json($malePatientsCount);
            var femalePatientsCount = @json($femalePatientsCount);

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

        });
    </script>
@endsection
