<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Importar a classe Response
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'As credenciais fornecidas não correspondem aos nossos registos.',
            ]);
        }

        // Se a tentativa de login for bem-sucedida, o Sanctum irá associar o cookie
        $request->session()->regenerate();
        $user = Auth::user();
        $user->load('profiles');

        return response()->json([
            'user' => $user,
            'message' => 'Login bem-sucedido.'
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // No logout, o 204 No Content é mais apropriado, pois não há necessidade de retornar dados.
        return response()->noContent();
    }
}
