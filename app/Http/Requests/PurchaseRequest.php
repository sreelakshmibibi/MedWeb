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
            'supplier.name' => 'required|string|max:255',
            'supplier.phone' => 'required|string|size:10',
            'supplier.address' => 'required|string',
            'supplier.gst_no' => 'required|string|max:15',
            'invoice_no' => 'required|string|max:50',
            'invoice_date' => 'required|date',
            'billfile.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
            'previousOutStanding' => 'nullable|numeric',
            'grandTotal' => 'required|numeric',
        ];

        // Conditionally add rules based on the category value
        if ($this->input('category') !== 'C') {
            $rules = array_merge($rules, [
                'itemmode_of_payment.*' => 'required|string|max:255',
                'itemgpay' => 'nullable|numeric|min:0',
                'itemcash' => 'nullable|numeric|min:0',
                'itemcard' => 'nullable|numeric|min:0',
                'itemAmountPaid' => 'required|numeric|min:0',
                'consider_for_next_payment' => 'nullable|string|max:255',
                'balance' => 'nullable|numeric|min:0',
                'itemBalance_given' => 'nullable|string|max:255',
                'itemBalanceToGiveBack' => 'nullable|numeric|min:0',
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'supplier.name.required' => 'The supplier name is required.',
            'supplier.phone.required' => 'The supplier phone number is required.',
            'supplier.phone.size' => 'The supplier phone number must be exactly 10 characters.',
            'supplier.address.required' => 'The supplier address is required.',
            'supplier.gst_no.required' => 'The GST number is required.',
            'supplier.gst_no.max' => 'The GST number may not exceed 15 characters.',
            'invoice_no.required' => 'The invoice number is required.',
            'invoice_no.max' => 'The invoice number may not exceed 50 characters.',
            'invoice_date.required' => 'The invoice date is required.',
            'invoice_date.date' => 'The invoice date must be a valid date.',
            'billfile.*.file' => 'Each bill file must be a valid file.',
            'billfile.*.mimes' => 'Each bill file must be a file of type: pdf, jpg, jpeg, png.',
            'billfile.*.max' => 'Each bill file may not exceed 2MB.',
            'branch_id.required' => 'The branch ID is required.',
            'branch_id.max' => 'The branch ID may not exceed 255 characters.',
            'category.required' => 'The category is required.',
            'category.size' => 'The category must be exactly 1 character.',
            'item.*.required' => 'Each item field is required.',
            'item.*.max' => 'Each item may not exceed 255 characters.',
            'price.*.required' => 'Each price field is required.',
            'price.*.numeric' => 'Each price must be a number.',
            'price.*.min' => 'Each price must be at least 0.',
            'quantity.*.required' => 'Each quantity field is required.',
            'quantity.*.numeric' => 'Each quantity must be a number.',
            'quantity.*.min' => 'Each quantity must be at least 0.',
            'itemAmount.*.required' => 'Each item amount field is required.',
            'itemAmount.*.numeric' => 'Each item amount must be a number.',
            'itemAmount.*.min' => 'Each item amount must be at least 0.',
            'itemtotal.required' => 'The item total is required.',
            'itemtotal.numeric' => 'The item total must be a number.',
            'itemtotal.min' => 'The item total must be at least 0.',
            'currentbilltotal.required' => 'The current bill total is required.',
            'currentbilltotal.numeric' => 'The current bill total must be a number.',
            'currentbilltotal.min' => 'The current bill total must be at least 0.',
            'grandTotal.required' => 'The grand total is required.',
            'grandTotal.numeric' => 'The grand total must be a number.',
            'grandTotal.min' => 'The grand total must be at least 0.',
        ];
    }
}
