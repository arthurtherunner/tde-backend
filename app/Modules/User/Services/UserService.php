<?php

namespace App\Modules\User\Services;

use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function list(int $perPage = 15)
    {
        // paginação em disco, na descrição do tde
        return User::paginate($perPage);
    }

    public function create(array $data): User
    {
        if (User::where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Já existe um usuário cadastrado com este e-mail.',
            ]);
        }

        return User::create([
            'nome' => $data['nome'],
            'email' => $data['email'],
            'tipo' => $data['tipo'],
            'senha' => Hash::make($data['senha'] ?? 'senha123'), // senha padrão se admin não informar
        ]);
    }

    public function update(User $user, array $data, User $authUser): User
    {
        // só o próprio usuário pode alterar suas informações
        if ($authUser->id !== $user->id) {
            abort(403, 'Você só pode alterar suas próprias informações.');
        }

        if (isset($data['email']) && $data['email'] !== $user->email) {
            if (User::where('email', $data['email'])->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'Já existe um usuário cadastrado com este e-mail.',
                ]);
            }
        }

        $user->update([
            'nome' => $data['nome'] ?? $user->nome,
            'email' => $data['email'] ?? $user->email,
        ]);

        return $user;
    }

    public function updatePassword(User $user, string $senhaAtual, string $novaSenha, User $authUser): User
    {
        if ($authUser->id !== $user->id) {
            abort(403, 'Você só pode redefinir sua própria senha.');
        }

        if (!Hash::check($senhaAtual, $user->senha)) {
            throw ValidationException::withMessages([
                'senha_atual' => 'A senha atual informada está incorreta.',
            ]);
        }

        $user->update(['senha' => Hash::make($novaSenha)]);

        return $user;
    }

    public function resetPassword(User $user): User
    {
        //só admin chama o metodo, q ta checado no controller
        $user->update(['senha' => Hash::make('senha123')]);

        return $user;
    }

    public function delete(User $user): void
    {
        // if ($user->questoes()->exists() || $user->avaliacoes()->exists()) {
        //     abort(422, 'Não é possível remover um usuário que possui questões ou avaliações criadas.');
        // }

    $user->delete();
    }
}