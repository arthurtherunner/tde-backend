<?php

namespace App\Modules\Questao\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nivel_dificuldade' => 'sometimes|in:facil,media,dificil',
            'descricao' => 'sometimes|string',
            'quantidade_opcoes' => 'sometimes|integer|min:2|max:10',
            'opcoes' => 'sometimes|array|min:2|max:10',
            'opcoes.*.texto_opcao' => 'required|string',
            'opcoes.*.correta' => 'required|boolean',
        ];
    }
}