<thead class="collapsible-header">
    <tr data-toggle="collapse" data-target="#deductionsSection" aria-expanded="false" aria-controls="deductionsSection">
        <th colspan="3" class="text-start text-warning">STATUTORY DEDUCTIONS (Per Month)</th>
        <th>
            <input type="text" class="form-control text-center totalamount" id="deductionstotal" name="deductionstotal"
                placeholder="0.00" value="{{ isset($salary) ? $salary->sdtotal : '' }}" readonly>
            <div class="invalid-feedback text-start"></div>
        </th>
    </tr>
</thead>

<tbody id="deductionsSection" class="collapse">
    @php
        $index = 0;
    @endphp
    @foreach ($SDPayHeads as $head)
        @php
            $deduction = isset($employeesalary) ? $employeesalary->where('pay_head_id', $head->id)->first() : null;
        @endphp
        <tr>
            <td>{{ ++$index }}</td>
            <td class="text-start" name="deductionshead_type[]">
                {{ $head->head_type }}
                <input type="hidden" name="deductionspay_head_id[]" value="{{ $head->id }}">
            </td>
            <td>
                <input class="form-control" type="date" name="deductionseffect_date[]"
                    {{ $mode === 'view' ? 'readonly' : '' }}
                    value="{{ $deduction ? $deduction->with_effect_from : date('Y-m-d') }}" required>
                <div class="invalid-feedback text-start"></div>
            </td>

            <td>
                <input type="text" class="form-control text-center amount" name="deductionsamount[]"
                    {{ $mode === 'view' ? 'readonly' : '' }} placeholder="0.00"
                    value="{{ $deduction ? $deduction->amount : '' }}" required>
                <div class="invalid-feedback text-start"></div>
            </td>
        </tr>
    @endforeach
</tbody>
