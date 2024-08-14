<div class="box no-border mb-2">
    <div class="box-header p-0">
        {{-- <h3>Collection Report</h3> --}}
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="box-title  ">
                Collection Report
            </h4>

            <button type='button'
                class='waves-effect waves-light btn btn-circle btn-secondary btn-treatment-pdf-generate btn-xs mt-0 mb-2'
                title='Download & Print Treatment Summary'><i class='fa fa-download'></i></button>
        </div>
    </div>
    <div class="box-body px-0 ">
        {{-- <div class="form-group">
                        <label class="form-label" for="name">Clinic Name <span class="text-danger">
                                *</span></label>
                        <input class="form-control " type="text" id="clinic_name" name="clinic_name"
                            placeholder="Clinic Name">

                    </div> --}}
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="billedby">Billed By</label>
                    <select class="form-control " type="text" id="billedby" name="billedby">
                        <option value="">All</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="createdby">Created By</label>
                    <select class="form-control " type="text" id="createdby" name="createdby">
                        <option value="">All</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="patienttype">Patient Type</label>
                    <select class="form-control " type="text" id="patienttype" name="patienttype">
                        <option value="">All</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="discount">Discount</label>
                    <select class="form-control " type="text" id="discount" name="discount">
                        <option value="">All</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="outstanding">Outstanding</label>
                    <select class="form-control " type="text" id="outstanding" name="outstanding">
                        <option value="">All</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="combooffer">Combo Offer</label>
                    <select class="form-control " type="text" id="combooffer" name="combooffer">
                        <option value="">All</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
            </div>
        </div>
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
        </div>
        <div class="d-none row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="website">Website</label>
                    <input class="form-control " type="url" id="clinic_website" name="clinic_website"
                        placeholder="http://">

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="name">
                        Is Insurance available?<span class="text-danger">*</span>
                    </label>
                    <div>
                        <input name="insurance" type="radio" class="form-control with-gap" id="yes"
                            value="Y">
                        <label for="yes">Yes</label>

                        <input name="insurance" type="radio" class="form-control with-gap" id="no"
                            value="N">
                        <label for="no">No</label>

                    </div>
                </div>
            </div>
        </div>
        <div class="d-none row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="tax">Tax (%)
                        <span class="text-danger">
                            *</span></label>
                    <input class="form-control " type="text" id="tax" name="treatment_tax"
                        placeholder="Tax">

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="treatment_tax">Treatment Tax Included
                        <span class="text-danger">
                            *</span></label>
                    <select class="form-control " type="text" id="treatment_tax_included"
                        name="treatment_tax_included">
                        <option value="Y">Yes</option>
                        <option value="N">No</option>
                    </select>


                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="currency">Currency<span class="text-danger">
                            *</span></label>
                    <select class="form-control " type="text" id="currency" name="currency">

                        <option value="OMR">OMR</option>
                    </select>

                </div>
            </div>
        </div>
        <div class="d-none row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="patient_registration_fees">Patient Registration
                        Fees<span class="text-danger">
                            *</span></label>
                    <input class="form-control " type="text" id="patient_registration_fees"
                        name="patient_registration_fees" placeholder="Registration Fees">

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="consultation_fees">Consultation
                        Fees<span class="text-danger">
                            *</span></label>
                    <input class="form-control " type="text" id="consultation_fees" name="consultation_fees"
                        placeholder="Consultation Fees">

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="consultation_fees_frequency">Frequency(Consult
                        fee)<span class="text-danger">
                            *</span></label>
                    <input class="form-control " type="text" id="consultation_fees_frequency"
                        name="consultation_fees_frequency" placeholder="Fees Frequency">

                </div>
            </div>
        </div>

        <div class="d-none row">
            <div class="col-lg-10">
                <div class="form-group ">
                    <label class="form-label" for="logo">Logo</label>
                    <input class="form-control " type="file" id="clinic_logo" name="clinic_logo"
                        placeholder="logo">

                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    <canvas id="logoCanvas" style="height: 64px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer p-3 px-0 text-end bb-1" style="border-radius: 0px;">
        <button type="submit" class="btn btn-success" id="searchcollectionbtn">
            <i class="fa fa-search"></i> Search
        </button>
    </div>
</div>
<div class="collectiondiv" style="display: none">
    {{-- <hr class="my-2" /> --}}
    <div class="table-responsive">
        <!-- Main content -->
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
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
                    {{-- <th>New Appointment</th> --}}
                    <td width="20px">Status</td>
                    <td width="170px">Action</td>
                </tr>
            </tbody>
        </table>
        <!-- /.content -->
    </div>
</div>
