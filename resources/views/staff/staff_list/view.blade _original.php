@extends('layouts.dashboard')
@section('title', 'Staff')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                @if (session('success'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-success alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('success') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                @if (session('error'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fade fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('error') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Staff Details</h3>
                    <div>
                        <a href="' . route('staff.staff_list.edit', $row->id) . '"
                            class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1"
                            title="edit"><i class="fa fa-pencil"></i></a>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-xs"
                            data-bs-toggle="modal" data-bs-target="#modal-status" data-id="' . $row->id . '"
                            title="change status"><i class="fa-solid fa-sliders"></i></button>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="row">
                    <div class="col-xl-8 col-12">
                        <div class="box">
                            <div class="box-body d-flex align-items-center">
                                <img src="{{ asset('images/avatar/avatar-1.png') }}" alt=""
                                    class="bg-success-light rounded10 me-20 align-self-end h-100">
                                <div class="d-flex flex-column flex-grow-1">
                                    <a href="#" class="box-title text-muted fw-600 fs-18 mb-2 hover-primary">Dr. John
                                        Doe</a>
                                    <span class="fw-500 text-fade">Qualification</span>
                                    <span class="fw-500 text-fade">16 years of Experience</span>
                                </div>
                                {{-- <img src="{{ asset('images/avatar/avatar-1.png') }}" alt=""
                                    class="align-self-end h-100"> --}}
                                {{-- <div class="box-body text-end min-h-150"
                                    style="background-image:url({{ asset('images/gallery/landscape14.jpg') }}); background-repeat: no-repeat; background-position: center;background-size: cover;"> --}}
                                {{-- <div class="bg-success rounded10 p-15 fs-18 d-inline min-h-50"><i
                                        class="fa fa-stethoscope"></i> --}}
                                <div class="bg-success rounded10 p-5 fs-18 d-inline min-h-10"><i
                                        class="fa fa-stethoscope"></i>
                                    ENT
                                    Designation</div>
                                {{-- </div> --}}
                            </div>
                            <div class="box-body">
                                <h4>About</h4>
                                <p>Vestibulum tincidunt sit amet sapien et eleifend. Fusce pretium libero enim, nec lacinia
                                    est
                                    ultrices id. Duis nibh sapien, ultrices in hendrerit ac, pulvinar ut mauris. Quisque eu
                                    condimentum justo. In consectetur dapibus justo, et dapibus augue pellentesque sed.
                                    Etiam
                                    pulvinar pharetra est, at euismod augue vulputate sed. Morbi id porta turpis, a porta
                                    turpis.
                                    Suspendisse maximus finibus est at pellentesque. Integer ut sapien urna.</p>
                                {{-- <ul class="nav d-block nav-stacked">
                                    <li class="nav-item">Addhaar No. <span>1310</span></li>
                                    <li class="nav-item">Gender <span>120</span>
                                    </li>
                                    <li class="nav-item">Date of Joining <span>8K</span></li>
                                    <li class="nav-item">License <span>58</span>
                                    </li>
                                    <li class="nav-item">Residential Adddress <span>58</span></li>
                                </ul> --}}

                                {{-- <h5>Specialized in</h5>
                                <span class="badge badge-secondary">Secondary</span> --}}

                                <ul class="flexbox flex-justified text-center p-20">
                                    <li>
                                        <span class="text-muted">Specialized in</span><br>
                                        <span class="fs-20">Dentistry</span>
                                    </li>
                                    <li class="be-1 bs-1 border-light">
                                        <span class="text-muted">Department</span><br>
                                        <span class="fs-20">Dental Medicine</span>
                                    </li>
                                    <li>
                                        <span class="text-muted">Subspeciality</span><br>
                                        <span class="fs-20">Pathology</span>
                                    </li>
                                </ul>


                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h4 class="mb-0">Other Information</h4>
                                    </div>
                                    <div class="box-body">
                                        <div class="inner-user-div5">
                                            {{-- <h4>Patients</h4> --}}
                                            {{-- <ul class="d-flex justify-content-between align-items-center">
                                                <li class="min-w-120">Role <span>120</span></li>
                                            </ul> --}}


                                            {{-- <div class="mb-10 d-flex justify-content-between align-items-center">
                                                <div class="fw-600 min-w-120">
                                                    Role
                                                </div>
                                                <div
                                                    class="w-p100 p-10 rounded10 justify-content-between align-items-center d-flex bg-lightest">
                                                    Admin
                                                </div>
                                            </div> --}}
                                            <ul class="nav d-block nav-stacked">
                                                <li class="nav-item d-flex justify-start align-items-center">
                                                    <div class="min-w-120 text-muted">
                                                        Role
                                                    </div>
                                                    <div>
                                                        Admin
                                                    </div>
                                                </li>
                                                <li class="nav-item d-flex justify-start align-items-center">
                                                    <div class="min-w-120 text-muted">
                                                        Date of Joining
                                                    </div>
                                                    <div>
                                                        12-08-2024
                                                    </div>
                                                </li>
                                                <li class="nav-item d-flex justify-start align-items-center">
                                                    <div class="min-w-120 text-muted">
                                                        Gender
                                                    </div>
                                                    <div>
                                                        Male
                                                    </div>
                                                </li>
                                                <li class="nav-item d-flex justify-start align-items-center">
                                                    <div class="min-w-120 text-muted">
                                                        Date of Birth
                                                    </div>
                                                    <div>
                                                        12-01-1996
                                                    </div>
                                                </li>
                                                <li class="nav-item d-flex justify-start align-items-center">
                                                    <div class="min-w-120 text-muted">
                                                        Aadhaar no
                                                    </div>
                                                    <div>
                                                        120456789123
                                                    </div>
                                                </li>
                                                <li class="nav-item d-flex justify-start align-items-center">
                                                    <div class="min-w-120 text-muted">
                                                        License No.
                                                    </div>
                                                    <div>
                                                        1234567890
                                                    </div>
                                                </li>
                                                <li class="nav-item d-flex justify-start align-items-center">
                                                    <div class="min-w-120 text-muted">
                                                        Res. Address
                                                    </div>
                                                    <div>
                                                        add1, add2, Thiruvanathapuram
                                                    </div>
                                                </li>
                                                {{-- <li class="nav-item">Role <span>120</span>
                                                </li>
                                                <li class="nav-item">Addhaar No. <span>1310</span></li>
                                                <li class="nav-item">Gender <span>120</span></li>
                                                <li class="nav-item">Date of Birth <span>8K</span></li>
                                                <li class="nav-item">Date of Joining <span>8K</span></li>
                                                <li class="nav-item">License <span>58</span></li>
                                                <li class="nav-item">Residential Adddress <span>58</span></li> --}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="box">
                                    <div class="box-body px-0 text-center">
                                        <div style="min-height: 156px;">
                                            <div id="chart124"></div>
                                        </div>
                                        <div class="mt-15 d-inline-block">
                                            <div class="text-start mb-10">
                                                <span class="badge badge-xl badge-dot badge-primary me-15"></span> Woman
                                                44%
                                            </div>
                                            <div class="text-start">
                                                <span class="badge badge-xl badge-dot badge-primary-light me-15"></span>
                                                Man
                                                55%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <h4>Patients</h4>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="fs-40 my-0">67</h2>
                                            <div>
                                                <span class="badge badge-pill badge-success-light"><i
                                                        class="fa fa-angle-up me-10"></i> 39%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box">
                                    <div class="box-body">
                                        <h4>Surgeries</h4>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="fs-40 my-0">27</h2>
                                            <div>
                                                <span class="badge badge-pill badge-danger-light"><i
                                                        class="fa fa-angle-down me-10"></i>04%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-12">
                        <div class="box">
                            <div class="box-body box-profile">
                                <div class="row">
                                    <div class="col-12">
                                        <div>
                                            {{-- <p><i class="fa-solid fa-envelope"></i> Email :<span
                                                    class="text-gray ps-10">David@yahoo.com</span> </p>
                                            <p><i class="fa-solid fa-phone"></i> Phone :<span class="text-gray ps-10">+11
                                                    123 456 7890</span></p>
                                            <p><i class="fa-solid fa-location-dot"></i> Address :<span class="text-gray ps-10">123,
                                                    Lorem Ipsum, Florida,
                                                    USA</span></p> --}}
                                            <p><i class="fa-solid fa-envelope text-muted"> </i> <span
                                                    class="text-gray ps-10">David@yahoo.com</span> </p>
                                            <p><i class="fa-solid fa-phone text-muted"></i> <span
                                                    class="text-gray ps-10">+11
                                                    123 456 7890</span></p>
                                            <p><i class="fa-solid fa-location-dot text-muted"></i> <span
                                                    class="text-gray ps-10">123,
                                                    Lorem Ipsum, Florida,
                                                    USA</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        {{-- <div class="col-lg-9 col-md-8"> --}}
                        {{-- <div class="box">
                            <div class="box-title"></div>
                            <div class="media-list media-list-divided media-list-hover">
                                <div class="media align-items-center">
                                    <span class="badge badge-dot badge-danger"></span>
                                    
                                    <div class="media-body">
                                        <p>
                                            <a href="#"><strong>Aaron</strong></a>
                                            <small class="sidetitle">Online</small>
                                        </p>
                                        <p>Designer</p>

                                       
                                    </div>

                                    <div class="media-right gap-items">
                                        <a class="media-action lead" href="#" data-bs-toggle="tooltip"
                                            title="Orders"><i class="ti-eye"></i></a>
                                     
                                       

                                    </div>
                                </div>

                                <div class="media align-items-center">

                                    <span class="badge badge-dot badge-warning"></span>

                                   

                                    <div class="media-body">
                                        <p>
                                            <a href="#"><strong>Isaiah</strong></a>
                                            <small class="sidetitle">Last seen: 2 hours ago</small>
                                        </p>
                                        <p>Full Stack Developer</p>

                                      
                                    </div>

                                    <div class="media-right gap-items">
                                        <a class="media-action lead" href="#" data-bs-toggle="tooltip"
                                            title="Orders"><i class="ti-eye"></i></a>
                                       

                                    </div>
                                </div>

                                <div class="media align-items-center">

                                    <span class="badge badge-dot badge-success"></span>

                                   

                                    <div class="media-body">
                                        <p>
                                            <a href="#"><strong>Cameron</strong></a>
                                            <small class="sidetitle">Last seen: 12 min ago</small>
                                        </p>
                                        <p>Support Agent</p>

                                      
                                    </div>

                                    <div class="media-right gap-items">
                                        <a class="media-action lead" href="#" data-bs-toggle="tooltip"
                                            title="Orders"><i class="ti-eye"></i></a>
                                      

                                    </div>
                                </div>

                                <div class="media align-items-center">

                                    <span class="badge badge-dot badge-danger"></span>

                                    <div class="media-body">
                                        <p>
                                            <a href="#"><strong>Eli</strong></a>
                                            <small class="sidetitle">Online</small>
                                        </p>
                                        <p>Support Agent</p>

                                      
                                    </div>

                                    <div class="media-right gap-items">
                                        <a class="media-action lead" href="#" data-bs-toggle="tooltip"
                                            title="Orders"><i class="ti-eye"></i></a>
                                     

                                    </div>
                                </div>

                                <div class="media align-items-center">

                                    <span class="badge badge-dot badge-success"></span>

                               
                                    <div class="media-body">
                                        <p>
                                            <a href="#"><strong>Charles</strong></a>
                                            <small class="sidetitle">Last seen: yesterday</small>
                                        </p>
                                        <p>Marketing Department</p>

                                       
                                    </div>

                                    <div class="media-right gap-items">
                                        <a class="media-action lead" href="#" data-bs-toggle="tooltip"
                                            title="Orders"><i class="ti-eye"></i></a>
                                       

                                    </div>
                                </div>

                            </div>
                        </div> --}}
                        {{-- <div class="col-lg-3 col-12"> --}}
                        {{-- <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h4 class="card-title b-0 px-0 pt-0">Branch 1</h4>
                                    {{-- <span class="badge badge-dot badge-success" title="active"></span> --}}
                        {{-- }}   </div>
                    <p><span class="text-muted"><i class="fa fa-location-dot"></i>
                        </span>&nbsp;Thrissur, New Dental Clinic</p>
                    <p><span class="text-muted"><i class="fa fa-phone"></i>
                        </span>&nbsp;7894561230</p>
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title b-0 px-0 pt-0">Department</h4>
                        {{-- <span class="badge badge-dot badge-success" title="active"></span> --}}
                        {{-- }}     </div>
                    <p><span class="text-muted"></i>
                        </span>&nbsp;Thrissur, New Dental Clinic</p>

                </div>

        </div> --}}
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title">Appointments</h4>
                            </div>
                            <div class="box-body">
                                <div id="paginator1"></div>
                            </div>
                            <div class="box-body">
                                <div class="inner-user-div4">
                                    {{-- <div class="table-responsive">
                                        <!-- Main content -->
                                        <table
                                            class="table table-bordered table-hover table-striped mb-0 border-2 data-table text-center">
                                            <thead class="bg-primary-light">
                                                <tr>
                                                    <th>Token No</th>
                                                    <th>Patient ID</th>
                                                    <th>First Name</th>
                                                    <th>Phone Number</th>
                                                    <th>Appointment Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>123</td>
                                                    <td>hi</td>
                                                    <td>1234567890</td>
                                                    <td>scheduld</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- /.content -->
                                    </div> --}}
                                    <div>
                                        <div class="d-flex align-items-center mb-15 py-10 bb-dashed border-bottom">
                                            <div class="me-15">
                                                {{-- <img src="../images/avatar/avatar-1.png"
                                                    class="avatar avatar-lg rounded10 bg-primary-light" alt="" /> --}}
                                                <p class="mb-0 text-muted"><i class="fa fa-clock-o me-5"></i> 10:00

                                                </p>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1 fw-500">
                                                <p class="hover-primary text-fade mb-1 fs-16">Shawn Hampton</p>
                                                <span class="text-dark fs-14">reason</span>
                                            </div>
                                            <div>
                                                <a href="#"
                                                    class="waves-effect waves-circle btn btn-circle btn-primary-light btn-sm"><i
                                                        class="fa-solid fa-eye"></i></a>
                                            </div>
                                        </div>
                                        {{-- <div
                                            class="d-flex justify-content-between align-items-end mb-15 py-10 bb-dashed border-bottom">
                                            <div>
                                                <p class="mb-0 text-muted"><i class="fa fa-clock-o me-5"></i> 10:00
                                                    <span class="mx-20">$ 30</span>
                                                </p>
                                            </div>
                                            <div>
                                                <div class="dropdown">
                                                    <a data-bs-toggle="dropdown" href="#"
                                                        class="base-font mx-10"><i class="ti-more-alt text-muted"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                                            Import</a>
                                                        <a class="dropdown-item" href="#"><i class="ti-export"></i>
                                                            Export</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ti-printer"></i>
                                                            Print</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ti-settings"></i>
                                                            Settings</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center mb-10">
                                            <div class="me-15">
                                                <img src="../images/avatar/avatar-2.png"
                                                    class="avatar avatar-lg rounded10 bg-primary-light" alt="" />
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1 fw-500">
                                                <p class="hover-primary text-fade mb-1 fs-14">Polly Paul</p>
                                                <span class="text-dark fs-16">USG + Consultation</span>
                                            </div>
                                            <div>
                                                <a href="#"
                                                    class="waves-effect waves-circle btn btn-circle btn-primary-light btn-sm"><i
                                                        class="fa fa-phone"></i></a>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between align-items-end mb-15 py-10 bb-dashed border-bottom">
                                            <div>
                                                <p class="mb-0 text-muted"><i class="fa fa-clock-o me-5"></i> 10:30
                                                    <span class="mx-20">$ 50</span>
                                                </p>
                                            </div>
                                            <div>
                                                <div class="dropdown">
                                                    <a data-bs-toggle="dropdown" href="#"
                                                        class="base-font mx-10"><i class="ti-more-alt text-muted"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                                            Import</a>
                                                        <a class="dropdown-item" href="#"><i class="ti-export"></i>
                                                            Export</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ti-printer"></i> Print</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ti-settings"></i> Settings</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center mb-10">
                                            <div class="me-15">
                                                <img src="../images/avatar/avatar-3.png"
                                                    class="avatar avatar-lg rounded10 bg-primary-light" alt="" />
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1 fw-500">
                                                <p class="hover-primary text-fade mb-1 fs-14">Johen Doe</p>
                                                <span class="text-dark fs-16">Laboratory screening</span>
                                            </div>
                                            <div>
                                                <a href="#"
                                                    class="waves-effect waves-circle btn btn-circle btn-primary-light btn-sm"><i
                                                        class="fa fa-phone"></i></a>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between align-items-end mb-15 py-10 bb-dashed border-bottom">
                                            <div>
                                                <p class="mb-0 text-muted"><i class="fa fa-clock-o me-5"></i> 11:00
                                                    <span class="mx-20">$ 70</span>
                                                </p>
                                            </div>
                                            <div>
                                                <div class="dropdown">
                                                    <a data-bs-toggle="dropdown" href="#"
                                                        class="base-font mx-10"><i class="ti-more-alt text-muted"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                                            Import</a>
                                                        <a class="dropdown-item" href="#"><i class="ti-export"></i>
                                                            Export</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ti-printer"></i> Print</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ti-settings"></i> Settings</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center mb-10">
                                            <div class="me-15">
                                                <img src="../images/avatar/avatar-4.png"
                                                    class="avatar avatar-lg rounded10 bg-primary-light" alt="" />
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1 fw-500">
                                                <p class="hover-primary text-fade mb-1 fs-14">Harmani Doe</p>
                                                <span class="text-dark fs-16">Keeping pregnant</span>
                                            </div>
                                            <div>
                                                <a href="#"
                                                    class="waves-effect waves-circle btn btn-circle btn-primary-light btn-sm"><i
                                                        class="fa fa-phone"></i></a>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between align-items-end mb-15 py-10 bb-dashed border-bottom">
                                            <div>
                                                <p class="mb-0 text-muted"><i class="fa fa-clock-o me-5"></i> 11:30
                                                </p>
                                            </div>
                                            <div>
                                                <div class="dropdown">
                                                    <a data-bs-toggle="dropdown" href="#"
                                                        class="base-font mx-10"><i class="ti-more-alt text-muted"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                                            Import</a>
                                                        <a class="dropdown-item" href="#"><i class="ti-export"></i>
                                                            Export</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ti-printer"></i> Print</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ti-settings"></i> Settings</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center mb-10">
                                            <div class="me-15">
                                                <img src="../images/avatar/avatar-5.png"
                                                    class="avatar avatar-lg rounded10 bg-primary-light" alt="" />
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1 fw-500">
                                                <p class="hover-primary text-fade mb-1 fs-14">Mark Wood</p>
                                                <span class="text-dark fs-16">Primary doctor consultation</span>
                                            </div>
                                            <div>
                                                <a href="#"
                                                    class="waves-effect waves-circle btn btn-circle btn-primary-light btn-sm"><i
                                                        class="fa fa-phone"></i></a>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between align-items-end mb-15 py-10 bb-dashed border-bottom">
                                            <div>
                                                <p class="mb-0 text-muted"><i class="fa fa-clock-o me-5"></i> 12:00
                                                    <span class="mx-20">$ 30</span>
                                                </p>
                                            </div>
                                            <div>
                                                <div class="dropdown">
                                                    <a data-bs-toggle="dropdown" href="#"
                                                        class="base-font mx-10"><i class="ti-more-alt text-muted"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                                            Import</a>
                                                        <a class="dropdown-item" href="#"><i class="ti-export"></i>
                                                            Export</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ti-printer"></i> Print</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ti-settings"></i> Settings</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center mb-10">
                                            <div class="me-15">
                                                <img src="../images/avatar/avatar-6.png"
                                                    class="avatar avatar-lg rounded10 bg-primary-light" alt="" />
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1 fw-500">
                                                <p class="hover-primary text-fade mb-1 fs-14">Shawn Marsh</p>
                                                <span class="text-dark fs-16">Emergency appointment</span>
                                            </div>
                                            <div>
                                                <a href="#"
                                                    class="waves-effect waves-circle btn btn-circle btn-primary-light btn-sm"><i
                                                        class="fa fa-phone"></i></a>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between align-items-end mb-15 py-10 bb-dashed border-bottom">
                                            <div>
                                                <p class="mb-0 text-muted"><i class="fa fa-clock-o me-5"></i> 13:00
                                                    <span class="mx-20">$ 90</span>
                                                </p>
                                            </div>
                                            <div>
                                                <div class="dropdown">
                                                    <a data-bs-toggle="dropdown" href="#"
                                                        class="base-font mx-10"><i class="ti-more-alt text-muted"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                                            Import</a>
                                                        <a class="dropdown-item" href="#"><i class="ti-export"></i>
                                                            Export</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ti-printer"></i> Print</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ti-settings"></i> Settings</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-8 col-12">
                        <div class="box">
                            <div class="box-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">Your Patients today</h4>
                                    <a href="#" class="">All patients <i
                                            class="ms-10 fa fa-angle-right"></i></a>
                                </div>
                            </div>
                            <div class="box-body p-15">
                                {{-- <div class="table-responsive">
                                    <!-- Main content -->
                                    <table
                                        class="table table-bordered table-hover table-striped mb-0 border-2 data-table text-center">
                                        <thead class="bg-primary-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Patient ID</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Phone Number</th>
                                                <th>Appointment Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>123</td>
                                                <td>hi</td>
                                                <td>Last Name</td>
                                                <td>female</td>
                                                <td>Address1, add</td>
                                                <td>1234567890</td>
                                                <td>scheduld</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- /.content -->
                                </div>
                                <br /> --}}
                                <div class="mb-10 d-flex justify-content-between align-items-center">
                                    <div class="fw-600 min-w-120">
                                        10:30am
                                    </div>
                                    <div
                                        class="w-p100 p-10 rounded10 justify-content-between align-items-center d-flex bg-lightest">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <img src="../images/avatar/1.jpg" class="me-10 avatar rounded-circle"
                                                alt="">
                                            <div>
                                                <h6 class="mb-0">Sarah Hostemn</h6>
                                                <p class="mb-0 fs-12 text-mute">Diagnosis: Bronchitis</p>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <a data-bs-toggle="dropdown" href="#" aria-expanded="false"
                                                class=""><i class="ti-more-alt rotate-90"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                                    Details</a>
                                                <a class="dropdown-item" href="#"><i class="ti-export"></i> Lab
                                                    Reports</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-10 d-flex justify-content-between align-items-center">
                                    <div class="fw-600 min-w-120">
                                        11:00am
                                    </div>
                                    <div
                                        class="w-p100 p-10 rounded10 justify-content-between align-items-center d-flex bg-lightest">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <img src="../images/avatar/2.jpg" class="me-10 avatar rounded-circle"
                                                alt="">
                                            <div>
                                                <h6 class="mb-0">Dakota Smith</h6>
                                                <p class="mb-0 fs-12 text-mute">Diagnosis: Stroke</p>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <a data-bs-toggle="dropdown" href="#"><i
                                                    class="ti-more-alt rotate-90"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                                    Details</a>
                                                <a class="dropdown-item" href="#"><i class="ti-export"></i> Lab
                                                    Reports</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="fw-600 min-w-120">
                                        11:30am
                                    </div>
                                    <div
                                        class="w-p100 p-10 rounded10 justify-content-between align-items-center d-flex bg-lightest">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <img src="../images/avatar/3.jpg" class="me-10 avatar rounded-circle"
                                                alt="">
                                            <div>
                                                <h6 class="mb-0">John Lane</h6>
                                                <p class="mb-0 fs-12 text-mute">Diagnosis: Liver cimhosis</p>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <a data-bs-toggle="dropdown" href="#"><i
                                                    class="ti-more-alt rotate-90"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                                    Details</a>
                                                <a class="dropdown-item" href="#"><i class="ti-export"></i> Lab
                                                    Reports</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-12">

                        <div class="box">
                            <div class="box-header d-flex justify-content-between align-items-center">
                                {{-- <h4 class="box-title">Availability</h4>
                            </div> --}}

                                <h4 class="box-title">Availability</h4>
                                <div style="width: 40%;">
                                    <select class="select2" id="clinic_branch_id1" name="clinic_branch_id1" required
                                        data-placeholder="Select a Branch" style="width: 100%;">
                                        @foreach ($clinicBranches as $clinicBranch)
                                            <?php
                                            $clinicAddress = $clinicBranch->clinic_address;
                                            $clinicAddress = explode('<br>', $clinicBranch->clinic_address);
                                            $clinicAddress = implode(', ', $clinicAddress);
                                            $branch = $clinicAddress . ', ' . $clinicBranch->city->city . ', ' . $clinicBranch->state->state;
                                            ?>
                                            <option value="{{ $clinicBranch->id }}">
                                                {{ $branch }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="media-list media-list-divided media-list-hover">

                                    <div class="media align-items-center justify">
                                        <div class="media-body d-flex justify-content-between">
                                            <h6>Sunday</h6>
                                            <div class="fw-600 min-w-120">
                                                10:30 AM - 12:00 PM
                                            </div>
                                            {{-- <small>
                                                <span>10:00 AM</span>
                                                <span class="divider-dash">12:00 PM/span>
                                            </small> --}}
                                        </div>
                                    </div>

                                    <div class="media align-items-center justify">
                                        <div class="media-body d-flex justify-content-between">
                                            <h6>Monday</h6>
                                            <div class="fw-600 min-w-120">
                                                10:30 AM - 12:00 PM
                                            </div>
                                        </div>
                                    </div>

                                    <div class="media align-items-center justify">
                                        <div class="media-body d-flex justify-content-between">
                                            <h6>Tuesday</h6>
                                            <div class="fw-600 min-w-120">
                                                10:30 AM - 12:00 PM
                                            </div>
                                        </div>
                                    </div>

                                    <div class="media align-items-center justify">
                                        <div class="media-body d-flex justify-content-between">
                                            <h6>Wednesday</h6>
                                            <div class="fw-600 min-w-120">
                                                10:30 AM - 12:00 PM
                                            </div>
                                        </div>
                                    </div>

                                    <div class="media align-items-center justify">
                                        <div class="media-body d-flex justify-content-between">
                                            <h6>Thursday</h6>
                                            <div class="fw-600 min-w-120">
                                                10:30 AM - 12:00 PM
                                            </div>
                                        </div>
                                    </div>

                                    <div class="media align-items-center justify">
                                        <div class="media-body d-flex justify-content-between">
                                            <h6>Friday</h6>
                                            <div class="fw-600 min-w-120">
                                                10:30 AM - 12:00 PM
                                            </div>
                                        </div>
                                    </div>

                                    <div class="media align-items-center justify">
                                        <div class="media-body d-flex justify-content-between">
                                            <h6>Saturday</h6>
                                            <div class="fw-600 min-w-120">
                                                10:30 AM - 12:00 PM
                                            </div>
                                        </div>
                                    </div>

















                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>


@endsection
