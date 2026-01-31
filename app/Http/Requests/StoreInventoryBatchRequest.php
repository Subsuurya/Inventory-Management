<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'purchase_order_id' => ['nullable', 'integer', 'exists:purchase_orders,id'],
            'batch_number' => ['required', 'string', 'max:255'],
            'quantity_received' => ['required', 'numeric', 'min:0.01'],
            'quantity_remaining' => ['required', 'numeric', 'min:0'],
            'received_date' => ['required', 'date'],
        ];
    }
}
