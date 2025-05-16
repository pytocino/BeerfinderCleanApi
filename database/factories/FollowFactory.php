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
        $userIds = User::pluck('id')->toArray();
        $follower = $this->faker->randomElement($userIds);
        $following = $this->faker->randomElement(array_diff($userIds, [$follower]));

        $statuses = ['pending', 'accepted', 'rejected'];

        return [
            'follower_id' => $follower,
            'following_id' => $following,
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}
