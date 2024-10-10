<?php

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseCategoryRequest extends FormRequest
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
            'category' => 'required|string|max:255',
            'status' => 'required|in:Y,N', // 'Y' for active, 'N' for inactive
        ];
    }

    public function messages(): array
    {
        return [
            'category.required' => 'The category name is required.',
            'status.required' => 'The status is required.',
            'status.in' => 'The status must be either Y or N.',
        ];
    }
}
