<div class="row">
    <div class="col-xl-8 col-12">
        <div class="box">
            <div class="box-body d-flex align-items-center">
                <img src="{{ asset('images/avatar/avatar-1.png') }}" alt=""
                    class="bg-success-light rounded10 me-20 align-self-end h-100">
                <div class="d-flex flex-column flex-grow-1">
                    <a href="#" class="box-title text-muted fw-600 fs-18 mb-2 hover-primary"><?php str_replace('<br>', ' ', $userDetails->name); ?></a>
                    <span class="fw-500 text-fade">{{ $staffProfile->qualification }}</span>
                    <span class="fw-500 text-fade">{{ $staffProfile->years_of_experience }}</span>

                    {{-- <span class="fw-500 text-fade">16 years of Experience</span> --}}
                </div>
                <div class="bg-success rounded10 p-5 fs-18 d-inline min-h-10"><i class="fa fa-stethoscope"></i>
                    {{ $staffProfile->designation }}</div>
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

                <ul class="flexbox flex-justified text-center p-20">
                    <li>
                        <span class="text-muted">Specialized in</span><br>
                        <span class="fs-20">{{ $staffProfile->specialization }}</span>
                    </li>
                    <li class="be-1 bs-1 border-light">
                        <span class="text-muted">Department</span><br>
                        <span class="fs-20">Dental Medicine</span>
                    </li>
                    <li>
                        <span class="text-muted">Subspeciality</span><br>
                        <span class="fs-20">{{ $staffProfile->subspecialty }}</span>
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
                            <ul class="nav d-block nav-stacked">
                                <li class="nav-item d-flex justify-start align-items-center">
                                    <div class="min-w-120 text-muted">
                                        Role
                                    </div>
                                    <div>
                                        <?php
                                        // $roletitle = [];
                                        $roletitle = '';
                                        if ($userDetails->is_admin) {
                                            // $roletitle . push('Admin');
                                            $roletitle .= 'Admin';
                                        } ?> <?php if ($userDetails->is_doctor) {
                                            // $roletitle . push('Doctor');
                                            $roletitle .= ' Doctor';
                                        } ?> <?php if ($userDetails->is_nurse) {
                                            // $roletitle . push('Nurse');
                                            $roletitle .= ' Nurse';
                                        } ?>
                                        <?php if ($userDetails->is_reception) {
                                            // $roletitle . push('Receptionist');
                                            $roletitle .= ' Receptionist';
                                        }
                                        echo str_replace(' ', ', ', $roletitle);
                                        ?>
                                    </div>
                                </li>
                                <li class="nav-item d-flex justify-start align-items-center">
                                    <div class="min-w-120 text-muted">
                                        Date of Joining
                                    </div>
                                    <div>{{ $staffProfile->date_of_joining }}</div>
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
                                <span class="badge badge-pill badge-success-light"><i class="fa fa-angle-up me-10"></i>
                                    39%</span>
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
                            <p><i class="fa-solid fa-envelope text-muted"> </i> <span
                                    class="text-gray ps-10">{{ $userDetails->email }}</span> </p>
                            <p><i class="fa-solid fa-phone text-muted"></i> <span
                                    class="text-gray ps-10">{{ $staffProfile->phone }}</span></p>
                            <p><i class="fa-solid fa-location-dot text-muted"></i> <span class="text-gray ps-10">123,
                                    Lorem Ipsum, Florida,
                                    USA</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>

        <div class="box">
            <div class="box-header d-flex justify-content-between align-items-center">
                {{-- <h4 class="box-title">Availability</h4>
            </div> --}}

                <h4 class="box-title">Availability</h4>
                @foreach ($availableBranches as $branch)
                    <div style="width: 40%;">
                        <select class="select2" id="clinic_branch_id1" name="clinic_branch_id1" required
                            data-placeholder="Select a Branch" style="width: 100%;">
                            @foreach ($clinicBranches as $clinicBranch)
                                <?php
                                $clinicAddress = $clinicBranch->clinic_address;
                                $clinicAddress = explode('<br>', $clinicAddress);
                                $clinicAddress = implode(', ', $clinicAddress);
                                $branchName = $clinicAddress . ', ' . $clinicBranch->city->city . ', ' . $clinicBranch->state->state;
                                ?>
                                @if ($branch['clinic_branch_id'] == $clinicBranch->id)
                                    <option value="{{ $clinicBranch->id }}">
                                        {{ $branchName }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
            <div class="box-body">
                <div class="media-list media-list-divided media-list-hover">

                    <div class="media align-items-center justify">
                        <div class="media-body d-flex justify-content-between">
                            <h6>Sunday</h6>
                            <div class="fw-600 min-w-120 text-center">
                                {{-- 10:30 AM - 12:00 PM --}}
                                {{ $branch['timings']['sunday_from'] ?? '' }} -
                                {{ $branch['timings']['sunday_to'] ?? '' }}
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
                            <div class="fw-600 min-w-120 text-center">
                                {{-- 10:30 AM - 12:00 PM --}}
                                {{ $branch['timings']['monday_from'] ?? '' }} -
                                {{ $branch['timings']['monday_to'] ?? '' }}
                            </div>
                        </div>
                    </div>

                    <div class="media align-items-center justify">
                        <div class="media-body d-flex justify-content-between">
                            <h6>Tuesday</h6>
                            <div class="fw-600 min-w-120 text-center">
                                {{-- 10:30 AM - 12:00 PM --}}
                                {{ $branch['timings']['tuesday_from'] ?? '' }} -
                                {{ $branch['timings']['tuesday_to'] ?? '' }}
                            </div>
                        </div>
                    </div>

                    <div class="media align-items-center justify">
                        <div class="media-body d-flex justify-content-between">
                            <h6>Wednesday</h6>
                            <div class="fw-600 min-w-120 text-center">
                                {{-- 10:30 AM - 12:00 PM --}}
                                {{ $branch['timings']['wednesday_from'] ?? '' }} -
                                {{ $branch['timings']['wednesday_to'] ?? '' }}
                            </div>
                        </div>
                    </div>

                    <div class="media align-items-center justify">
                        <div class="media-body d-flex justify-content-between">
                            <h6>Thursday</h6>
                            <div class="fw-600 min-w-120 text-center">
                                {{-- 10:30 AM - 12:00 PM --}}
                                {{ $branch['timings']['thursday_from'] ?? '' }} -
                                {{ $branch['timings']['thursday_to'] ?? '' }}
                            </div>
                        </div>
                    </div>

                    <div class="media align-items-center justify">
                        <div class="media-body d-flex justify-content-between">
                            <h6>Friday</h6>
                            <div class="fw-600 min-w-120 text-center">
                                {{-- 10:30 AM - 12:00 PM --}}
                                {{ $branch['timings']['friday_from'] ?? '' }} -
                                {{ $branch['timings']['friday_to'] ?? '' }}
                            </div>
                        </div>
                    </div>

                    <div class="media align-items-center justify">
                        <div class="media-body d-flex justify-content-between">
                            <h6>Saturday</h6>
                            <div class="fw-600 min-w-120 text-center">
                                {{-- 10:30 AM - 12:00 PM --}}
                                {{ $branch['timings']['satday_from'] ?? '' }} -
                                {{ $branch['timings']['satday_to'] ?? '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

                    </div>
                    {{-- <div>
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
                    </div> --}}
                    {{-- <div>
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
                    </div> --}}
                    {{-- <div>
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
                    </div> --}}
                    {{-- <div>
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
                    </div> --}}
                    {{-- <div>
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
                    </div> --}}
                </div>
            </div>
        </div>

    </div>
</div>
