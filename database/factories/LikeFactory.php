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
        // Puedes ajustar los modelos polimórficos según tus necesidades
        $likeableTypes = [
            'App\\Models\\Post',
            'App\\Models\\Comment',
            'App\\Models\\Beer',
        ];

        return [
            'user_id' => fn() => User::inRandomOrder()->first()?->id ?? User::factory(),
            'likeable_id' => $this->faker->numberBetween(1, 100),
            'likeable_type' => $this->faker->randomElement($likeableTypes),
        ];
    }
}
