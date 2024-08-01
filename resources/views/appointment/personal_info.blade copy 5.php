<?php

use App\Services\CommonService;
$commonService = new CommonService();

?>
<div class="row ">
    <div class="col-xl-8 col-12">
        <div class="box flex-grow-1 mb-3" style="border-radius: 0px; /*height: 364px;*/">
            <div class="box-body ">


                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/svg-icon/user.svg') }}" alt="photo"
                        class="bg-success rounded10 me-20 align-self-end h-100">

                    <div class="d-flex flex-column flex-grow-1">
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

                        <span class="fw-500 text-fade"><i class="fa-solid fa-droplet text-muted"> </i>
                            @php
                                if ($appointment->patient->blood_group == '') {
                                    echo '-';
                                } else {
                                    echo $appointment->patient->blood_group;
                                }
                            @endphp
                            &nbsp;
                            <i class="fa-regular fa-heart text-muted"></i>
                            <?php $age = $commonService->calculateAge($appointment->patient->date_of_birth);
                            echo $age; ?>
                        </span>

                    </div>
                    <div class=" d-flex flex-wrap  flex-column b-0 w-150">
                        <p class="mb-1"><i class="fa-solid fa-envelope text-muted"> </i>
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
            <div class="box-header  pt-3">
                <h4 class="mb-0">Other Information</h4>
            </div>
            <div class="box-body ">
                <ul class="nav d-block nav-stacked py-0 ">
                    <div class="row">
                        <div class="col be-1">
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

                        <div class="d-none col  ps-20">
                            {{-- <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Date of Registration
                                </div>
                                <div>
                                </div>
                            </li> --}}


                        </div>
                        <div class="col">
                            <div class="row pt-0 ">
                                <div class="media-list  px-0">
                                    <div class="row  pb-10">
                                        <div class="col-6 ">
                                            <div class="media media-single p-0 px-0">
                                                {{-- <div><i class="fa-solid fa-square-virus"></i> <span
                                                        class="text-danger">Allergies</span></div> --}}
                                                <div class="media-body">
                                                    {{-- <span class="text-warning fs-16"><i
                                                            class="fa-solid fa-square-virus text-muted"></i>&nbsp;<u>Allergies</u></span> --}}
                                                    <span class="text-warning fs-16"><u>Allergies</u></span>

                                                    <h6>
                                                        @php
                                                            if ($appointment->patient->allergies == '') {
                                                                echo '-';
                                                            } else {
                                                                echo $appointment->patient->allergies;
                                                            }
                                                        @endphp
                                                    </h6>
                                                    {{-- <small class="text-fader text-muted">Allergies</small> --}}


                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6 ps-10">
                                            <div class="media media-single   p-0">
                                                {{-- <div class="w-10"><i class="fas fa-wine-glass"></i></div> --}}
                                                <div class="media-body">
                                                    {{-- <span class="text-warning fs-16"><i
                                                            class="fa-solid fa-disease text-muted"></i>&nbsp;<u>Disease
                                                            History</u></span> --}}
                                                    <span class="text-warning fs-16"><u>Disease
                                                            History</u></span>
                                                    <h6>
                                                        @php
                                                            if ($appointment->patient->history == '') {
                                                                echo '-';
                                                            } else {
                                                                foreach ($appointment->patient->history as $history) {
                                                                    echo $history->history . '<br>';
                                                                }
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
                        <div class="d-none col  ps-20">
                            <li class="d-none nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Referred Doctor
                                </div>
                                <div>

                                </div>
                            </li>
                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Allergies
                                </div>
                                <div>
                                    @php
                                        // if ($staffProfile->gender === 'M') {
                                        //     echo 'Male';
                                        // } elseif ($staffProfile->gender === 'F') {
                                        //     echo 'Female';
                                        // } else {
                                        //     echo 'Other';
                                        // }
                                    @endphp
                                </div>
                            </li>
                            <li class="nav-item d-flex justify-start align-items-center">
                                <div class="min-w-120 text-muted">
                                    Disease
                                </div>
                                <div>
                                    @php
                                        // echo date('d-m-Y', strtotime($staffProfile->date_of_birth));
                                    @endphp
                                </div>
                            </li>


                        </div>
                    </div>
                </ul>
            </div>


        </div>


    </div>
    <div class="d-none col-xl-8 col-12">
        <div class="box flex-grow-1 mb-3" style="border-radius: 0px;">
            {{-- <div class="box-header border-0 pb-0 ">
                <h4 class="box-title text-decoration-underline">Basic Information</h4>
            </div> --}}
            {{-- <div class="row bb-1 ">
                <h4 class="text-center  my-2 mb-1">Basic Info</h4>
                {{-- <p class="text-center mb-0 text-warning">
                    <small>Recorded on
                </p> --}}
            {{-- } </div> --}}
            <div class="box-body">
                <div class="media-list  px-0">
                    <div class="media media-single p-0">
                        <div class="media-body">
                            <ul class="d-block list-none">
                                <div class="row">
                                    <div class="col">
                                        <li class="d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                <h6 class="text-primary">Patient Name:</h6>
                                            </div>
                                            <div>
                                                <h6><?= str_replace('<br>', ' ', $appointment->patient->first_name . ' ' . $appointment->patient->last_name) ?>
                                                </h6>
                                            </div>
                                        </li>
                                        <li class="d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                <h6 class="text-primary">Patient ID:</h6>
                                            </div>
                                            <div>
                                                <h6><?= $appointment->patient->patient_id ?></h6>
                                            </div>
                                        </li>
                                        <li class="d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                <h6 class="text-primary">Gender:</h6>
                                            </div>
                                            <div>
                                                <h6>
                                                    @php
                                                        if ($appointment->patient->gender == 'M') {
                                                            echo 'Male';
                                                        } elseif ($appointment->patient->gender == 'F') {
                                                            echo 'Female';
                                                        } else {
                                                            echo 'Other';
                                                        }
                                                    @endphp
                                                </h6>
                                            </div>
                                        </li>

                                        <li class="d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                <h6 class="text-primary">Age: </h6>
                                            </div>
                                            <div>
                                                <h6>
                                                    <?php $age = $commonService->calculateAge($appointment->patient->date_of_birth);
                                                    echo $age; ?>
                                                </h6>
                                            </div>
                                        </li>

                                        <li class="d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                <h6 class="text-primary">Blood Group:</h6>
                                            </div>
                                            <div>
                                                <h6>
                                                    @php
                                                        if ($appointment->patient->blood_group == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $appointment->patient->blood_group;
                                                        }
                                                    @endphp
                                                </h6>
                                            </div>
                                        </li>

                                    </div>
                                    <div class="col">
                                        <li class="d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                <h6 class="text-primary">D.O.B:</h6>
                                            </div>
                                            <div>
                                                <h6>
                                                    <?= (new DateTime($appointment->patient->date_of_birth))->format('d-m-Y') ?>
                                                </h6>
                                            </div>
                                        </li>

                                        <li class="d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                <h6 class="text-primary">Aadhaar No.:</h6>
                                            </div>
                                            <div>
                                                <h6>
                                                    @php
                                                        if ($appointment->patient->aadhaar_no == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $appointment->patient->aadhaar_no;
                                                        }
                                                    @endphp
                                                </h6>
                                            </div>
                                        </li>

                                        <li class="d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                <h6 class="text-primary">Marital Status:</h5>
                                            </div>
                                            <div>
                                                <h6>
                                                    @php
                                                        if ($appointment->patient->marital_status == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $appointment->patient->marital_status;
                                                        }
                                                    @endphp
                                                </h6>
                                            </div>
                                        </li>

                                        <li class="d-flex justify-start">
                                            <div class="min-w-120 text-muted">
                                                <h6 class="text-primary">Address:</h6>
                                            </div>
                                            <div>
                                                <h6>
                                                    <?= $appointment->patient->address1 . ', ' . $appointment->patient->address2 . ', ' . $appointment->patient->city->city . ', ' . $appointment->patient->state->state . ', ' . $appointment->patient->country->country . '- ' . $appointment->patient->pincode ?>
                                                </h6>
                                            </div>
                                        </li>

                                        <li class="d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                <h6 class="text-primary">Contact Number:</h6>
                                            </div>
                                            <div>
                                                <h6><?= $appointment->patient->phone ?>
                                                    @php
                                                        if ($appointment->patient->alternate_phone != '') {
                                                            echo ', ' . $appointment->patient->alternate_phone;
                                                        }
                                                    @endphp
                                                </h6>
                                            </div>
                                        </li>

                                        <li class="d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                <h6 class="text-primary">Email ID:</h6>
                                            </div>
                                            <div>
                                                <h6>
                                                    @php
                                                        if ($appointment->patient->email == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $appointment->patient->email;
                                                        }
                                                    @endphp
                                                </h6>
                                            </div>
                                        </li>
                                    </div>
                                    <div class="col">
                                        <li class="d-flex justify-start align-items-center">
                                            <div class="min-w-120 text-muted">
                                                <h6 class="text-warning">Referred Doctor:</h6>
                                            </div>
                                            <div>
                                                <h6>
                                                    @php
                                                        if ($appointment->referred_doctor == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $appointment->referred_doctor;
                                                        }
                                                    @endphp
                                                </h6>
                                            </div>
                                        </li>
                                        <li class="d-flex justify-start">
                                            <div class="min-w-120 text-muted">
                                                <h6 class="text-warning">Allergies:</h6>
                                            </div>
                                            <div>
                                                <h6>
                                                    @php
                                                        if ($appointment->patient->allergies == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $appointment->patient->allergies;
                                                        }
                                                    @endphp
                                                </h6>
                                            </div>
                                        </li>
                                        <li class="d-flex justify-start">
                                            <div class="min-w-120 text-muted ">
                                                <h6 class="text-warning">Disease History:</h6>
                                            </div>
                                            <div>
                                                <h6>
                                                    @php
                                                        if ($appointment->patient->history == '') {
                                                            echo '-';
                                                        } else {
                                                            foreach ($appointment->patient->history as $history) {
                                                                echo $history->history . '<br>';
                                                            }
                                                        }
                                                    @endphp
                                                </h6>
                                            </div>
                                        </li>
                                    </div>
                                </div>
                            </ul>
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
                                <h2 class="float-start mt-0 mr-10 me-10"><strong><?= $parts[0] ?></strong></h2>
                                <div>
                                    <p class="mb-0"><small>Systolic</small></p>
                                    <p class="mb-0 mt-0"><small class="vertical-align-super">mmHg</small></p>
                                </div>
                            </div>
                            <div class="col-6 ps-20">
                                <div class="progress progress-xs mb-0 mt-5 w-40">
                                    <div class="progress-bar progress-bar-danger progress-bar-striped"
                                        role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                        style="width: 100%">
                                    </div>
                                </div>
                                <h2 class="float-start mt-0 me-10"><strong><?= $parts[1] ?></strong></h2>
                                <div class="mt-0">
                                    <p class="mb-0"><small>Diastolic</small></p>
                                    <p class="mb-0 mt-0"><small class="vertical-align-super">mmHg</small></p>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-5 ">
                            <div class="media-list  px-0">
                                <div class="row  pb-10">
                                    <div class="col-6 be-1">
                                        <div class="media media-single p-2 px-0">
                                            <div class="w-10"><i class="fas fa-smoking"></i></div>
                                            <div class="media-body">
                                                <h6>
                                                    @php
                                                        if ($appointment->patient->smoking_status == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $appointment->patient->smoking_status;
                                                        }
                                                    @endphp
                                                </h6>
                                                <small class="text-fader text-muted">Smoking status</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6 ps-20">
                                        <div class="media media-single  p-2 px-0">
                                            <div class="w-10"><i class="fas fa-wine-glass"></i></div>
                                            <div class="media-body">
                                                <h6>
                                                    @php
                                                        if ($appointment->patient->alcoholic_status == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $appointment->patient->alcoholic_status;
                                                        }
                                                    @endphp
                                                </h6>
                                                <small class="text-fader text-muted">Drinking status</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="media media-single bt-1 pt-10  p-2 px-0">
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
                                        <small class="text-fader text-muted">Dieting status</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="d-none text-center mb-0 text-warning"><small>Recorded on 25/05/2018</small></p>
                </div>
            </div>
        </div>
    </div>
</div>
