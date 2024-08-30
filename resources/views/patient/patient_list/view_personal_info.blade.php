<div class="row ">
    <input type="hidden" id="pvisitcount" name="pvisitcount" value="{{ $patientProfile->visit_count }}">
    <div class="col-xl-8 col-12">
        <div class="box" style="border-radius: 0px;">
            <div class="box-body bb-1" style="border-radius: 0px;">
                <div class="d-flex align-items-center treat_personal_info_div">
                    <img src="{{ asset('images/svg-icon/user.svg') }}" alt="photo"
                        class="bg-success rounded10 me-20 align-self-end h-100 treat_patient_photo">
                    <div class="d-flex flex-column flex-grow-1 treat_patient_info">
                        <a href="#" class="box-title text-muted fw-500 fs-18 mb-2 hover-primary">
                            <?= str_replace('<br>', ' ', $patientProfile->first_name . ' ' . $patientProfile->last_name) ?><br>
                            <?= $patientProfile->patient_id ?>
                        </a>
                        <span class="fw-500 text-fade">
                            @php
                                if ($patientProfile->gender == 'M') {
                                    echo 'Male';
                                } elseif ($patientProfile->gender == 'F') {
                                    echo 'Female';
                                } else {
                                    echo 'Other';
                                }
                            @endphp
                        </span>

                        <span class="fw-500 text-fade"><i class="fa-solid fa-droplet text-danger"> </i>
                            @php
                                echo $patientProfile->blood_group ?: '-';
                            @endphp
                            &nbsp;
                            <i class="fa-regular fa-heart text-danger"></i>
                            <?php $age = $commonService->calculateAge($patientProfile->date_of_birth);
                            echo $age; ?>
                        </span>
                    </div>
                    <div class="d-flex flex-wrap flex-column b-0 w-150 treat_patient_info">
                        <p class="mb-1" style="word-break: break-all;"><i class="fa-solid fa-envelope text-muted">
                            </i>
                            <span class="text-gray ps-10">
                                @php
                                    echo $patientProfile->email ?: '-';
                                @endphp
                            </span>
                        </p>
                        <p class="mb-1"><i class="fa-solid fa-phone text-muted"></i>
                            <span class="text-gray ps-10">
                                <?= $patientProfile->phone ?>
                                @php
                                    echo $patientProfile->alternate_phone
                                        ? ', ' . $patientProfile->alternate_phone
                                        : '';
                                @endphp
                            </span>
                        </p>
                        <p class="mb-0"><i class="fa-solid fa-location-dot text-muted"></i>
                            <span class="text-gray ps-10"><?= $patientProfile->state->state ?? '' ?>,
                                <?= $patientProfile->country->country ?? '' ?>-
                                <?= $patientProfile->pincode ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col be-1">
                    <div class="box flex-grow-1 mb-0 no-border">
                        <div class="box-body">
                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Date of Birth
                                </div>
                                <div>
                                    <?= (new DateTime($patientProfile->date_of_birth))->format('d-m-Y') ?>
                                </div>
                            </li>
                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Aadhaar No.
                                </div>
                                <div>
                                    @php
                                        echo $patientProfile->aadhaar_no ?: '-';
                                    @endphp
                                </div>
                            </li>
                            <li class="nav-item d-flex justify-start">
                                <div class="min-w-120 text-muted">
                                    Address
                                </div>
                                <div>
                                    <?= $patientProfile->address1 . ', ' . $patientProfile->address2 . ', ' . $patientProfile->city->city ?? ('' . ', ' . $patientProfile->state->state ?? ('' . ', ' . $patientProfile->country->country ?? '' . '- ' . $patientProfile->pincode)) ?>
                                </div>
                            </li>

                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Marital Status
                                </div>
                                <div>
                                    @php
                                        echo $patientProfile->marital_status ?: '-';
                                    @endphp
                                </div>
                            </li>

                            @if ($patientProfile->gender == 'F')
                                <li class="nav-item d-flex justify-start align-items-center">
                                    <div class="min-w-120 text-muted">
                                        Pregnant
                                    </div>
                                    <div>
                                        @php

                                            if (isset($appointment)) {
                                                $pregnant = $appointment->pregnant;
                                                echo is_null($pregnant) ? '-' : ($pregnant == 'Y' ? 'Yes' : 'No');
                                            } else {
                                                echo '-';
                                            }
                                        @endphp
                                    </div>
                                </li>
                            @endif

                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Referred Doctor
                                </div>
                                <div>
                                    @php
                                        if ($appointment) {
                                            echo $appointment->referred_doctor ?: '-';
                                        } else {
                                            echo '-';
                                        }
                                    @endphp
                                </div>
                            </li>
                        </div>
                    </div>
                </div>

                <div class="col ps-0">
                    <div class="box flex-grow-1 mb-0 no-border">
                        <div class="box-body">
                            <div class="media-list px-0">
                                <div class="media media-single pt-0 px-0 pb-3 bb-1">
                                    <div class="media-body m-0 p-0">
                                        <span class="text-warning fs-16"><i class="fa-solid fa-square-virus">
                                            </i> Allergies</span>

                                        <h6 class="ps-2 mt-2">
                                            @php
                                                if ($appointment) {
                                                    echo $appointment->allergies ?: '-';
                                                } else {
                                                    echo '-';
                                                }
                                            @endphp
                                        </h6>
                                    </div>
                                </div>

                                <div class="media media-single pt-2 px-0 ">
                                    <div class="media-body m-0 p-0">
                                        <span class="text-warning fs-16 "><i
                                                class="fa-solid fa-disease "></i>&nbsp;Disease
                                            History</span>
                                        <h6 class="ms-2 mt-2">
                                            @php
                                                // Check if the history collection is empty
                                                if ($history->isEmpty()) {
                                                    echo '-';
                                                } else {
                                                    // Iterate over the history collection
                                                    // echo '<ul>';
                                                    foreach ($history as $record) {
                                                        // Access the 'history' field of each record
                                                        echo '<li>' . $record->history . '</li>';
                                                    }
                                                    // echo '</ul>';
                                                }
                                            @endphp
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-12 g-0 pe-3">
        <div class="box mb-0 " style="border-radius: 0px;">
            <div class="box-body py-2 ">
                <div class="row">
                    <div class="col-12">
                        <div class="row bb-1 ">
                            <h4 class="text-center mb-0">Current Vitals</h4>
                            <p class="text-center mb-0 text-warning">
                                <small>Recorded on
                                    <?= (new DateTime($appointment->updated_at))->format('d-m-Y h:i A') ?></small>
                            </p>
                        </div>
                        <div class="row bb-1 pb-10 pt-5">

                            <div class="col-4 d-flex align-content-center flex-wrap">
                                <div> <img class="img-fluid float-start w-30 mt-10 me-10"
                                        src="{{ asset('images/weight.png') }}" alt=""></div>
                                <div style="width: 60%">
                                    <p class="mb-0"><small>Weight</small></p>
                                    <h5 class="mb-0"><strong>
                                            @php
                                                if ($appointment->weight_kg == '') {
                                                    echo '-';
                                                } else {
                                                    echo $appointment->weight_kg . ' kg';
                                                }
                                            @endphp
                                        </strong></h5>
                                </div>
                            </div>

                            <div class="col-4 bs-1 be-1 d-flex align-content-center flex-wrap">
                                <div> <img class="img-fluid float-start w-30 me-10"
                                        src="{{ asset('images/human.png') }}" alt=""></div>
                                {{-- <div class="mt-10 pt-1" style="width: 60%"> --}}
                                <div class="mt-1" style="width: 60%">
                                    <p class="mb-0"><small>Height</small></p>
                                    <h5 class=" mb-0"><strong>
                                            @php
                                                if ($appointment->height_cm == '') {
                                                    echo '-';
                                                } else {
                                                    echo $appointment->height_cm . ' cm';
                                                }
                                            @endphp
                                        </strong></h5>
                                </div>
                            </div>
                            <div class="col-4 d-flex align-content-center flex-wrap">
                                <div><img class="img-fluid float-start w-30 mt-10 me-10"
                                        src="{{ asset('images/bmi.png') }}" alt=""></div>
                                <div>
                                    <p class="mb-0"><small>BMI</small></p>
                                    <h5 class="mb-0"><strong>
                                            @php
                                                if ($appointment->weight_kg == '' || $appointment->height_cm == '') {
                                                    echo '-';
                                                } else {
                                                    $bmi =
                                                        $appointment->weight_kg / pow($appointment->height_cm / 100, 2);
                                                    // Round BMI to 2 decimal places
                                                    $bmi = round($bmi, 2);
                                                    echo $bmi;
                                                }
                                            @endphp
                                        </strong></h5>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-5 bb-1 pb-10">
                            <div class="col-12 text-center">
                                <span class="text-danger">Blood Pressure</span>
                                @php
                                    if ($appointment->blood_pressure == '') {
                                        $parts = ['-', '-'];
                                    } else {
                                        $parts = explode('/', $appointment->blood_pressure);
                                    }
                                @endphp
                            </div>
                            <div class="col-6 be-1">
                                <div class="progress progress-xs mb-0 mt-5 w-40">
                                    <div class="progress-bar progress-bar-success progress-bar-striped"
                                        role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                        style="width: 100%">
                                    </div>
                                </div>
                                <h2 class="float-start mt-0 mr-10 me-10">
                                    <strong><?= $parts[0] ?></strong>
                                </h2>
                                <div>
                                    <p class="mb-0"><small>Systolic</small></p>
                                    <p class="mb-0 mt-0"><small class="vertical-align-super">mmHg</small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-6 ps-20">
                                <div class="progress progress-xs mb-0 mt-5 w-40">
                                    <div class="progress-bar progress-bar-danger progress-bar-striped"
                                        role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                        style="width: 100%">
                                    </div>
                                </div>
                                <h2 class="float-start mt-0 me-10">
                                    <strong><?= $parts[1] ?></strong>
                                </h2>
                                <div class="mt-0">
                                    <p class="mb-0"><small>Diastolic</small></p>
                                    <p class="mb-0 mt-0"><small class="vertical-align-super">mmHg</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-5 ">
                            <div class="media-list  px-0">
                                <div class="row  pb-10">
                                    <div class="col-6 be-1">
                                        <div class="media media-single p-2 px-0">
                                            <div class="w-10">
                                                {{-- <i class="fas fa-smoking"></i> --}}
                                                <h4 class="text-light mb-0"><i class="fas fa-smoking"></i>
                                                </h4>
                                            </div>
                                            <div class="media-body">
                                                <h6>
                                                    @php
                                                        if ($appointment->smoking_status == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $appointment->smoking_status;
                                                        }
                                                    @endphp
                                                </h6>
                                                <small class="text-fader text-muted">Smoking
                                                    status</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6 ps-20">
                                        <div class="media media-single  p-2 px-0">
                                            <div class="w-10">
                                                <h4 class="text-light mb-0"><i class="fas fa-wine-glass"></i>
                                                </h4>
                                            </div>
                                            <div class="media-body">
                                                <h6>
                                                    @php
                                                        if ($appointment->alcoholic_status == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $appointment->alcoholic_status;
                                                        }
                                                    @endphp
                                                </h6>
                                                <small class="text-fader text-muted">Drinking
                                                    status</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mx-0 bt-1 pt-2  pb-0">
                                    <div class="col-6 px-0 be-1">
                                        <div class="media media-single p-2 px-0">
                                            <div class="w-10">
                                                <h4 class="text-light mb-0"><i class="fas fa-utensils"></i></h4>
                                            </div>
                                            <div class="media-body">
                                                <h6>
                                                    @php
                                                        if ($appointment->diet == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $appointment->diet;
                                                        }
                                                    @endphp
                                                </h6>
                                                <small class="text-fader text-muted">Dieting
                                                    status</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6 ps-20">
                                        <div class="media media-single  p-2 px-0">
                                            <div class="w-10">
                                                <h4 class="text-light mb-0">
                                                    <i class="fa-solid fa-temperature-half"></i>
                                                </h4>
                                            </div>
                                            <div class="media-body">
                                                <h6>
                                                    @php
                                                        if ($appointment->temperature == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $appointment->temperature;
                                                        }
                                                    @endphp
                                                    &deg;F
                                                </h6>
                                                <small class="text-fader text-muted">Temperature</small>
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
    </div>
</div>
