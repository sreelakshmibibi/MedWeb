<?php

use App\Services\CommonService;
$commonService = new CommonService();

?>
<input type="hidden" id="visitcount" name="visitcount" value="{{ $appointment->patient->visit_count }}">
<input type="hidden" id="isAdmin" name="isAdmin" value="{{ Auth::user()->is_admin }}">
<div class="row px-2">
    <div class="col-xl-8 col-12 ps-0">
        <div class="box flex-grow-1 mb-3" style="border-radius: 0px; /*height: 364px;*/">
            <div class="box-body bb-1" style="border-radius: 0px; ">
                <div class="d-flex align-items-center treat_personal_info_div">
                    <img src="{{ asset('images/svg-icon/user.svg') }}" alt="photo"
                        class="bg-success rounded10 me-20 align-self-end h-100 treat_patient_photo">
                    <div class="d-flex flex-column flex-grow-1 treat_patient_info">
                        <a href="#" class="box-title text-muted fw-500 fs-18 mb-2 hover-primary">
                            <?= str_replace('<br>', ' ', $appointment->patient->first_name . ' ' . $appointment->patient->last_name) ?><br>
                            <?= $appointment->patient->patient_id ?>
                        </a>
                        <span class="fw-500 text-fade">
                            @php
                                if ($appointment->patient->gender == 'M') {
                                    echo 'Male';
                                } elseif ($appointment->patient->gender == 'F') {
                                    echo 'Female';
                                } else {
                                    echo 'Other';
                                }
                            @endphp
                        </span>

                        <span class="fw-500 text-fade"><i class="fa-solid fa-droplet text-danger"> </i>
                            @php
                                if ($appointment->patient->blood_group == '') {
                                    echo '-';
                                } else {
                                    echo $appointment->patient->blood_group;
                                }
                            @endphp
                            &nbsp;
                            <i class="fa-regular fa-heart text-danger"></i>
                            <?php $age = $commonService->calculateAge($appointment->patient->date_of_birth);
                            echo $age; ?>
                        </span>
                    </div>
                    <div class=" d-flex flex-wrap flex-column b-0 w-150 treat_patient_info">
                        <p class="mb-1" style="word-break: break-all;"><i class="fa-solid fa-envelope text-muted">
                            </i>
                            <span class="text-gray ps-10">
                                @php
                                    if ($appointment->patient->email == '') {
                                        echo '-';
                                    } else {
                                        echo $appointment->patient->email;
                                    }
                                @endphp
                            </span>
                        </p>
                        <p class="mb-1"><i class="fa-solid fa-phone text-muted"></i>
                            <span class="text-gray ps-10">
                                <?= $appointment->patient->phone ?>
                                @php
                                    if ($appointment->patient->alternate_phone != '') {
                                        echo ', ' . $appointment->patient->alternate_phone;
                                    }
                                @endphp
                            </span>
                        </p>
                        <p class="mb-0"><i class="fa-solid fa-location-dot text-muted"></i>
                            <span
                                class="text-gray ps-10"><?= $appointment->patient->state->state . ', ' . $appointment->patient->country->country . '- ' . $appointment->patient->pincode ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row treat_other_info_div">
                <div class="col be-1 pe-0">
                    <div class="box flex-grow-1 mb-0 no-border" style="border-radius: 0px; /*height: 195px;*/">
                        <div class="box-body ">
                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Date of Birth
                                </div>
                                <div>
                                    <?= (new DateTime($appointment->patient->date_of_birth))->format('d-m-Y') ?>
                                </div>
                            </li>
                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Aadhaar No.
                                </div>
                                <div>
                                    @php
                                        if ($appointment->patient->aadhaar_no == '') {
                                            echo '-';
                                        } else {
                                            echo $appointment->patient->aadhaar_no;
                                        }
                                    @endphp
                                </div>
                            </li>
                            <li class="nav-item d-flex justify-start">
                                <div class="min-w-120 text-muted">
                                    Address
                                </div>
                                <div>
                                    <?= $appointment->patient->address1 . ', ' . $appointment->patient->address2 . ', ' . $appointment->patient->city->city . ', ' . $appointment->patient->state->state . ', ' . $appointment->patient->country->country . '- ' . $appointment->patient->pincode ?>
                                </div>
                            </li>

                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Marital Status
                                </div>
                                <div>
                                    @php
                                        if ($appointment->patient->marital_status == '') {
                                            echo '-';
                                        } else {
                                            echo $appointment->patient->marital_status;
                                        }
                                    @endphp
                                </div>
                            </li>

                            <?php if ($appointment->patient->gender == 'F') { ?>
                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Pregnant
                                </div>
                                <div>
                                    @php
                                        if ($appointment->pregnant == '') {
                                            echo '-';
                                        } else {
                                            if ($appointment->pregnant == 'Y') {
                                                echo 'Yes';
                                            } else {
                                                echo 'No';
                                            }
                                        }
                                    @endphp
                                </div>
                            </li>
                            <?php } ?>

                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Referred Doctor
                                </div>
                                <div>
                                    @php
                                        if ($appointment->referred_doctor == '') {
                                            echo '-';
                                        } else {
                                            echo $appointment->referred_doctor;
                                        }
                                    @endphp
                                </div>
                            </li>
                        </div>
                    </div>
                </div>
                <div class="col ps-0">
                    <div class="box flex-grow-1 mb-0 no-border" style="border-radius: 0px; /*height: 195px;*/">
                        <div class="box-body ">
                            <div class="media-list  px-0">
                                <div class="media media-single pt-0 px-0 pb-3 bb-1">
                                    <div class="media-body m-0 p-0">
                                        <span class="text-warning fs-16"><i class="fa-solid fa-square-virus">
                                            </i> Allergies</span>

                                        <h6 class="ps-2 mt-2">
                                            @php
                                                if ($appointment->allergies == '') {
                                                    echo '-';
                                                } else {
                                                    echo $appointment->allergies;
                                                }
                                            @endphp
                                        </h6>
                                    </div>
                                </div>

                                <div class="media media-single  pt-2 px-0 ">
                                    <div class="media-body m-0 p-0">
                                        <span class="text-warning fs-16 "><i
                                                class="fa-solid fa-disease "></i>&nbsp;Disease
                                            History</span>
                                        <h6 class="ms-2 mt-2">
                                            <?php
                                                    if ($appointment->patient->history == '') {
                                                        echo '-';
                                                    } else {
                                                        foreach ($appointment->patient->history as $history) {
                                                            // echo $history->history . '<br>';
                                                          ?> <li>
                                                <?php echo $history->history; ?> </li>
                                            <?php
                                                        }
                                                    }
                                                ?>
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

    <div class="col-xl-4 col-12 g-0">
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
                                                <h4 class="text-light mb-0"><i class="fas fa-smoking"></i></h4>
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

                                {{-- <div class="media media-single bt-1 pt-10  p-2 px-0">
                                    <div class="w-10"><i class="fas fa-utensils"></i></div>
                                    <div class="media-body">
                                        <h6>
                                            @php
                                                if ($appointment->patient->diet == '') {
                                                    echo '-';
                                                } else {
                                                    echo $appointment->patient->diet;
                                                }
                                            @endphp
                                        </h6>
                                        <small class="text-fader text-muted">Dieting
                                            status</small>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
