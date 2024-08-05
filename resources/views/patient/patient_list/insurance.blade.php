@php
    // Check if $patientInsurance is a collection or if it's empty
    $insurance =
        $patientInsurance instanceof \Illuminate\Support\Collection ? $patientInsurance->first() : $patientInsurance;
@endphp

<div class="row">
    <!-- Policy Holder Type -->
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label">Policy Holder Type</label>
            <div class="d-flex align-items-center">
                <div class="form-check me-3">
                    <input type="checkbox" name="policy_holder_type[]" id="policy_holder" value="policy_holder"
                        class="form-check-input"
                        {{ isset($insurance) && in_array('policy_holder', $insurance->policy_holder_type ?? []) ? 'checked' : '' }}>
                    <label class="form-check-label" for="policy_holder">Policy Holder</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="policy_holder_type[]" id="responsible_party" value="responsible_party"
                        class="form-check-input"
                        {{ isset($insurance) && in_array('responsible_party', $insurance->policy_holder_type ?? []) ? 'checked' : '' }}>
                    <label class="form-check-label" for="responsible_party">Responsible Party</label>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Primary Insurance Details -->
<div class="col-md-12">
    <h3>Primary Insurance Details</h3>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="is_primary_insurance">Is Primary Insurance? <span
                    class="text-danger">*</span></label>
            <select class="form-select" id="is_primary_insurance" name="is_primary_insurance">
                <option value="Y" {{ ($insurance->is_primary_insurance ?? 'N') == 'Y' ? 'selected' : '' }}>Yes
                </option>
                <option value="N" {{ ($insurance->is_primary_insurance ?? 'N') == 'N' ? 'selected' : '' }}>No
                </option>
            </select>
        </div>
    </div>
</div>

<!-- Insurance Fields -->
@php
    $insuranceFields = [
        'prim_ins_id' => 'Insurance ID',
        'prim_ins_insured_name' => 'Insured Name',
        'prim_ins_insured_dob' => 'Insured Date of Birth',
        'prim_ins_company' => 'Insurance Company',
        'prim_ins_com_address' => 'Company Address',
        'prim_ins_group_name' => 'Group Name',
        'prim_ins_group_number' => 'Group Number',
        'prim_ins_policy_start_date' => 'Policy Start Date',
        'prim_ins_policy_end_date' => 'Policy End Date',
        'prim_ins_relation_to_insured' => 'Relation to Insured',
    ];
@endphp
<div class="row">
    @foreach ($insuranceFields as $field => $label)
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                @if (in_array($field, ['prim_ins_insured_dob', 'prim_ins_policy_start_date', 'prim_ins_policy_end_date']))
                    <input type="date" class="form-control" id="{{ $field }}" name="{{ $field }}"
                        value="{{ $insurance->$field ?? '' }}">
                @elseif ($field == 'prim_ins_company')
                    <select class="form-select" id="{{ $field }}" name="{{ $field }}">
                        <option value="">Select Insurance Company</option>
                        <!-- Add options here -->
                        <option value="company1" {{ ($insurance->$field ?? '') == 'company1' ? 'selected' : '' }}>
                            Company 1</option>
                        <option value="company2" {{ ($insurance->$field ?? '') == 'company2' ? 'selected' : '' }}>
                            Company 2</option>
                        <!-- Add more options as needed -->
                    </select>
                @elseif ($field == 'prim_ins_relation_to_insured')
                    <select class="form-select" id="{{ $field }}" name="{{ $field }}">
                        <option value="">Select Relation</option>
                        <option value="self" {{ ($insurance->$field ?? '') == 'self' ? 'selected' : '' }}>Self
                        </option>
                        <option value="spouse" {{ ($insurance->$field ?? '') == 'spouse' ? 'selected' : '' }}>Spouse
                        </option>
                        <option value="father" {{ ($insurance->$field ?? '') == 'father' ? 'selected' : '' }}>Father
                        </option>
                        <option value="mother" {{ ($insurance->$field ?? '') == 'mother' ? 'selected' : '' }}>Mother
                        </option>
                        <option value="family" {{ ($insurance->$field ?? '') == 'family' ? 'selected' : '' }}>Family
                        </option>
                        <option value="child" {{ ($insurance->$field ?? '') == 'child' ? 'selected' : '' }}>Child
                        </option>
                        <option value="other" {{ ($insurance->$field ?? '') == 'other' ? 'selected' : '' }}>Other
                        </option>
                    </select>
                @elseif ($field == 'prim_ins_com_address')
                    <input type="text" class="form-control" id="{{ $field }}" name="{{ $field }}"
                        placeholder="Enter {{ $label }}" value="{{ $insurance->$field ?? '' }}">
                @else
                    <input type="text" class="form-control" id="{{ $field }}" name="{{ $field }}"
                        placeholder="Enter {{ $label }}" value="{{ $insurance->$field ?? '' }}">
                @endif
            </div>
        </div>
    @endforeach
</div>

