@extends('layouts.dashboard')
@section('title', 'Dashboard')
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
                                                <h3 class="mb-0">1245</h3>
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
                                                <h3 class="mb-0">240</h3>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div>
                                                <h6 class="mb-0 mt-2"><span class="text-xs text-warning">Doctors-</span>
                                                    45
                                                    &nbsp; <span class="text-xs text-info">Others-</span>
                                                    195</h6>
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
                                                <h3 class="mb-0">245</h3>
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
                                <div class="box">
                                    <div class="box-header with-border">
                                        {{-- <h4 class="box-title">Today's Patients</h4> --}}
                                        <h4 class="box-title">Patients List</h4>
                                        <p class="mb-0 pull-right">Today</p>
                                        <div class="box-controls pull-right">
                                            <div class="lookup lookup-circle lookup-right">
                                                <input type="text" name="s">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-body no-padding">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                {{-- <table class="table mb-0 table-striped"> --}}
                                                <tbody>
                                                    {{-- <tr class="bg-info-light"> --}}
                                                    <tr class="bg-primary-light">
                                                        <th>No</th>
                                                        {{-- <th>Date</th> --}}
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Age</th>
                                                        <th>City</th>
                                                        <th>Gender</th>
                                                        <th>Doctor</th>
                                                        <th>Settings</th>
                                                    </tr>
                                                    <tr>
                                                        <td>01</td>
                                                        {{-- <td>01/08/2021</td> --}}
                                                        <td>DO-124585</td>
                                                        <td><strong>Shawn Hampton</strong></td>
                                                        <td>27</td>
                                                        <td>Miami</td>
                                                        <td>Male</td>
                                                        <td>Dr.Samuel</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="#"
                                                                    class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5"><i
                                                                        class="fa fa-pencil"></i></a>
                                                                <a href="#"
                                                                    class="waves-effect waves-circle btn btn-circle btn-danger btn-xs"><i
                                                                        class="fa fa-trash"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>02</td>
                                                        {{-- <td>01/08/2021</td> --}}
                                                        <td>DO-412577</td>
                                                        <td><strong>Polly Paul</strong></td>
                                                        <td>31</td>
                                                        <td>Naples</td>
                                                        <td>Female</td>
                                                        <td>Dr.Geeta</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="#"
                                                                    class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5"><i
                                                                        class="fa fa-pencil"></i></a>
                                                                <a href="#"
                                                                    class="waves-effect waves-circle btn btn-circle btn-danger btn-xs"><i
                                                                        class="fa fa-trash"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>03</td>
                                                        {{-- <td>01/08/2021</td> --}}
                                                        <td>DO-412151</td>
                                                        <td><strong>Harmani Doe</strong></td>
                                                        <td>21</td>
                                                        <td>Destin</td>
                                                        <td>Female</td>
                                                        <td>Dr.Gaya</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="#"
                                                                    class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5"><i
                                                                        class="fa fa-pencil"></i></a>
                                                                <a href="#"
                                                                    class="waves-effect waves-circle btn btn-circle btn-danger btn-xs"><i
                                                                        class="fa fa-trash"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>04</td>
                                                        {{-- <td>01/08/2021</td> --}}
                                                        <td>DO-123654</td>
                                                        <td><strong>Mark Wood</strong></td>
                                                        <td>30</td>
                                                        <td>Orlando</td>
                                                        <td>Male</td>
                                                        <td>Dr.Geeta</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="#"
                                                                    class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5"><i
                                                                        class="fa fa-pencil"></i></a>
                                                                <a href="#"
                                                                    class="waves-effect waves-circle btn btn-circle btn-danger btn-xs"><i
                                                                        class="fa fa-trash"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>05</td>
                                                        {{-- <td>01/08/2021</td> --}}
                                                        <td>DO-159874</td>
                                                        <td><strong>Johen Doe</strong></td>
                                                        <td>58</td>
                                                        <td>Tampa</td>
                                                        <td>Male</td>
                                                        <td>Dr.Raghu</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="#"
                                                                    class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5"><i
                                                                        class="fa fa-pencil"></i></a>
                                                                <a href="#"
                                                                    class="waves-effect waves-circle btn btn-circle btn-danger btn-xs"><i
                                                                        class="fa fa-trash"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="box-footer bg-light py-10 with-border">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0">Total 90 Patient</p>
                                            <a type="button" class="waves-effect waves-light btn btn-primary">View
                                                All</a>
                                        </div>
                                    </div>
                                </div>
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
                                        <div class="me-15">
                                            <img src="images/avatar/avatar-1.png"
                                                class="avatar avatar-lg rounded10 bg-primary-light" alt="" />
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <a href="#" class="text-dark hover-primary mb-1 fs-16">Dr.
                                                Jaylon Stanton</a>
                                            <span class="text-fade">Dentist</span>
                                        </div>
                                        <div class="dropdown">
                                            <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i
                                                    class="ti-more-alt"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Inbox</span>
                                                    <span class="badge badge-pill badge-info">5</span>
                                                </a>
                                                <a class="dropdown-item" href="#">Sent</a>
                                                <a class="dropdown-item" href="#">Spam</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Draft</span>
                                                    <span class="badge badge-pill badge-default">1</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-30">
                                        <div class="me-15">
                                            <img src="images/avatar/avatar-10.png"
                                                class="avatar avatar-lg rounded10 bg-primary-light" alt="" />
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <a href="#" class="text-dark hover-danger mb-1 fs-16">Dr.
                                                Carla Schleifer</a>
                                            <span class="text-fade">Oculist</span>
                                        </div>
                                        <div class="dropdown">
                                            <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i
                                                    class="ti-more-alt"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Inbox</span>
                                                    <span class="badge badge-pill badge-info">5</span>
                                                </a>
                                                <a class="dropdown-item" href="#">Sent</a>
                                                <a class="dropdown-item" href="#">Spam</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Draft</span>
                                                    <span class="badge badge-pill badge-default">1</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-30">
                                        <div class="me-15">
                                            <img src="images/avatar/avatar-10.png"
                                                class="avatar avatar-lg rounded10 bg-primary-light" alt="" />
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <a href="#" class="text-dark hover-danger mb-1 fs-16">Dr.
                                                Carla Schleifer</a>
                                            <span class="text-fade">Oculist</span>
                                        </div>
                                        <div class="dropdown">
                                            <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i
                                                    class="ti-more-alt"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Inbox</span>
                                                    <span class="badge badge-pill badge-info">5</span>
                                                </a>
                                                <a class="dropdown-item" href="#">Sent</a>
                                                <a class="dropdown-item" href="#">Spam</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Draft</span>
                                                    <span class="badge badge-pill badge-default">1</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-30">
                                        <div class="me-15">
                                            <img src="images/avatar/avatar-11.png"
                                                class="avatar avatar-lg rounded10 bg-primary-light" alt="" />
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <a href="#" class="text-dark hover-success mb-1 fs-16">Dr.
                                                Hanna Geidt</a>
                                            <span class="text-fade">Surgeon</span>
                                        </div>
                                        <div class="dropdown">
                                            <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i
                                                    class="ti-more-alt"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Inbox</span>
                                                    <span class="badge badge-pill badge-info">5</span>
                                                </a>
                                                <a class="dropdown-item" href="#">Sent</a>
                                                <a class="dropdown-item" href="#">Spam</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Draft</span>
                                                    <span class="badge badge-pill badge-default">1</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-30">
                                        <div class="me-15">
                                            <img src="images/avatar/avatar-12.png"
                                                class="avatar avatar-lg rounded10 bg-primary-light" alt="" />
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <a href="#" class="text-dark hover-info mb-1 fs-16">Dr. Roger
                                                George</a>
                                            <span class="text-fade">General Practitioners</span>
                                        </div>
                                        <div class="dropdown">
                                            <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i
                                                    class="ti-more-alt"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Inbox</span>
                                                    <span class="badge badge-pill badge-info">5</span>
                                                </a>
                                                <a class="dropdown-item" href="#">Sent</a>
                                                <a class="dropdown-item" href="#">Spam</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Draft</span>
                                                    <span class="badge badge-pill badge-default">1</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="me-15">
                                            <img src="images/avatar/avatar-15.png"
                                                class="avatar avatar-lg rounded10 bg-primary-light" alt="" />
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <a href="#" class="text-dark hover-warning mb-1 fs-16">Dr.
                                                Natalie doe</a>
                                            <span class="text-fade">Physician</span>
                                        </div>
                                        <div class="dropdown">
                                            <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i
                                                    class="ti-more-alt"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Inbox</span>
                                                    <span class="badge badge-pill badge-info">5</span>
                                                </a>
                                                <a class="dropdown-item" href="#">Sent</a>
                                                <a class="dropdown-item" href="#">Spam</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Draft</span>
                                                    <span class="badge badge-pill badge-default">1</span>
                                                </a>
                                            </div>
                                        </div>
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
