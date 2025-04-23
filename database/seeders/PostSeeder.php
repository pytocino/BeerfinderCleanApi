<?php

namespace Database\Seeders;

use App\Models\Beer;
use App\Models\Post;
use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Ejecuta los seeds de la base de datos.
     */
    public function run(): void
    {
        // Obtener usuarios, cervezas y ubicaciones existentes
        $users = User::all();
        $beers = Beer::all();
        $locations = Location::all();

        // Verificar que existan registros para crear los posts
        if ($users->isEmpty() || $beers->isEmpty()) {
            $this->command->error('No hay usuarios o cervezas en la base de datos. Debes ejecutar primero UserSeeder y BeerSeeder.');
            return;
        }

        // Crear 50 posts aleatorios
        Post::factory(50)->create();

        // Crear posts para usuarios específicos
        foreach ($users->take(5) as $user) {
            // 3 posts normales por usuario
            Post::factory(3)
                ->forUser($user)
                ->forBeer($beers->random())
                ->create();

            // 1 post sin valoración por usuario
            Post::factory()
                ->forUser($user)
                ->forBeer($beers->random())
                ->withoutRating()
                ->create();

            // 1 post sin ubicación por usuario
            Post::factory()
                ->forUser($user)
                ->forBeer($beers->random())
                ->withoutLocation()
                ->create();
        }

        // Crear múltiples posts para cervezas populares
        $popularBeers = $beers->random(min(3, $beers->count()));
        foreach ($popularBeers as $beer) {
            Post::factory(5)
                ->forBeer($beer)
                ->create();
        }

        // Crear posts para ubicaciones específicas (si existen)
        if (!$locations->isEmpty()) {
            foreach ($locations->take(5) as $location) {
                Post::factory(2)
                    ->state([
                        'location_id' => $location->id,
                        'user_id' => $users->random()->id,
                        'beer_id' => $beers->random()->id,
                    ])
                    ->create();
            }
        }
    }
}
