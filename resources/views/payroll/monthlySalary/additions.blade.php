<thead class="collapsible-header">
    <tr data-toggle="collapse" data-target="#additionsSection" aria-expanded="false" aria-controls="additionsSection">
        <th colspan="3" class="text-start text-warning">STATUTORY ADDITIONS (Per Month)</th>
        <th>
            <input type="text" class="form-control text-center totalamount" id="additionstotal" name="additionstotal"
                placeholder="0.00" value="{{ isset($salary) ? $salary->satotal : '' }}" readonly>
            <div class="invalid-feedback text-start"></div>
        </th>
    </tr>
</thead>

<tbody id="additionsSection" class="collapse">
    @php
        $index = 0;
        $rowspancount = count($SAPayHeads);
    @endphp
    @foreach ($SAPayHeads as $head)
        @php
            $addition = isset($employeesalary) ? $employeesalary->where('pay_head_id', $head->id)->first() : null;
        @endphp
        <tr>
            <td>{{ ++$index }}</td>
            <td class="text-start" name="additionshead_type[]">
                {{ $head->head_type }}
                <input type="hidden" name="additionspay_head_id[]" value="{{ $head->id }}">
            </td>
            <td>
                <input class="form-control" type="date" name="additionseffect_date[]"
                    value="{{ $addition ? $addition->with_effect_from : date('Y-m-d') }}" required readonly>
                <div class="invalid-feedback text-start"></div>
            </td>

            <td>
                <input type="text" class="form-control text-center amount" name="additionsamount[]"
                readonly placeholder="0.00"
                    value="{{ $addition ? $addition->amount : '' }}" required>
                <div class="invalid-feedback text-start"></div>
            </td>
        </tr>
    @endforeach
</tbody>
