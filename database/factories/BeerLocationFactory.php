<?php

namespace Database\Factories;

use App\Models\BeerLocation;
use App\Models\Beer;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeerLocationFactory extends Factory
{
    protected $model = BeerLocation::class;

    public function definition(): array
    {
        return [
            'beer_id' => fn() => Beer::inRandomOrder()->first()?->id ?? Beer::factory(),
            'location_id' => fn() => Location::inRandomOrder()->first()?->id ?? Location::factory(),
            'price' => $this->faker->optional(0.9)->randomFloat(2, 1, 15),
            'is_featured' => $this->faker->boolean(20),
        ];
    }
}
