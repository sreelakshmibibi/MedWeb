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
                <label class="form-label" for="emp_type">Employee Type <span class="text-danger">
                        *</span></label>
                <select class="select2 type_select" id="emp_type" name="emp_type" style="width: 100%;" required
                    {{ $mode === 'view' ? 'disabled' : '' }}>
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

        <!-- Casual Leave -->
        {{-- <div class="col-md-3 col-lg-2">
            <div class="form-group">
                <label class="form-label" for="casual_leaves">Total Casual Leaves <span class="text-danger">
                        *</span></label>
                <input type="number" id="casual_leaves" name="casual_leaves" class="form-control"
                    placeholder="Enter leaves per month" required {{ $mode === 'view' ? 'readonly' : '' }}
                    value="{{ isset($employeeLeave) ? $employeeLeave->casual_leave_monthly : '' }}">
                <div id="casual_leavesError" class="invalid-feedback"></div>
            </div>
        </div> --}}

        <!-- Sick Leave -->
        {{-- <div class="col-md-3 col-lg-2">
            <div class="form-group">
                <label class="form-label" for="sick_leaves">Total Sick Leaves <span class="text-danger">
                        *</span></label>
                <input type="number" id="sick_leaves" name="sick_leaves" class="form-control"
                    placeholder="Enter leaves per month" required {{ $mode === 'view' ? 'readonly' : '' }}
                    value="{{ isset($employeeLeave) ? $employeeLeave->sick_leave_monthly : '' }}">
                <div id="sick_leavesError" class="invalid-feedback"></div>
            </div>
        </div> --}}

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

            @include('payroll.salary.earnings')
            @include('payroll.salary.deductions')
            @include('payroll.salary.additions')

            <tbody>
                <tr class="bt-3 border-primary">
                    <td colspan="3" class="text-end">
                        <h3><b>Salary</b></h3>
                    </td>

                    <td colspan="2">
                        <input type="text" class="form-control text-center form-control-lg text-bold" id="salary"
                            name="salary" readonly placeholder="0.00"
                            value="{{ isset($salary) ? $salary->salary : '' }}">
                        <span class="text-danger" id="salaryError">
                            @error('salary')
                                {{ $message }}
                            @enderror
                        </span>
                    </td>
                </tr>

                <tr>
                    <th colspan="3" class="text-end text-info">Net Salary</th>
                    <td colspan="2">
                        <input type="text" readonly name="netsalary" id="netsalary"
                            value="{{ isset($salary) ? $salary->netsalary : '' }}"
                            class="form-control text-center text-bold text-info" placeholder="0.00">
                    </td>
                </tr>
                <tr>
                    <th colspan="3" class="text-end text-info">CTC</th>
                    <td colspan="2">
                        <input type="text" readonly name="ctc" id="ctc"
                            value="{{ isset($salary) ? $salary->ctc : '' }}"
                            class="form-control text-center text-bold text-info" placeholder="0.00">
                    </td>
                </tr>
            </tbody>
        </table>

        <div>
            <ul>
                <li>
                    Salary/ Gross Salary = Basic Salary + Allowances (DA+HRA+LTA+Others) + Bonus + Reimbursements
                </li>
                <li>
                    Net Salary = Gross Salary - Statutory Deductions (EPF, ESIC, Gratuity) - Income Tax (TDS, PTI)
                </li>
                <li>
                    CTC = Gross Pay + Statutory Additions (PF+ESI+Bonus)
                </li>
            </ul>
        </div>
    </div>
</div>
