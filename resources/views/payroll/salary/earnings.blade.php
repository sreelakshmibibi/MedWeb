<thead class="collapsible-header">
    <tr data-toggle="collapse" data-target="#earningsSection" aria-expanded="false" aria-controls="earningsSection">
        <th colspan="3" class="text-start text-warning">EARNINGS (Per Month)</th>
        <th>
            <input type="text" class="form-control text-center totalamount" id="earningstotal" name="earningstotal"
                placeholder="0.00" value="{{ isset($salary) ? $salary->etotal : '' }}" readonly>
            <div class="invalid-feedback text-start"></div>
        </th>
    </tr>
</thead>

<tbody id="earningsSection" class="collapse">
    @php
        $index = 0;
    @endphp
    @foreach ($EPayHeads as $head)
        @php
            $earning = isset($employeesalary) ? $employeesalary->where('pay_head_id', $head->id)->first() : null;
        @endphp
        <tr>
            <td>{{ ++$index }}</td>
            <td class="text-start" name="earningshead_type[]">
                {{ $head->head_type }}
                <input type="hidden" name="earningspay_head_id[]" value="{{ $head->id }}">
            </td>
            <td>
                <input class="form-control" type="date" name="earningseffect_date[]"
                    {{ $mode === 'view' ? 'readonly' : '' }}
                    value="{{ $earning ? $earning->with_effect_from : date('Y-m-d') }}" required>
                <div class="invalid-feedback text-start"></div>
            </td>

            <td>
                <input type="text" class="form-control text-center amount" name="earningsamount[]" placeholder="0.00"
                    value="{{ $earning ? $earning->amount : '' }}" required {{ $mode === 'view' ? 'readonly' : '' }}>
                <div class="invalid-feedback text-start"></div>
            </td>
        </tr>
    @endforeach
</tbody>
