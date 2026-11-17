<?php

namespace App\Modules\Curso\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // controle de admin feito pelo middleware
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'formato' => 'required|in:presencial,ead,semi-presencial',
        ];
    }
}
