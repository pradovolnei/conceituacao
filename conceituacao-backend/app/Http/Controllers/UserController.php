<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::with('profiles')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'profile_ids' => 'array',
                'profile_ids.*' => 'exists:profiles,id',
            ], [
                'name.required' => 'O campo nome é obrigatório.',
                'email.required' => 'O campo email é obrigatório.',
                'email.unique' => 'Este email já está em uso.',
                'password.required' => 'O campo senha é obrigatório.',
                'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
                'password.confirmed' => 'A confirmação de senha não confere.',
                'profile_ids.*.exists' => 'Um dos perfis selecionados não existe.',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if ($request->has('profile_ids')) {
                $user->profiles()->attach($request->profile_ids);
            }

            return response()->json($user->load('profiles'), 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user->load('profiles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'profile_ids' => 'array',
                'profile_ids.*' => 'exists:profiles,id',
            ], [
                'name.required' => 'O campo nome é obrigatório.',
                'email.required' => 'O campo email é obrigatório.',
                'email.unique' => 'Este email já está em uso.',
                'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
                'password.confirmed' => 'A confirmação de senha não confere.',
                'profile_ids.*.exists' => 'Um dos perfis selecionados não existe.',
            ]);

            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            if ($request->has('profile_ids')) {
                $user->profiles()->sync($request->profile_ids); // Sincroniza os perfis
            } else {
                $user->profiles()->detach(); // Remove todos os perfis se nenhum for fornecido
            }


            return response()->json($user->load('profiles'));
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar usuário.'], 500);
        }
    }

    /**
     * Associa um ou mais perfis a um usuário.
     */
    public function attachProfiles(Request $request, User $user)
    {
        try {
            $request->validate([
                'profile_ids' => 'required|array',
                'profile_ids.*' => 'exists:profiles,id',
            ], [
                'profile_ids.required' => 'É necessário fornecer IDs de perfis.',
                'profile_ids.*.exists' => 'Um dos perfis fornecidos não existe.',
            ]);

            $user->profiles()->attach($request->profile_ids);
            return response()->json($user->load('profiles'), 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor.'], 500);
        }
    }

    /**
     * Desassocia um ou mais perfis de um usuário.
     */
    public function detachProfiles(Request $request, User $user)
    {
        try {
            $request->validate([
                'profile_ids' => 'required|array',
                'profile_ids.*' => 'exists:profiles,id',
            ], [
                'profile_ids.required' => 'É necessário fornecer IDs de perfis.',
                'profile_ids.*.exists' => 'Um dos perfis fornecidos não existe.',
            ]);

            $user->profiles()->detach($request->profile_ids);
            return response()->json($user->load('profiles'), 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor.'], 500);
        }
    }
}
