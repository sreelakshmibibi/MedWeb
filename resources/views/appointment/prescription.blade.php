<div class="d-flex align-items-center justify-content-between">
    <h5 class="box-title text-info mb-0 mt-2"><i class="fa-solid fa-prescription me-15"></i>
        Prescription
    </h5>
    <div>
        {{-- <button id="medicineAddRow" type="button" class="waves-effect waves-light btn btn-sm btn-outline-primary">
            <i class="fa fa-add"></i>
            Add
        </button> --}}

        <a href='#'
            class='waves-effect waves-light btn btn-circle btn-prescription-pdf-generate btn-warning btn-xs me-1'
            title='Download & Print Prescription' data-app-id='{{ session('appId') }}'
            data-patient-id='{{ session('patientId') }}'><i class='fa fa-prescription'></i></a>
    </div>
</div>
<hr class="my-15">

<div class="table-responsive" id="prescriptionFormContainer">
    <table id="myTable_presc" class="table table-bordered table-hover table-striped mb-0 text-center">

        <thead>
            <tr class="bg-primary-light">
                <th>No</th>
                <th>Medicine</th>
                <th style="width:124px;">Dose</th>
                <th>Frequency</th>
                <th style="width:124px;">Duration</th>
                <th>Advice</th>
                <th>Route</th>
                <th>Remarks</th>
                <th> <button id="medicineAddRow" type="button" class="waves-effect waves-light btn btn-sm btn-primary">
                        <i class="fa fa-add"></i>
                        Add Row
                    </button></th>
            </tr>
        </thead>

        <tbody id="presctablebody">
            @forelse ($patientPrescriptions as $index => $prescription)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <select class="form-control medicine_id_select" id="medicine_id{{ $index + 1 }}"
                            name="prescriptions[{{ $index + 1 }}][medicine_id]" style="width: 100%;" required>
                            <option value=""> Select a Medicine </option>
                            @foreach ($medicines as $medicine)
                                <option value="{{ $medicine->id }}"
                                    {{ $prescription->medicine_id == $medicine->id ? 'selected' : '' }}>
                                    {{ $medicine->med_name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control text-center" id="dose{{ $index + 1 }}"
                            name="prescriptions[{{ $index + 1 }}][dose]" placeholder="Dose"
                            value="{{ $prescription->dose ?? '' }}" required>
                    </td>
                    <td>
                        <select class="form-control dosage_id_select" id="dosage{{ $index + 1 }}"
                            name="prescriptions[{{ $index + 1 }}][dosage_id]" required style="width: 100%;">
                            <option value=""> Select a Dosage </option>
                            @foreach ($dosages as $dosage)
                                <option value="{{ $dosage->id }}"
                                    {{ $prescription->dosage_id == $dosage->id ? 'selected' : '' }}>
                                    {{ $dosage->dos_name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div class="input-group col-12">
                            <input type="number" class="form-control text-center" id="duration{{ $index + 1 }}"
                                name="prescriptions[{{ $index + 1 }}][duration]" aria-describedby="basic-addon2"
                                value="{{ $prescription->duration ?? '' }}" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">days</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <select class="form-control advice_select" id="advice{{ $index + 1 }}"
                            name="prescriptions[{{ $index + 1 }}][advice]" required style="width: 100%;">
                            <option value="After food" {{ $prescription->advice == 'After food' ? 'selected' : '' }}>
                                After food</option>
                            <option value="Before food" {{ $prescription->advice == 'Before food' ? 'selected' : '' }}>
                                Before food</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control medicine_route_select" id="route{{ $index + 1 }}"
                            name="prescriptions[{{ $index + 1 }}][route_id]" required style="width: 100%;">
                            <option value="">Select a Route</option>
                            @foreach ($medicineRoutes as $route)
                                <option value="{{ $route->id }}"
                                    {{ $prescription->route_id == $route->id ? 'selected' : '' }}>
                                    {{ $route->route_name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control text-center" id="remarks{{ $index + 1 }}"
                            name="prescriptions[{{ $index + 1 }}][remark]" placeholder="remarks"
                            value="{{ $prescription->remark ?? '' }}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                {{-- <tr>
                    <td colspan="7">No prescriptions available.</td>
                </tr> --}}
            @endforelse
        </tbody>
    </table>
</div>
