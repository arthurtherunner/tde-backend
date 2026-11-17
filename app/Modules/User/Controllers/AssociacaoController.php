<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;

class AssociacaoController extends Controller
{
    public function cursos(Request $request, User $user)
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        return response()->json($user->cursos()->paginate($perPage), 200);
    }

    public function syncCursos(Request $request, User $user)
    {
        $data = $request->validate([
            'cursos' => 'present|array',
            'cursos.*' => 'integer|distinct|exists:cursos,id',
        ]);

        $user->cursos()->sync($data['cursos']);

        return response()->json($user->load('cursos:id,nome'), 200);
    }

    public function disciplinas(Request $request, User $user)
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        return response()->json($user->disciplinas()->paginate($perPage), 200);
    }

    public function syncDisciplinas(Request $request, User $user)
    {
        $data = $request->validate([
            'disciplinas' => 'present|array',
            'disciplinas.*' => 'integer|distinct|exists:disciplinas,id',
        ]);

        $user->disciplinas()->sync($data['disciplinas']);

        return response()->json($user->load('disciplinas:id,nome'), 200);
    }
}
