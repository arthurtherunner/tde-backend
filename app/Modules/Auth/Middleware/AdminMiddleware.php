<?php

namespace App\Modules\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();

        if (!$user || $user->tipo !== 'admin') {
            return response()->json(['message' => 'Acesso negado. Apenas administradores.'], 403);
        }

        return $next($request);
    }
}
