<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'sale_date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.inventory_batch_id' => ['required', 'integer', 'exists:inventory_batches,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.selling_price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
