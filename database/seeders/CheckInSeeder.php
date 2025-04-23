<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\CheckIn;
use Illuminate\Database\Seeder;

class CheckInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los posts existentes
        $posts = Post::all();

        // Verificar que existan posts para crear los check-ins
        if ($posts->isEmpty()) {
            $this->command->error('No hay posts en la base de datos. Debes ejecutar primero PostSeeder.');
            return;
        }

        // Crear exactamente un check-in por cada post
        foreach ($posts as $post) {
            CheckIn::factory()
                ->state([
                    'post_id' => $post->id,
                    'user_id' => $post->user_id,
                    'beer_id' => $post->beer_id,
                    'location_id' => $post->location_id,
                ])
                ->create();
        }

        $this->command->info('Se han creado ' . $posts->count() . ' check-ins, uno por cada post.');
    }
}
