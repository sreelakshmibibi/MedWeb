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
                                        <div class="d-flex align-items-center">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-20.svg" alt=""
                                                    class="w-120" />
                                            </div>
                                            <div>
                                                <h4 class="mb-0">Total Patients</h4>
                                                <h3 class="countnm mb-0" id="total-patient"> <?= $totalPatients ?></h3>
                                            </div>
                                        </div>
                                        <pre class="mt-2"> </pre>
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
                                        <div class="d-flex align-items-center pb-8">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-19.svg" alt=""
                                                    class="w-120" />
                                            </div>
                                            <div>
                                                <h4 class="mb-0">Total Surgery</h4>
                                                <h3 class="countnm mb-0" id="total-surgery">245</h3>
                                            </div>
                                        </div>
                                        <pre class="mt-2"> </pre>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12 col-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h4 class="box-title">Patient Statistics</h4>
                                    </div>
                                    <div class="box-body">
                                        <div id="patient_statistics"></div>
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
                            <div class="box-header">
                                <h4 class="box-title">Total Patient</h4>
                            </div>
                            <div class="box-body">
                                <div id="total_patient"></div>
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Doctors List</h4>
                                <p class="mb-0 pull-right">Today</p>
                            </div>
                            <div class="box-body">
                                <div class="inner-user-div3">
                                    <div class="d-flex align-items-center mb-30">
                                        @if (!empty($workingDoctors))
                                            @foreach ($workingDoctors as $workingDoctor)
                                                <div class="me-15">
                                                    <img src="{{ asset('storage/' . $workingDoctor->user->staffProfile->photo) }}"
                                                        class="avatar avatar-lg rounded10 bg-primary-light"
                                                        alt="" />
                                                </div>

                                                <div class="d-flex flex-column flex-grow-1 fw-500">
                                                    <a href="#" class="text-dark hover-primary mb-1 fs-16">
                                                        <?= str_replace('<br>', ' ', $workingDoctor->user->name) ?>
                                                    </a>
                                                    <span
                                                        class="text-fade"><?= $workingDoctor->user->staffProfile->designation ?>
                                                    </span>
                                                </div>
                                                <a class="px-10 pt-5"
                                                    href="{{ route('staff.staff_list.view', $workingDoctor->user->staffProfile->id) }}"><i
                                                        class="fa fa-eye"></i></a>
                                            @endforeach
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
@endsection
