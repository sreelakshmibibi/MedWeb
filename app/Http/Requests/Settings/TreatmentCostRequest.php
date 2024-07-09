<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TreatmentCostRequest extends FormRequest
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
        $treatmentCostId = $this->route('treatment_cost');

        return [
            'treat_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('treatment_types')->ignore($treatmentCostId),
            ],
            'treat_cost' => [
                'required',
                'numeric',
                'min:0',
            ],
            'discount_percentage' => [
                'nullable',
                'numeric',
                'between:0,100',
            ],
            'discount_from' => [
                'nullable',
                'date',
                'before_or_equal:discount_to',
            ],
            'discount_to' => [
                'nullable',
                'date',
                'after_or_equal:discount_from',
            ],
            'status' => 'required|string|size:1',
        ];
    }

    public function messages()
    {
        return [
            'treat_name.required' => 'The treatment name is required.',
            'treat_name.string' => 'The treatment name must be a string.',
            'treat_name.max' => 'The treatment name may not be greater than 255 characters.',
            'treat_cost.required' => 'The treatment cost is required.',
            'treat_cost.numeric' => 'The treatment cost must be a number.',
            'treat_cost.min' => 'The treatment cost must be at least 0.',
            'discount_percentage.numeric' => 'The discount percentage must be a number.',
            'discount_percentage.between' => 'The discount percentage must be between 0 and 100.',
            'discount_from.date' => 'The discount start date must be a valid date.',
            'discount_from.before_or_equal' => 'The discount start date must be before or equal to the end date.',
            'discount_to.date' => 'The discount end date must be a valid date.',
            'discount_to.after_or_equal' => 'The discount end date must be after or equal to the start date.',
            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
            'status.size' => 'The status must be a single character.',
        ];
    }
}
