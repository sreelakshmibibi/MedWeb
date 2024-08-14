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
            'appointmentId' => 'required|string',
            'patientId' => 'required|exists:patient_profiles,patient_id',
            'total' => 'required|numeric|min:0',
            'medtax' => 'required|numeric|min:0',
            'grandTotal' => 'required|numeric|min:0',
            'medmode_of_payment' => 'required|array',
            'medmode_of_payment.*' => 'in:gpay,cash,card',
            'medgpay' => 'nullable|numeric|min:0',
            'medcash' => 'nullable|numeric|min:0',
            'medcard' => 'nullable|numeric|min:0',
            'medmachine' => 'nullable|exists:card_pays,id', // Ensure 'card_pays' is the correct table
            'medamountPaid' => 'required|numeric|min:0',
            'medbalanceToGiveBack' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {

        return [
            'appointmentId.required' => 'The appointment ID is required.',
            'patientId.required' => 'The patient ID is required.',
            'patientId.exists' => 'The selected patient ID is invalid.',
            'total.required' => 'The total amount is required.',
            'total.numeric' => 'The total amount must be a number.',
            'total.min' => 'The total amount must be at least 0.',
            'medtax.required' => 'The tax percentage is required.',
            'medtax.numeric' => 'The tax percentage must be a number.',
            'medtax.min' => 'The tax percentage must be at least 0.',
            'grandTotal.required' => 'The grand total amount is required.',
            'grandTotal.numeric' => 'The grand total amount must be a number.',
            'grandTotal.min' => 'The grand total amount must be at least 0.',
            'medmode_of_payment.required' => 'At least one mode of payment is required.',
            'medmode_of_payment.array' => 'The mode of payment must be an array.',
            'medmode_of_payment.*.in' => 'Invalid payment method selected.',
            'medgpay.numeric' => 'The Gpay amount must be a number.',
            'medgpay.min' => 'The Gpay amount must be at least 0.',
            'medcash.numeric' => 'The cash amount must be a number.',
            'medcash.min' => 'The cash amount must be at least 0.',
            'medcard.numeric' => 'The card amount must be a number.',
            'medcard.min' => 'The card amount must be at least 0.',
            'medmachine.exists' => 'The selected machine is invalid.',
            'medamountPaid.required' => 'The amount paid is required.',
            'medamountPaid.numeric' => 'The amount paid must be a number.',
            'medamountPaid.min' => 'The amount paid must be at least 0.',
            'medbalanceToGiveBack.required' => 'The balance to give back is required.',
            'medbalanceToGiveBack.numeric' => 'The balance to give back must be a number.',
            'medbalanceToGiveBack.min' => 'The balance to give back must be at least 0.',
        ];
    }
}
