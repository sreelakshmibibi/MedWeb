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
                <div class="col-lg-12 col-12">
                    <div class="box">
                        <div class="box-body d-flex align-items-center">
                            <img src="{{ asset('images/avatar/avatar-1.png') }}" alt=""
                                class="bg-success-light rounded10 me-20 align-self-end h-100">
                            <div class="d-flex flex-column flex-grow-1">
                                <a href="#" class="box-title text-muted fw-600 fs-18 mb-2 hover-primary">Dr. John
                                    Doe</a>
                                <span class="fw-500 text-fade">Education</span>
                                <span class="fw-500 text-fade">16 years of Experience</span>
                            </div>
                            {{-- <img src="{{ asset('images/avatar/avatar-1.png') }}" alt=""
                                class="align-self-end h-100"> --}}
                            {{-- <div class="box-body text-end min-h-150"
                                style="background-image:url({{ asset('images/gallery/landscape14.jpg') }}); background-repeat: no-repeat; background-position: center;background-size: cover;"> --}}
                            <div class="bg-success rounded10 p-15 fs-18 d-inline min-h-50"><i class="fa fa-stethoscope"></i>
                                ENT
                                Specialist</div>
                            {{-- </div> --}}
                        </div>
                        <div class="box-body">
                            <h4>About</h4>
                            <p>Vestibulum tincidunt sit amet sapien et eleifend. Fusce pretium libero enim, nec lacinia est
                                ultrices id. Duis nibh sapien, ultrices in hendrerit ac, pulvinar ut mauris. Quisque eu
                                condimentum justo. In consectetur dapibus justo, et dapibus augue pellentesque sed. Etiam
                                pulvinar pharetra est, at euismod augue vulputate sed. Morbi id porta turpis, a porta
                                turpis.
                                Suspendisse maximus finibus est at pellentesque. Integer ut sapien urna.</p>
                            {{-- <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                                laudantium,
                                totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae
                                vitae
                                dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut
                                fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque
                                porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed
                                quia
                                non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat
                                voluptatem.
                            </p> --}}

                            {{-- <div class="row">
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header"><i class="fa fa-mail-bulk"></i></h5>
                                        <span class="description-text">David@yahoo.com</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 be-1 bs-1">
                                    <div class="description-block">
                                        <h5 class="description-header"><i class="fa fa-phone"></i>Contact Number</h5>
                                        <span class="description-text">+11 123 456 7890</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header"><i class="fa fa-address-card"></i>Address</h5>
                                        <span class="description-text">123, Lorem Ipsum, Florida, USA</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                            </div> --}}

                            {{-- <div class="row">
                                <div class="col-12">
                                    <div>
                                        <p>Email :<span class="text-gray ps-10">David@yahoo.com</span> </p>
                                        <p>Phone :<span class="text-gray ps-10">+11 123 456 7890</span></p>
                                        <p>Address :<span class="text-gray ps-10">123, Lorem Ipsum, Florida, USA</span></p>
                                    </div>
                                </div>

                            </div> --}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-12">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="box">
                                        <div class="box-body px-0 text-center">
                                            <div style="min-height: 156px; position: relative;">
                                                <div id="chart124" style="min-height: 146.533px;">
                                                    <div id="apexchartsoy6vblpe"
                                                        class="apexcharts-canvas apexchartsoy6vblpe apexcharts-theme-light"
                                                        style="width: 175px; height: 146.533px;"><svg id="SvgjsSvg1269"
                                                            width="175" height="146.53333333333333"
                                                            xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg"
                                                            xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                                            style="background: transparent;">
                                                            <g id="SvgjsG1271" class="apexcharts-inner apexcharts-graphical"
                                                                transform="translate(27.58333333333333, 0)">
                                                                <defs id="SvgjsDefs1270">
                                                                    <clipPath id="gridRectMaskoy6vblpe">
                                                                        <rect id="SvgjsRect1273" width="127.83333333333334"
                                                                            height="145.83333333333334" x="-3" y="-1"
                                                                            rx="0" ry="0" opacity="1"
                                                                            stroke-width="0" stroke="none"
                                                                            stroke-dasharray="0" fill="#fff"></rect>
                                                                    </clipPath>
                                                                    <clipPath id="gridRectMarkerMaskoy6vblpe">
                                                                        <rect id="SvgjsRect1274" width="125.83333333333334"
                                                                            height="147.83333333333334" x="-2" y="-2"
                                                                            rx="0" ry="0" opacity="1"
                                                                            stroke-width="0" stroke="none"
                                                                            stroke-dasharray="0" fill="#fff"></rect>
                                                                    </clipPath>
                                                                </defs>
                                                                <g id="SvgjsG1276" class="apexcharts-pie">
                                                                    <g id="SvgjsG1277" transform="translate(0, 0) scale(1)">
                                                                        <circle id="SvgjsCircle1278" r="48.121951219512205"
                                                                            cx="60.91666666666667" cy="71.91666666666667"
                                                                            fill="transparent"></circle>
                                                                        <g id="SvgjsG1279" class="apexcharts-slices">
                                                                            <g id="SvgjsG1280"
                                                                                class="apexcharts-series apexcharts-pie-series"
                                                                                seriesName="Woman" rel="1"
                                                                                data:realIndex="0">
                                                                                <path id="SvgjsPath1281"
                                                                                    d="M 60.91666666666668 7.754065040650403 A 64.16260162601627 64.16260162601627 0 0 1 82.86156887094455 132.2097899450601 L 77.37534331987507 117.13650912546174 A 48.121951219512205 48.121951219512205 0 0 0 60.91666666666667 23.794715447154466 L 60.91666666666668 7.754065040650403 z"
                                                                                    fill="rgba(81,86,190,1)"
                                                                                    fill-opacity="1" stroke-opacity="1"
                                                                                    stroke-linecap="butt" stroke-width="2"
                                                                                    stroke-dasharray="0"
                                                                                    class="apexcharts-pie-area apexcharts-donut-slice-0"
                                                                                    index="0" j="0" data:angle="160"
                                                                                    data:startAngle="0"
                                                                                    data:strokeWidth="2" data:value="440"
                                                                                    data:pathOrig="M 60.91666666666668 7.754065040650403 A 64.16260162601627 64.16260162601627 0 0 1 82.86156887094455 132.2097899450601 L 77.37534331987507 117.13650912546174 A 48.121951219512205 48.121951219512205 0 0 0 60.91666666666667 23.794715447154466 L 60.91666666666668 7.754065040650403 z"
                                                                                    stroke="#ffffff"></path>
                                                                            </g>
                                                                            <g id="SvgjsG1282"
                                                                                class="apexcharts-series apexcharts-pie-series"
                                                                                seriesName="Man" rel="2"
                                                                                data:realIndex="1">
                                                                                <path id="SvgjsPath1283"
                                                                                    d="M 82.86156887094455 132.2097899450601 A 64.16260162601627 64.16260162601627 0 1 1 60.90546818017332 7.7540660179027014 L 60.908267801796654 23.794716180093694 A 48.121951219512205 48.121951219512205 0 1 0 77.37534331987507 117.13650912546174 L 82.86156887094455 132.2097899450601 z"
                                                                                    fill="rgba(200,201,238,1)"
                                                                                    fill-opacity="1" stroke-opacity="1"
                                                                                    stroke-linecap="butt" stroke-width="2"
                                                                                    stroke-dasharray="0"
                                                                                    class="apexcharts-pie-area apexcharts-donut-slice-1"
                                                                                    index="0" j="1" data:angle="200"
                                                                                    data:startAngle="160"
                                                                                    data:strokeWidth="2" data:value="550"
                                                                                    data:pathOrig="M 82.86156887094455 132.2097899450601 A 64.16260162601627 64.16260162601627 0 1 1 60.90546818017332 7.7540660179027014 L 60.908267801796654 23.794716180093694 A 48.121951219512205 48.121951219512205 0 1 0 77.37534331987507 117.13650912546174 L 82.86156887094455 132.2097899450601 z"
                                                                                    stroke="#ffffff"></path>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                    <g id="SvgjsG1284" class="apexcharts-datalabels-group"
                                                                        transform="translate(0, 0) scale(1)"><text
                                                                            id="SvgjsText1285"
                                                                            font-family="Helvetica, Arial, sans-serif"
                                                                            x="60.91666666666667" y="61.91666666666667"
                                                                            text-anchor="middle" dominant-baseline="auto"
                                                                            font-size="16px" font-weight="400"
                                                                            fill="#373d3f"
                                                                            class="apexcharts-text apexcharts-datalabel-label"
                                                                            style="font-family: Helvetica, Arial, sans-serif;">Total</text><text
                                                                            id="SvgjsText1286"
                                                                            font-family="Helvetica, Arial, sans-serif"
                                                                            x="60.91666666666667" y="97.91666666666667"
                                                                            text-anchor="middle" dominant-baseline="auto"
                                                                            font-size="20px" font-weight="400"
                                                                            fill="#373d3f"
                                                                            class="apexcharts-text apexcharts-datalabel-value"
                                                                            style="font-family: Helvetica, Arial, sans-serif;">990</text>
                                                                    </g>
                                                                </g>
                                                                <line id="SvgjsLine1287" x1="0" y1="0"
                                                                    x2="121.83333333333334" y2="0"
                                                                    stroke="#b6b6b6" stroke-dasharray="0"
                                                                    stroke-width="1" class="apexcharts-ycrosshairs">
                                                                </line>
                                                                <line id="SvgjsLine1288" x1="0" y1="0"
                                                                    x2="121.83333333333334" y2="0"
                                                                    stroke-dasharray="0" stroke-width="0"
                                                                    class="apexcharts-ycrosshairs-hidden"></line>
                                                            </g>
                                                            <g id="SvgjsG1272" class="apexcharts-annotations"></g>
                                                        </svg>
                                                        <div class="apexcharts-legend"></div>
                                                        <div class="apexcharts-tooltip apexcharts-theme-dark">
                                                            <div class="apexcharts-tooltip-series-group"><span
                                                                    class="apexcharts-tooltip-marker"
                                                                    style="background-color: rgb(81, 86, 190);"></span>
                                                                <div class="apexcharts-tooltip-text"
                                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                                    <div class="apexcharts-tooltip-y-group"><span
                                                                            class="apexcharts-tooltip-text-label"></span><span
                                                                            class="apexcharts-tooltip-text-value"></span>
                                                                    </div>
                                                                    <div class="apexcharts-tooltip-z-group"><span
                                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                                            class="apexcharts-tooltip-text-z-value"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="apexcharts-tooltip-series-group"><span
                                                                    class="apexcharts-tooltip-marker"
                                                                    style="background-color: rgb(200, 201, 238);"></span>
                                                                <div class="apexcharts-tooltip-text"
                                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                                    <div class="apexcharts-tooltip-y-group"><span
                                                                            class="apexcharts-tooltip-text-label"></span><span
                                                                            class="apexcharts-tooltip-text-value"></span>
                                                                    </div>
                                                                    <div class="apexcharts-tooltip-z-group"><span
                                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                                            class="apexcharts-tooltip-text-z-value"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="resize-triggers">
                                                    <div class="expand-trigger">
                                                        <div style="width: 467px; height: 157px;"></div>
                                                    </div>
                                                    <div class="contract-trigger"></div>
                                                </div>
                                            </div>
                                            <div class="mt-15 d-inline-block">
                                                <div class="text-start mb-10">
                                                    <span class="badge badge-xl badge-dot badge-primary me-15"></span>
                                                    Woman 44%
                                                </div>
                                                <div class="text-start">
                                                    <span
                                                        class="badge badge-xl badge-dot badge-primary-light me-15"></span>
                                                    Man 55%
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
                                            <h4>Old Patients</h4>
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
                                <div class="box-header">
                                    <h4 class="box-title">Availability</h4>
                                </div>
                                <div class="box-body">
                                    <div class="media d-lg-flex d-block text-lg-start text-center">
                                        <img class="me-3 img-fluid rounded bg-primary-light w-100"
                                            src="../images/avatar/1.jpg" alt="">
                                        <div class="media-body my-10 my-lg-0">
                                            <h4 class="mt-0 mb-2">Loky Doe</h4>
                                            <h6 class="mb-4 text-primary">Cold &amp; Flue</h6>
                                            <div class="d-flex justify-content-center justify-content-lg-start">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-sm btn-primary-light me-4">Unassign</a>
                                                <a href="javascript:void(0);"
                                                    class="btn btn-sm btn-danger-light ">Imporvement</a>
                                            </div>
                                        </div>
                                        <div id="chart" class="me-3"></div>
                                        <div class="media-footer align-self-center">
                                            <div class="up-sign text-success">
                                                <i class="fa fa-caret-up fs-38"></i>
                                                <h3 class="text-success">10%</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-5 col-xl-4">
                            <div class="box">
                                <div class="box-body box-profile">
                                    <div class="row">
                                        <div class="col-12">
                                            <div>
                                                <p>Email :<span class="text-gray ps-10">David@yahoo.com</span> </p>
                                                <p>Phone :<span class="text-gray ps-10">+11 123 456 7890</span></p>
                                                <p>Address :<span class="text-gray ps-10">123, Lorem Ipsum, Florida,
                                                        USA</span></p>
                                            </div>
                                        </div>
                                        {{-- <div class="col-12">
                                            <div class="pb-15">
                                                <p class="mb-10">Social Profile</p>
                                                <div class="user-social-acount">
                                                    <button class="btn btn-circle btn-social-icon btn-facebook"><i
                                                            class="fa fa-facebook"></i></button>
                                                    <button class="btn btn-circle btn-social-icon btn-twitter"><i
                                                            class="fa fa-twitter"></i></button>
                                                    <button class="btn btn-circle btn-social-icon btn-instagram"><i
                                                            class="fa fa-instagram"></i></button>
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-12">
                                            <div>
                                                <div class="map-box">
                                                    <iframe
                                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2805244.1745767146!2d-86.32675167439648!3d29.383165774894163!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c1766591562abf%3A0xf72e13d35bc74ed0!2sFlorida%2C+USA!5e0!3m2!1sen!2sin!4v1501665415329"
                                                        width="100%" height="100" frameborder="0" style="border:0"
                                                        allowfullscreen=""></iframe>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <div class="col-12 col-lg-5 col-xl-4">
                            <div class="box">
                                <div class="box-body box-profile">
                                    <div class="row">
                                        <div class="col-12">
                                            <div>
                                                <p>Email :<span class="text-gray ps-10">David@yahoo.com</span> </p>
                                                <p>Phone :<span class="text-gray ps-10">+11 123 456 7890</span></p>
                                                <p>Address :<span class="text-gray ps-10">123, Lorem Ipsum, Florida,
                                                        USA</span></p>
                                            </div>
                                        </div>
                                        {{-- <div class="col-12">
                                            <div class="pb-15">
                                                <p class="mb-10">Social Profile</p>
                                                <div class="user-social-acount">
                                                    <button class="btn btn-circle btn-social-icon btn-facebook"><i
                                                            class="fa fa-facebook"></i></button>
                                                    <button class="btn btn-circle btn-social-icon btn-twitter"><i
                                                            class="fa fa-twitter"></i></button>
                                                    <button class="btn btn-circle btn-social-icon btn-instagram"><i
                                                            class="fa fa-instagram"></i></button>
                                                </div>
                                            </div>
                                        </div> --}}
                        {{-- <div class="col-12">
                                            <div>
                                                <div class="map-box">
                                                    <iframe
                                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2805244.1745767146!2d-86.32675167439648!3d29.383165774894163!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c1766591562abf%3A0xf72e13d35bc74ed0!2sFlorida%2C+USA!5e0!3m2!1sen!2sin!4v1501665415329"
                                                        width="100%" height="100" frameborder="0" style="border:0"
                                                        allowfullscreen=""></iframe>
                                                </div>
                                            </div>
                                        </div> --}}
                        {{-- }} </div>
                </div>
                <!-- /.box-body -->
        </div>
    </div> --}}
                        <div class="col-12 col-lg-7 col-xl-8">
                            {{-- <div class="col-12 col-lg-4 col-xl-4"> --}}
                            <div class="row">
                                <div class="col-lg-4 col-12">
                                    <div class="box">
                                        <div class="box-body">
                                            <h4>Patients</h4>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h2 class="fs-40 my-0">67</h2>
                                                <div>
                                                    <span class="badge badge-pill badge-success-light"><i
                                                            class="fa fa-angle-up me-10"></i>
                                                        39%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
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
                        <div class="col-12 col-lg-5 col-xl-4">
                            <div class="box">
                                <div class="box-body box-profile">
                                    <div class="row">
                                        <div class="col-12">
                                            <div>
                                                <p>Email :<span class="text-gray ps-10">David@yahoo.com</span> </p>
                                                <p>Phone :<span class="text-gray ps-10">+11 123 456 7890</span></p>
                                                <p>Address :<span class="text-gray ps-10">123, Lorem Ipsum, Florida,
                                                        USA</span></p>
                                            </div>
                                        </div>
                                        {{-- <div class="col-12">
                                            <div class="pb-15">
                                                <p class="mb-10">Social Profile</p>
                                                <div class="user-social-acount">
                                                    <button class="btn btn-circle btn-social-icon btn-facebook"><i
                                                            class="fa fa-facebook"></i></button>
                                                    <button class="btn btn-circle btn-social-icon btn-twitter"><i
                                                            class="fa fa-twitter"></i></button>
                                                    <button class="btn btn-circle btn-social-icon btn-instagram"><i
                                                            class="fa fa-instagram"></i></button>
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-12">
                                            <div>
                                                <div class="map-box">
                                                    <iframe
                                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2805244.1745767146!2d-86.32675167439648!3d29.383165774894163!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c1766591562abf%3A0xf72e13d35bc74ed0!2sFlorida%2C+USA!5e0!3m2!1sen!2sin!4v1501665415329"
                                                        width="100%" height="100" frameborder="0" style="border:0"
                                                        allowfullscreen=""></iframe>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>


                    </div>

                </div>



                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Assigned Patient</h4>
                    </div>
                    <div class="box-body">
                        <div class="media d-lg-flex d-block text-lg-start text-center">
                            <img class="me-3 img-fluid rounded bg-primary-light w-100" src="../images/avatar/1.jpg"
                                alt="">
                            <div class="media-body my-10 my-lg-0">
                                <h4 class="mt-0 mb-2">Loky Doe</h4>
                                <h6 class="mb-4 text-primary">Cold &amp; Flue</h6>
                                <div class="d-flex justify-content-center justify-content-lg-start">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-4">Unassign</a>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger-light ">Imporvement</a>
                                </div>
                            </div>
                            <div id="chart" class="me-3"></div>
                            <div class="media-footer align-self-center">
                                <div class="up-sign text-success">
                                    <i class="fa fa-caret-up fs-38"></i>
                                    <h3 class="text-success">10%</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#staffform .actions ul li:last-child a").addClass("bg-success btn btn-success");
            let count = '{{ $availabilityCount }}';

            // Event listener for Add Row button click
            $(document).on('click', '#buttonAddRow', function() {
                count++;
                let newRow = `<tr>
                    <td>${count}</td>
                    <td>
                        <select class="select2" id="clinic_branch_id${count}" name="clinic_branch_id${count}" required
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
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="sunday_from${count}"
                            name="sunday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="sunday_to${count}"
                            name="sunday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="monday_from${count}"
                            name="monday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="monday_to${count}"
                            name="monday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="tuesday_from${count}"
                            name="tuesday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="tuesday_to${count}"
                            name="tuesday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="wednesday_from${count}"
                            name="wednesday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="wednesday_to${count}"
                            name="wednesday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="thursday_from${count}"
                            name="thursday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="thursday_to${count}"
                            name="thursday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="friday_from${count}"
                            name="friday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="friday_to${count}"
                            name="friday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput fromTime" id="saturday_from${count}"
                            name="saturday_from${count}" title="from" style="width:115px;">
                        <input type="time" class="form-control timeInput toTime" id="saturday_to${count}"
                            name="saturday_to${count}" title="to" style="width:115px;">
                    </td>
                    <td>
                        <button type="button" class="btnDelete waves-effect waves-light btn btn-danger btn-sm"
                            title="delete row"> <i class="fa fa-trash"></i></button>
                    </td>
                </tr>`;

                $('#tablebody').append(newRow);
                updateRowCount();
            });

            // Event listener for Delete button click
            $(document).on('click', '.btnDelete', function() {
                $(this).closest('tr').remove();
                updateRowCount();
            });

            // Function to update row count input field value
            function updateRowCount() {
                $('#row_count').val(count);
            }

            // Validation for 'from' and 'to' time fields
            $(document).on('focusout', '.fromTime, .toTime', function() {
                let index = $(this).attr('id').match(/\d+/)[0];
                let day = $(this).closest('td').prevAll().length; // Determine the column index
                switch (day) {
                    case 2:
                        fromField = $(`#sunday_from${index}`);
                        toField = $(`#sunday_to${index}`);
                        break;
                    case 3: // Monday column (index 3)
                        fromField = $(`#monday_from${index}`);
                        toField = $(`#monday_to${index}`);
                        break;
                    case 4: // Tuesday column (index 4)
                        fromField = $(`#tuesday_from${index}`);
                        toField = $(`#tuesday_to${index}`);
                        break;
                    case 5: // Wednesday column (index 5)
                        fromField = $(`#wednesday_from${index}`);
                        toField = $(`#wednesday_to${index}`);
                        break;
                    case 6: // Thursday column (index 6)
                        fromField = $(`#thursday_from${index}`);
                        toField = $(`#thursday_to${index}`);
                        break;
                    case 7: // Friday column (index 7)
                        fromField = $(`#friday_from${index}`);
                        toField = $(`#friday_to${index}`);
                        break;
                    case 8: // Saturday column (index 8)
                        fromField = $(`#saturday_from${index}`);
                        toField = $(`#saturday_to${index}`);
                        break;
                    default:
                        break;
                }

                if ($(this).hasClass('fromTime')) {
                    if ($(this).val()) {
                        toField.rules('add', {
                            required: true,
                            messages: {
                                required: `To Time is required when From time is entered`
                            }
                        });
                    } else {
                        toField.rules('remove', 'required');
                    }
                }

                if ($(this).hasClass('toTime')) {
                    if ($(this).val()) {
                        fromField.rules('add', {
                            required: true,
                            messages: {
                                required: `From Time is required when To time is entered`
                            }
                        });
                    } else {
                        fromField.rules('remove', 'required');
                    }
                }
            });

            // Handle change event for role dropdown
            $('select[name="role[]"]').change(function() {
                if ($(this).val() && $(this).val().includes('3')) {
                    $('.doctorFields').show();
                    $('.otherFields').hide();
                } else {
                    $('.doctorFields').hide();
                    $('.otherFields').show();
                }
            });

            // Function to load states based on country ID
            function loadStates(countryId, stateSelectElement, initialSelected) {
                if (countryId) {
                    $.ajax({
                        url: '{{ route('get.states', '') }}' + '/' + countryId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            stateSelectElement.empty();
                            stateSelectElement.append('<option value="">Select State</option>');
                            $.each(data, function(key, value) {
                                var selected = null;
                                if (key == initialSelected) {

                                    selected = "selected";

                                }
                                var state = '{{ $staffProfile->com_state_id }}';
                                stateSelectElement.append('<option value="' + key + '" ' +
                                    selected + '>' +
                                    value + '</option>');
                            });
                            // Trigger change event to load initial cities
                            stateSelectElement.trigger('change');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error loading states:', textStatus, errorThrown);
                        }
                    });
                } else {
                    stateSelectElement.empty();
                }
            }

            // Function to load cities based on state ID
            function loadCities(stateId, citySelectElement, initialSelected) {
                if (stateId) {
                    $.ajax({
                        url: '{{ route('get.cities', '') }}' + '/' + stateId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            citySelectElement.empty();
                            citySelectElement.append('<option value="">Select City</option>');
                            $.each(data, function(key, value) {
                                var selected = null;
                                if (key == initialSelected) {

                                    selected = "selected";

                                }
                                citySelectElement.append('<option value="' + key + '" ' +
                                    selected + '>' +
                                    value + '</option>');
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error loading cities:', textStatus, errorThrown);
                        }
                    });
                } else {
                    citySelectElement.empty();
                }
            }

            // Initializations
            var initialSelectedStateId = '{{ $staffProfile->state_id }}';
            var initialSelectedComStateId = '{{ $staffProfile->com_state_id }}';
            var initialSelectedCityId = '{{ $staffProfile->city_id }}';
            var initialSelectedComCityId = '{{ $staffProfile->com_city_id }}';

            var initialCountryId = $('#country_id').val(); // Assuming India is selected initially
            loadStates(initialCountryId, $('#state_id'), initialSelectedStateId);

            // Handle change event for country dropdown
            $('#country_id').change(function() {
                var countryId = $(this).val();
                loadStates(countryId, $('#state_id'), null);
            });

            // Handle change event for state dropdown
            $('#state_id').change(function() {
                var stateId = $(this).val();
                loadCities(stateId, $('#city_id'), initialSelectedCityId);
            });

            // Same logic for communication address
            var com_initialCountryId = $('#com_country_id').val(); // Assuming India is selected initially
            loadStates(com_initialCountryId, $('#com_state_id'), initialSelectedComStateId);

            $('#com_country_id').change(function() {
                var countryId = $(this).val();
                loadStates(countryId, $('#com_state_id'), null);
            });

            $('#com_state_id').change(function() {
                var stateId = $(this).val();
                loadCities(stateId, $('#com_city_id'), initialSelectedComCityId);
            });

            // Validate weekday time inputs
            function validateWeekdayTime(day) {
                var fromValue = $('#' + day + '_from').val();
                var toValue = $('#' + day + '_to').val();

                // Check if fromValue is filled and toValue is empty
                if (fromValue && !toValue) {
                    $('#' + day + '_to').addClass('is-invalid'); // Add Bootstrap's is-invalid class
                    return false;
                } else {
                    $('#' + day + '_to').removeClass('is-invalid'); // Remove is-invalid class if valid
                    return true;
                }
            }

            // Event handlers for weekday inputs
            ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].forEach(function(day) {
                // Validate on change of weekday from
                $('#' + day + '_from').change(function() {
                    validateWeekdayTime(day);
                });

                // Validate on change of weekday to
                $('#' + day + '_to').change(function() {
                    validateWeekdayTime(day);
                });
            });

            // Form submit validation
            $('form.validation-wizard').submit(function(event) {
                var isValid = true;

                // Validate all weekdays
                ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].forEach(
                    function(day) {
                        if (!validateWeekdayTime(day)) {
                            isValid = false;
                        }
                    });

                if (!isValid) {
                    event.preventDefault(); // Prevent form submission if validation fails
                    $('.error-message').text('Please fill all weekday times');
                } else {
                    $('.error-message').text(''); // Clear error message if validation passes
                }
            });

            // Event listener for dropdown item click
            $(".dropdown-menu .dropdown-item").click(function() {
                // Get the selected salutation text
                let salutation = $(this).text().trim();

                // Update the button text with the selected salutation
                $(".input-group .dropdown-toggle").text(salutation);
            });

            // Event listener for communication address checkbox
            $("#add_checkbox").change(function() {
                if ($(this).is(':checked')) {
                    $('#communicationAddress').hide();
                    $('#com_address1').removeAttr('required');
                    $('#com_address2').removeAttr('required');
                    $('#com_city_id').removeAttr('required');
                    $('#com_state_id').removeAttr('required');
                    $('#com_country_id').removeAttr('required');
                    $('#com_pincode').removeAttr('required');
                } else {
                    $('#communicationAddress').show();
                    $('#com_address1').attr('required', true);
                    $('#com_address2').attr('required', true);
                    $('#com_city_id').attr('required', true);
                    $('#com_state_id').attr('required', true);
                    $('#com_country_id').attr('required', true);
                    $('#com_pincode').attr('required', true);
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            var canvas = document.getElementById('profilePic');
            var ctx = canvas.getContext('2d');
            if ('{{ $staffProfile }}') {

                var profileUrl = '{{ $staffProfile->photo ?? '' }}';
                var photoUrl = '{{ asset('storage/') }}/' + profileUrl;
                if (profileUrl) {
                    var img = new Image();
                    img.onload = function() {
                        canvas.width = img.width;
                        canvas.height = img.height;
                        ctx.drawImage(img, 0, 0);
                    };
                    img.src = photoUrl;
                }
            }
        });
    </script>

@endsection
