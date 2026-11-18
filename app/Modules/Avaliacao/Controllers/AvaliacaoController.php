<?php

namespace App\Modules\Avaliacao\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Avaliacao\Models\Avaliacao;
use App\Modules\Avaliacao\Requests\StoreAvaliacaoRequest;
use App\Modules\Avaliacao\Requests\UpdateAvaliacaoRequest;
use App\Modules\Avaliacao\Services\AvaliacaoService;

class AvaliacaoController extends Controller
{
    public function __construct(private AvaliacaoService $avaliacaoService)
    {
    }

    public function store(StoreAvaliacaoRequest $request)
    {
        $avaliacao = $this->avaliacaoService->create($request->validated(), auth('api')->user());

        return response()->json($avaliacao, 201);
    }

    public function update(UpdateAvaliacaoRequest $request, Avaliacao $avaliacao)
    {
        $atualizada = $this->avaliacaoService->update($avaliacao, $request->validated(), auth('api')->user());

        return response()->json($atualizada, 200);
    }

    public function destroy(Avaliacao $avaliacao)
    {
        $this->avaliacaoService->delete($avaliacao, auth('api')->user());

        return response()->json(null, 204);
    }
}