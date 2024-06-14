<div class="modal center-modal fade" id="modal-center" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Clinic Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="box">
                            <!-- /.box-header -->
                            <form class="form">
                                <div class="box-body">
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
                                        <input name="branch" type="radio" class="form-control with-gap"
                                            id="yes">
                                        <label for="yes">Yes</label>
                                        <input name="branch" type="radio" class="form-control with-gap"
                                            id="no">
                                        <label for="no">No</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="addressline1" class="form-label">Address Line
                                                    1</label>
                                                <input type="text" class="form-control" id="addressline1">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="addressline2" class="form-label">Address Line
                                                    2</label>
                                                <input type="text" class="form-control" id="addressline2">
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
                                <!-- /.box-body -->
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end">Save</button>
                </div>
            </div>
        </div>
    </div>