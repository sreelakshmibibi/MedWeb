<!--appointment-->
<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="clinic_branch_id0">Branch</label>
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
            <label class="form-label" for="appdate">Appointment Date & Time</label>
            <input class="form-control" type="datetime-local" id="appdate" name="appdate"
                value="{{ now()->setTimezone('Asia/Kolkata')->format('Y-m-d\TH:i') }}" required>

        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="doctor2">Doctor</label>
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
            <label class="form-label" for="appstatus">Appointment Status</label>
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
            <label class="form-label" for="rdoctor">Referrerd Doctor</label>
            <input type="text" class="form-control" id="rdoctor" name="rdoctor" placeholder="Enter doctor name">
        </div>
    </div>
</div>
