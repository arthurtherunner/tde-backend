<?php

namespace App\Modules\Curso\Services;

use App\Modules\Curso\Models\Curso;

class CursoService
{
    public function list(int $perPage = 15)
    {
        return Curso::paginate($perPage);
    }

    public function create(array $data): Curso
    {
        return Curso::create($data);
    }

    public function update(Curso $curso, array $data): Curso
    {
        $curso->update($data);

        return $curso->fresh();
    }

    public function delete(Curso $curso): void
    {
        if ($curso->disciplinas()->exists() || $curso->avaliacoes()->exists() || $curso->users()->exists()) {
            abort(409, 'Não é possível remover um curso que possui disciplinas, avaliações ou usuários associados.');
        }

        $curso->delete();
    }
}
