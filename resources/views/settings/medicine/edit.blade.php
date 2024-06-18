<form method="post" action="{{ route('settings.medicine.update') }}">
    @csrf
    <input type="hidden" id="edit_medicine_id" name="edit_medicine_id" value="">
    <div class="modal modal-right slideInRight" id="modal-edit" tabindex="-1">
        <div class="modal-dialog" style="width:40%; max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-briefcase"> </i> Edit Medicine Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="form-group">
                            <label class="form-label" for="name">Medicine Name</label>
                            <input class="form-control" type="text" id="name" name="name" required
                                minlength="3" placeholder="Medicine Name">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="barcode">Barcode</label>
                                    <input class="form-control" type="text" id="barcode" name="barcode" required
                                        minlength="3" placeholder="Barcode">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="strength">Strength</label>
                                    <input class="form-control" type="text" id="strength" name="strength" required
                                        minlength="3" placeholder="Medicine Strength">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="strength">Strength</label>
                            <input class="form-control" type="text" id="strength" name="strength" required
                                minlength="3" placeholder="Medicine Strength">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="remarks">Remarks</label>
                            <input class="form-control" type="text" id="remarks" name="remarks" required
                                minlength="3" placeholder="Medicine Remarks">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="price">Price</label>
                                    <input class="form-control" type="text" id="price" name="price" required
                                        minlength="3" placeholder="Medicine Price">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="mstatus">Medicine Status</label>
                                    <input class="form-control" type="text" id="mstatus" name="mstatus" required
                                        minlength="3" placeholder="Medicine Status">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label col-md-6">Status</label>
                            <input name="status" type="radio" checked class="form-control with-gap" id="yes"
                                value="Y">
                            <label for="yes">Yes</label>
                            <input name="status" type="radio" class="form-control with-gap" id="no"
                                value="N">
                            <label for="no">No</label>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="mdate">Medicine Date</label>
                                    <input class="form-control" type="date" id="mdate" name="mdate"
                                        required minlength="3" placeholder="Medicine Date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="mlast_update">Expiring Date</label>
                                    <input class="form-control" type="date" id="mlast_update" name="mlast_update"
                                        required minlength="3" placeholder="Expiring date">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cname">Company Name</label>
                            <input class="form-control" type="text" id="cname" name="cname" required
                                minlength="3" placeholder="Company Name">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="mname">Medicine Name</label>
                            <input class="form-control" type="text" id="mname" name="mname" required
                                minlength="3" placeholder="Medicine Name">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="strength">Strength</label>
                            <input class="form-control" type="text" id="strength" name="strength" required
                                minlength="3" placeholder="Medicine Strength">
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-label" for="name">Barcode</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="barcodeInput"
                                        placeholder="Enter text...">
                                    {{-- <input type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload"> --}}
                                    <button class="btn btn-primary btn-sm input-group-text" type="button"
                                        id="inputGroupFileAddon04" onclick="generateBarcode()">Generate
                                        Barcode</button>
                                </div>
                            </div>

                            <canvas id="barcodeCanvas" class="col-md-4" style=" height:64px;"></canvas>
                        </div>

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
                    <button type="submit" class="btn btn-success float-end" id="buttonalert">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
