<div class="box-header d-flex justify-content-between align-items-center py-2">
    <h4 class="box-title">Item Details</h4>
    @if ($mode !== 'view')
        <button id="itemAddRow" type="button" class="waves-effect waves-light btn btn-sm btn-primary">
            <i class="fa fa-add"> </i> Add Row
        </button>
    @endif
</div>

<div class="box-body">
    <div class="table-responsive">
        <table class="table table-bordered text-center" id="itemTable">
            <thead class="bg-dark">
                <tr>
                    <th style="width:5%;">Slno</th>
                    <th style="width:40%;">Item Name</th>
                    <th style="width:20%;">Price</th>
                    <th style="width:10%;">Quantity</th>
                    <th style="width:20%;" class="text-center">Amount
                        ({{ $clinicDetails->currency }})</th>
                    @if ($mode !== 'view')
                        <th style="width:5%;"></th>
                    @endif
                </tr>
            </thead>

            <tbody id="itembody">
                @if ($mode === 'create')
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
                            <input type="text" class="form-control text-center item-amount" name="itemAmount[]"
                                readonly placeholder="0.00">
                            <div class="invalid-feedback text-start"></div>
                        </td>

                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @else
                    @foreach ($purchaseditems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td class="text-start">
                                <input type="text" name="item[]" class="form-control" placeholder="Enter item"
                                    value="{{ $item->name }}" {{ $mode === 'view' ? 'readonly' : '' }}>
                                <div class="invalid-feedback"></div>
                            </td>

                            <td>
                                <input type="number" min="1" name="price[]" class="form-control price-input"
                                    placeholder="Price" value="{{ $item->price }}"
                                    {{ $mode === 'view' ? 'readonly' : '' }}>
                                <div class="invalid-feedback text-start"></div>
                            </td>

                            <td>
                                <input type="number" min="1" name="quantity[]" value="{{ $item->quantity }}"
                                    {{ $mode === 'view' ? 'readonly' : '' }} class="form-control quantity-input"
                                    placeholder="Quantity">
                                <div class="invalid-feedback text-start"></div>
                            </td>

                            <td>
                                <input type="text" class="form-control text-center item-amount" name="itemAmount[]"
                                    readonly placeholder="0.00" value="{{ $item->amount }}"
                                    {{ $mode === 'view' ? 'readonly' : '' }}>
                                <div class="invalid-feedback text-start"></div>
                            </td>
                            @if ($mode !== 'view')
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endif
            </tbody>

            @include('purchase.purchase.calculation')
        </table>
    </div>
</div>
