<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class LeaveApplicationRequest extends FormRequest
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
            'leave_type' => 'required|string|max:255',
            'leave_from' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'leave_to' => 'required|date|date_format:Y-m-d|after_or_equal:leave_from',
            'reason' => 'required|string|max:1000',
        ];
    }
}