<!-- Secondary Insurance Details Toggle -->
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="has_secondary_insurance">Do you have Secondary Insurance?</label>
            <div class="btn-group" role="group" aria-label="Secondary Insurance">
                <button type="button"
                    class="btn btn-primary {{ ($insurance->is_secondary_insurance ?? 'N') == 'Y' ? 'active' : '' }}"
                    onclick="toggleSecondaryInsurance(true)">
                    Yes
                </button>
                <button type="button"
                    class="btn btn-secondary {{ ($insurance->is_secondary_insurance ?? 'N') == 'N' ? 'active' : '' }}"
                    onclick="toggleSecondaryInsurance(false)">
                    No
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Insurance Details (Initially Hidden) -->
<div class="row">
    <div id="secondary-insurance-details" style="display: none;">
        <h3>Secondary Insurance Details</h3>
        <div class="row">
            @foreach ($insuranceFields as $field => $label)
                @php
                    $secField = str_replace('prim_', 'sec_', $field);
                @endphp
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label" for="{{ $secField }}">{{ $label }}</label>
                        @if (in_array($field, ['prim_ins_insured_dob', 'prim_ins_policy_start_date', 'prim_ins_policy_end_date']))
                            <input type="date" class="form-control" id="{{ $secField }}"
                                name="{{ $secField }}" value="{{ $insurance->$secField ?? '' }}">
                        @elseif ($field == 'prim_ins_company')
                            <select class="form-select" id="{{ $secField }}" name="{{ $secField }}">
                                <option value="">Select Insurance Company</option>
                                <!-- Add options here -->
                                <option value="company1"
                                    {{ ($insurance->$secField ?? '') == 'company1' ? 'selected' : '' }}>Company 1
                                </option>
                                <option value="company2"
                                    {{ ($insurance->$secField ?? '') == 'company2' ? 'selected' : '' }}>Company 2
                                </option>
                                <!-- Add more options as needed -->
                            </select>
                        @elseif ($field == 'prim_ins_relation_to_insured')
                            <select class="form-select" id="{{ $secField }}" name="{{ $secField }}">
                                <option value="">Select Relation</option>
                                <option value="self"
                                    {{ ($insurance->$secField ?? '') == 'self' ? 'selected' : '' }}>Self</option>
                                <option value="spouse"
                                    {{ ($insurance->$secField ?? '') == 'spouse' ? 'selected' : '' }}>Spouse</option>
                                <option value="father"
                                    {{ ($insurance->$secField ?? '') == 'father' ? 'selected' : '' }}>Father</option>
                                <option value="mother"
                                    {{ ($insurance->$secField ?? '') == 'mother' ? 'selected' : '' }}>Mother</option>
                                <option value="family"
                                    {{ ($insurance->$secField ?? '') == 'family' ? 'selected' : '' }}>Family</option>
                                <option value="child"
                                    {{ ($insurance->$secField ?? '') == 'child' ? 'selected' : '' }}>Child</option>
                                <option value="other"
                                    {{ ($insurance->$secField ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        @elseif ($field == 'prim_ins_com_address')
                            <input type="text" class="form-control" id="{{ $secField }}"
                                name="{{ $secField }}" placeholder="Enter {{ $label }}"
                                value="{{ $insurance->$secField ?? '' }}">
                        @else
                            <input type="text" class="form-control" id="{{ $secField }}"
                                name="{{ $secField }}" placeholder="Enter {{ $label }}"
                                value="{{ $insurance->$secField ?? '' }}">
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Status -->
<div class="form-group mt-2">
    <label class="form-label">Status <span class="text-danger">*</span></label>

    <div class="form-check form-check-inline">
        <input name="status" type="radio" id="yes" value="Y" class="form-check-input"
            {{ ($insurance->status ?? 'Y') == 'Y' ? 'checked' : '' }}>
        <label class="form-check-label" for="yes">Active</label>
    </div>

    <div class="form-check form-check-inline">
        <input name="status" type="radio" id="no" value="N" class="form-check-input"
            {{ ($insurance->status ?? 'Y') == 'N' ? 'checked' : '' }}>
        <label class="form-check-label" for="no">Inactive</label>
    </div>

    <div id="statusError" class="invalid-feedback"></div>
</div>

@section('scripts')
    <script>
        function toggleSecondaryInsurance(show) {
            const secondaryInsuranceDetails = document.getElementById('secondary-insurance-details');
            secondaryInsuranceDetails.style.display = show ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const hasSecondaryInsurance = '{{ $insurance->is_secondary_insurance ?? 'N' }}';
            const secondaryInsuranceDetails = document.getElementById('secondary-insurance-details');

            // Toggle based on the secondary insurance status
            toggleSecondaryInsurance(hasSecondaryInsurance === 'Y');

            // Initialize based on secondary insurance data presence
            const hasSecInsuranceData =
                '{{ isset($insurance->sec_ins_id) && !empty($insurance->sec_ins_id) ? 'true' : 'false' }}';
            if (hasSecInsuranceData) {
                toggleSecondaryInsurance(true);
            }
        });
    </script>
@endsection
