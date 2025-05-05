<?php

namespace Database\Factories;

use App\Models\Brewery;
use Illuminate\Database\Eloquent\Factories\Factory;

class BreweryFactory extends Factory
{
    protected $model = Brewery::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Brewery',
            'description' => $this->faker->optional()->sentence(),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'website' => $this->faker->optional()->url(),
            'image_url' => $this->faker->optional()->imageUrl(640, 480, 'business'),
        ];
    }
}
