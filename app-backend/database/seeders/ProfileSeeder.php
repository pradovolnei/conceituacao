<?php

namespace Database\Seeders;

use App\Domains\Profile\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::create([
            'name' => 'Administrador',
        ]);
    }
}
