<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuotationItemsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->access_level === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items.*.id' => 'exists:quotation_items',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:1',
            'items.*.measurement_unit' => 'required|max:255',
            'items.*.status' => [
                'required',
                Rule::in(['available', 'unavailable']),
            ],
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
            'items.*.price' => [
                'required' => 'Price field is required.',
                'numeric' => 'Price field must be numeric.',
                'min' => 'Price field must be at least 1.',
            ],
            'items.*.measurement_unit' => [
                'required' => 'Measurement unit field is required.',
                'max' => 'Measurement unit field exceeded the limit.',
            ],
        ];
    }
}
