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
        // Evitar que follower_id y following_id sean iguales
        do {
            $follower_id = User::all()->random()->id;
            $following_id = User::all()->random()->id;
        } while ($follower_id === $following_id);

        $statuses = ['pending', 'accepted', 'rejected'];

        return [
            'follower_id' => $follower_id,
            'following_id' => $following_id,
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}
