<section id="finalStepContent" class="tabHideSection">
    <div class="d-flex align-items-center justify-content-between">
        <h5 class="box-title text-info mb-0 mt-2 "><i class="fa fa-clock me-15"></i> Availability</h5>
        <button id="buttonAddRow" type="button" class="waves-effect waves-light btn btn-sm btn-outline-primary">
            <i class="fa fa-add"></i> Add</button>
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
                @php
                $i = 1;
                @endphp
                @foreach($availableBranches as $branch)
                    <tr>
                        <td>{{$i}}</td>
                        <td>
                            <select class="select2" id="clinic_branch_id{{$i}}" name="clinic_branch_id{{$i}}" required
                                data-placeholder="Select a Branch" style="width: 100%;">
                                @foreach ($clinicBranches as $clinicBranch)
                                    <?php
                                    $clinicAddress = $clinicBranch->clinic_address;
                                    $clinicAddress = explode('<br>', $clinicAddress);
                                    $clinicAddress = implode(', ', $clinicAddress);
                                    $branchName = $clinicAddress . ', ' . $clinicBranch->city->city . ', ' . $clinicBranch->state->state;
                                    ?>
                                    <option value="{{ $clinicBranch->id }}" @if($branch['clinic_branch_id'] == $clinicBranch->id) selected @endif>
                                        {{ $branchName }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="time" class="form-control timeInput fromTime" id="sunday_from{{$i}}" title="from"
                                name="sunday_from{{$i}}" style="width:115px;" value="{{ $branch['timings']['sunday_from'] ?? '' }}">
                            <input type="time" class="form-control timeInput toTime" id="sunday_to{{$i}}" title="to" name="sunday_to{{$i}}"
                                style="width:115px;" value="{{ $branch['timings']['sunday_to'] ?? '' }}">
                        </td>
                        <td>
                            <input type="time" class="form-control timeInput fromTime" id="monday_from{{$i}}" name="monday_from{{$i}}" title="from"
                                style="width:115px;" value="{{ $branch['timings']['monday_from'] ?? '' }}">
                            <input type="time" class="form-control timeInput toTime" id="monday_to{{$i}}" name="monday_to{{$i}}" title="to"
                                style="width:115px;" value="{{ $branch['timings']['monday_to'] ?? '' }}">
                        </td>
                        <td>
                            <input type="time" class="form-control timeInput fromTime" id="tuesday_from{{$i}}" name="tuesday_from{{$i}}"
                                title="from" style="width:115px;" value="{{ $branch['timings']['tuesday_from'] ?? '' }}">
                            <input type="time" class="form-control timeInput toTime" id="tuesday_to{{$i}}" name="tuesday_to{{$i}}" title="to"
                                style="width:115px;" value="{{ $branch['timings']['tuesday_to'] ?? '' }}">
                        </td>
                        <td>
                            <input type="time" class="form-control timeInput fromTime" id="wednesday_from{{$i}}" name="wednesday_from{{$i}}"
                                title="from" style="width:115px;" value="{{ $branch['timings']['wednesday_from'] ?? '' }}">
                            <input type="time" class="form-control timeInput toTime" id="wednesday_to{{$i}}" name="wednesday_to{{$i}}"
                                title="to" style="width:115px;" value="{{ $branch['timings']['wednesday_to'] ?? '' }}">
                        </td>
                        <td>
                            <input type="time" class="form-control timeInput fromTime" id="thursday_from{{$i}}" name="thursday_from{{$i}}"
                                title="from" style="width:115px;" value="{{ $branch['timings']['thursday_from'] ?? '' }}">
                            <input type="time" class="form-control timeInput toTime" id="thursday_to{{$i}}" name="thursday_to{{$i}}" title="to"
                                style="width:115px;" value="{{ $branch['timings']['thursday_to'] ?? '' }}">
                        </td>
                        <td>
                            <input type="time" class="form-control timeInput fromTime" id="friday_from{{$i}}" name="friday_from{{$i}}" title="from"
                                style="width:115px;" value="{{ $branch['timings']['friday_from'] ?? '' }}">
                            <input type="time" class="form-control timeInput toTime" id="friday_to{{$i}}" name="friday_to{{$i}}" title="to"
                                style="width:115px;" value="{{ $branch['timings']['friday_to'] ?? '' }}">
                        </td>
                        <td>
                            <input type="time" class="form-control timeInput fromTime" id="saturday_from{{$i}}" name="saturday_from{{$i}}"
                                title="from" style="width:115px;" value="{{ $branch['timings']['saturday_from'] ?? '' }}">
                            <input type="time" class="form-control timeInput toTime" id="saturday_to{{$i}}" name="saturday_to{{$i}}"
                                title="to" style="width:115px;" value="{{ $branch['timings']['saturday_to'] ?? '' }}">
                        </td>
                        <td>
                            <button type="button" id="btnDelete" title="delete row"
                                class="waves-effect waves-light btn btn-danger btn-sm btnDelete" > <i
                                    class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    @php
                    $i++;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</section>