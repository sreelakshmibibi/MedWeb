@php
    use App\Services\CommonService;
    $commonService = new CommonService();
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.dashboard')
@section('title', 'Purchases')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success"></div>
                <div id="errorMessagecreate" style="display:none;" class="alert alert-danger"></div>

                @if (session('success'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-success alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('success') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                @if (session('error'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('error') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                <div class="d-flex align-items-center justify-content-between">
                    @if ($mode == 'create')
                        <h3 class="page-title">Add Medicine Purchases</h3>

                        <a type="button" class="waves-effect waves-light btn btn-primary"
                            href="{{ route('medicine.purchases.history') }}"><i class="fa-solid fa-clock-rotate-left"> </i>
                            Medicine
                            Purchase
                            History</a>
                    @else
                        <h3 class="page-title">{{ $mode === 'view' ? 'View' : 'Edit' }} Medicine Purchases</h3>

                        <a type="button" class="waves-effect waves-light btn btn-primary" title="Back"
                            href="{{ route('medicine.purchases.history') }}">
                            <span class="hidden-sm-up"><i class="fa-solid fa-angles-left"></i></span>
                            <span class="hidden-xs-down"><i class="fa-solid fa-angles-left"></i> Back</span>
                        </a>
                    @endif
                </div>
            </div>

            <section class="content">
                <form id="medicinePurchaseItemsForm" method="post"
                    action="{{ $mode === 'create' ? route('medicine.purchases.store') : ($mode === 'edit' ? route('medicine.purchases.update') : '') }}">
                    @csrf
                    <input type="hidden" id="purchase_id" name="purchase_id"
                        value="{{ isset($purchase) ? $purchase->id : '' }}">
                    <div class="box">
                        @include('purchase.purchase.supplierEntry')

                        @include('purchase.medicine_purchase.itemsTable')

                        <div class="box-footer text-end p-3">
                            @if ($mode != 'view')
                                <button type="submit" class="btn btn-success" id="savePurchaseButton">
                                    <i class="fa fa-save"></i> {{ $mode === 'create' ? 'Save' : 'Update' }} Purchase
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/medicine_purchase.js') }}"></script>
    <script>
        // Optional: Script to handle the closing of alerts
        document.querySelectorAll('.closed').forEach(item => {
            item.addEventListener('click', event => {
                event.target.closest('.myadmin-alert').style.display = 'none';
            });
        });
        $(document).ready(function() {
            let rowIndex = 1;

            $(document).on("click", "#itemAddRow", function() {
                // Increment the row index
                rowIndex++;

                // Get the table body element where rows will be added
                const tbody = document.getElementById("itembody");

                // Create a new table row
                const row = document.createElement("tr");

                // Set the inner HTML for the new row to match your table structure
                row.innerHTML = `
                <td>${rowIndex}</td>
                <td class="text-start">
                <select class="form-control medname_select" name="item[]" required>
                    <option value="">Select a Medicine</option>
                    @foreach ($medicines as $medicine)
                        <option value="{{ $medicine->id }}">{{ $medicine->med_name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
                </td>
                <td class="text-start">
                    <input type="text" name="batchNo[]" class="form-control" placeholder="Enter Batch No">
                    <div class="invalid-feedback"></div>
                </td>
                <td class="text-start">
                    <input type="date" name="expiryDate[]" class="form-control" placeholder="Expiry Date">
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
                    <input type="number" min="1" name="packageCount[]" class="form-control packageno-input" placeholder="Package Count">
                    <div class="invalid-feedback text-start"></div>
                </td>
                <td>
                    <input type="number" min="1" name="unitsPerPackage[]" class="form-control packageunit-input" placeholder="Units per package">
                    <div class="invalid-feedback text-start"></div>
                </td>
                <td>
                    <input type="number" min="1" name="quantity[]" class="form-control quantity-input" placeholder="Quantity">
                    <input type="hidden" name="exquantity[]" value="0">
                    <div class="invalid-feedback text-start"></div>
                </td>
                <td>
                    <input type="number" min="1" name="price[]" class="form-control price-input" placeholder="Price">
                    <div class="invalid-feedback text-start"></div>
                </td>
                <td>
                    <input type="text" class="form-control text-center item-amount" name="itemAmount[]" readonly placeholder="0.00">
                    <div class="invalid-feedback text-start"></div>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                    <i class="fa fa-trash"></i>
                    </button>
                </td>
                `;

                // Append the new row to the table body
                tbody.appendChild(row);
                $(".medname_select")
                    .select2({
                        tags: true, // Allow user to add new options
                        tokenSeparators: [","], // Define how tags are separated
                        createTag: function(params) {
                            var term = $.trim(params.term);
                            if (term === "") {
                                return null; // Don't create a tag for empty input
                            }

                            // Return a new tag object
                            return {
                                id: term,
                                text: term,
                                newTag: true, // Indicate this is a new tag
                            };
                        },
                    })
            });

            function togglePaymentInput(checkbox, inputId) {
                const inputField = $('#' + inputId);
                if (checkbox.checked) {
                    inputField.show().css('display', 'inline');
                } else {
                    inputField.hide().val(''); // Clear the input if unchecked
                }
            }

            // Checkbox change event listeners
            $('#paymode_gpay').change(function() {
                togglePaymentInput(this, 'itemgpay');
            });

            $('#paymode_cash').change(function() {
                togglePaymentInput(this, 'itemcash');
            });

            $('#paymode_card').change(function() {
                togglePaymentInput(this, 'itemcard');
            });

            // Initial state for view mode
            if ("{{ $mode }}" != "create") {
                $('#uploadedBills').show();
                togglePaymentInput(document.getElementById('paymode_gpay'), 'itemgpay');
                togglePaymentInput(document.getElementById('paymode_cash'), 'itemcash');
                togglePaymentInput(document.getElementById('paymode_card'), 'itemcard');
                $("#category").trigger("change");
            }
        });

         // Download bills when button is clicked
        $('#uploadedBills').click(function() {
            var purchaseId = $("#purchase_id").val();
            window.location.href = '{{ url('purchases') }}' + "/" + purchaseId + "/download-bills";
        });
    </script>
@endsection