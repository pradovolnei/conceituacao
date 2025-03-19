<?php
namespace App\Domains\Auth\Controllers;

use App\Core\Controllers\Controller;
use App\Domains\Auth\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{


    public function authenticate(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('JWT');

            return response()->json([
                'user' => $user,
                'tokenType' => 'bearer',
                'token'      => $token->plainTextToken
            ]);

        }

        return response()->json([
            'message'   => 'Não podemos encontrar uma conta com essas credenciais.'
        ], 401);
    }
}
