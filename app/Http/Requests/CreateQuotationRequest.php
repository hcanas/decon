<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuotationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'nullable|max:255|exists:customers',
            'email' => 'nullable|max:255|required_without:code',
            'contact_number' => 'nullable|max:255|required_with:email',
            'viber_id' => 'nullable',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products',
            'items.*.qty' => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'items.*.qty' => [
                'required' => 'Qty field is required.',
                'numeric' => 'Qty field must be numeric.',
                'min' => 'Qty field must be at least 1.',
            ],
        ];
    }
}
