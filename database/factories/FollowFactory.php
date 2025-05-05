<?php

namespace Database\Factories;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowFactory extends Factory
{
    protected $model = Follow::class;

    public function definition(): array
    {
        // Selecciona dos usuarios distintos
        $follower = User::inRandomOrder()->first()?->id ?? User::factory();
        $following = User::inRandomOrder()->where('id', '!=', $follower)->first()?->id ?? User::factory();

        $statuses = [
            Follow::STATUS_PENDING,
            Follow::STATUS_ACCEPTED,
            Follow::STATUS_REJECTED,
        ];

        return [
            'follower_id' => $follower,
            'following_id' => $following,
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}
