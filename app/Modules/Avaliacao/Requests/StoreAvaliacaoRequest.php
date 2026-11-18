<?php

namespace App\Modules\Avaliacao\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAvaliacaoRequest extends FormRequest
{
    private const TIPOS = [
        'AV1', 'AV1-SEGUNDA-CHAMADA', 'AV1-ADAPTADA', 'AV1-ADAPTADA-SEGUNDA-CHAMADA',
        'AV2', 'AV2-SEGUNDA-CHAMADA', 'AV2-ADAPTADA', 'AV2-ADAPTADA-SEGUNDA-CHAMADA',
        'FINAL', 'FINAL-SEGUNDA-CHAMADA', 'FINAL-ADAPTADA', 'FINAL-ADAPTADA-SEGUNDA-CHAMADA',
    ];

    public function authorize(): bool
    {
        return true; // as regras de perfil e de associação ficam no Service
    }

    public function rules(): array
    {
        return [
            'curso_id' => 'required|exists:cursos,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'semestre' => 'required|string|max:20',
            'docente' => 'nullable|string|max:255',
            'data_avaliacao' => 'required|date',
            'data_elaboracao' => 'nullable|date',
            'tipo' => ['required', Rule::in(self::TIPOS)],
            'valor' => 'required|numeric|min:0.01|max:999.99',
            'quantidade_questoes_multipla' => 'required|integer|min:0|max:100',
            'quantidade_questoes_abertas' => 'required|integer|min:0|max:100',
        ];
    }
}