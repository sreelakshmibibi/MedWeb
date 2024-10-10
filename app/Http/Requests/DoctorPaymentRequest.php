<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorPaymentRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:staff_profiles,user_id', // Ensure user_id exists in the staff_profiles table
            'amount' => 'required|numeric|min:0', // Amount must be a positive number
            'paid_on' => 'required|date', // Paid on must be a valid date
            'remarks' => 'nullable|string|max:255', // Remarks can be a string with a max length of 255 characters
        ];
    }

    /**
     * Customize the error messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'user_id.required' => 'The user ID is required.',
            'user_id.integer' => 'The user ID must be an integer.',
            'user_id.exists' => 'The selected user ID is invalid.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.',
            'paid_on.required' => 'The paid on date is required.',
            'paid_on.date' => 'The paid on date must be a valid date.',
            'remarks.max' => 'The remarks may not be greater than 255 characters.',
        ];
    }
}
