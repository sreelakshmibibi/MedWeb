<section id="finalStepContent" class="tabHideSection">
    <div class="d-flex align-items-center justify-content-between">
        <h5 class="box-title text-info mb-0 mt-2 "><i class="fa-solid fa-indian-rupee-sign me-15"></i>
            Charge
        </h5>
        <button id="chargeAddRow" type="button" class="waves-effect waves-light btn btn-sm btn-outline-primary">
            <i class="fa fa-add"></i>
            Add</button>
    </div>
    <hr class="my-15 ">

    <div class="table-responsive ">
        <table class="table table-bordered table-hover table-striped mb-0 text-center">

            <thead>
                <tr class="bg-primary-light">
                    <th>No</th>
                    <th>Treatment</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th style="width:80px;">Action</th>
                </tr>
            </thead>
            <tbody id="chargetablebody">
                <tr>
                    <td>1</td>
                    <td>
                        <select class="select2" id="treatment_id1" name="treatment_id1"
                            data-placeholder="Select a Treatment" style="width: 100%;">

                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" class="form-control" id="quantity1" name="quantity1"
                                aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Tooth</span>
                            </div>
                        </div>
                    </td>
                    <td>1000</td>
                    <td>
                        <button type="button" id="btnchargeDelete" title="delete row"
                            class="waves-effect waves-light btn btn-danger btn-sm btnchargeDelete"> <i
                                class="fa fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr class="bt-3 border-primary">
                    <th colspan="3">Total Rate</th>
                    <td colspan="2">1000</td>
                </tr>
                <tr>
                    <th colspan="3">Discount</th>
                    <td colspan="2">
                        <div class="input-group">
                            <input type="number" class="form-control" id="discount1" name="discount1"
                                aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="bg-primary">
                    <th colspan="3">Total Amount</th>
                    <td colspan="2">1000</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
