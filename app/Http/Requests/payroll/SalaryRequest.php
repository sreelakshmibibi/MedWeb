<?php

namespace App\Http\Requests\payroll;

use Illuminate\Foundation\Http\FormRequest;

class SalaryRequest extends FormRequest
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
        $rules = [
            'emp_type' => 'required|string|max:255',
            'casual_leaves' => 'required|numeric|min:0',
            'sick_leaves' => 'required|numeric|min:0',
            'earningstotal' => 'required|numeric|min:0',
            'earningseffect_date.*' => 'required|date',
            'earningsamount.*' => 'required|numeric|min:0',
            'deductionstotal' => 'required|numeric|min:0',
            'deductionseffect_date.*' => 'required|date',
            'deductionsamount.*' => 'required|numeric|min:0',
            'additionstotal' => 'required|numeric|min:0',
            'additionseffect_date.*' => 'required|date',
            'additionsamount.*' => 'required|numeric|min:0',
            'salary' => 'required|numeric|min:0',
            'netsalary' => 'required|numeric|min:0',
            'ctc' => 'required|numeric|min:0',
        ];
        return $rules;
    }

    public function messages(): array
    {
        return [
            'emp_type.required' => 'The employment type is required.',
            'casual_leaves.required' => 'The total casual leaves is required.',
            'casual_leaves.numeric' => 'Total casual leaves must be a number.',
            'casual_leaves.min' => 'Total casual leaves must be at least 0.',
            'sick_leaves.required' => 'The total sick leaves is required.',
            'sick_leaves.numeric' => 'Total sick leaves must be a number.',
            'sick_leaves.min' => 'Total sick leaves must be at least 0.',
            'earningsamount.*.required' => 'Each earnings amount field is required.',
            'earningsamount.*.numeric' => 'Each earnings amount must be a number.',
            'earningsamount.*.min' => 'Each earnings amount must be at least 0.',
            'earningseffect_date.required' => 'The date is required.',
            'earningseffect_date.date' => 'The date must be a valid date.',
            'additionsamount.*.required' => 'Each additions amount field is required.',
            'additionsamount.*.numeric' => 'Each additions amount must be a number.',
            'additionsamount.*.min' => 'Each additions amount must be at least 0.',
            'additionseffect_date.required' => 'The date is required.',
            'additionseffect_date.date' => 'The date must be a valid date.',
            'deductionsamount.*.required' => 'Each deductions amount field is required.',
            'deductionsamount.*.numeric' => 'Each deductions amount must be a number.',
            'deductionsamount.*.min' => 'Each deductions amount must be at least 0.',
            'deductionseffect_date.required' => 'The date is required.',
            'deductionseffect_date.date' => 'The date must be a valid date.',
            'salary.required' => 'The salary is required.',
            'salary.numeric' => 'The salary must be a number.',
            'salary.min' => 'The salary must be at least 0.',
            'netsalary.required' => 'The net salary is required.',
            'netsalary.numeric' => 'The net salary must be a number.',
            'netsalary.min' => 'The net salary must be at least 0.',
            'ctc.required' => 'The ctc is required.',
            'ctc.numeric' => 'The ctc must be a number.',
            'ctc.min' => 'The ctc must be at least 0.',
        ];
    }
}
