<!--appointment-->
<div class="row">



    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="clinic_branch_id0">Branch <span class="text-danger">
                    *</span></label>
            <select class="select2" id="clinic_branch_id0" name="clinic_branch_id0" required
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

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="appdate">Appointment Date & Time <span class="text-danger">
                    *</span></label>
            <input class="form-control" type="datetime-local" id="appdate" name="appdate"
                value="{{ now()->setTimezone('Asia/Kolkata')->format('Y-m-d\TH:i') }}" required>

        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="doctor2">Doctor <span class="text-danger">
                    *</span></label>
            <select class="select2" id="doctor2" name="doctor2" required data-placeholder="Select a Doctor"
                style="width: 100%;">
                <option value="">select a doctor</option>
                @foreach ($workingDoctors as $doctor)
                    <?php $doctorName = str_replace('<br>', ' ', $doctor->user->name); ?>
                    <option value="{{ $doctor->user_id }}"> {{ $doctorName }}</option>
                @endforeach
            </select>
        </div>
    </div>



    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="appstatus">Appointment Status <span class="text-danger">
                    *</span></label>
            <select class="form-select" id="appstatus" name="appstatus" required>
                @foreach ($appointmentStatuses as $status)
                    <option value="{{ $status->id }}">{{ $status->status }}</option>
                @endforeach
            </select>
        </div>
    </div>

</div>

<!--basic-->
<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="bp">Blood Pressure</label>
            <input type="text" class="form-control" id="bp" name="bp" placeholder="Enter Blood Pressure">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="height">Height</label>
            <input type="text" class="form-control" id="height" name="height" placeholder="Enter Height">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="weight">Weight</label>
            <input type="text" class="form-control" id="weight" name="weight" placeholder="Enter Weight">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="temperature">Temperature</label>
            <input type="text" class="form-control" id="temperature" name="temperature"
                placeholder="Enter Temperature in °F">
        </div>
    </div>
</div>
<div class="row">


    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="smoking_status">Smoking Status</label>
            <select class="form-select" id="smoking_status" name="smoking_status">
                <option value="">Select Smoking Status</option>
                <option value="Non-smoker">Non-smoker</option>
                <option value="Former smoker">Former smoker</option>
                <option value="Current smoker">Current smoker</option>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="alcoholic_status">Alcoholic Status</label>
            <select class="form-select" id="alcoholic_status" name="alcoholic_status">
                <option value="">Select Alcoholic Status</option>
                <option value="Non-drinker">Non-drinker</option>
                <option value="Former drinker">Former drinker</option>
                <option value="Current drinker">Current drinker</option>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="diet">Diet</label>
            <select class="form-select" id="diet" name="diet">
                <option value="">Select Diet</option>
                <option value="Vegetarian">Vegetarian</option>
                <option value="Non-Vegetarian">Non-Vegetarian</option>
                <option value="Vegan">Vegan</option>
                <option value="Other">Other</option>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="rdoctor">Referrerd Doctor</label>
            <input type="text" class="form-control" id="rdoctor" name="rdoctor"
                placeholder="Enter doctor name">
        </div>
    </div>
    <div class="col-md-3" id="pregnant_container" style="display: none;">
        <div class="form-group">
            <label class="form-label" for="pregnant">Are you pregnant?</label>
            <select class="form-select" id="pregnant" name="pregnant">
                <option value="">Select an option</option>
                <option value="Y">Yes</option>
                <option value="N">No</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="allergies">Allergies</label>
            <textarea class="form-control" id="allergies" name="allergies" placeholder="List any allergies"></textarea>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="medical_conditions">Medical Conditions</label>
            <div id="medical-conditions-wrapper">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="medical_conditions[]"
                        placeholder="Medical Condition">
                    <button class="btn btn-success" type="button" onclick="addMedicalCondition()">+</button>
                </div>
            </div>
        </div>
    </div>
</div>
