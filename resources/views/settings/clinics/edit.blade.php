<form method="post" action="{{ route('settings.clinic.update') }}">
    @csrf
    <input type="hidden" id="edit_department_id" name="edit_department_id" value="">
    <div class="modal modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog" style="width:40%; max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-clinic-medical"> </i> Edit Clinic Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label class="form-label" for="name">Clinic Name</label>
                            <input class="form-control" type="text" id="name" name="name"
                                placeholder="Clinic Name">
                        </div>

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
                                        placeholder="Phone">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="logo">Logo</label>
                                    <input class="form-control" type="file" id="logo" name="logo"
                                        placeholder="logo">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="website">Website</label>
                                    <input class="form-control" type="url" id="website" name="website"
                                        placeholder="http://">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6" for="branch">Is main branch?</label>
                            <input name="branch" type="radio" class="form-control with-gap" id="yes">
                            <label for="yes">Yes</label>
                            <input name="branch" type="radio" class="form-control with-gap" id="no">
                            <label for="no">No</label>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="address">Address Line
                                        1</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="adress line 1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="address">Address Line
                                        2</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="adress line 2">
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
                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success float-end" id="buttonalert">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
