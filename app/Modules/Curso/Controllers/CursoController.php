<?php

namespace App\Modules\Curso\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Curso\Models\Curso;
use App\Modules\Curso\Requests\StoreCursoRequest;
use App\Modules\Curso\Requests\UpdateCursoRequest;
use App\Modules\Curso\Services\CursoService;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function __construct(private CursoService $cursoService)
    {
    }

    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        return response()->json($this->cursoService->list($perPage), 200);
    }

    public function store(StoreCursoRequest $request)
    {
        return response()->json($this->cursoService->create($request->validated()), 201);
    }

    public function update(UpdateCursoRequest $request, Curso $curso)
    {
        return response()->json($this->cursoService->update($curso, $request->validated()), 200);
    }

    public function destroy(Curso $curso)
    {
        $this->cursoService->delete($curso);

        return response()->json(null, 204);
    }
}
