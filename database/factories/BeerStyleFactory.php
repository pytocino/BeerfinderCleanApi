<?php

namespace Database\Factories;

use App\Models\BeerStyle;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeerStyleFactory extends Factory
{
    protected $model = BeerStyle::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . ' Style',
            'description' => $this->faker->optional()->sentence(),
            'origin_country' => $this->faker->country(),
        ];
    }
}
