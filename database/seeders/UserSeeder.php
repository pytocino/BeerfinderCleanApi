<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario administrador
        User::create([
            'name' => 'Admin',
            'username' => 'ElAdmin',
            'email' => 'admin@beerfinder.com',
            'password' => Hash::make('password'),
            'bio' => 'Administrador de la plataforma BeerFinder',
            'location' => 'Madrid, España',
            'email_verified_at' => now(),
        ]);

        // Crear 30 usuarios regulares
        User::factory()->count(30)->create();
    }
}
