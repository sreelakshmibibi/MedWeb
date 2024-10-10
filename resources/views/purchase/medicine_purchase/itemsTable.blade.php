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
                    <th style="width:10%;">Medicine Name</th>
                    <th style="width:10%;">Batch No</th>
                    <th style="width:10%;">Expiry Date</th>
                    <th style="width:10%;">Package Type</th>
                    <th style="width:10%;">Package Count</th>
                    <th style="width:10%;">Units Per Package</th>
                    <th style="width:10%;">Total Quantity</th>
                    <th style="width:10%;">Price</th>
                    <th style="width:10%;" class="text-center">Amount
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
                            <select class="form-control name_select" name="item[]">
                                <option value=""> Select a Medicine </option>
                                @foreach ($medicines as $medicine)
                                    <option value="{{ $medicine->id }}">
                                        {{ $medicine->med_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </td>

                        <td class="text-start">
                            <input type="text" name="batchNo[]" class="form-control" placeholder="Enter Batch No">
                            <div class="invalid-feedback"></div>
                        </td>

                        <td class="text-start">
                            <input type="date" name="expiryDate[]" class="form-control" placeholder="Expiring Date">
                            <div class="invalid-feedback"></div>
                        </td>

                        <td class="text-start">
                            <select class="form-control" name="packageType[]">
                                <option value="" disabled selected>Select type</option>
                                <option value="Strip">Strip</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </td>

                        <td>
                            <input type="number" min="1" name="packageCount[]"
                                class="form-control packageno-input" placeholder="Package Count">
                            <div class="invalid-feedback text-start"></div>
                        </td>

                        <td>
                            <input type="number" min="1" name="unitsPerPackage[]"
                                class="form-control packageunit-input" placeholder="Units per package">
                            <div class="invalid-feedback text-start"></div>
                        </td>

                        <td>
                            <input type="number" min="1" name="quantity[]" class="form-control quantity-input"
                                placeholder="Quantity">
                            <div class="invalid-feedback text-start"></div>
                        </td>

                        <td>
                            <input type="number" min="1" name="price[]" class="form-control price-input"
                                placeholder="Price">
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
                    @foreach ($medicinePurchaseItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td class="text-start">
                                <select class="form-control name_select" name="item[]">
                                    <option value=""> Select a Medicine </option>
                                    @foreach ($medicines as $medicine)
                                        <option value="{{ $medicine->id }}"
                                            {{ $item->medicine_id == $medicine->id ? 'selected' : '' }}>
                                            {{ $medicine->med_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </td>
    
                            <td class="text-start">
                                <input type="text" name="batchNo[]" class="form-control" placeholder="Enter Batch No" 
                                value="{{ $item->batch_no }}" {{ $mode === 'view' ? 'readonly' : '' }}>
                                <div class="invalid-feedback"></div>
                            </td>
    
                            <td class="text-start">
                                <input type="date" name="expiryDate[]" class="form-control" placeholder="Expiring Date"
                                value="{{ $item->expiry_date }}" {{ $mode === 'view' ? 'readonly' : '' }}>
                                <div class="invalid-feedback"></div>
                            </td>
    
                            <td class="text-start">
                                <select class="form-control" name="packageType[]">
                                    <option value="" disabled selected>Select type</option>
                                    <option value="Strip" {{ $item->package_type == 'Strip' ? 'selected' : '' }}>Strip</option>
                                    <option value="Other" {{ $item->package_type == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </td>
    
                            <td>
                                <input type="number" min="1" name="packageCount[]"
                                    class="form-control packageno-input" placeholder="Package Count"
                                    value="{{ $item->package_count }}" {{ $mode === 'view' ? 'readonly' : '' }}>
                                <div class="invalid-feedback text-start"></div>
                            </td>
    
                            <td>
                                <input type="number" min="1" name="unitsPerPackage[]"
                                    class="form-control packageunit-input" placeholder="Units per package"
                                    value="{{ $item->units_per_package }}" {{ $mode === 'view' ? 'readonly' : '' }}>
                                <div class="invalid-feedback text-start"></div>
                            </td>
    
                            <td>
                                <input type="number" min="1" name="quantity[]" class="form-control quantity-input"
                                    placeholder="Quantity" value="{{ $item->total_quantity }}" {{ $mode === 'view' ? 'readonly' : '' }}>
                                <input type="hidden" name="exquantity[]" value="{{ $item->total_quantity }}">
                                <div class="invalid-feedback text-start"></div>
                            </td>
    
                            <td>
                                <input type="number" min="1" name="price[]" class="form-control price-input"
                                    placeholder="Price" value="{{ $item->purchase_unit_price }}"
                                    {{ $mode === 'view' ? 'readonly' : '' }}>
                                <div class="invalid-feedback text-start"></div>
                            </td>
    
                            <td>
                                <input type="text" class="form-control text-center item-amount" name="itemAmount[]"
                                    readonly placeholder="0.00" value="{{ $item->purchase_amount }}" {{ $mode === 'view' ? 'readonly' : '' }}>
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

            @include('purchase.medicine_purchase.calculation')
        </table>
    </div>
</div>
