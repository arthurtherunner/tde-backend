<?php

namespace App\Modules\Disciplina\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDisciplinaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'carga_horaria' => 'required|integer|min:1',
            'turno' => 'required|in:vespertino,noturno',
            'curso_id' => 'required|exists:cursos,id',
        ];
    }
}
