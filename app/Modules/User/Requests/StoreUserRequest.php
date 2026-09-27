<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // só admin pode criar usuário
        return auth('api')->user()?->tipo === 'admin';
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tipo' => 'required|in:admin,autor',
            'senha' => 'nullable|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail informado não é válido.',
            'tipo.required' => 'O tipo de usuário é obrigatório.',
            'tipo.in' => 'O tipo deve ser "admin" ou "autor".',
        ];
    }
}