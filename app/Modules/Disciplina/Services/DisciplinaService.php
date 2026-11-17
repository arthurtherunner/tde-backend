<?php

namespace App\Modules\Disciplina\Services;

use App\Modules\Curso\Models\Curso;
use App\Modules\Disciplina\Models\Disciplina;

class DisciplinaService
{
    public function list(int $perPage = 15)
    {
        return Disciplina::paginate($perPage);
    }

    public function listByCurso(Curso $curso, int $perPage = 15)
    {
        return $curso->disciplinas()->paginate($perPage);
    }

    public function create(array $data): Disciplina
    {
        return Disciplina::create($data);
    }

    public function update(Disciplina $disciplina, array $data): Disciplina
    {
        $disciplina->update($data);

        return $disciplina->fresh();
    }

    public function delete(Disciplina $disciplina): void
    {
        if ($disciplina->questoes()->exists() || $disciplina->avaliacoes()->exists() || $disciplina->users()->exists()) {
            abort(409, 'Não é possível remover uma disciplina que possui questões, avaliações ou usuários associados.');
        }

        $disciplina->delete();
    }
}
