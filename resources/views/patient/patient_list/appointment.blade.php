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
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="regfee">Registration Fee</label>
            <input type="text" class="form-control" id="regfee" name="regfee" placeholder="Registration Fee"
                value="{{ $registrationFees }}" readonly>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="paymode">Payment Mode <span class="text-danger">
                    *</span></label>
            <select class="form-select" id="paymode" name="paymode">
                <option value="">Select</option>
                <option value="Cash">Cash</option>
                <option value="Card">Card</option>
                <option value="GPay">GPay</option>
            </select>
        </div>
    </div>
    <div class="col-md-3" id="machinediv" style="display: none;">
        <div class="form-group">
            <label class="form-label" for="cardmachine">Machine <span class="text-danger">
                    *</span></label>
            <select class="form-select" id="cardmachine" name="cardmachine">
                <option value="">Select Machine</option>
                @foreach ($cardPay as $machine)
                    <option value="{{ $machine->id }}">
                        {{ $machine->card_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

</div>
<div class="row">
    <div style="display:none" id="doctorNotAvailable">
        <span class="text-danger">Sorry, the doctor is not available at the selected time.
            Please choose another time.</span>
    </div>
</div>
<div class="row mb-3">
    <div style="display:none" id="existingAppointmentsError" class="text-danger">
        <span class="text-danger">Appointments already exists for the selected time!</span>
    </div>
</div>

<div class="row" style="display:none" id="existAppContainer">
    <hr />
    <div class="mb-3" style="display:none" id="existingAppointments">
    </div>
    <hr />
</div>

<div class="row mb-2">
    <div class="form-group col-md-2">
        <label class="form-label" for="bp">Other Information</label>
        <input type="text" class="form-control" id="bp" name="bp" placeholder="Blood Pressure- 80/120">
    </div>

    <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
        <input type="text" class="form-control" id="height" name="height" placeholder="Height">
    </div>

    <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
        <input type="text" class="form-control" id="weight" name="weight" placeholder="Weight">
    </div>

    <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
        <input type="text" class="form-control" id="temperature" name="temperature"
            placeholder="Temperature in °F">
    </div>

    <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
        <select class="form-select" id="marital_status" name="marital_status">
            <option value="">Marital Status</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widowed">Widowed</option>
            <option value="Divorced">Divorced</option>
            <option value="Separated">Separated</option>
        </select>
    </div>

    <div class="form-group col-md-2 d-flex flex-wrap align-content-end" id="pregnant_container"
        style="display: none;">
        <select class="form-select" id="pregnant" name="pregnant">
            <option value="">Are you pregnant?</option>
            <option value="Y">Yes</option>
            <option value="N">No</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            {{-- <label class="form-label" for="smoking_status">Smoking Status</label> --}}
            <select class="form-select" id="smoking_status" name="smoking_status">
                <option value="">Smoking Status</option>
                <option value="Non-smoker">Non-smoker</option>
                <option value="Former smoker">Former smoker</option>
                <option value="Current smoker">Current smoker</option>
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            {{-- <label class="form-label" for="alcoholic_status">Alcoholic Status</label> --}}
            <select class="form-select" id="alcoholic_status" name="alcoholic_status">
                <option value="">Alcoholic Status</option>
                <option value="Non-drinker">Non-drinker</option>
                <option value="Former drinker">Former drinker</option>
                <option value="Current drinker">Current drinker</option>
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            {{-- <label class="form-label" for="diet">Diet</label> --}}
            <select class="form-select" id="diet" name="diet">
                <option value="">Diet</option>
                <option value="Vegetarian">Vegetarian</option>
                <option value="Non-Vegetarian">Non-Vegetarian</option>
                <option value="Vegan">Vegan</option>
                <option value="Other">Other</option>
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            {{-- <label class="form-label" for="allergies">Allergies</label> --}}
            <textarea class="form-control" id="allergies" name="allergies" placeholder="List any allergies" rows="1"></textarea>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            {{-- <label class="form-label" for="medical_conditions">Medical Conditions</label> --}}
            <div id="medical-conditions-wrapper">
                <div class="input-group mb-0">
                    <input type="text" class="form-control" name="medical_conditions[]"
                        placeholder="Medical Conditions">
                    <button class="btn-sm btn-success" type="button" onclick="addMedicalCondition()">+</button>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
        <input type="text" class="form-control" id="rdoctor" name="rdoctor" placeholder="Referred Doctor">
    </div>
</div>
<script>
    $(document).ready(function() {
        // Initially hide the machinediv
        $('#machinediv').hide();

        // Event handler for changes in the paymode select
        $('#paymode').change(function() {
            // Get the selected value
            var selectedValue = $(this).val();

            // Check if the selected value is "Card"
            if (selectedValue === 'Card') {
                // Show the machinediv
                $('#machinediv').show();
            } else {
                // Hide the machinediv
                $('#machinediv').hide();
            }
        });
    });
</script>
