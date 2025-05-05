<?php

namespace Database\Factories;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    public function definition(): array
    {
        // Puedes ajustar los modelos polimórficos según tus necesidades
        $favorableTypes = [
            'App\\Models\\Beer',
            'App\\Models\\Location',
            'App\\Models\\Post',
        ];

        return [
            'user_id' => fn() => User::inRandomOrder()->first()?->id ?? User::factory(),
            'favorable_id' => $this->faker->numberBetween(1, 100),
            'favorable_type' => $this->faker->randomElement($favorableTypes),
        ];
    }
}
