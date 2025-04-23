<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Follow>
 */
class FollowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'follower_id' => User::factory(),
            'following_id' => User::factory(),
            'accepted' => true,
            'followed_at' => now(),
            'unfollowed_at' => null,
        ];
    }

    /**
     * Configura el seguimiento como pendiente de aceptación.
     */
    public function pending(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'accepted' => false,
            ];
        });
    }

    /**
     * Configura el seguimiento como dejado de seguir.
     */
    public function unfollowed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'unfollowed_at' => now(),
            ];
        });
    }

    /**
     * Configura el seguimiento para usuarios específicos.
     */
    public function between(User $follower, User $following): static
    {
        return $this->state(function (array $attributes) use ($follower, $following) {
            return [
                'follower_id' => $follower->id,
                'following_id' => $following->id,
            ];
        });
    }
}
