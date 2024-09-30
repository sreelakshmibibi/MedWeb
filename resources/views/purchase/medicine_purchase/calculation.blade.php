<tbody>
    <tr>
        <th colspan="9" class="text-end text-info">Sub - Total amount</th>
        <td colspan="2">
            <input type="text" readonly name="itemtotal" id="itemtotal"
                class="form-control text-center text-bold text-info" placeholder="0.00">
        </td>
    </tr>

    <tr>
        <td colspan="9" class="text-end">Delivery Charge</td>
        <td colspan="2">
            <input type="text" class="form-control text-center" name="deliverycharge" id="deliverycharge"
                placeholder="0.00">
        </td>
    </tr>

    <tr>
        <td colspan="9" class="text-end">GST</td>
        <td colspan="2">
            <input type="text" name="tax" id="tax" class="form-control text-center" placeholder="0.00">
        </td>
    </tr>
    {{-- @if ($previousOutStanding != 0) --}}
    <tr>
        <th colspan="9" class="text-end text-info">
            Current Bill Total
        </th>
        <td colspan="2">
            <input type="text" readonly name="currentbilltotal" id="currentbilltotal"
                class="form-control text-center text-bold text-info" placeholder="0.00">
        </td>
    </tr>
    <tr>
        <td colspan="9" class="text-end">Discount</td>

        <td colspan="2">
            <input type="text" name="discount" id="discount" class="form-control text-center" placeholder="0.00">
        </td>
    </tr>
    <tr>
        <td colspan="9" class="text-end">Previous Outstanding</td>

        <td colspan="2">
            <input type="text" readonly name="previousOutStanding" id="previousOutStanding"
                class="form-control text-center" placeholder="0.00">
        </td>
    </tr>
    {{-- @endif --}}

    <tr class="bt-3 border-primary">
        <td colspan="9" class="text-end">
            <h3><b>Total</b></h3>
        </td>

        <td colspan="2">
            <input type="text" class="form-control text-center form-control-lg text-bold" id="grandTotal"
                name="grandTotal" readonly placeholder="0.00">
            <span class="text-danger" id="grandTotalError">
                @error('grandTotal')
                    {{ $message }}
                @enderror
            </span>
        </td>
    </tr>
</tbody>

<tbody id="paymentbody">
    <tr>
        <td colspan="8" class="text-start">
            <span class="text-bold me-2">Mode of Payment:</span>

            <!-- Checkbox for Gpay -->
            <input type="checkbox" class="filled-in chk-col-success" id="paymode_gpay" name="itemmode_of_payment[]"
                value="gpay">
            <label class="form-check-label me-2" for="paymode_gpay">Gpay</label>
            <input type="text" name="itemgpay" id="itemgpay" class="form-control w-100 me-2 itempay"
                style="display: none;" placeholder="0.00">

            <!-- Checkbox for Cash -->
            <input type="checkbox" class="filled-in chk-col-success" id="paymode_cash" name="itemmode_of_payment[]"
                value="cash">
            <label class="form-check-label me-2" for="paymode_cash">Cash</label>
            <input type="text" name="itemcash" id="itemcash" class="form-control w-100 itempay"
                style="display: none;" placeholder="0.00">
            &nbsp;
            <!-- Checkbox for Card -->
            <input type="checkbox" class="filled-in chk-col-success" id="paymode_card" value="card"
                name="itemmode_of_payment[]">
            <label class="form-check-label me-2" for="paymode_card">Card</label>
            <input type="text" name="itemcard" id="itemcard" class="form-control w-100 itempay"
                style="display: none;" placeholder="0.00">

            <span class="text-danger" id="itemModePaymentError">
                @error('itemmode_of_payment')
                    {{ $message }}
                @enderror
            </span>
        </td>

        <th class="text-end text-info">Paid Amount</th>

        <td colspan="2">
            <input type="text" name="itemAmountPaid" id="itemAmountPaid" placeholder="0.00"
                class="form-control text-center text-info" readonly>
            <span id="itemAmountPaidError" class="error-message text-danger">
                @error('itemAmountPaid')
                    {{ $message }}
                @enderror
            </span>
        </td>
    </tr>

    <tr>
        <td colspan="8" class="text-start">
            <input type="checkbox" name="consider_for_next_payment" id="consider_for_next_payment"
                class="filled-in chk-col-success">
            <label class="form-check-label" for="consider_for_next_payment">Consider for
                Next Payment</label>
        </td>

        <th class="text-end ">Balance Due</th>

        <td colspan="2"><input type="text" name="balance" id="balance" readonly
                class="form-control text-center" placeholder="0.00">
        </td>
    </tr>

    <tr>
        <td colspan="8" class="text-start">
            <input type="checkbox" name="itemBalance_given" id="itemBalance_given"
                class="filled-in chk-col-success">
            <label class="form-check-label" for="itemBalance_given">Balance
                Given</label>
            <span class="error-message text-danger" id="itemCheckError"></span>
        </td>

        <td class="text-end">Balance to Give Back</td>

        <td colspan="2"><input type="text" name="itemBalanceToGiveBack" id="itemBalanceToGiveBack"
                class="form-control text-center" readonly placeholder="0.00">
            <span class="text-danger" id="itemBalanceToGiveBackError">
                @error('itemBalanceToGiveBack')
                    {{ $message }}
                @enderror
            </span>
        </td>
    </tr>
</tbody>
