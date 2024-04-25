<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'image' => 'sometimes|nullable|file|image',
            'brand' => 'sometimes|nullable|max:255',
            'name' => ['sometimes', 'required', 'max:255', Rule::unique('products')->ignore($this->route('product'))],
            'description' => 'sometimes|required|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'measurement_unit' => 'sometimes|required|max:255',
            'product_category' => 'sometimes|required|max:255',
            'status' => [
                'sometimes',
                Rule::in(['available', 'unavailable', 'archived']),
            ],
        ];
    }
}
