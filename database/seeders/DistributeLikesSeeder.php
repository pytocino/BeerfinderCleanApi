<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DistributeLikesSeeder extends Seeder
{
    public function run(): void
    {
        // IDs de los usuarios que darán likes
        $userIds = [1, 2, 3, 4, 5];

        // Obtener todos los posts de esos usuarios
        $postIds = Post::whereIn('user_id', $userIds)->pluck('id')->toArray();

        if (empty($postIds)) {
            $this->command->error('No hay posts de los usuarios 1-5');
            return;
        }

        // Limpiar likes existentes (opcional)
        DB::table('likes')->truncate();

        // Distribuir likes aleatoriamente: cada usuario da like a varios posts
        foreach ($userIds as $userId) {
            // Cada usuario dará like a la mitad de los posts (aprox)
            $postsToLike = collect($postIds)->random((int) ceil(count($postIds) / 2))->all();
            foreach ($postsToLike as $postId) {
                DB::table('likes')->insertOrIgnore([
                    'user_id' => $userId,
                    'post_id' => $postId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
