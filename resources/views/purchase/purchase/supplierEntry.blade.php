<div class="box-body ">
    <div class="row">

        <!-- supplier name -->
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label" for="name">Supplier Name <span class="text-danger">
                        *</span></label>
                <select class="form-control name_select" id="name" name="supplier[name]" style="width: 100%;" required
                    {{ $mode === 'view' ? 'disabled' : '' }}>
                    <option value="">Select a Supplier </option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @if (isset($selectedSupplier) && $selectedSupplier->id == $supplier->id) selected @endif>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
                <div id="nameError" class="invalid-feedback"></div>
            </div>
        </div>

        <!-- phone number -->
        <div class="col-md-3 col-lg-2">
            <div class="form-group">
                <label class="form-label" for="phone">Phone Number <span class="text-danger">
                        *</span></label>
                <input type="tel" id="phone" name="supplier[phone]" class="form-control sup_details"
                    placeholder="Supplier Phone No" pattern="[1-9]{1}[0-9]{9}" size="10" minlength="10"
                    maxlength="10" required value="{{ isset($selectedSupplier) ? $selectedSupplier->phone : '' }}"
                    readonly>
                <div id="phoneError" class="invalid-feedback"></div>
            </div>
        </div>

        <!-- address -->
        <div class="col-md-3 col-lg-4">
            <div class="form-group">
                <label class="form-label" for="address">Address <span class="text-danger">
                        *</span></label>
                <textarea class="form-control sup_details" rows="1" id="address" name="supplier[address]"
                    placeholder="Enter Supplier Address" required readonly>{{ isset($selectedSupplier) ? $selectedSupplier->address : '' }}
                </textarea>
                <div id="addressError" class="invalid-feedback"></div>
            </div>
        </div>

        <!-- gst no -->
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label" for="gst_no">GST No. <span class="text-danger">
                        *</span></label>
                <input type="text" id="gst_no" name="supplier[gst_no]" class="form-control sup_details"
                    placeholder="Enter GST No" required readonly
                    value="{{ isset($selectedSupplier) ? $selectedSupplier->gst : '' }}">
                <div id="gst_noError" class="invalid-feedback"></div>
            </div>
        </div>

        <!-- invoice no -->
        <div class="col-md-2">
            <div class="form-group">
                <label class="form-label" for="invoice_no">Invoice No. <span class="text-danger">
                        *</span></label>
                <input type="text" id="invoice_no" name="invoice_no" class="form-control"
                    placeholder="Enter Invoice No" required {{ $mode === 'view' ? 'readonly' : '' }}
                    value="{{ isset($purchase) ? $purchase->invoice_no : '' }}">
                <div id="invoice_noError" class="invalid-feedback"></div>
            </div>
        </div>

        <!-- invoice date -->
        <div class="col-md-3 col-lg-2">
            <div class="form-group">
                <label class="form-label" for="invoice_date">Invoice Date <span class="text-danger">
                        *</span></label>
                <input class="form-control" type="date" id="invoice_date" name="invoice_date" placeholder="bill date"
                    value="<?php echo date('Y-m-d'); ?>" required {{ $mode === 'view' ? 'readonly' : '' }}
                    value="{{ isset($purchase) ? $purchase->invoice_date : '' }}">
                <div id="invoice_dateError" class="invalid-feedback"></div>
            </div>
        </div>

        <!-- bill -->
        <div class="col-md-3 col-lg-4">
            <div class="form-group">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label" for="billfile">Attach Bill</label>
                    <button type="button" id="uploadedBills" style="display:none;"
                        {{ isset($purchase) && $purchase->billfile == null ? 'disabled' : '' }}
                        class="waves-effect waves-light btn btn-circle btn-secondary btn-xs" title="download bills"><i
                            class="fa-solid fa-download"></i></button>
                </div>
                <input type="file" class="form-control" id="billfile" type="file" name="billfile[]" multiple
                    {{ $mode === 'view' ? 'disabled' : '' }}>
                <div id="billfileError" class="invalid-feedback"></div>
            </div>
        </div>

        <!-- Branch -->
        <div class="col-md-2">
            <div class="form-group">
                <label class="form-label" for="branch">Branch <span class="text-danger">
                        *</span></label>
                <select class="select2 branch_select" id="branch" name="branch_id" style="width: 100%;" required
                    {{ $mode === 'view' ? 'disabled' : '' }}>
                    <option value="">Select a Branch</option>
                    @foreach ($clinicBranches as $clinicBranch)
                        <?php
                        $clinicAddress = $clinicBranch->clinic_address;
                        $clinicAddress = explode('<br>', $clinicBranch->clinic_address);
                        $clinicAddress = implode(', ', $clinicAddress);
                        $branch = $clinicAddress . ', ' . $clinicBranch->city->city . ', ' . $clinicBranch->state->state;
                        ?>
                        <option value="{{ $clinicBranch->id }}" @if (isset($purchase) && $purchase->branch_id == $clinicBranch->id) selected @endif>
                            {{ $branch }}</option>
                    @endforeach
                </select>
                <div id="branchError" class="invalid-feedback"></div>
            </div>
        </div>

        <!-- debit/credit -->
        <div class="col-md-2">
            <div class="form-group">
                <label class="form-label" for="invoice_no">Payable as</label>
                <select class="form-select category_select" id="category" name="category"
                    {{ $mode === 'view' ? 'disabled' : '' }}>
                    <option value="D" @if (isset($purchase) && $purchase->category == 'D') selected @endif>Debit</option>
                    <option value="C" @if (isset($purchase) && $purchase->category == 'C') selected @endif>Credit</option>
                </select>
                <div id="categoryError" class="invalid-feedback">
                </div>
            </div>
        </div>
    </div>
</div>
