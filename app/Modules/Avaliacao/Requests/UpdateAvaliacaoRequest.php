<?php

namespace App\Modules\Avaliacao\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAvaliacaoRequest extends FormRequest
{
    private const TIPOS = [
        'AV1', 'AV1-SEGUNDA-CHAMADA', 'AV1-ADAPTADA', 'AV1-ADAPTADA-SEGUNDA-CHAMADA',
        'AV2', 'AV2-SEGUNDA-CHAMADA', 'AV2-ADAPTADA', 'AV2-ADAPTADA-SEGUNDA-CHAMADA',
        'FINAL', 'FINAL-SEGUNDA-CHAMADA', 'FINAL-ADAPTADA', 'FINAL-ADAPTADA-SEGUNDA-CHAMADA',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'curso_id' => 'sometimes|exists:cursos,id',
            'disciplina_id' => 'sometimes|exists:disciplinas,id',
            'semestre' => 'sometimes|string|max:20',
            'docente' => 'sometimes|string|max:255',
            'data_avaliacao' => 'sometimes|date',
            'data_elaboracao' => 'sometimes|date',
            'tipo' => ['sometimes', Rule::in(self::TIPOS)],
            'valor' => 'sometimes|numeric|min:0.01|max:999.99',
            'quantidade_questoes_multipla' => 'sometimes|integer|min:0|max:100',
            'quantidade_questoes_abertas' => 'sometimes|integer|min:0|max:100',
        ];
    }
}