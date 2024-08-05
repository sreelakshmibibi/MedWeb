@php
    // Check if $patientInsurance is a collection or if it's empty
    $insurance =
        $patientInsurance instanceof \Illuminate\Support\Collection ? $patientInsurance->first() : $patientInsurance;
@endphp

@php
    $insuranceFields = [
        'policy_number' => 'Policy Number',
        'insurance_company_id' => 'Insurance Company',
        'policy_end_date' => 'Policy Expiry Date',
        // 'insured_name' => 'Insured Name',
        // 'insured_dob' => 'Insured Date of Birth',
    ];
@endphp

<div class="row">
    @foreach ($insuranceFields as $field => $label)
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="{{ $field }}">{{ $label }}</label>

                @if ($field == 'insurance_company_id')
                    <select class="form-select" id="{{ $field }}" name="{{ $field }}">
                        <option value="">Select Insurance Company</option>
                        @foreach ($insuranceCompanies as $company)
                            <option value="{{ $company->id }}"
                                {{ isset($insurance) && $insurance->insurance_company_id == $company->id ? 'selected' : '' }}>
                                {{ $company->company_name }}
                            </option>
                        @endforeach
                    </select>
                @elseif ($field == 'policy_end_date')
                    <input type="date" class="form-control" id="{{ $field }}" name="{{ $field }}"
                        value="{{ isset($insurance) ? $insurance->policy_end_date : '' }}">
                @else
                    <input type="text" class="form-control" id="{{ $field }}" name="{{ $field }}"
                        placeholder="Enter {{ $label }}"
                        value="{{ isset($insurance) ? $insurance->$field : '' }}">
                @endif
            </div>
        </div>
    @endforeach
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
