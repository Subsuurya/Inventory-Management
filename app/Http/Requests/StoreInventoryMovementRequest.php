<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'inventory_batch_id' => ['required', 'integer', 'exists:inventory_batches,id'],
            'movement_type' => ['required', 'string', 'in:IN,OUT,ADJUSTMENT'],
            'quantity' => ['required', 'numeric', 'not_in:0'],
            'reference_type' => ['nullable', 'string', 'max:255'],
            'reference_id' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
