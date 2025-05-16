<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'post_id' => Post::inRandomOrder()->first()?->id,
            'content' => $this->faker->sentence(),
            'parent_id' => null, // Para comentarios raíz, puedes asignar replies en tests/seeder
            'edited' => $this->faker->boolean(10),
            'pinned' => $this->faker->boolean(5),
            'edited_at' => null,
            'likes_count' => $this->faker->numberBetween(0, 100),
        ];
    }
}
