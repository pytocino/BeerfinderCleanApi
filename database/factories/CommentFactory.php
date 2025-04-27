<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? 1,
            'post_id' => Post::query()->inRandomOrder()->value('id') ?? 1,
            'content' => $this->faker->paragraph(),
            'parent_id' => null, // Por defecto, no es una respuesta a otro comentario
            'edited' => false,
            'pinned' => false,
        ];
    }

    /**
     * Indicar que el comentario es una respuesta a otro comentario.
     */
    public function asReply(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => Comment::query()->inRandomOrder()->value('id'),
            ];
        });
    }

    /**
     * Indicar que el comentario ha sido editado.
     */
    public function edited(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'edited' => true,
            ];
        });
    }

    /**
     * Indicar que el comentario está fijado.
     */
    public function pinned(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'pinned' => true,
            ];
        });
    }
}
