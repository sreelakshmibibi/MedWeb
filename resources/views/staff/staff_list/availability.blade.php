{{-- <div class="doctordiv" style="display: none;"> --}}
<section id="finalStepContent" class="tabHideSection">
    <div class="d-flex align-items-center justify-content-between">
        <h5 class="box-title text-info mb-0 mt-2 "><i class="fa fa-clock me-15"></i>
            Availability
        </h5>
        <button id="buttonAddRow" type="button" class="waves-effect waves-light btn btn-sm btn-outline-primary">
            <i class="fa fa-add"></i>
            Add</button>
    </div>
    <hr class="my-15 ">

    <div class="table-responsive">
        <table id="myTable" class="table table-bordered table-hover table-striped mb-0 text-center">

            <thead>
                <tr class="bg-primary-light">
                    <th>No</th>
                    <th>Branch</th>
                    <th>Sunday</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tablebody">
                <tr>
                    <td>1</td>
                    <td>
                        <select class="select2" id="clinic_branch_id1" name="clinic_branch_id1" required
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
                    </td>
                    <td>
                        <input type="time" class="form-control timeInput" id="sunday_from1" title="from"
                            name="sunday_from1" style="width:115px;">
                        <input type="time" class="form-control" id="sunday_to1" title="to" name="sunday_to1"
                            style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control" id="monday_from1" name="monday_from1" title="from"
                            style="width:115px;">
                        <input type="time" class="form-control" id="monday_to1" name="monday_to1" title="to"
                            style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control" id="tuesday_from1" name="tuesday_from1"
                            title="from" style="width:115px;">
                        <input type="time" class="form-control" id="tuesday_to1" name="tuesday_to1" title="to"
                            style="width:115px;">

                    </td>
                    <td>
                        <input type="time" class="form-control" id="wednesday_from1" name="wednesday_from1"
                            title="from" style="width:115px;">
                        <input type="time" class="form-control" id="wednesday_to1" name="wednesday_to1"
                            title="to" style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control" id="thursday_from1" name="thursday_from1"
                            title="from" style="width:115px;">
                        <input type="time" class="form-control" id="thursday_to1" name="thursday_to1" title="to"
                            style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control" id="friday_from1" name="friday_from1" title="from"
                            style="width:115px;">
                        <input type="time" class="form-control" id="friday_to1" name="friday_to1" title="to"
                            style="width:115px;">
                    </td>
                    <td>
                        <input type="time" class="form-control" id="saturday_from1" name="saturday_from1"
                            title="from" style="width:115px;">
                        <input type="time" class="form-control" id="saturday_to1" name="saturday_to1"
                            title="to" style="width:115px;">
                    </td>
                    <td>
                        <button type="button" id="btnDelete" title="delete row"
                            class="waves-effect waves-light btn btn-danger btn-sm"> <i
                                class="fa fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
{{-- </div> --}}
