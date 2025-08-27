<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retorna todos os perfis
        return response()->json(Profile::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:profiles,name',
            ], [
                'name.required' => 'O campo nome do perfil é obrigatório.',
                'name.unique' => 'Um perfil com este nome já existe.',
            ]);

            $profile = Profile::create($request->all());
            return response()->json($profile, 201);
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
    public function show(Profile $profile)
    {
        return response()->json($profile);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profile $profile)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:profiles,name,' . $profile->id,
            ], [
                'name.required' => 'O campo nome do perfil é obrigatório.',
                'name.unique' => 'Um perfil com este nome já existe.',
            ]);

            $profile->update($request->all());
            return response()->json($profile);
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
    public function destroy(Profile $profile)
    {
        try {
            $profile->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar perfil.'], 500);
        }
    }
}
