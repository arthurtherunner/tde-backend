<?php

namespace App\Modules\Questao\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Disciplina\Models\Disciplina;
use App\Modules\Questao\Models\Questao;
use App\Modules\Questao\Requests\StoreQuestaoRequest;
use App\Modules\Questao\Requests\UpdateQuestaoRequest;
use App\Modules\Questao\Services\QuestaoService;
use Illuminate\Http\Request;

class QuestaoController extends Controller
{
    public function __construct(private QuestaoService $questaoService)
    {
    }

    public function porDisciplina(Request $request, Disciplina $disciplina)
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        return response()->json(
            $this->questaoService->listByDisciplina($disciplina, auth('api')->user(), $perPage),
            200
        );
    }

    public function store(StoreQuestaoRequest $request)
    {
        $questao = $this->questaoService->create($request->validated(), auth('api')->user());

        return response()->json($questao, 201);
    }

    public function update(UpdateQuestaoRequest $request, Questao $questao)
    {
        $atualizada = $this->questaoService->update($questao, $request->validated(), auth('api')->user());

        return response()->json($atualizada, 200);
    }

    public function destroy(Questao $questao)
    {
        $this->questaoService->delete($questao, auth('api')->user());

        return response()->json(null, 204);
    }
}