<?php

namespace App\Modules\Questao\Services;

use App\Modules\Disciplina\Models\Disciplina;
use App\Modules\Questao\Models\Questao;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class QuestaoService
{
    public function listByDisciplina(Disciplina $disciplina, User $user, int $perPage = 15)
    {
        if ($user->tipo !== 'admin' && !$this->estaAssociado($user, $disciplina->id)) {
            abort(403, 'Você não está associado a esta disciplina.');
        }

        return $disciplina->questoes()->with('opcoes')->paginate($perPage);
    }

    public function create(array $data, User $user): Questao
    {
        if ($user->tipo !== 'autor') {
            abort(403, 'Apenas usuários do tipo autor podem cadastrar questões.');
        }

        if (!$this->estaAssociado($user, (int) $data['disciplina_id'])) {
            abort(403, 'Você não está associado à disciplina informada.');
        }

        $opcoes = $data['opcoes'] ?? [];
        $this->validarOpcoes($data['tipo'], $data['quantidade_opcoes'] ?? null, $opcoes);

        return DB::transaction(function () use ($data, $user, $opcoes) {
            $multipla = $data['tipo'] === 'multipla_escolha';

            $questao = Questao::create([
                'tipo' => $data['tipo'],
                'nivel_dificuldade' => $data['nivel_dificuldade'],
                'descricao' => $data['descricao'],
                'disciplina_id' => $data['disciplina_id'],
                'user_id' => $user->id,
                'quantidade_opcoes' => $multipla ? count($opcoes) : null,
            ]);

            if ($multipla) {
                $questao->opcoes()->createMany($this->normalizarOpcoes($opcoes));
            }

            return $questao->load('opcoes');
        });
    }

    public function update(Questao $questao, array $data, User $user): Questao
    {
        $this->autorizarAlteracao($questao, $user);

        $enviouOpcoes = array_key_exists('opcoes', $data);
        $enviouQuantidade = array_key_exists('quantidade_opcoes', $data);

        if ($questao->tipo === 'aberta' && ($enviouOpcoes || $enviouQuantidade)) {
            throw ValidationException::withMessages([
                'opcoes' => 'Questões abertas não possuem opções de marcação.',
            ]);
        }

        return DB::transaction(function () use ($questao, $data, $enviouOpcoes, $enviouQuantidade) {
            $campos = array_intersect_key($data, array_flip(['nivel_dificuldade', 'descricao']));

            if ($questao->tipo === 'multipla_escolha' && ($enviouOpcoes || $enviouQuantidade)) {
                $opcoes = $enviouOpcoes ? $data['opcoes'] : $this->opcoesAtuais($questao);
                $quantidade = $data['quantidade_opcoes'] ?? count($opcoes);

                $this->validarOpcoes('multipla_escolha', $quantidade, $opcoes);

                if ($enviouOpcoes) {
                    $questao->opcoes()->delete();
                    $questao->opcoes()->createMany($this->normalizarOpcoes($opcoes));
                }

                $campos['quantidade_opcoes'] = count($opcoes);
            }

            $questao->update($campos);

            return $questao->fresh('opcoes');
        });
    }

    public function delete(Questao $questao, User $user): void
    {
        $this->autorizarAlteracao($questao, $user);

        if ($questao->avaliacoes()->exists()) {
            abort(409, 'Não é possível remover uma questão que já foi utilizada em uma avaliação.');
        }

        DB::transaction(function () use ($questao) {
            $questao->opcoes()->delete();
            $questao->delete();
        });
    }

    private function estaAssociado(User $user, int $disciplinaId): bool
    {
        return $user->disciplinas()->where('disciplinas.id', $disciplinaId)->exists();
    }

    private function autorizarAlteracao(Questao $questao, User $user): void
    {
        if ($user->tipo !== 'admin' && (int) $questao->user_id !== (int) $user->id) {
            abort(403, 'Apenas o autor da questão ou um administrador pode alterá-la ou removê-la.');
        }
    }

    private function validarOpcoes(string $tipo, $quantidade, array $opcoes): void
    {
        if ($tipo === 'aberta') {
            if (!empty($opcoes)) {
                throw ValidationException::withMessages([
                    'opcoes' => 'Questões abertas não possuem opções de marcação.',
                ]);
            }

            return;
        }

        if (count($opcoes) !== (int) $quantidade) {
            throw ValidationException::withMessages([
                'quantidade_opcoes' => 'A quantidade de opções deve ser igual ao número de opções enviadas.',
            ]);
        }

        $corretas = collect($opcoes)
            ->filter(fn ($o) => filter_var($o['correta'] ?? false, FILTER_VALIDATE_BOOLEAN))
            ->count();

        if ($corretas !== 1) {
            throw ValidationException::withMessages([
                'opcoes' => 'Informe exatamente uma opção correta.',
            ]);
        }
    }

    private function normalizarOpcoes(array $opcoes): array
    {
        return collect($opcoes)->map(fn ($o) => [
            'texto_opcao' => $o['texto_opcao'],
            'correta' => filter_var($o['correta'], FILTER_VALIDATE_BOOLEAN),
        ])->values()->all();
    }

    private function opcoesAtuais(Questao $questao): array
    {
        return $questao->opcoes->map(fn ($o) => [
            'texto_opcao' => $o->texto_opcao,
            'correta' => $o->correta,
        ])->all();
    }
}