<div class="d-flex align-items-center justify-content-between">
    <h5 class="box-title text-info mb-0 mt-2"><i class="fa-solid fa-prescription me-15"></i>
        Prescription
    </h5>
    <button id="medicineAddRow" type="button" class="waves-effect waves-light btn btn-sm btn-outline-primary">
        <i class="fa fa-add"></i>
        Add
    </button>
</div>
<hr class="my-15">

<div class="table-responsive">
    <table id="myTable" class="table table-bordered table-hover table-striped mb-0 text-center">

        <thead>
            <tr class="bg-primary-light">
                <th>No</th>
                <th>Medicine</th>
                <th>Dosage</th>
                <th style="width:200px;">Duration</th>
                <th>Advice</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="presctablebody">
            <tr>
                <td>1</td>
                <td>
                    <select class="form-control" id="medicine_id1" name="medicine_id1" style="width: 100%;" required>
                        <option value=""> Select a Medicine </option>
                        @foreach ( $medicines as $medicine )
                        <option value="{{ $medicine->id}}"> {{ $medicine->med_name}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control" id="dosage1" name="dosage1" required style="width: 100%;">
                        <option value=""> Select a Dosage </option>
                        @foreach ( $dosages as $dosage )
                        <option value="{{ $dosage->id}}"> {{ $dosage->dos_name}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" class="form-control" id="duration1" name="duration1" aria-describedby="basic-addon2" required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">days</span>
                        </div>
                    </div>
                </td>
                <td>
                    <select class="form-control" id="advice1" name="advice1" required style="width: 100%;" required>
                        <option value="After food">After food</option>
                        <option value="Before food">Before food</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control" id="remarks1" name="remarks1" placeholder="remarks">
                </td>
                <td>
                    <!-- <button type="button" id="btnDelete" title="delete row" class="waves-effect waves-light btn btn-danger btn-sm"> <i class="fa fa-trash"></i></button> -->
                </td>
            </tr>
        </tbody>
    </table>
</div>
<!-- <script>
    $(document).ready(function() {

     $('#medicine_id1').select2({
        width: '100%',
        placeholder: 'Select a Medicine',
    });

    $('#dosage1').select2({
        width: '100%',
        placeholder: 'Select a Dosage',
    });

    $('#advice1').select2({
        width: '100%',
    });
});
</script> -->