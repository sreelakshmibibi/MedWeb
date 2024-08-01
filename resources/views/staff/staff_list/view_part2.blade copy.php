<div class="row">
    <div class="col-xl-8 col-12">
        <div class="box">
            <div class="box-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Your Patients today</h4>
                    <a href="#" class="">All patients <i class="ms-10 fa fa-angle-right"></i></a>
                </div>
            </div>
            <div class="box-body p-15">
                <div class="mb-10 d-flex justify-content-between align-items-center">
                    <div class="fw-600 min-w-120">
                        10:30am
                    </div>
                    <div class="w-p100 p-10 rounded10 justify-content-between align-items-center d-flex bg-lightest">
                        <div class="d-flex justify-content-between align-items-center">
                            {{-- <img src="../images/avatar/1.jpg" class="me-10 avatar rounded-circle" alt=""> --}}
                            <div>
                                <h6 class="mb-0">Sarah Hostemn</h6>
                                <p class="mb-0 fs-12 text-mute">Diagnosis: Bronchitis</p>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a data-bs-toggle="dropdown" href="#" aria-expanded="false" class=""><i
                                    class="ti-more-alt rotate-90"></i></a>
                            <div class="dropdown-menu dropdown-menu-end" style="">
                                <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                    Details</a>
                                <a class="dropdown-item" href="#"><i class="ti-export"></i> Lab
                                    Reports</a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="mb-10 d-flex justify-content-between align-items-center">
                    <div class="fw-600 min-w-120">
                        11:00am
                    </div>
                    <div class="w-p100 p-10 rounded10 justify-content-between align-items-center d-flex bg-lightest">
                        <div class="d-flex justify-content-between align-items-center">
                            {{-- <img src="../images/avatar/2.jpg" class="me-10 avatar rounded-circle" alt=""> --}}
                {{-- }}    <div>
                                <h6 class="mb-0">Dakota Smith</h6>
                                <p class="mb-0 fs-12 text-mute">Diagnosis: Stroke</p>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a data-bs-toggle="dropdown" href="#"><i class="ti-more-alt rotate-90"></i></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                    Details</a>
                                <a class="dropdown-item" href="#"><i class="ti-export"></i> Lab
                                    Reports</a>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="d-flex justify-content-between align-items-center">
                    <div class="fw-600 min-w-120">
                        11:30am
                    </div>
                    <div class="w-p100 p-10 rounded10 justify-content-between align-items-center d-flex bg-lightest">
                        <div class="d-flex justify-content-between align-items-center">
                            {{-- <img src="../images/avatar/3.jpg" class="me-10 avatar rounded-circle" alt=""> --}}
                {{-- }  <div>
                                <h6 class="mb-0">John Lane</h6>
                                <p class="mb-0 fs-12 text-mute">Diagnosis: Liver cimhosis</p>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a data-bs-toggle="dropdown" href="#"><i class="ti-more-alt rotate-90"></i></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                    Details</a>
                                <a class="dropdown-item" href="#"><i class="ti-export"></i> Lab
                                    Reports</a>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-12">

        {{-- <div class="box">
            <div class="box-header d-flex justify-content-between align-items-center">
                {{-- <h4 class="box-title">Availability</h4>
            </div> --}}

        {{-- }}        <h4 class="box-title">Availability</h4>
                @foreach ($availableBranches as $branch)
                    <div style="width: 40%;">
                        <select class="select2" id="clinic_branch_id1" name="clinic_branch_id1" required
                            data-placeholder="Select a Branch" style="width: 100%;">
                            @foreach ($clinicBranches as $clinicBranch)
                                <?php
                                // $clinicAddress = $clinicBranch->clinic_address;
                                // $clinicAddress = explode('<br>', $clinicAddress);
                                // $clinicAddress = implode(', ', $clinicAddress);
                                // $branchName = $clinicAddress . ', ' . $clinicBranch->city->city . ', ' . $clinicBranch->state->state;
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
        {{-- {{ $branch['timings']['sunday_from'] ?? '' }} - --}}
        {{-- {{ $branch['timings']['sunday_to'] ?? '' }} --}}
    </div>
    {{-- <small>
                                <span>10:00 AM</span>
                                <span class="divider-dash">12:00 PM/span>
                            </small> --}}
    {{-- }}      </div>
                    </div>

                    <div class="media align-items-center justify">
                        <div class="media-body d-flex justify-content-between">
                            <h6>Monday</h6>
                            <div class="fw-600 min-w-120 text-center">
                                {{-- 10:30 AM - 12:00 PM --}}
    {{-- {{ $branch['timings']['monday_from'] ?? '' }} - --}}
    {{-- {{ $branch['timings']['monday_to'] ?? '' }} --}}
    {{-- }} </div>
                        </div>
                    </div>

                    <div class="media align-items-center justify">
                        <div class="media-body d-flex justify-content-between">
                            <h6>Tuesday</h6>
                            <div class="fw-600 min-w-120 text-center">
                                {{-- 10:30 AM - 12:00 PM --}}
    {{-- {{ $branch['timings']['tuesday_from'] ?? '' }} - --}}
    {{-- {{ $branch['timings']['tuesday_to'] ?? '' }} --}}
    {{-- }}  </div>
                        </div>
                    </div>

                    <div class="media align-items-center justify">
                        <div class="media-body d-flex justify-content-between">
                            <h6>Wednesday</h6>
                            <div class="fw-600 min-w-120 text-center">
                                {{-- 10:30 AM - 12:00 PM --}}
    {{-- {{ $branch['timings']['wednesday_from'] ?? '' }} - --}}
    {{-- {{ $branch['timings']['wednesday_to'] ?? '' }} --}}
    {{-- }}  </div>
                        </div>
                    </div>

                    <div class="media align-items-center justify">
                        <div class="media-body d-flex justify-content-between">
                            <h6>Thursday</h6>
                            <div class="fw-600 min-w-120 text-center">
                                {{-- 10:30 AM - 12:00 PM --}}
    {{-- {{ $branch['timings']['thursday_from'] ?? '' }} - --}}
    {{-- {{ $branch['timings']['thursday_to'] ?? '' }} --}}
    {{-- }}    </div>
                        </div>
                    </div>

                    <div class="media align-items-center justify">
                        <div class="media-body d-flex justify-content-between">
                            <h6>Friday</h6>
                            <div class="fw-600 min-w-120 text-center">
                                {{-- 10:30 AM - 12:00 PM --}}
    {{-- {{ $branch['timings']['friday_from'] ?? '' }} - --}}
    {{-- {{ $branch['timings']['friday_to'] ?? '' }} --}}
    {{-- }}       </div>
                        </div>
                    </div>

                    <div class="media align-items-center justify">
                        <div class="media-body d-flex justify-content-between">
                            <h6>Saturday</h6>
                            <div class="fw-600 min-w-120 text-center">
                                {{-- 10:30 AM - 12:00 PM --}}
    {{-- {{ $branch['timings']['satday_from'] ?? '' }} - --}}
    {{-- {{ $branch['timings']['satday_to'] ?? '' }} --}}
    {{-- }}  </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

</div>
</div>
