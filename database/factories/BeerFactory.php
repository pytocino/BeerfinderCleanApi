<?php

namespace Database\Factories;

use App\Models\Beer;
use App\Models\Brewery;
use App\Models\BeerStyle;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeerFactory extends Factory
{
    protected $model = Beer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . ' Beer',
            'description' => $this->faker->optional()->paragraph(),
            'brewery_id' => fn() => Brewery::inRandomOrder()->first()?->id ?? Brewery::factory(),
            'style_id' => fn() => BeerStyle::inRandomOrder()->first()?->id ?? BeerStyle::factory(),
            'abv' => $this->faker->randomFloat(1, 3.0, 12.0),
            'ibu' => $this->faker->numberBetween(5, 120),
            'image_url' => $this->faker->optional()->imageUrl(400, 600, 'beer'),
        ];
    }
}
