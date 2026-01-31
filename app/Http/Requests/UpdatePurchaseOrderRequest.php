<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => ['sometimes', 'integer', 'exists:suppliers,id'],
            'order_date' => ['sometimes', 'date'],
            'status' => ['sometimes', 'string', 'in:pending,received,cancelled'],
            'items' => ['sometimes', 'array', 'min:1'],
            'items.*.product_id' => ['required_with:items', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required_with:items', 'numeric', 'min:0.01'],
            'items.*.cost_price' => ['required_with:items', 'numeric', 'min:0'],
        ];
    }
}
