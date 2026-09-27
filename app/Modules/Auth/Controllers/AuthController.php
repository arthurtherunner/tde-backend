<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'senha' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // jwtauth espera as chaves  email e passwors i
        $credentials = [
            'email' => $request->email,
            'password' => $request->senha,
        ];

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ], 200);
    }

    public function me(Request $request)
    {
        return response()->json(auth('api')->user());
    }

    public function logout(Request $request)
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logout realizado com sucesso'], 200);
    }
}