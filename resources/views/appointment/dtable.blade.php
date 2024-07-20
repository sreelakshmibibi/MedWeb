<div class="d-flex align-items-center justify-content-between">
    <h5 class="box-title text-info mb-0 mt-2 "><i class="fa fa-medkit me-15"></i>
        Treatment Chart
    </h5>

    <button type="button" class="waves-effect waves-light btn btn-sm btn-outline-primary" id="table_info_btn">
        <i class="fa fa-table"></i>
        Info</button>
</div>
<hr class="my-15 ">

<div class="table-responsive mb-4">
    <table id="myTable" class="table table-bordered table-hover table-striped mb-0 text-center">

        <thead>
            <tr class="bg-primary-light">
                <th>No</th>
                <th>Tooth No</th>
                <th>Buccal</th>
                <th>Palatal</th>
                <th>Mesial</th>
                <th>Distal</th>
                <th>Occulusal</th>
                <th>Lingual</th>
                <th>Labial</th>
                <th>Tooth Score</th>
                <th>Treatment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="tablebody">
            <tr>
                <td>1</td>
                <td>11</td>
                <td>7</td>
                <td>0</td>
                <td>9</td>
                <td>0</td>
                <td>0</td>
                <td>N/A</td>
                <td>N/A</td>
                <td>2</td>
                <td>Root Canal</td>
                <td> <input type="checkbox" id="checkbox_row3" class="filled-in chk-col-primary">
                    <label for="checkbox_row3"></label>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="table-responsive" id="table_infodiv" style="display: none;">
    <table id="toothScoreTable"
        class="table table-sm table-bordered table-hover table-striped mb-0 text-center b-1 border-dark">

        <thead class="bg-dark">
            {{-- <tr class="bg-primary-light"> --}}
            <tr>
                <th>Tooth Score</th>
                <th>Code</th>
                <th>Tooth Score</th>
                <th>Code</th>
                <th>Tooth Surface</th>
                <th>Code</th>
            </tr>
        </thead>
        <tbody id="tablebody">
            <tr>
                <td>Sound</td>
                <td>0</td>
                <td>For Extraction- X(x)</td>
                <td>4</td>
                <td>Decayed</td>
                <td>7</td>
            </tr>
            <tr>
                <td>Decayed- D(d)</td>
                <td>1</td>
                <td>Impacted</td>
                <td>5</td>
                <td>Filled</td>
                <td>8</td>
            </tr>
            <tr>
                <td>Missing- M</td>
                <td>2</td>
                <td>Unerupted</td>
                <td>6</td>
                <td>Have Fissure Sealant (HFS)</td>
                <td>9</td>
            </tr>
            <tr>
                <td>Filled- F</td>
                <td>3</td>
                <td>Need Fissure Sealant (NFS)</td>
                <td>10</td>
            </tr>

        </tbody>

    </table>
</div>

<div class="d-flex align-items-center justify-content-between">
    <h5 class="box-title text-info mb-0 mt-2 "><i class="fa fa-clock me-15"></i>
        Follow up
    </h5>
    <input type="checkbox" id="follow_checkbox" name="follow_checkbox" class="filled-in chk-col-success" />
    <label for="follow_checkbox"></label>
</div>
<hr class="my-15 ">

<div class="row mb-4" id="followupdiv" style="display: none;">
    <div class="table-responsive">
        <table id="myTable" class="table table-bordered table-hover table-striped mb-0 text-center">

            <thead>
                <tr class="bg-primary-light">
                    <th>No</th>
                    <th>Treatment</th>
                    <th>Consulting Doctor</th>
                    <th>Branch</th>
                    <th>Appointment Date & Time</th>
                    <th>Appointment Type</th>
                    <th>Remarks</th>
                    <th><button type="button" class="waves-effect waves-light btn btn-sm btn-primary">
                            <i class="fa fa-add"></i>
                            Add</button></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <select class="select2" id="treatment_id1" name="treatment_id1"
                            data-placeholder="Select a Treatment" style="width: 100%;">

                        </select>
                    </td>
                    <td>
                        <select class="select2" id="doctor_id1" name="doctor_id1" data-placeholder="Select a Doctor"
                            style="width: 100%;">

                        </select>
                    </td>
                    <td>
                        <select class="select2" id="clinic_branch_id1" name="clinic_branch_id1"
                            data-placeholder="Select a Branch" style="width: 100%;">

                        </select>
                    </td>
                    <td>
                        <input class="form-control" type="datetime-local" id="appdate" name="appdate"
                            value="{{ now()->setTimezone('Asia/Kolkata')->format('Y-m-d\TH:i') }}">
                    </td>
                    <td>
                        <select class="form-select" id="apptype" name="apptype">

                        </select>
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
</div>

<div class="d-flex align-items-center justify-content-between">
    <h5 class="box-title text-info mb-0 mt-2 "><i class="fa-solid fa-prescription me-15"></i>
        Prescription
    </h5>
    <input type="checkbox" id="presc_checkbox" name="presc_checkbox" class="filled-in chk-col-success" />
    <label for="presc_checkbox"></label>
</div>
<hr class="my-15 ">
