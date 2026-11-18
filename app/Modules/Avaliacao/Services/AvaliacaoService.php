<?php

namespace App\Modules\Avaliacao\Services;

use App\Modules\Avaliacao\Models\Avaliacao;
use App\Modules\Disciplina\Models\Disciplina;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AvaliacaoService
{
    public function create(array $data, User $user): Avaliacao
    {
        if ($user->tipo !== 'autor') {
            abort(403, 'Apenas usuários do tipo autor podem cadastrar avaliações.');
        }

        if (!$this->estaAssociado($user, (int) $data['disciplina_id'])) {
            abort(403, 'Você não está associado à disciplina informada.');
        }

        $this->conferirCursoDaDisciplina((int) $data['curso_id'], (int) $data['disciplina_id']);
        $this->conferirQuantidades(
            (int) $data['quantidade_questoes_multipla'],
            (int) $data['quantidade_questoes_abertas']
        );

        $avaliacao = Avaliacao::create([
            'curso_id' => $data['curso_id'],
            'disciplina_id' => $data['disciplina_id'],
            'semestre' => $data['semestre'],
            'docente' => $data['docente'] ?? $user->nome,
            'data_avaliacao' => $data['data_avaliacao'],
            'data_elaboracao' => $data['data_elaboracao'] ?? now()->toDateString(),
            'tipo' => $data['tipo'],
            'valor' => $data['valor'],
            'quantidade_questoes_multipla' => $data['quantidade_questoes_multipla'],
            'quantidade_questoes_abertas' => $data['quantidade_questoes_abertas'],
            'user_id' => $user->id,
        ]);

        return $this->comRelacoes($avaliacao);
    }

    public function update(Avaliacao $avaliacao, array $data, User $user): Avaliacao
    {
        $this->autorizarAlteracao($avaliacao, $user);

        $cursoId = (int) ($data['curso_id'] ?? $avaliacao->curso_id);
        $disciplinaId = (int) ($data['disciplina_id'] ?? $avaliacao->disciplina_id);
        $multipla = (int) ($data['quantidade_questoes_multipla'] ?? $avaliacao->quantidade_questoes_multipla);
        $abertas = (int) ($data['quantidade_questoes_abertas'] ?? $avaliacao->quantidade_questoes_abertas);

        $this->conferirCursoDaDisciplina($cursoId, $disciplinaId);
        $this->conferirQuantidades($multipla, $abertas);

        $temQuestoes = $avaliacao->questoes()->exists();

        if ($disciplinaId !== (int) $avaliacao->disciplina_id) {
            if ($user->tipo !== 'admin' && !$this->estaAssociado($user, $disciplinaId)) {
                abort(403, 'Você não está associado à disciplina informada.');
            }

            if ($temQuestoes) {
                abort(409, 'Remova as questões da avaliação antes de trocar a disciplina.');
            }
        }

        $mudouQuantidade = $multipla !== (int) $avaliacao->quantidade_questoes_multipla
            || $abertas !== (int) $avaliacao->quantidade_questoes_abertas;

        if ($mudouQuantidade && $temQuestoes) {
            abort(409, 'Remova as questões da avaliação antes de mudar a quantidade de questões.');
        }

        $campos = array_intersect_key($data, array_flip([
            'curso_id', 'disciplina_id', 'semestre', 'docente',
            'data_avaliacao', 'data_elaboracao', 'tipo', 'valor',
            'quantidade_questoes_multipla', 'quantidade_questoes_abertas',
        ]));

        $avaliacao->update($campos);

        return $this->comRelacoes($avaliacao->fresh());
    }

    public function delete(Avaliacao $avaliacao, User $user): void
    {
        $this->autorizarAlteracao($avaliacao, $user);

        DB::transaction(function () use ($avaliacao) {
            $avaliacao->questoes()->detach();
            $avaliacao->delete();
        });
    }

    private function estaAssociado(User $user, int $disciplinaId): bool
    {
        return $user->disciplinas()->where('disciplinas.id', $disciplinaId)->exists();
    }

    private function autorizarAlteracao(Avaliacao $avaliacao, User $user): void
    {
        if ($user->tipo !== 'admin' && (int) $avaliacao->user_id !== (int) $user->id) {
            abort(403, 'Apenas o autor da avaliação ou um administrador pode alterá-la ou removê-la.');
        }
    }

    private function conferirCursoDaDisciplina(int $cursoId, int $disciplinaId): void
    {
        $disciplina = Disciplina::findOrFail($disciplinaId);

        if ((int) $disciplina->curso_id !== $cursoId) {
            throw ValidationException::withMessages([
                'curso_id' => 'A disciplina informada não pertence a este curso.',
            ]);
        }
    }

    private function conferirQuantidades(int $multipla, int $abertas): void
    {
        if ($multipla + $abertas < 1) {
            throw ValidationException::withMessages([
                'quantidade_questoes_multipla' => 'A avaliação precisa ter pelo menos uma questão.',
            ]);
        }
    }

    private function comRelacoes(Avaliacao $avaliacao): Avaliacao
    {
        return $avaliacao->load(['curso:id,nome', 'disciplina:id,nome', 'autor:id,nome']);
    }
}