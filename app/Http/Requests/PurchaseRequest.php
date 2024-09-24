<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|size:10',
            'address' => 'required|string',
            'gst_no' => 'required|string|max:15',
            'invoice_no' => 'required|string|max:50',
            'invoice_date' => 'required|date',
            'billfile.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Allow files with specific extensions and size limit
            'branch_id' => 'required|string|max:255',
            'category' => 'required|string|size:1',
            'item.*' => 'required|string|max:255',
            'price.*' => 'required|numeric|min:0',
            'quantity.*' => 'required|numeric|min:0',
            'itemAmount.*' => 'required|numeric|min:0',
            'itemtotal' => 'required|numeric|min:0',
            'deliverycharge' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'currentbilltotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'previousOutStanding' => 'nullable|numeric|min:0',
            'grandTotal' => 'required|numeric|min:0',
            // 'itemmode_of_payment.*' => 'required|string|max:255',
            // 'itemAmountPaid' => 'required|numeric|min:0',
            // 'consider_for_next_payment' => 'nullable|string|max:255',
            // 'balance' => 'required|numeric|min:0',
            // 'itemBalance_given' => 'nullable|string|max:255',
            // 'itemBalanceToGiveBack' => 'required|numeric|min:0',
        ];

        // Conditionally add rules based on the category value
        if ($this->input('category') !== 'C') {
            $rules = array_merge($rules, [
                'itemmode_of_payment.*' => 'required|string|max:255',
                'itemAmountPaid' => 'required|numeric|min:0',
                'consider_for_next_payment' => 'nullable|string|max:255',
                'balance' => 'required|numeric|min:0',
                'itemBalance_given' => 'nullable|string|max:255',
                'itemBalanceToGiveBack' => 'required|numeric|min:0',
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',

            'phone.required' => 'The phone field is required.',
            'phone.string' => 'The phone must be a string.',
            'phone.size' => 'The phone must be exactly 10 characters.',

            'address.required' => 'The address field is required.',
            'address.string' => 'The address must be a string.',

            'gst_no.max' => 'The GST number may not be greater than 15 characters.',

            'invoice_no.required' => 'The invoice number field is required.',
            'invoice_no.max' => 'The invoice number may not be greater than 50 characters.',

            'invoice_date.required' => 'The invoice date field is required.',
            'invoice_date.date' => 'The invoice date must be a valid date.',

            'billfile.*.file' => 'Each bill file must be a valid file.',
            'billfile.*.mimes' => 'Each bill file must be a file of type: pdf, jpg, jpeg, png.',
            'billfile.*.max' => 'Each bill file may not be greater than 2MB.',

            'branch_id.required' => 'The branch ID field is required.',
            'branch_id.max' => 'The branch ID may not be greater than 255 characters.',

            'category.required' => 'The category field is required.',
            'category.size' => 'The category must be exactly 1 character.',

            'item.*.required' => 'Each item field is required.',
            'item.*.max' => 'Each item may not be greater than 255 characters.',

            'price.*.required' => 'Each price field is required.',
            'price.*.numeric' => 'Each price must be a number.',
            'price.*.min' => 'Each price must be at least 0.',

            'quantity.*.required' => 'Each quantity field is required.',
            'quantity.*.numeric' => 'Each quantity must be a number.',
            'quantity.*.min' => 'Each quantity must be at least 0.',

            'itemAmount.*.required' => 'Each item amount field is required.',
            'itemAmount.*.numeric' => 'Each item amount must be a number.',
            'itemAmount.*.min' => 'Each item amount must be at least 0.',

            'itemtotal.required' => 'The item total field is required.',
            'itemtotal.numeric' => 'The item total must be a number.',
            'itemtotal.min' => 'The item total must be at least 0.',

            'deliverycharge.required' => 'The delivery charge field is required.',
            'deliverycharge.numeric' => 'The delivery charge must be a number.',
            'deliverycharge.min' => 'The delivery charge must be at least 0.',

            'tax.required' => 'The tax field is required.',
            'tax.numeric' => 'The tax must be a number.',
            'tax.min' => 'The tax must be at least 0.',

            'currentbilltotal.required' => 'The current bill total field is required.',
            'currentbilltotal.numeric' => 'The current bill total must be a number.',
            'currentbilltotal.min' => 'The current bill total must be at least 0.',

            'discount.required' => 'The discount field is required.',
            'discount.numeric' => 'The discount must be a number.',
            'discount.min' => 'The discount must be at least 0.',

            'previousOutStanding.required' => 'The previous outstanding field is required.',
            'previousOutStanding.numeric' => 'The previous outstanding must be a number.',
            'previousOutStanding.min' => 'The previous outstanding must be at least 0.',

            'grandTotal.required' => 'The grand total field is required.',
            'grandTotal.numeric' => 'The grand total must be a number.',
            'grandTotal.min' => 'The grand total must be at least 0.',

            'itemmode_of_payment.*.required' => 'Each payment mode field is required.',
            'itemmode_of_payment.*.max' => 'Each payment mode may not be greater than 255 characters.',

            'itemAmountPaid.required' => 'The amount paid field is required.',
            'itemAmountPaid.numeric' => 'The amount paid must be a number.',
            'itemAmountPaid.min' => 'The amount paid must be at least 0.',

            'consider_for_next_payment.required' => 'The consideration for next payment field is required.',
            'consider_for_next_payment.max' => 'The consideration for next payment may not be greater than 255 characters.',

            'balance.required' => 'The balance field is required.',
            'balance.numeric' => 'The balance must be a number.',
            'balance.min' => 'The balance must be at least 0.',

            'itemBalance_given.required' => 'The item balance given field is required.',
            'itemBalance_given.max' => 'The item balance given may not be greater than 255 characters.',

            'itemBalanceToGiveBack.required' => 'The balance to give back field is required.',
            'itemBalanceToGiveBack.numeric' => 'The balance to give back must be a number.',
            'itemBalanceToGiveBack.min' => 'The balance to give back must be at least 0.',
        ];
    }

}
