<div class="box-header d-flex justify-content-between align-items-center py-2">
    <h4 class="box-title">Item Details</h4>
    <button id="itemAddRow" type="button" class="waves-effect waves-light btn btn-sm btn-primary">
        <i class="fa fa-add"> </i> Add Row
    </button>
</div>

<div class="box-body">
    <table class="table table-bordered text-center" id="itemTable">
        <thead class="bg-dark">
            <tr>
                <th style="width:5%;">Slno</th>
                <th style="width:40%;">Item Name</th>
                <th style="width:20%;">Price</th>
                <th style="width:10%;">Quantity</th>
                <th style="width:20%;" class="text-center">Amount
                    ({{ $clinicDetails->currency }})</th>
                <th style="width:5%;"></th>
            </tr>
        </thead>

        <tbody id="itembody">
            <tr>
                <td>1</td>

                <td class="text-start">
                    <input type="text" name="item[]" class="form-control" placeholder="Enter item">
                    <div class="invalid-feedback"></div>
                </td>

                <td>
                    <input type="number" min="1" name="price[]" class="form-control price-input"
                        placeholder="Price">
                    <div class="invalid-feedback text-start"></div>
                </td>

                <td>
                    <input type="number" min="1" name="quantity[]" class="form-control quantity-input"
                        placeholder="Quantity">
                    <div class="invalid-feedback text-start"></div>
                </td>

                <td>
                    <input type="text" class="form-control text-center item-amount" name="itemAmount[]" readonly
                        placeholder="0.00">
                    <div class="invalid-feedback text-start"></div>
                </td>

                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>

        @include('purchase.purchase.calculation')
    </table>
</div>
