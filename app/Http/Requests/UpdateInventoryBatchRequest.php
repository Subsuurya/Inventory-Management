<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['sometimes', 'integer', 'exists:products,id'],
            'supplier_id' => ['sometimes', 'integer', 'exists:suppliers,id'],
            'purchase_order_id' => ['sometimes', 'nullable', 'integer', 'exists:purchase_orders,id'],
            'batch_number' => ['sometimes', 'string', 'max:255'],
            'quantity_received' => ['sometimes', 'numeric', 'min:0.01'],
            'quantity_remaining' => ['sometimes', 'numeric', 'min:0'],
            'received_date' => ['sometimes', 'date'],
        ];
    }
}
