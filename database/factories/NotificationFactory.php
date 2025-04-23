<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['like', 'comment', 'follow', 'mention', 'system'];
        $type = fake()->randomElement($types);

        return [
            'user_id' => User::factory(),
            'from_user_id' => fake()->boolean(80) ? User::factory() : null, // 80% con usuario de origen
            'type' => $type,
            'related_id' => fake()->randomNumber(5), // ID del objeto relacionado
            'is_read' => fake()->boolean(30), // 30% ya leídas
            'read_at' => function (array $attributes) {
                return $attributes['is_read'] ? now()->subMinutes(rand(1, 60)) : null;
            },
        ];
    }

    /**
     * Configura la notificación como leída.
     */
    public function read(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_read' => true,
            'read_at' => now()->subMinutes(rand(1, 60)),
        ]);
    }

    /**
     * Configura la notificación como no leída.
     */
    public function unread(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Configura la notificación como de tipo like.
     */
    public function like(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'like',
        ]);
    }

    /**
     * Configura la notificación como de tipo comment.
     */
    public function comment(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'comment',
        ]);
    }

    /**
     * Configura la notificación como de tipo follow.
     */
    public function follow(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'follow',
        ]);
    }

    /**
     * Configura para un usuario específico que recibe la notificación.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Configura el usuario que generó la notificación.
     */
    public function fromUser(User $fromUser): static
    {
        return $this->state(fn(array $attributes) => [
            'from_user_id' => $fromUser->id,
        ]);
    }
}
