@php
    $index = 0;
    $totalWorkingdays = 0;
    $paidDays = 0;
    $unPaidDays = 0;
    $unPaidLeave = 0;
    $partiallyPaidLeave = 0;
    $paidLeave = 0;
    $monthBasicPay = 0;
    $perDaySalary = 0;
    $absenceDeduction = 0;
    $lossOfPay = 0;
    $totDt = 0;
    if (isset($salaryData)) {
        $totalWorkingdays = $salaryData['totalWorkingDays'];
        $paidDays = $salaryData['paidDays'];
        $unPaidDays = $salaryData['unPaidDays'];

        $unPaidLeave = $salaryData['totalUnpaidInput'];
        $partiallyPaidLeave = $salaryData['partiallyPaidLeave'];
        $paidLeave = $salaryData['totalPaidInput'];

        $monthBasicPay = $salary ? $salary->netsalary : 0;
        $perDaySalary = round($monthBasicPay / $totalWorkingdays, 1);
        $lossOfPay = $unPaidDays * $perDaySalary;
        $totDt = $lossOfPay;
    }
    if ($monthlySalary) {
        $totalWorkingdays = $monthlySalary->working_days;
        $paidDays = $monthlySalary->paid_days + round($monthlySalary->partially_paid_days / 2, 1);
        $unPaidDays = $monthlySalary->unpaid_days + round($monthlySalary->partially_paid_days / 2, 1);

        $unPaidLeave = $monthlySalary->unpaid_days;
        $partiallyPaidLeave = $monthlySalary->partially_paid_days;
        $paidLeave = $monthlySalary->paid_days;

        $monthBasicPay = $monthlySalary->basic_salary;
        $lossOfPay = $monthlySalary->absence_deduction;
        $totDt = $monthlySalary->total_deduction;
    }

@endphp
<thead class="collapsible-header">
    <tr data-toggle="collapse" data-target="#monthlydeductionsSection" aria-expanded="false"
        aria-controls="deductionsSection">
        <th colspan="3" class="text-start text-warning">DEDUCTIONS (Per Month)</th>
        <th>
            <input type="text" class="form-control text-center totalamount" id="monthlyDeductionsTotal"
                name="monthlyDeductionsTotal" placeholder="0.00"
                value="{{ isset($monthlySalary) ? $monthlySalary->total_deduction : $totDt }}" readonly>
            <div class="invalid-feedback text-start"></div>
        </th>
    </tr>
</thead>

<tbody id="monthlydeductionsSection" class="collapse">

    <tr>
        <td>{{ ++$index }}</td>
        <td class="text-start">
            Loss of Pay

            <input type="hidden" class="form-control text-center amount" id="totalWorkingDays" name="totalWorkingDays"
                placeholder="0.00" value="{{ $totalWorkingdays }}" required readonly>
            <input type="hidden" class="form-control text-center amount" id="paid_days" name="paid_days"
                placeholder="0.00" value="{{ $paidLeave }}" required readonly>
            <input type="hidden" class="form-control text-center amount" id="unpaid_days" name="unpaid_days"
                placeholder="0.00" value="{{ $unPaidLeave }}" required readonly>
            <input type="hidden" class="form-control text-center amount" id="partially_paid_days"
                name="partially_paid_days" placeholder="0.00" value="{{ $partiallyPaidLeave }}" required readonly>
            <input type="hidden" class="form-control text-center amount" id="month" name="month"
                placeholder="0.00" value="{{ $month }}" required readonly>
            <input type="hidden" class="form-control text-center amount" id="year" name="year"
                placeholder="0.00" value="{{ $year }}" required readonly>
            <input type="hidden" class="form-control text-center amount" id="salary_id" name="salary_id"
                placeholder="0.00" value="{{ $salary->id }}" required readonly>
            <input type="hidden" class="form-control text-center amount" id="basic_salary" name="basic_salary"
                placeholder="0.00" value="{{ $monthBasicPay }}" required readonly>

        </td>
        <td>
            <label for="totalWorkingDays">Total Working Days : {{ $totalWorkingdays }}</label>

            <label for="lossofPayDays">Loss of Pay Days : {{ $unPaidDays }}</label>
        </td>
        <td>
            <input type="text" class="form-control text-center amount" name="lossOfPay" placeholder="0.00"
                value="{{ $lossOfPay }}" readonly>

            <div class="invalid-feedback text-start"></div>
        </td>
    </tr>
    <tr>
        <td>{{ ++$index }}</td>
        <td class="text-start">
            Other Deductions
        </td>
        <td>
            <input type="text" class="form-control text-center amount" name="deductionReason"
                placeholder="Deduction Reason" value="{{ $monthlySalary ? $monthlySalary->deduction_reason : '' }}">
        </td>
        <td>
            <input type="text" class="form-control text-center amount" name="monthlyDeduction" placeholder="0.00"
                value="{{ $monthlySalary ? $monthlySalary->monthly_deduction : 0 }}">
            <div class="invalid-feedback text-start"></div>
        </td>
    </tr>
</tbody>
