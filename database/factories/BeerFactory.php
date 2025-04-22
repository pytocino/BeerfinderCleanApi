<?php

namespace Database\Factories;

use App\Models\Beer;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeerFactory extends Factory
{
    protected $model = Beer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(rand(1, 3), true) . ' ' . $this->faker->randomElement(['Lager', 'IPA', 'Stout', 'Porter', 'Ale']),
            'abv' => $this->faker->randomFloat(1, 3, 12),
            'ibu' => $this->faker->numberBetween(5, 120),
            'description' => $this->faker->paragraph(),
            'image_url' => $this->faker->boolean(70) ? $this->faker->imageUrl(400, 400, 'food') : null,
        ];
    }
}
