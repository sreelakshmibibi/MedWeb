<?php

use App\Services\CommonService;
$commonService = new CommonService();

?>
<div class="row ">
    <div class="col-xl-8 col-12">
        <div class="box flex-grow-1 mb-3 no-border">
            <div class="box-header border-0 pb-0">
                <h4 class="box-title text-decoration-underline">Basic Information</h4>
            </div>
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
                                            <h6><?= str_replace("<br>", " ", $appointment->patient->first_name . " " . $appointment->patient->last_name) ?></h6>
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
                                            <h6><?= $appointment->patient->gender?></h6>
                                        </div>
                                    </li>

                                    <li class="d-flex justify-start align-items-center">
                                        <div class="min-w-120 text-muted">
                                            <h6 class="text-primary">D.O.B:</h6>
                                        </div>
                                        <div>
                                            <h6><?= $appointment->patient->date_of_birth ?></h5>
                                        </div>
                                    </li>

                                    <li class="d-flex justify-start align-items-center">
                                        <div class="min-w-120 text-muted">
                                            <h6 class="text-primary">Age: </h6>
                                        </div>
                                        <div>
                                            <h6><?php $age =  $commonService->calculateAge($appointment->patient->date_of_birth);
                                            echo $age;?></h6>
                                        </div>
                                    </li>

                                    <li class="d-flex justify-start align-items-center">
                                        <div class="min-w-120 text-muted">
                                            <h6 class="text-primary">Blood Group:</h6>
                                        </div>
                                        <div>
                                            <h6><?= $appointment->patient->blood_group ?></h6>
                                        </div>
                                    </li>

                                    <li class="d-flex justify-start align-items-center">
                                        <div class="min-w-120 text-muted">
                                            <h6 class="text-primary">Aadhaar No.:</h6>
                                        </div>
                                        <div>
                                            <h6><?= $appointment->patient->aadhaar_no ?></h6>
                                        </div>
                                    </li>

                                    <li class="d-flex justify-start align-items-center">
                                        <div class="min-w-120 text-muted">
                                            <h6 class="text-primary">Marital Status:</h5>
                                        </div>
                                        <div>
                                            <h6><?= $appointment->patient->marital_status ?></h6>
                                        </div>
                                    </li>

                                    <li class="d-flex justify-start align-items-center">
                                        <div class="min-w-120 text-muted">
                                            <h6 class="text-primary">Address:</h6>
                                        </div>
                                        <div>
                                            <h6><?= $appointment->patient->address1 ."," . $appointment->patient->address2 
                                             . " , " . $appointment->patient->city->city
                                             . " , " . $appointment->patient->state->state
                                             . " , " . $appointment->patient->country->country
                                             . "-" . $appointment->patient->pincode ?></h6>
                                        </div>
                                    </li>

                                    <li class="d-flex justify-start align-items-center">
                                        <div class="min-w-120 text-muted">
                                            <h6 class="text-primary">Contact Number:</h6>
                                        </div>
                                        <div>
                                            <h6><?= $appointment->patient->phone ?></h6>
                                        </div>
                                    </li>
                                </div>
                                <div class="col">
                                    <li class="d-flex justify-start align-items-center">
                                        <div class="min-w-120 text-muted">
                                            <h6 class="text-warning">Allergies:</h6>
                                        </div>
                                        <div>
                                            <h6><?= $appointment->patient->allergies ?></h6>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-start align-items-center">
                                        <div class="min-w-120 text-muted ">
                                            <h6 class="text-warning">Disease History:</h6>
                                        </div>
                                        <div>
                                            <h6><?php foreach( $appointment->patient->history as $history ) {
                                                echo $history->history. "<br>";
                                            } ?></h6>
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

    <div class="col-xl-4 col-12 g-0 ">
        {{-- < class="col-xl-4 col-5"> --}}
        {{-- <div class="box mb-0 mt-3" style="border-radius: 0px;"> --}}
        <div class="box mb-0 " style="border-radius: 0px;">
            <div class="box-body py-2 ">
                <div class="row">
                    <div class="col-12">
                        <div class="row bb-1 ">
                            <h4 class="text-center mb-0">Current Vitals</h4>
                            <p class="text-center mb-0 text-warning"><small>Recorded on <?= $appointment->updated_at?></small></p>
                        </div>
                        <div class="row bb-1 pb-10 pt-5">

                            <div class="col-4 d-flex align-content-center flex-wrap">
                                <div> <img class="img-fluid float-start w-30 mt-10 me-10"
                                        src="{{ asset('images/weight.png') }}" alt=""></div>
                                <div>
                                    <p class="mb-0"><small>Weight</small></p>
                                    <h5 class="mb-0"><strong><?= $appointment->weight_kg?> kg</strong></h5>
                                </div>
                            </div>

                            <div class="col-4 bs-1 be-1 d-flex align-content-center flex-wrap">
                                <div> <img class="img-fluid float-start w-30 me-10"
                                        src="{{ asset('images/human.png') }}" alt=""></div>
                                <div class="mt-10 pt-1">
                                    <p class="mb-0"><small>Height</small></p>
                                    <h5 class=" mb-0"><strong><?= $appointment->height_cm ?> cm</strong></h5>
                                </div>
                            </div>
                            <div class="col-4 d-flex align-content-center flex-wrap">
                                <div><img class="img-fluid float-start w-30 mt-10 me-10"
                                        src="{{ asset('images/bmi.png') }}" alt=""></div>
                                <div>
                                    <p class="mb-0"><small>BMI</small></p>
                                    <h5 class="mb-0"><strong><?php 
                                    $bmi = ($appointment->weight_kg / pow($appointment->height_cm / 100, 2));
                                    // Round BMI to 2 decimal places
                                    $bmi = round($bmi, 2);
                                    echo $bmi; ?></strong></h5>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-5 bb-1 pb-10">
                            <div class="col-12 text-center">
                                <span class="text-danger">Blood Pressure</span>
                                <?php 
                                $parts = explode('/', $appointment->blood_pressure);
                                ?>
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
                        {{-- <div class="row pt-5 bb-1 "> --}}
                        <div class="row pt-5 ">
                            <div class="media-list  px-0">
                                <div class="row  pb-10">
                                    <div class="col-6 be-1">
                                        <div class="media media-single p-2 px-0">
                                            <div class="w-10"><i class="fas fa-smoking"></i></div>
                                            <div class="media-body">
                                                <h6><?= $appointment->patient->smoking_status ?></h6>
                                                <small class="text-fader text-muted">Smoking status</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6 ps-20">
                                        <div class="media media-single  p-2 px-0">
                                            <div class="w-10"><i class="fas fa-wine-glass"></i></div>
                                            <div class="media-body">
                                                <h6><?= $appointment->patient->alcoholic_status ?></h6>
                                                <small class="text-fader text-muted">Drinking status</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="media media-single bt-1 pt-10  p-2 px-0">
                                    <div class="w-10"><i class="fas fa-utensils"></i></div>
                                    <div class="media-body">
                                        <h6><?= $appointment->patient->diet ?></h6>
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