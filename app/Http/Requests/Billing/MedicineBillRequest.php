<?php

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;

class MedicineBillRequest extends FormRequest
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

            'patientId' => 'required|integer|exists:patient_profiles,patient_id',
            'medicine_checkbox.*' => 'required|integer|exists:prescriptions,id',
            'quantity.*' => 'nullable|numeric|min:0',
            'rate.*' => 'numeric|min:0',
            //'unitcost.*' => 'numeric|min:0',
            'total' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'grandTotal' => 'required|numeric|min:0',
            'mode_of_payment' => 'required|in:gpay,card,cash',
            'amountPaid' => 'required|numeric|min:0',
            'balanceToGiveBack' => 'nullable|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'appointmentId.required' => 'The appointment ID is required.',
            'patientId.required' => 'The patient ID is required.',
            'medicine_checkbox.*.required' => 'Please select at least one medicine.',
            'quantity.*.numeric' => 'The quantity must be a number.',
            'rate.*.numeric' => 'The rate must be a number.',
            'total.required' => 'The total amount is required.',
            'tax.required' => 'The tax amount is required.',
            'grandTotal.required' => 'The grand total amount is required.',
            'mode_of_payment.required' => 'The mode of payment is required.',
            'amountPaid.required' => 'The amount paid is required.',
            'balanceToGiveBack.numeric' => 'The balance to give back must be a number.',
        ];
    }
}
