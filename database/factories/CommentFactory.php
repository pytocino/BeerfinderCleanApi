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
            'user_id' => fn() => User::inRandomOrder()->first()?->id ?? User::factory(),
            'post_id' => fn() => Post::inRandomOrder()->first()?->id ?? Post::factory(),
            'content' => $this->faker->sentence(),
            'parent_id' => null, // Puedes asignar en tests para replies
            'edited' => $this->faker->boolean(10),
            'pinned' => $this->faker->boolean(5),
            'edited_at' => null,
            'likes_count' => $this->faker->numberBetween(0, 100),
        ];
    }
}
