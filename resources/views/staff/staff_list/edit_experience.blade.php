<!--qualification-->
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="qualification">Qualification</label>
            <input type="text" class="form-control" id="qualification" name="qualification" placeholder="qualification"
                required  value="{{ $staffProfile->qualification}}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="experience">Experience</label>
            <input type="text" class="form-control" id="experience" name="experience" placeholder="experience"
                required  value="{{ $staffProfile->years_of_experience}}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="department">Department</label>
            <select class="select2" id="department_id" name="department_id" required
                data-placeholder="Select a Department" style="width: 100%;">
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" <?php if ($department->id == $staffProfile->department_id) {
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
            <label class="form-label" for="designation">Designation</label>
            <input type="text" class="form-control" id="designation" name="designation" placeholder="Designation"
                required value="{{ $staffProfile->designation}}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="date_of_joining">Date of Joining</label>
            <input class="form-control" type="date" id="date_of_joining" name="date_of_joining" required value="{{ $staffProfile->date_of_joining}}">

        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="date_of_relieving">Date of Relieving</label>
            <input class="form-control" type="date" id="date_of_relieving" name="date_of_relieving" value="{{ $staffProfile->date_of_relieving}}">

        </div>
    </div>
</div>

<!--for doctors-->
<div class="row doctorFields" style="<?php if ($userDetails->is_doctor == 0) echo 'display: none';?>">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="specialization">Specialization</label>
            <input type="text" class="form-control" id="specialization" name="specialization"
                placeholder="Specialization"  value="{{ $staffProfile->specialization}}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="subspecialty">Subspeciality</label>
            <input type="text" class="form-control" id="subspecialty" name="subspecialty" placeholder="Subspeciality"
                 value="{{ $staffProfile->subspecialty}}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="license_number">Licence</label>
            <input type="text" class="form-control" id="license_number" name="license_number"
                placeholder="Council No."  value="{{ $staffProfile->license_number}}">
        </div>
    </div>
</div>

<!--for others-->
<div class="row otherFields" style="display: none;">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="specialization">Branch</label>
            <select class="select2" id="clinic_branch_id" name="clinic_branch_id" required 
                data-placeholder="Select a Branch" style="width: 100%;">
                @foreach ($clinicBranches as $clinicBranch)
                    <?php
                    $clinicAddress = $clinicBranch->clinic_address;
                    $clinicAddress = explode('<br>', $clinicBranch->clinic_address);
                    $clinicAddress = implode(', ', $clinicAddress);
                    $branch = $clinicAddress . ', ' . $clinicBranch->city->city . ', ' . $clinicBranch->state->state;
                    ?>
                    <option value="{{ $clinicBranch->id }}" <?php if ($staffProfile->clinic_branch_id == $clinicBranch->id) echo "selected";?>>
                        {{ $branch }}</option>
                @endforeach
            </select>
        </div>
    </div>

</div>
