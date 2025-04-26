<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Brewery;
use App\Models\BeerStyle;
use App\Models\Beer;
use App\Models\Comment;
use App\Models\Location;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database (solo lo básico).
     */
    public function run(): void
    {
        // Usuarios
        User::create([
            'name' => 'Admin',
            'username' => 'ElAdmin',
            'email' => 'admin@beerfinder.com',
            'password' => Hash::make('password'),
            'bio' => 'Administrador de la plataforma BeerFinder',
            'location' => 'Madrid, España',
            'email_verified_at' => now(),
        ]);
        User::factory()->count(30)->create();

        // // Cervecerías
        Brewery::factory()->count(30)->create();

        // // Estilos de cerveza
        BeerStyle::factory()->count(30)->create();

        // // Cervezas
        Beer::factory()->count(200)->create();

        // // Ubicaciones
        Location::factory()->count(20)->create();

        // // Posts
        Post::factory(300)->create();

        // Comentarios
        Comment::factory(600)->create();
    }
}
