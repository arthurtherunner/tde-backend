<?php

namespace App\Modules\Disciplina\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Curso\Models\Curso;
use App\Modules\Disciplina\Models\Disciplina;
use App\Modules\Disciplina\Requests\StoreDisciplinaRequest;
use App\Modules\Disciplina\Requests\UpdateDisciplinaRequest;
use App\Modules\Disciplina\Services\DisciplinaService;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    public function __construct(private DisciplinaService $disciplinaService)
    {
    }

    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        return response()->json($this->disciplinaService->list($perPage), 200);
    }

    public function porCurso(Request $request, Curso $curso)
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        return response()->json($this->disciplinaService->listByCurso($curso, $perPage), 200);
    }

    public function store(StoreDisciplinaRequest $request)
    {
        return response()->json($this->disciplinaService->create($request->validated()), 201);
    }

    public function update(UpdateDisciplinaRequest $request, Disciplina $disciplina)
    {
        return response()->json($this->disciplinaService->update($disciplina, $request->validated()), 200);
    }

    public function destroy(Disciplina $disciplina)
    {
        $this->disciplinaService->delete($disciplina);

        return response()->json(null, 204);
    }
}
