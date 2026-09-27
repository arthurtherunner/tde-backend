<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // aqui só garantimos que existe alguém autenticado.
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'nome' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
        ];
    }
}