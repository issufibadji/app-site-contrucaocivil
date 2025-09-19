<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ddi' => 'required|string|max:4',
            'ddd' => 'required|string|max:4',
            'phone' => 'required|string|max:20',
            'user_id' => 'required|exists:users,id',
            'professional_id' => 'nullable|exists:agendaai_professionals,id',
        ];
    }
}
