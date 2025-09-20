<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdditionalDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cpf' => ['nullable', 'string', 'max:20'],
            'rg' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:30'],
            'secondary_phone' => ['nullable', 'string', 'max:30'],
        ];
    }
}
