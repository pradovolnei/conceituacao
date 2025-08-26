<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Encontra o perfil 'Administrador'.
        // É importante que AdminProfileSeeder já tenha sido executado.
        $adminProfile = Profile::where('name', 'Administrador')->first();

        // Se o perfil 'Administrador' não existir, crie-o.
        // Embora deva existir se AdminProfileSeeder rodou primeiro.
        if (!$adminProfile) {
            $adminProfile = Profile::firstOrCreate(['name' => 'Administrador']);
        }

        // Cria um usuário de exemplo ou encontra um existente.
        // Usamos firstOrCreate para evitar duplicatas se o seeder for executado mais de uma vez.
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'], // Condição para encontrar ou criar
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Senha padrão para testes
                'email_verified_at' => now(),
            ]
        );

        // Associa o perfil 'Administrador' ao usuário, se ainda não estiver associado.
        if (!$adminUser->profiles->contains($adminProfile->id)) {
            $adminUser->profiles()->attach($adminProfile);
            $this->command->info('Perfil Administrador associado ao usuário admin@example.com');
        } else {
            $this->command->info('Usuário admin@example.com já possui o perfil Administrador.');
        }

        $this->command->info('Usuário administrador criado/verificado: admin@example.com com senha: password');
    }
}
