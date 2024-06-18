<form method="post" action="{{ route('patient.patient_list.store') }}">
    @csrf
    <div class="modal modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-hospital-user"> </i> Patient Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">

                        <!--id-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="pid">Patient ID</label>
                                    <input type="text" class="form-control" id="pid" name="pid"
                                        placeholder="Patient ID" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="nid">National ID</label>
                                    <input type="text" class="form-control" id="nid" name="nid"
                                        placeholder="National ID" required>
                                </div>
                            </div>
                        </div>

                        <!--name-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="fname">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname"
                                        placeholder="First Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="lname">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname"
                                        placeholder="Last Name" required>
                                </div>
                            </div>
                        </div>

                        <!--gender-->
                        <div class="form-group mt-2">
                            <label class="form-label col-md-4">Gender</label>
                            <input name="gender" type="radio" checked class="form-control with-gap" id="male"
                                value="M">
                            <label for="male">Male</label>
                            <input name="gender" type="radio" class="form-control with-gap" id="female"
                                value="F">
                            <label for="female">Female</label>
                            <input name="gender" type="radio" class="form-control with-gap" id="other"
                                value="O">
                            <label for="other">Other</label>
                        </div>

                        <!--dob-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="dob">Date of Birth</label>
                                    <input type="date" class="form-control" id="dob" name="dob">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="age">Age</label>
                                    <input type="text" class="form-control" id="age" name="age"
                                        placeholder="age">
                                </div>
                            </div>
                        </div>

                        <!--address-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="address">Address Line
                                        1</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="Adress line 1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="address">Address Line
                                        2</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="Adress line 2">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="location3">City</label>
                                    <select class="form-select" id="location3" name="location">
                                        <option value="">Select City</option>
                                        <option value="Hyderabad">Hyderabad</option>
                                        <option value="Dubai">Dubai</option>
                                        <option value="Delhi">Delhi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="location3">State</label>
                                    <select class="form-select" id="location3" name="location">
                                        <option value="">Select State</option>
                                        <option value="Kerala">Kerala</option>
                                        <option value="Karnataka">Karnataka</option>
                                        <option value="Tamil Nadu">Tamil Nadu</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="location3">Country</label>
                                    <select class="form-select" id="location3" name="location">
                                        <option value="">Select Country</option>
                                        <option value="India">India</option>
                                        <option value="UAE">UAE</option>
                                        <option value="USA">USA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="pincode">Pin Code</label>
                                    <input class="form-control" type="text" id="pincode" name="pincode"
                                        placeholder="XXX XXX">
                                </div>
                            </div>
                        </div>

                        <!--contact details-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="email">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="E-mail">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="phone">Contact
                                        Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Phone Number">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="nationality">Nationality</label>
                            <input class="form-control" type="text" id="nationality" name="nationality" required
                                minlength="3" placeholder="Nationality">
                        </div>

                        <!--registration dates-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="rdate">Registration Date</label>
                                    <input class="form-control" type="date" id="rdate" name="rdate">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="adate">Appointment Date</label>
                                    <input class="form-control" type="date" id="adate" name="adate">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="gsm">GSM</label>
                                    <input type="text" class="form-control" id="gsm" name="gsm"
                                        placeholder="GSM">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="regby">Regby</label>
                                    <input type="text" class="form-control" id="regby" name="regby"
                                        placeholder="Regby">
                                </div>
                            </div>
                        </div>

                        <!--status-->
                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Active</label>
                            <input name="status" type="radio" checked class="form-control with-gap"
                                id="yes" value="Y">
                            <label for="yes">Yes</label>
                            <input name="status" type="radio" class="form-control with-gap" id="no"
                                value="N">
                            <label for="no">No</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success float-end" id="buttonalert">Save</button>
                </div>

            </div>

        </div>
    </div>
</form>
