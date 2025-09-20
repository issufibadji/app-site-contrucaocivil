<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'zip_code' => ['nullable', 'string', 'max:20'],
            'street' => ['nullable', 'string', 'max:150'],
            'number' => ['nullable', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:120'],
            'district' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:5'],
            'country' => ['nullable', 'string', 'max:120'],
            'is_international' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_international' => filter_var($this->input('is_international', false), FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
