<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class ComboOfferRequest extends FormRequest
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
            'offer_amount' => 'required|numeric|min:0', // Must be a number and cannot be negative
            'treatments' => 'required|array|min:1', // Must be an array and contain at least one treatment
            'treatments.*' => 'exists:treatment_types,id', // Each treatment must exist in the treatment_types table
            'status' => 'required|in:Y,N', // Must be either 'Y' or 'N'
            'offer_from' => 'required|date|before_or_equal:offer_to', // Must be a valid date and before or equal to offer_to
            'offer_to' => 'required|date|after_or_equal:offer_from', // Must be a valid date and after or equal to offer_from
        ];
    }

    public function messages()
    {
        return [
            'offer_amount.required' => 'Offer amount is required.',
            'offer_amount.numeric' => 'Offer amount must be a number.',
            'offer_amount.min' => 'Offer amount must be at least 0.',
            'treatments.required' => 'At least one treatment must be selected.',
            'treatments.array' => 'Treatments must be an array.',
            'treatments.min' => 'At least one treatment must be selected.',
            'treatments.*.exists' => 'Selected treatment is invalid.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either "Y" or "N".',
            'offer_from.required' => 'Offer start date is required.',
            'offer_from.date' => 'Offer start date must be a valid date.',
            'offer_from.before_or_equal' => 'Offer start date must be before or equal to the end date.',
            'offer_to.required' => 'Offer end date is required.',
            'offer_to.date' => 'Offer end date must be a valid date.',
            'offer_to.after_or_equal' => 'Offer end date must be after or equal to the start date.',
        ];
    }
}
