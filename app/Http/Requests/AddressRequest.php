<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cep'        => 'required|string|max:9',
            'uf'         => 'required|string|max:2',
            'city'       => 'required|string',
            'street'     => 'required|string',
            'complement' => 'nullable|string',
            'user_id'    => 'required|exists:users,id',
        ];
    }
}
