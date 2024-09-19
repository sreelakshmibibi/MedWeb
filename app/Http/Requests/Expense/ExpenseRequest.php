<?php

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseRequest extends FormRequest
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
    public function rules()
    {
        return [
            'billdate' => 'required|date', // Ensures billdate is a valid date
            'name' => [
                'required',
                'string',
                'max:255',
                // Rule::unique('expenses')->ignore($this->expense), // Ignores the current record during update
            ],
            'amount' => 'required|numeric|min:0', // Validates that amount is a non-negative number
            'category' => 'required|string|max:255', // Assuming category is a string
            'billfile.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Allow files with specific extensions and size limit
            'status' => 'required|string|size:1', // Assuming status is a single character
        ];
    }

    public function messages()
    {
        return [
            'billdate.required' => 'The bill date is required.',
            'billdate.date' => 'The bill date must be a valid date.',
            'name.required' => 'The name is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'name.unique' => 'The name has already been taken for this expense.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.',
            'category.required' => 'The category is required.',
            'category.string' => 'The category must be a string.',
            'category.max' => 'The category may not be greater than 255 characters.',
            'billfile.file' => 'The bill file must be a valid file.',
            'billfile.mimes' => 'The bill file must be a file of type: pdf, jpg, jpeg, png.',
            'billfile.max' => 'The bill file may not be greater than 2 MB.',
            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
            'status.size' => 'The status must be exactly 1 character.',
        ];
    }
}
