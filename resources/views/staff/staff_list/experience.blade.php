<!--qualification-->
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="qualification">Qualification <span class="text-danger">
                    *</span></label>
            <input type="text" class="form-control" id="qualification" name="qualification" placeholder="Qualification"
                required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="years_of_experience">Experience <span class="text-danger">
                    *</span></label>
            <input type="text" class="form-control" id="years_of_experience" name="years_of_experience"
                placeholder="Experience" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="department">Department <span class="text-danger">
                    *</span></label>
            <select class="select2" id="department_id" name="department_id" required
                data-placeholder="Select a Department" style="width: 100%;">
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" <?php if ($department->id == 101) {
                        echo 'selected';
                    } ?>>
                        {{ $department->department }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="designation">Designation <span class="text-danger">
                    *</span></label>
            <input type="text" class="form-control" id="designation" name="designation" placeholder="Designation"
                required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="date_of_joining">Date of Joining <span class="text-danger">
                    *</span></label>
            <input class="form-control" type="date" id="date_of_joining" name="date_of_joining"
                value="<?php echo date('Y-m-d'); ?>" required>

        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="date_of_relieving">Date of Relieving</label>
            <input class="form-control" type="date" id="date_of_relieving" name="date_of_relieving" disabled>

        </div>
    </div>
</div>

<!--for doctors-->
<div class="row doctorFields" style="display: none;">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="specialization">Specialization <span class="text-danger">
                    *</span></label>
            <input type="text" class="form-control" id="specialization" name="specialization"
                placeholder="Specialization">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="subspecialty">Subspeciality <span class="text-danger">
                    *</span></label>
            <input type="text" class="form-control" id="subspecialty" name="subspecialty"
                placeholder="Subspeciality">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="license_number">Licence <span class="text-danger">
                    *</span></label>
            <input type="text" class="form-control" id="license_number" name="license_number"
                placeholder="Council No.">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="consultation_fees">Fees <span class="text-danger">
                    *</span></label>
            <input type="text" class="form-control" id="consultation_fees" name="consultation_fees" value="{{$consultationFees}}"
                placeholder="Consultation fees">
        </div>
    </div>
</div>

<!--for others-->
<div class="row otherFields" style="display: none;">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="clinic_branch_id">Branch <span class="text-danger">
                    *</span></label>
            <select class="select2" id="clinic_branch_id" name="clinic_branch_id" data-placeholder="Select a Branch"
                style="width: 100%;">
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
    <div class="col-md-4 nurseFields" style="display:none">
        <div class="form-group">
            <label class="form-label" for="license_number_nurse">Licence <span class="text-danger">
                    *</span></label>
            <input type="text" class="form-control" id="license_number_nurse" name="license_number_nurse"
                placeholder="Nursing Council No.">
        </div>
    </div>

</div>
