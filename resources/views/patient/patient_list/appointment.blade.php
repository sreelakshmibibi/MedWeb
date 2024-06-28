<!--appointment-->
<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="doctor">Doctor</label>
            <select class="select2" id="department_id" name="department_id" required data-placeholder="Select a Department"
                style="width: 100%;">
                <option>select a doctor</option>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="specialization">Branch</label>
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
            <input class="form-control" type="datetime-local" id="appdate" name="appdate" required>

        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="appstatus">Appointment Status</label>
            <select class="form-select" id="appstatus" name="appstatus" required>
                <option value="">Select Status</option>
                <option value="W">Waiting</option>
                <option value="S">Success</option>
            </select>
        </div>
    </div>

</div>

<!--basic-->
<div class="row">
    {{-- <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="doctor">Blood Group</label>
            <select class="select2" id="department_id" name="department_id" required
                data-placeholder="Select a Department" style="width: 100%;">
                <option>A+</option>
                <option>B+</option>
            </select>
        </div>
    </div> --}}

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="bp">Blood Pressure</label>
            <input type="text" class="form-control" id="bp" name="bp" placeholder="Enter Blood Pressure"
                required>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="height">Height</label>
            <input type="text" class="form-control" id="height" name="height" placeholder="Enter Height"
                required>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="weight">Weight</label>
            <input type="text" class="form-control" id="weight" name="weight" placeholder="Enter Weight"
                required>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="rdoctor">Referrer Doctor</label>
            <input type="text" class="form-control" id="rdoctor" name="rdoctor" placeholder="Enter doctor name"
                required>
        </div>
    </div>
</div>

{{-- <div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="rdoctor">Referrer Doctor</label>
            <input type="text" class="form-control" id="rdoctor" name="rdoctor" placeholder="Enter doctor name"
                required>
        </div>
    </div>
</div> --}}
