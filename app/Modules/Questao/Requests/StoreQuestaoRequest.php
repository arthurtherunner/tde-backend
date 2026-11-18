<?php

namespace App\Modules\Questao\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // as regras de perfil e de associação ficam no Service
    }

    public function rules(): array
    {
        return [
            'tipo' => 'required|in:aberta,multipla_escolha',
            'nivel_dificuldade' => 'required|in:facil,media,dificil',
            'descricao' => 'required|string',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'quantidade_opcoes' => 'required_if:tipo,multipla_escolha|nullable|integer|min:2|max:10',
            'opcoes' => 'required_if:tipo,multipla_escolha|nullable|array|max:10',
            'opcoes.*.texto_opcao' => 'required|string',
            'opcoes.*.correta' => 'required|boolean',
        ];
    }
}