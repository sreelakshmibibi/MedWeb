<?php

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;

class DueBillRequest extends FormRequest
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
            'dueTotal' => 'required|numeric|min:0',
            'duemode_of_payment' => 'required|array',
            'dueAmountPaid' => 'required|numeric|min:0',

            'duegpay' => 'nullable|required_if:duemode_of_payment,gpay|numeric|min:0',
            'duecash' => 'nullable|required_if:duemode_of_payment,cash|numeric|min:0',
            'duecard' => 'nullable|required_if:duemode_of_payment,card|numeric|min:0',
            'duemachine' => 'nullable|required_if:duemode_of_payment,card|exists:card_pays,id',

            'dueBalanceToGiveBack' => 'nullable|numeric|min:0',
            'dueBalance_given' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'dueTotal.required' => 'The total due amount is required.',
            'dueTotal.numeric' => 'The total due amount must be a valid number.',
            'dueAmountPaid.required' => 'The amount paid is required.',
            'dueAmountPaid.numeric' => 'The amount paid must be a valid number.',
            'duemode_of_payment.required' => 'Please select at least one mode of payment.',
            'duegpay.required_if' => 'Please enter the GPay amount if GPay is selected.',
            'duecash.required_if' => 'Please enter the Cash amount if Cash is selected.',
            'duecard.required_if' => 'Please enter the Card amount if Card is selected.',
            'duemachine.required_if' => 'Please select a valid card machine if Card is selected.',
            'dueBalanceToGiveBack.numeric' => 'The balance to give back must be a valid number.',
            'dueBalance_given.boolean' => 'The balance given status must be true or false.',
        ];
    }
}
