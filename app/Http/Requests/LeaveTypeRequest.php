<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules()
    {
        $rules = [
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'duration_type' => 'required|in:day,month',
            'payment_status' => 'required|in:paid,unpaid',
            'status' => 'required|in:Y,N',
        ];

        // Add rules for the update scenario
        if ($this->isMethod('post') && $this->routeIs('settings.leaveType.update')) {
            $rules['edit_leaveType_id'] = 'required|exists:leave_types,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'type.required' => 'Leave Type is required.',
            'duration.required' => 'Duration is required.',
            'duration_type.required' => 'Duration Type is required.',
            'payment_status.required' => 'Payment status is required.',
            'status.required' => 'Status is required.',
        ];
    }
}
