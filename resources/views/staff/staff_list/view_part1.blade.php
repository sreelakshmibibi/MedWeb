@php
    if ($totalUniquePatients != 0) {
        $woman = number_format(($femalePatientsCount / $totalUniquePatients) * 100, 2);
        $man = number_format(($malePatientsCount / $totalUniquePatients) * 100, 2);
    }
@endphp
<div class="row">
    <div class="col-xl-8 col-12">
        <div class="box">
            <div class="box-body ribbon-box">
                <div class="ribbon fs-18 @php echo ($staffProfile->status == 'Y') ? 'ribbon-success' : 'ribbon-danger'; @endphp"
                    style="float: right;
    margin-left: 0;
    margin-right: -30px; margin-right:-22px; border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;">

                    <?php if ($userDetails->is_doctor) { ?>
                    {{-- <i class="fa fa-stethoscope"></i> --}}
                    <i class="fa-solid fa-user-doctor"></i>
                    <?php } else if ($userDetails->is_nurse) { ?>
                    <i class="fa-solid fa-user-nurse"></i>
                    <?php } else if ($userDetails->is_reception) { ?>
                    <i class="fa-solid fa-user"></i>
                    <?php } else { ?>
                    <i class="fa-solid fa-user-tie"></i>
                    <?php } ?>
                    {{ $staffProfile->designation }}
                </div>

                <div class="d-flex align-items-center staffview_header">
                    @if ($staffProfile->photo != '')
                        <img src="{{ asset('storage/' . $staffProfile->photo) }}" alt="photo"
                            class="bg-success-light rounded10 me-20 align-self-end h-100">
                    @else
                        <img src="{{ asset('images/svg-icon/user.svg') }}" alt="photo"
                            class="bg-primary rounded10 me-20 align-self-end h-100">
                    @endif

                    <div class="d-flex flex-column flex-grow-1">
                        <a href="#"
                            class="box-title text-muted fw-600 fs-18 mb-2 hover-primary"><?php echo str_replace('<br>', ' ', $userDetails->name); ?></a>
                        <span class="fw-500 text-fade">{{ $staffProfile->qualification }}</span>
                        <span class="fw-500 text-fade">{{ $staffProfile->years_of_experience }}</span>
                        {{-- <span class="fw-500 text-fade">16 years of Experience</span> --}}
                    </div>

                    <div
                        class="d-none rounded10 p-15 fs-18 d-inline min-h-50 @php echo ($staffProfile->status == 'Y') ? 'bg-success' : 'bg-danger'; @endphp">
                        <?php if ($userDetails->is_doctor) { ?>
                        {{-- <i class="fa fa-stethoscope"></i> --}}
                        <i class="fa-solid fa-user-doctor"></i>
                        <?php } else if ($userDetails->is_nurse) { ?>
                        <i class="fa-solid fa-user-nurse"></i>
                        <?php } else if ($userDetails->is_reception) { ?>
                        <i class="fa-solid fa-user"></i>
                        <?php } else { ?>
                        <i class="fa-solid fa-user-tie"></i>
                        <?php } ?>
                        {{ $staffProfile->designation }}
                    </div>
                </div>
            </div>
            <div class="box-header staff-section pt-3" style="display: none;">
                <h4 class="mb-0">Other Information</h4>
            </div>
            <div class="box-body staff-section" style="display: none;">
                <ul class="nav d-block nav-stacked py-0 ">
                    <div class="row">
                        <div class="col">
                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Role
                                </div>
                                <div>
                                    @php
                                        $roletitle = '';
                                        if ($userDetails->is_admin) {
                                            $roletitle .= 'Admin';
                                        }
                                        if ($userDetails->is_doctor) {
                                            $roletitle .= 'Doctor';
                                        }
                                        if ($userDetails->is_nurse) {
                                            $roletitle .= 'Nurse';
                                        }
                                        if ($userDetails->is_reception) {
                                            $roletitle .= 'Receptionist';
                                        }
                                        echo preg_replace('/(?<!^)([A-Z])/', ', $1', $roletitle);
                                    @endphp
                                </div>
                            </li>
                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Date of Joining
                                </div>
                                <div>
                                    @php
                                        echo date('d-m-Y', strtotime($staffProfile->date_of_joining));
                                    @endphp
                                </div>
                            </li>
                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Aadhaar No.
                                </div>
                                <div>{{ $staffProfile->aadhaar_no }}</div>
                            </li>
                            <?php 
                            if ($userDetails->is_nurse) { ?>
                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    License No.
                                </div>
                                <div>{{ $staffProfile->license_number }}</div>
                            </li>
                            <?php } ?>
                            <li class="nav-item d-flex justify-start">
                                <div class="min-w-120 text-muted">
                                    Leaves taken
                                </div>
                                <div>{{ $totalLeaves }}</div>
                            </li>
                        </div>

                        <div class="col  ps-20">
                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Gender
                                </div>
                                <div>
                                    @php
                                        if ($staffProfile->gender === 'M') {
                                            echo 'Male';
                                        } elseif ($staffProfile->gender === 'F') {
                                            echo 'Female';
                                        } else {
                                            echo 'Other';
                                        }
                                    @endphp
                                </div>
                            </li>
                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Date of Birth
                                </div>
                                <div>
                                    @php
                                        echo date('d-m-Y', strtotime($staffProfile->date_of_birth));
                                    @endphp
                                </div>
                            </li>

                            <li class="nav-item d-flex justify-start">
                                <div class="min-w-120 text-muted">
                                    Address (P)
                                </div>
                                <div>
                                    {{ $staffProfile->address1 . ', ' . $staffProfile->address2 . ', ' }}
                                    @php
                                        $countryName =
                                            $countries->firstWhere('id', $staffProfile->country_id)->country ?? '';
                                        $stateName = $states->firstWhere('id', $staffProfile->state_id)->state ?? '';
                                        $cityName = $cities->firstWhere('id', $staffProfile->city_id)->city ?? '';
                                        $address = $cityName . ', ' . $stateName . ', ' . $countryName;
                                        echo $address;
                                    @endphp
                                    {{ '- ' . $staffProfile->pincode }}
                                </div>
                            </li>
                            {{-- <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Total Leaves taken
                                </div>
                                <div>
                                    @php
                                        echo $totalLeaves;
                                    @endphp
                                </div>
                            </li> --}}

                        </div>
                    </div>
                </ul>
            </div>

            <div class="box-body doctor-section pt-0" style="display: none;">
                <h4>About</h4>
                <p>@php echo str_replace('<br>', ' ', $userDetails->name); @endphp is an experienced dentist specializing in {{ $staffProfile->specialization }},
                    with
                    {{-- over 15 years of  --}}
                    practice of {{ $staffProfile->years_of_experience }}. He qualified
                    {{ $staffProfile->qualification }} and is dedicated to providing
                    compassionate care and achieving optimal oral health outcomes for his patients.</p>
                <ul class="flexbox flex-justified text-center p-0">
                    <li>
                        <span class="text-muted">Specialized in</span><br>
                        <span class="fs-20">{{ $staffProfile->specialization }}</span>
                    </li>
                    <li class="be-1 bs-1 border-light">
                        <span class="text-muted">Department</span><br>
                        <span class="fs-20">
                            @php
                                echo $departments->firstWhere('id', $staffProfile->department_id)->department ?? '';
                            @endphp
                        </span>
                    </li>
                    <li>
                        <span class="text-muted">Subspeciality</span><br>
                        <span class="fs-20">{{ $staffProfile->subspecialty }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row doctor-section" style="display: none;">
            <div class="col-md-9 col-12">
                <div class="box">
                    <div class="box-header">
                        <h4 class="mb-0">Other Information</h4>
                    </div>
                    <div class="box-body">
                        <div class="inner-user-div5">
                            <ul class="nav d-block nav-stacked py-0 ">
                                <div class="row">
                                    <div class="col">
                                        <li class="nav-item d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                Role
                                            </div>
                                            <div>
                                                @php
                                                    $roletitle = '';
                                                    if ($userDetails->is_admin) {
                                                        $roletitle .= 'Admin';
                                                    }
                                                    if ($userDetails->is_doctor) {
                                                        $roletitle .= 'Doctor';
                                                    }
                                                    if ($userDetails->is_nurse) {
                                                        $roletitle .= 'Nurse';
                                                    }
                                                    if ($userDetails->is_reception) {
                                                        $roletitle .= 'Receptionist';
                                                    }
                                                    echo preg_replace('/(?<!^)([A-Z])/', ', $1', $roletitle);
                                                @endphp
                                            </div>
                                        </li>
                                        <li class="nav-item d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                Date of Joining
                                            </div>
                                            <div>
                                                @php
                                                    echo date('d-m-Y', strtotime($staffProfile->date_of_joining));
                                                @endphp
                                            </div>
                                        </li>
                                        {{-- <li class="nav-item d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                Aadhaar no
                                            </div>
                                            <div>{{ $staffProfile->aadhaar_no }}</div>
                                        </li> --}}
                                        <li class="nav-item d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                License No.
                                            </div>
                                            <div>{{ $staffProfile->license_number }}</div>
                                        </li>
                                        <li class="nav-item d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                Consultation Fee
                                            </div>
                                            <div>&#8377; {{ $staffProfile->consultation_fees }}</div>
                                        </li>
                                        <li class="nav-item d-flex justify-start">
                                            <div class="min-w-120 text-muted">
                                                Leaves taken
                                            </div>
                                            <div>{{ $totalLeaves }}</div>
                                        </li>

                                    </div>
                                    <div class="col ps-20">
                                        <li class="nav-item d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                Gender
                                            </div>
                                            <div>
                                                @php
                                                    if ($staffProfile->gender === 'M') {
                                                        echo 'Male';
                                                    } elseif ($staffProfile->gender === 'F') {
                                                        echo 'Female';
                                                    } else {
                                                        echo 'Other';
                                                    }
                                                @endphp
                                            </div>
                                        </li>
                                        <li class="nav-item d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                Aadhaar no
                                            </div>
                                            <div>{{ $staffProfile->aadhaar_no }}</div>
                                        </li>
                                        <li class="nav-item d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                Date of Birth
                                            </div>
                                            <div>
                                                @php
                                                    echo date('d-m-Y', strtotime($staffProfile->date_of_birth));
                                                @endphp
                                            </div>
                                        </li>

                                        <li class="nav-item d-flex justify-start">
                                            <div class="min-w-120 text-muted">
                                                Address (P)
                                            </div>
                                            <div>
                                                {{ $staffProfile->address1 . ', ' . $staffProfile->address2 . ', ' }}
                                                @php
                                                    $countryName =
                                                        $countries->firstWhere('id', $staffProfile->country_id)
                                                            ->country ?? '';
                                                    $stateName =
                                                        $states->firstWhere('id', $staffProfile->state_id)->state ?? '';
                                                    $cityName =
                                                        $cities->firstWhere('id', $staffProfile->city_id)->city ?? '';
                                                    $address = $cityName . ', ' . $stateName . ', ' . $countryName;
                                                    echo $address;
                                                @endphp
                                                {{ '- ' . $staffProfile->pincode }}
                                            </div>
                                        </li>
                                        {{-- <li class="nav-item d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                Total Leaves taken
                                            </div>
                                            <div>
                                                @php
                                                    echo $totalLeaves;
                                                @endphp
                                            </div>
                                        </li> --}}

                                    </div>
                                </div>
                            </ul>
                            {{-- <p class="hover-primary text-fade my-1 fs-16 text-decoration-underline">Uploaded Documents
                            </p> --}}
                            {{-- <ul class="nav d-block nav-stacked py-10 ">

                                <li class="nav-item d-flex justify-start align-items-center bb-dashed border-bottom">
                                    <div class="min-w-120">
                                        Photo
                                    </div>
                                    <div class="min-w-200 text-muted">{{ $staffProfile->date_of_joining }}</div>
                                    <a class="btn "><i class="fa fa-download"></i></a>
                                </li>

                                <li class="nav-item d-flex justify-start align-items-center bb-dashed border-bottom">
                                    <div class="min-w-120">
                                        Passport
                                    </div>
                                    <div class="min-w-200 text-muted">{{ $staffProfile->date_of_joining }}</div>
                                    <a class="btn "><i class="fa fa-download"></i></a>
                                </li>
                            </ul> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="box">
                    <div class="box-body px-0 text-center py-3">
                        <div style="min-height: 156px;">
                            <div id="patientschart"></div>
                        </div>

                        <div class="mt-15 d-inline-block">
                            <div class="text-start mb-10">
                                <span class="badge badge-xl badge-dot badge-primary me-15"></span> Woman
                                {{ isset($woman) ? $woman : '-' }}%
                            </div>
                            <div class="text-start">
                                <span class="badge badge-xl badge-dot badge-primary-light me-15"></span>
                                Man
                                {{ isset($man) ? $man : '-' }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-3 col-12">
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
            </div> --}}
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
                            <p class="mb-0"><i class="fa-solid fa-location-dot text-muted"></i> <span
                                    class="text-gray ps-10">
                                    {{ $staffProfile->com_address1 . ', ' . $staffProfile->com_address2 . ', ' }}
                                    @php
                                        $countryName =
                                            $countries->firstWhere('id', $staffProfile->com_country_id)->country ?? '';
                                        $stateName =
                                            $states->firstWhere('id', $staffProfile->com_state_id)->state ?? '';
                                        $cityName = $cities->firstWhere('id', $staffProfile->com_city_id)->city ?? '';
                                        $address = $cityName . ', ' . $stateName . ', ' . $countryName;
                                        echo $address;
                                    @endphp
                                    {{ '- ' . $staffProfile->com_pincode }}
                                </span></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>

        <div class="box staff-section" style="display: none;">
            <div class="box-body box-profile">
                <ul class="flexbox flex-justified text-center">
                    <li>
                        <span class="text-muted">Department</span><br>
                        <span class="fs-16">@php
                            echo $departments->firstWhere('id', $staffProfile->department_id)->department ?? '';
                        @endphp</span>
                    </li>
                    <li class=" bs-1 border-light">
                        <span class="text-muted">Branch</span><br>
                        <span class="fs-16">
                            @php
                                $clinicAddress =
                                    $clinicBranches->firstWhere('id', $staffProfile->clinic_branch_id)
                                        ->clinic_address ?? '';
                                $clinicAddress = explode('<br>', $clinicAddress);
                                $clinicAddress = implode(', ', $clinicAddress);
                                echo $clinicAddress;
                            @endphp
                        </span>
                    </li>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>

        <div class="box doctor-section" style="display: none;">
            <div class="box-header d-flex justify-content-between align-items-center py-3">
                <h4 class="box-title">Availability</h4>
                <div style="width: 40%;">
                    <select class="select2" id="clinic_branch_id1" name="clinic_branch_id1" required
                        data-placeholder="Select a Branch" style="width: 100%;">
                        @foreach ($clinicBranches as $clinicBranch)
                            @php
                                $clinicAddress = $clinicBranch->clinic_address;
                                $clinicAddress = explode('<br>', $clinicAddress);
                                $clinicAddress = implode(', ', $clinicAddress);
                                $branchName =
                                    $clinicAddress .
                                    ', ' .
                                    $clinicBranch->city->city .
                                    ', ' .
                                    $clinicBranch->state->state;
                            @endphp
                            @foreach ($availableBranches as $branch)
                                @if ($clinicBranch->id == $branch['clinic_branch_id'])
                                    <option value="{{ $clinicBranch->id }}">
                                        {{ $branchName }}</option>
                                @endif
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="box-body">
                <div class="media-list media-list-divided media-list-hover" id="availability-list">

                </div>
            </div>
        </div>

        <button class="btn btn-success btn-file w-p100 mb-4" type="button" id="uploadButton" data-bs-toggle="modal"
            data-bs-target="#modal-documents"><i class="fa-solid fa-upload"></i>
            Upload Documents
            {{-- <input id="fileInput" type="file" name="documents[]" multiple> --}}
        </button>
    </div>
</div>
