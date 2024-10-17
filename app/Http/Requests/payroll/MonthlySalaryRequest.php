<?php

namespace App\Http\Requests\payroll;

use Illuminate\Foundation\Http\FormRequest;

class MonthlySalaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:staff_profiles,user_id',
            'totalWorkingDays' => 'required|numeric|min:0',
            'paid_days' => 'required|numeric|min:0',
            'unpaid_days' => 'required|numeric|min:0',
            'partially_paid_days' => 'required|numeric|min:0',
            'salary_id' => 'required|exists:salaries,id',
            'basic_salary' => 'required|numeric|min:0',
            'earningstotal' => 'required|numeric|min:0',
            'monthlyDeductionsTotal' => 'required|numeric|min:0',
            'incentive' => 'nullable|numeric|min:0',
            'lossOfPay' => 'nullable|numeric|min:0',
            'monthlyDeduction' => 'nullable|numeric|min:0',
            'deductionReason' => 'nullable|string|max:255',
            'netsalary' => 'required|numeric|min:0',
            'ctc' => 'required|numeric|min:0',
            'previousDue' => 'nullable|numeric|min:0',
            'advance' => 'nullable|numeric|min:0',
            'advance_id' => 'nullable|exists:salary_advances,id',
            'monthlySalary' => 'required|numeric|min:0',
            'medamountPaid' => 'required|numeric|min:0',
            'balance_due' => 'nullable|numeric|min:0',
            'paid_on' => 'nullable|date',
            'medcash' => 'nullable|numeric|min:0',
            'medbank' => 'nullable|numeric|min:0',
            'month' => 'required|numeric', 
            'year' => 'required|integer|min:2000|max:' . date('Y'), 
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'The employee ID is required.',
            'user_id.exists' => 'The selected employee ID does not exist.',
            'totalWorkingDays.required' => 'Total working days are required.',
            'totalWorkingDays.numeric' => 'Total working days must be a number.',
            'paid_days.required' => 'Paid days are required.',
            'paid_days.numeric' => 'Paid days must be a number.',
            'unpaid_days.required' => 'Unpaid days are required.',
            'unpaid_days.numeric' => 'Unpaid days must be a number.',
            'partially_paid_days.required' => 'Partially paid days are required.',
            'partially_paid_days.numeric' => 'Partially paid days must be a number.',
            'salary_id.required' => 'Salary ID is required.',
            'salary_id.exists' => 'The selected salary ID does not exist.',
            'basic_salary.required' => 'Basic salary is required.',
            'basic_salary.numeric' => 'Basic salary must be a number.',
            'earningstotal.required' => 'Total earnings are required.',
            'earningstotal.numeric' => 'Total earnings must be a number.',
            'monthlyDeductionsTotal.required' => 'Total monthly deductions are required.',
            'monthlyDeductionsTotal.numeric' => 'Total monthly deductions must be a number.',
            'incentive.required' => 'Incentive is required.',
            'incentive.numeric' => 'Incentive must be a number.',
            'lossOfPay.numeric' => 'Loss of pay must be a number.',
            'monthlyDeduction.numeric' => 'Monthly deduction must be a number.',
            'deductionReason.string' => 'Deduction reason must be a string.',
            'deductionReason.max' => 'Deduction reason must not exceed 255 characters.',
            'netsalary.required' => 'Net salary is required.',
            'netsalary.numeric' => 'Net salary must be a number.',
            'ctc.required' => 'CTC is required.',
            'ctc.numeric' => 'CTC must be a number.',
            'previousDue.numeric' => 'Previous due must be a number.',
            'advance.numeric' => 'Advance must be a number.',
            'advance_id.exists' => 'The selected advance ID does not exist.',
            'monthlySalary.required' => 'Monthly salary is required.',
            'monthlySalary.numeric' => 'Monthly salary must be a number.',
            'medamountPaid.numeric' => 'Medical amount paid must be a number.',
            'balance_due.numeric' => 'Balance due must be a number.',
            'paid_on.date' => 'Paid on must be a valid date.',
            'medcash.numeric' => 'Medical cash must be a number.',
            'medbank.numeric' => 'Medical bank must be a number.',
            'month.required' => 'Month is required.',
            'month.numeric' => 'Month must be a number.',
            'year.required' => 'Year is required.',
            'year.integer' => 'Year must be an integer.',
            'year.min' => 'Year must be at least 2000.',
            'year.max' => 'Year must not exceed the current year.',
        ];
    }
}
