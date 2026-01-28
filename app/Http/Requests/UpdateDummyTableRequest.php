<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDummyTableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('dummy_table');

        return [
            // Define validation rules for updating a dummy_table record
            // 'name' => ['sometimes', 'string', 'max:255'],
            // Example for unique field:
            // 'email' => ['sometimes', 'email', 'unique:dummy_tables,email,' . $id],
        ];
    }
}

