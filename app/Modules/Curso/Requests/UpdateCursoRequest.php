<?php

namespace App\Modules\Curso\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'sometimes|string|max:255',
            'descricao' => 'nullable|string',
            'formato' => 'sometimes|in:presencial,ead,semi-presencial',
        ];
    }
}
