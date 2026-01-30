<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $productId = is_object($product) ? $product->getKey() : $product;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'sku_part_number' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('products', 'sku_part_number')->ignore($productId),
            ],
            'category' => ['sometimes', 'nullable', 'string', 'max:255'],
            'unit' => ['sometimes', 'nullable', 'string', 'max:50'],
        ];
    }
}

