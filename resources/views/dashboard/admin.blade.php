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
                    <div class="col-xxxl-9 col-xl-8 col-12">

                        <div class="row">
                            <div class="col-lg-4 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="d-flex align-items-center" style="min-height: 110px;">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-20.svg" alt=""
                                                    class="w-120" />
                                            </div>
                                            <div>
                                                <h4 class="mb-0">Total Patients</h4>
                                                <h3 class="countnm mb-0" id="total-patient"> <?= $totalPatients ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-18.svg" alt=""
                                                    class="w-120" />
                                            </div>
                                            <div>
                                                <h4 class="mb-0">Total Staffs</h4>
                                                <h3 class="countnm mb-0" id="total-staff"><?= $totalStaffs ?></h3>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div>
                                                <h6 class="mb-0 mt-2"><span
                                                        class="text-xs text-warning">Doctors-</span><span class="countnm"
                                                        id="total-doctor"><?= $totalDoctors ?></span>
                                                    &nbsp; <span class="text-xs text-info">Others-</span><span
                                                        class="countnm" id="total-other"><?= $totalOthers ?></span></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="d-flex align-items-center pb-8" style="min-height: 110px;">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-19.svg" alt=""
                                                    class="w-120" />
                                            </div>
                                            <div>
                                                <h4 class="mb-0">Total Surgery</h4>
                                                <h3 class="countnm mb-0" id="total-surgery"><?= $totalTreatments ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12 col-12">
                                <div class="box">
                                    <div class="box-header pb-3">
                                        <h4 class="box-title">Patient Statistics</h4>
                                    </div>
                                    <div class="box-body">
                                        <div id="monthly_patient_count"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                @include('patient.today.index')
                            </div>
                        </div>
                    </div>
                    <div class="col-xxxl-3 col-xl-4 col-12">
                        <div class="box">
                            <div class="box-header pb-3">
                                <h4 class="box-title">Total Patient</h4>
                            </div>
                            <div class="box-body">
                                <div id="daywise_total_patient"></div>
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-header with-border pb-2">
                                <h4 class="box-title">Doctors List</h4>
                                <p class="mb-0 pull-right">Today</p>
                            </div>
                            <div class="box-body">
                                <div class="inner-user-div3">
                                    <div class="d-flex align-items-center mb-30">
                                        {{-- @if (!empty($workingDoctors)) --}}
                                        @if ($workingDoctors->isNotEmpty())
                                            @foreach ($workingDoctors as $workingDoctor)
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
                                                    // Get the ID from the related model
                                                    $id = $workingDoctor->user->staffProfile->id;

                                                    // Base64 encode the ID
                                                    $base64Id = base64_encode($id);

                                                    // Encrypt the Base64 encoded ID
                                                    $idEncrypted = Crypt::encrypt($base64Id);
                                                @endphp
                                                <a class="px-10 pt-5"
                                                    href="{{ route('staff.staff_list.view', $idEncrypted) }}"><i
                                                        class="fa fa-eye"></i></a>
                                            @endforeach
                                        @else
                                            <h6 class="text-muted mb-1 fs-16">No doctors available today!</h6>
                                        @endif
                                    </div>

                                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        var revisitedPatientsData = @json(array_values($revisitedPatientsData));
        var newlyRegisteredData = @json(array_values($newlyRegisteredData));
        var allData = revisitedPatientsData.concat(newlyRegisteredData);
        var maxValue = Math.max(...allData);
        var minValue = Math.min(...allData);
        var tickAmount = Math.ceil(maxValue / 5);
        var options = {
            series: [{
                name: 'Revisited',
                data: @json(array_values($revisitedPatientsData))
            }, {
                name: 'Newly Registered',
                data: @json(array_values($newlyRegisteredData))
            }],
            chart: {
                type: 'bar',
                foreColor: "#bac0c7",
                height: 260,
                stacked: true,
                toolbar: {
                    show: false,
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '30%',
                },
            },
            dataLabels: {
                enabled: false,
            },
            grid: {
                show: true,
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            colors: ['#5156be', '#ffa800'],
            xaxis: {
                categories: @json($months),
            },
            yaxis: {
                title: {
                    text: 'Patient Statistics'
                },
                tickAmount: maxValue, // Dynamically set tickAmount
                labels: {
                    formatter: function(value) {
                        return Math.floor(value); // Ensure integer values
                    }
                }
            },

            legend: {
                show: true,
                position: 'top',
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " patient";
                    }
                },
                marker: {
                    show: false,
                },
            }
        };

        var chart = new ApexCharts(document.querySelector("#monthly_patient_count"), options);
        chart.render();

        var options = {
            series: [{
                name: 'Total Patient',
                type: 'column',
                data: @json($chartTotalPatients)
            }, {
                name: 'Followup Patient',
                type: 'line',
                data: @json($chartfollowupPatients)
            }],
            chart: {
                height: 350,
                type: 'line',
                toolbar: {
                    show: false,
                }
            },
            stroke: {
                width: [0, 4],
                curve: 'smooth'
            },
            colors: ['#E7E4FF', '#5156be'],
            dataLabels: {
                enabled: false,
            },
            labels: @json($dates),
            xaxis: {
                type: 'datetime'
            },
            legend: {
                show: true,
                position: 'top',
            }
        };

        var chart = new ApexCharts(document.querySelector("#daywise_total_patient"), options);
        chart.render();
    </script>
@endsection
