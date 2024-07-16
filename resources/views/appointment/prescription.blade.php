<section id="finalStepContent" class="tabHideSection">
    <div class="d-flex align-items-center justify-content-between">
        <h5 class="box-title text-info mb-0 mt-2 "><i class="fa-solid fa-prescription me-15"></i>
            Prescription
        </h5>
        <button id="medicineAddRow" type="button" class="waves-effect waves-light btn btn-sm btn-outline-primary">
            <i class="fa fa-add"></i>
            Add</button>
    </div>
    <hr class="my-15 ">

    <div class="table-responsive">
        <table id="myTable" class="table table-bordered table-hover table-striped mb-0 text-center">

            <thead>
                <tr class="bg-primary-light">
                    <th>No</th>
                    <th>Medicine</th>
                    <th>Dosage</th>
                    <th style="width:200px;">Duration</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="presctablebody">
                <tr>
                    <td>1</td>
                    <td>
                        <select class="select2" id="medicine_id1" name="medicine_id1" required
                            data-placeholder="Select a Medicine" style="width: 100%;">

                        </select>
                    </td>
                    <td>
                        <select class="select2" id="dosage1" name="dosage1" required
                            data-placeholder="Select a Dosage" style="width: 100%;">
                            <option value="1">1-0-0</option>
                            <option value="2">0-1-0</option>
                            <option value="3">0-0-1</option>
                            <option value="4">1-1-1</option>
                            <option value="5">1-0-1</option>
                            <option value="6">1-1-0</option>
                            <option value="7">0-1-1</option>
                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" class="form-control" id="duration1" name="duration1"
                                aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">days</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control" id="remarks1" name="remarks1" placeholder="remarks">
                    </td>
                    <td>
                        <button type="button" id="btnDelete" title="delete row"
                            class="waves-effect waves-light btn btn-danger btn-sm"> <i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
