<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    protected $model = Like::class;

    public function definition(): array
    {
        $likeableTypes = [
            'App\\Models\\Post',
            'App\\Models\\Comment',
            'App\\Models\\Beer',
        ];

        $likeableType = $this->faker->randomElement($likeableTypes);
        $likeableModel = new $likeableType;
        $likeableId = $likeableModel->inRandomOrder()->first()?->id;

        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'likeable_id' => $likeableId,
            'likeable_type' => $likeableType,
        ];
    }
}
