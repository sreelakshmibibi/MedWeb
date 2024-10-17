<div class="box-body ">
    <div class="row">

        <!-- Employee ID -->
        <div class="col-md-2">
            <div class="form-group">
                <label class="form-label" for="emp_id">Employee ID</label>
                <input type="text" id="emp_id" name="emp_id" class="form-control" readonly
                    value="{{ isset($staff) ? $staff->staff_id : '' }}">
            </div>
        </div>

        <!-- Name -->
        <div class="col-lg-4 col-md-3">
            <div class="form-group">
                <label class="form-label" for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" readonly
                    value="{{ isset($staff) ? str_replace('<br>', ' ', $staff->user->name) : '' }}">
            </div>
        </div>

        <!-- Employee Type -->
        <div class="col-md-2">
            <div class="form-group">
                <label class="form-label" for="emp_type">Employee Type </label>
                <select class="select2 type_select" id="emp_type" name="emp_type" style="width: 100%;" disabled
                    required>
                    <option value="">Select Type </option>
                    @foreach ($employeeType as $type)
                        <option value="{{ $type->id }}" @if (isset($salary) && $salary->employee_type_id == $type->id) selected @endif>
                            {{ $type->employee_type }}
                        </option>
                    @endforeach
                </select>
                <div id="emp_typeError" class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-lg-4 col-md-3">
            <div class="form-group">
                <label class="form-label" for="name">Salary Period</label>
                <input type="text" id="salaryPeriod" name="salaryPeriod" class="form-control" readonly
                    value="{{ isset($month) ? \Carbon\Carbon::createFromDate($year ?? now()->year, $month ?? now()->month, 1)->format('F Y') : '' }}">
                <input type="hidden" id="salaryMonth" name="salaryMonth" class="form-control"
                    value="{{ isset($month) ? $month : '' }}">
                <input type="hidden" id="salaryYear" name="salaryYear" class="form-control"
                    value="{{ isset($year) ? $year : '' }}">
            </div>
        </div>



    </div>
</div>

<div class="box-header d-flex justify-content-between align-items-center py-2">
    <h4 class="box-title">Salary Details</h4>
</div>

