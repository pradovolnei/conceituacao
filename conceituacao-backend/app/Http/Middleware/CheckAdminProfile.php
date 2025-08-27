<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        // Carrega os perfis do usuário para verificar
        $user = auth()->user()->load('profiles');

        if (!$user || !$user->hasProfile('Administrador')) {
            return response()->json(['message' => 'Não autorizado. Apenas administradores podem acessar esta funcionalidade.'], 403);
        }

        return $next($request);
    }
}
