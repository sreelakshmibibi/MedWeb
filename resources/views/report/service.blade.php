<form method="post" action="{{ route('report.service')}}">
    @csrf
    <div class="box no-border mb-2">
        <div class="box-header p-0">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="box-title ">
                    Service Report
                </h4>

                <button type='button'
                    class='waves-effect waves-light btn btn-circle btn-secondary btn-treatment-pdf-generate btn-xs mt-0 mb-2'
                    title='Download & Print Treatment Summary'><i class='fa fa-download'></i></button>
            </div>
        </div>
        <div class="box-body px-0 ">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="fromdate">From <span class="text-danger">
                                *</span></label>
                        <input type="date" class="form-control" id="fromdate" name="fromdate"
                            value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="todate">To <span class="text-danger">
                                *</span></label>
                        <input type="date" class="form-control" id="todate" name="todate" value="<?php echo date('Y-m-d'); ?>"
                            required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="branch">Branch</label>
                        <select class="form-control " type="text" id="branch" name="branch">
                            <option value="">All</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch['id'] }}"> {{ $branch['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="createdby">Done By</label>
                        <select class="form-control " type="text" id="createdby" name="createdby">
                            <option value="">All</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}"> {{ str_replace("<br>", " ", $doctor->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>



                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="treatment">Treatment</label>
                        <select class="form-control " type="text" id="treatment" name="treatment">
                            <option value="">All</option>
                            @foreach ($treatments as $treatment)
                                <option value="{{ $treatment->id }}"> {{ $treatment->treat_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="combooffer">Combo Offer</label>
                        <select class="form-control " type="text" id="combooffer" name="combooffer">
                            <option value="">All</option>
                            @foreach ($comboOffers as $comboOffer)
                                <option value="{{$comboOffer->id}}"> {{ $comboOffer->treatments->pluck('treat_name')->implode(', ') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="gender">Gender</label>
                        <select class="form-control " type="text" id="gender" name="gender">
                            <option value="">All</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                            <option value="O">Others</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="age">Age</label>
                        <div class="input-group">
                            <input type="number" class="form-control" placeholder="From" name="sAgeFrom" id="sAgeFrom" aria-label="From" min="0" max="99">
                            <span class="input-group-text">-</span>
                            <input type="number" class="form-control" placeholder="To" name="sAgeTo" id="sAgeTo" aria-label="To" min="0" max="99">
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="box-footer p-3 px-0 text-end bb-1" style="border-radius: 0px;">
            <button type="submit" class="btn btn-success" id="searchservicebtn">
                <i class="fa fa-search"></i> Search
            </button>
        </div>
    </div>
    <div class="servicediv" style="display: none">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center">
                <thead class="bg-primary-light">
                    <tr>
                        <th width="10px">No</th>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Phone Number</th>
                        <th>Last Appointment Date</th>
                        <th>Upcoming (if any)</th>
                        <th width="20px">Status</th>
                        <th width="170px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="10px">1</td>
                        <td>Patient ID</td>
                        <td>Name</td>
                        <td>Gender</th>
                        <td>Phone Number</th>
                        <td>Last Appointment Date</td>
                        <td>Upcoming (if any)</td>
                        <td width="20px">Status</td>
                        <td width="170px">Action</td>
                    </tr>
                    <tr>
                        <td width="10px">1</td>
                        <td>Patient ID</td>
                        <td>Name</td>
                        <td>Gender</th>
                        <td>Phone Number</th>
                        <td>Last Appointment Date</td>
                        <td>Upcoming (if any)</td>
                        <td width="20px">Status</td>
                        <td width="170px">Action</td>
                    </tr>
                    <tr>
                        <td width="10px">1</td>
                        <td>Patient ID</td>
                        <td>Name</td>
                        <td>Gender</th>
                        <td>Phone Number</th>
                        <td>Last Appointment Date</td>
                        <td>Upcoming (if any)</td>
                        <td width="20px">Status</td>
                        <td width="170px">Action</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</form>