<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'route_name' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'order_no' => 'required|integer|min:0',
            'parent_id' => 'nullable|exists:menu_items,id',
            'status' => 'required|string|max:5',
            'roles' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'url.required' => 'The URL field is required.',
            'url.string' => 'The URL must be a string.',
            'url.max' => 'The URL may not be greater than 255 characters.',
            'route_name.required' => 'The route name field is required.',
            'route_name.string' => 'The route name must be a string.',
            'route_name.max' => 'The route name may not be greater than 255 characters.',
            'icon.required' => 'The icon field is required.',
            'icon.string' => 'The icon must be a string.',
            'icon.max' => 'The icon may not be greater than 255 characters.',
            'order_no.required' => 'The order number field is required.',
            'order_no.integer' => 'The order number must be an integer.',
            'order_no.min' => 'The order number must be at least 0.',
            'parent_id.exists' => 'The selected parent menu item does not exist.',
            'status.required' => 'The status field is required.',
            'status.string' => 'The status must be a string.',
            'status.max' => 'The status may not be greater than 5 characters.',
            'roles.required' => 'At least one role is required.',
            'roles.array' => 'The roles field must be an array.',
        ];
    }
}
