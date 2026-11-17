<?php

namespace App\Modules\Disciplina\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDisciplinaRequest extends FormRequest
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
            'carga_horaria' => 'sometimes|integer|min:1',
            'turno' => 'sometimes|in:vespertino,noturno',
            'curso_id' => 'sometimes|exists:cursos,id',
        ];
    }
}
