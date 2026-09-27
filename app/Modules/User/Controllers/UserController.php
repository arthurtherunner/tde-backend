<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use App\Modules\User\Requests\StoreUserRequest;
use App\Modules\User\Requests\UpdateUserRequest;
use App\Modules\User\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function index(Request $request)
    {
        // só admin pode listar todos os usuários
        if (auth('api')->user()->tipo !== 'admin') {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $perPage = $request->query('per_page', 15);
        return response()->json($this->userService->list((int) $perPage), 200);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        return response()->json($user, 201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $updated = $this->userService->update($user, $request->validated(), auth('api')->user());
        return response()->json($updated, 200);
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'senha_atual' => 'required|string',
            'nova_senha' => 'required|string|min:6',
        ]);

        $updated = $this->userService->updatePassword(
            $user,
            $request->senha_atual,
            $request->nova_senha,
            auth('api')->user()
        );

        return response()->json(['message' => 'Senha atualizada com sucesso.'], 200);
    }

    public function resetPassword(User $user)
    {
        if (auth('api')->user()->tipo !== 'admin') {
            return response()->json(['message' => 'Apenas administradores podem resetar senhas.'], 403);
        }

        $this->userService->resetPassword($user);
        return response()->json(['message' => 'Senha redefinida para o padrão.'], 200);
    }

    public function destroy(User $user)
    {
        if (auth('api')->user()->tipo !== 'admin') {
            return response()->json(['message' => 'Apenas administradores podem remover usuários.'], 403);
        }

        $this->userService->delete($user);
        return response()->json(null, 204);
    }
}