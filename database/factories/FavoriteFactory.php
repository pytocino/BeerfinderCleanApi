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
        $favorableTypes = [
            'App\\Models\\Beer',
            'App\\Models\\Location',
            'App\\Models\\Post',
        ];

        $favorableType = $this->faker->randomElement($favorableTypes);
        $favorableModel = new $favorableType;
        $favorableId = $favorableModel->inRandomOrder()->first()?->id;

        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'favorable_id' => $favorableId,
            'favorable_type' => $favorableType,
        ];
    }
}