<div class="box-body">
    <div class="table-responsive">
        <table class="table table-bordered text-center" id="itemTable">
            <thead class="bg-dark">
                <tr>
                    <th style="width:5%;">Slno</th>
                    <th style="width:40%;">Pay Head</th>
                    <th style="width:10%;">with effect from </th>
                    <th style="width:20%;">Amount</th>
                </tr>
            </thead>

            @include('payroll.monthlySalary.earnings')
            @include('payroll.monthlySalary.deductions')
            @include('payroll.monthlySalary.additions')
            @include('payroll.monthlySalary.monthlydeductions')
            <tbody>
                <tr class="bt-3 border-primary">
                    <td colspan="3" class="text-end">
                        <h3><b>Salary</b></h3>
                    </td>

                    <td colspan="2">
                        <input type="text" class="form-control text-center form-control-lg text-bold" id="salary"
                            name="salary" readonly placeholder="0.00"
                            value="{{ isset($monthlySalary) ? $monthlySalary->total_earnings : (isset($salary) ? $salary->salary : '') }}">
                        <span class="text-danger" id="salaryError">
                            @error('salary')
                                {{ $message }}
                            @enderror
                        </span>
                    </td>
                </tr>
                <tr>
                    <th colspan="3" class="text-end text-info">CTC</th>
                    <td colspan="2">
                        <input type="text" readonly name="ctc" id="ctc"
                            value="{{ isset($monthlySalary) ? $monthlySalary->ctc : (isset($salary) ? $salary->ctc : '') }}"
                            class="form-control text-center text-bold text-info" placeholder="0.00">
                    </td>
                </tr>
                <tr>
                    <th colspan="3" class="text-end text-info">Net Salary</th>
                    <td colspan="2">
                        <input type="text" readonly name="netsalary" id="netsalary"
                            value="{{ isset($monthlySalary) ? $monthlySalary->total_salary : (isset($salary) ? $salary->netsalary : '') }}"
                            class="form-control text-center text-bold text-info" placeholder="0.00">
                    </td>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Previous Due</th>
                    <td colspan="2">
                        <input type="text" readonly name="previousDue" id="previousDue"
                            value="{{ isset($monthlySalary) ? $monthlySalary->previous_due : (isset($previousBalanceDue) ? $previousBalanceDue : 0) }}"
                            class="form-control text-center text-bold" placeholder="0.00">
                    </td>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Advance Given</th>
                    <td colspan="2">
                        <input type="text" readonly name="advance" id="advance"
                            value="{{ isset($monthlySalary) ? $monthlySalary->advance_given : (isset($currentMonthAdvance) ? $currentMonthAdvance->amount : 0) }}"
                            class="form-control text-center text-bold" placeholder="0.00">
                        <input type="hidden" class="form-control text-center amount" id="advance_id"
                            name="advance_id" placeholder="0.00"
                            value="{{ isset($monthlySalary) ? $monthlySalary->advance_id : (isset($currentMonthAdvance) ? $currentMonthAdvance->id : '') }}"
                            readonly>
                    </td>
                </tr>
                <tr class="bt-3 border-primary">
                    <td colspan="3" class="text-end">
                        <h3><b>Current Month Salary</b></h3>
                    </td>

                    <td colspan="2">
                        <input type="text" class="form-control text-center form-control-lg text-bold"
                            id="monthlySalary" name="monthlySalary" readonly placeholder="0.00"
                            value="{{ isset($monthlySalary) ? $monthlySalary->amount_to_be_paid : 0 }}">
                        <span class="text-danger" id="salaryError">
                            @error('monthlysalary')
                                {{ $message }}
                            @enderror
                        </span>
                    </td>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">
                        <span class="text-bold me-2">Mode of Payment:</span>

                        <!-- Checkbox for Bank Transfer -->
                        <input type="checkbox" class="filled-in chk-col-success" id="medmode_of_payment_bank"
                            {{ isset($monthlySalary) && $monthlySalary->bank > 0 ? 'checked' : '' }}
                            name="medmode_of_payment[]" value="bank">
                        <label class="form-check-label me-2" for="medmode_of_payment_bank">bank</label>
                        <input type="text" name="medbank" id="medbank" class="form-control  w-100 "
                            value="{{ isset($monthlySalary) ? $monthlySalary->bank : 0 }}"
                            style="{{ isset($monthlySalary) && $monthlySalary->bank > 0 ? 'display: inline-block;' : 'display: none;' }}">
                        &nbsp;
                        <!-- Checkbox for Cash -->
                        <input type="checkbox" class="filled-in chk-col-success" id="medmode_of_payment_cash"
                            name="medmode_of_payment[]" value="cash"
                            {{ isset($monthlySalary) && $monthlySalary->cash > 0 ? 'checked' : '' }}>
                        <label class="form-check-label me-2" for="medmode_of_payment_cash">Cash</label>
                        <input type="text" name="medcash" id="medcash" class="form-control  w-100"
                            value="{{ isset($monthlySalary) ? $monthlySalary->cash : 0 }}"
                            style="{{ isset($monthlySalary) && $monthlySalary->cash > 0 ? 'display: inline-block;' : 'display: none;' }}">
                        <span class="text-danger" id="modePaymentError">
                            @error('medmode_of_payment')
                                {{ $message }}
                            @enderror
                        </span>
                    </th>
                    <td colspan="2">
                        <input type="text" name="medamountPaid" id="medamountPaid"
                            class="form-control text-center form-control-lg text-bold"
                            value="{{ isset($monthlySalary) ? $monthlySalary->amount_paid : 0 }}" required readonly>
                        <span id="amountPaidError" class="error-message text-danger">
                            @error('medamountPaid')
                                {{ $message }}
                            @enderror
                        </span>
                    </td>
                </tr>

                <tr>
                    <th colspan="3" class="text-end text-info">Total Paid</th>
                    <td colspan="2">
                        <input type="text" id="total_paid" name="total_paid"
                            class="form-control text-center text-info" placeholder="Total Paid"
                            value="{{ isset($monthlySalary) ? $monthlySalary->amount_paid : 0 }}" readonly>
                    </td>
                </tr>

                <tr>
                    <th colspan="3" class="text-end text-info">Balance Due</th>
                    <td colspan="2">
                        <input type="text" id="balance_due" name="balance_due"
                            class="form-control text-center text-info" placeholder="Balance Due"
                            value="{{ isset($monthlySalary) ? $monthlySalary->balance_due : 0 }}" readonly>
                    </td>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Salary Paid Date</th>
                    <td colspan="2">
                        <input type="date" id="paid_on" name="paid_on" class="form-control text-center"
                            placeholder="Paid date"
                            value="{{ isset($monthlySalary) ? $monthlySalary->paid_on : date('Y-m-d') }}">
                    </td>
                </tr>


            </tbody>
        </table>

        <div>
            <ul>
                <li>
                    Salary/ Gross Salary = Basic Salary + Allowances (DA+HRA+LTA+Others) + Bonus + Reimbursements +
                    incentives
                </li>

                <li>
                    CTC = Gross Pay + Statutory Additions (PF+ESI+Bonus)
                </li>
                <li>
                    Net Salary = Gross Salary - Statutory Deductions (EPF, ESIC, Gratuity) - Income Tax (TDS, PTI) -
                    Deductions(Loss of pay, Other deductions)
                </li>
                <li>
                    Current Month Salary = Net Salary + Previous due - advance given
                </li>
            </ul>
        </div>
    </div>
</div>
