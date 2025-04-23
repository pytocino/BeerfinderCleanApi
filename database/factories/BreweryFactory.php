<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brewery>
 */
class BreweryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Brewery',
            'country' => fake()->country(),
            'city' => fake()->city(),
            'address' => fake()->streetAddress(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'description' => $this->faker->paragraph(3),
            'logo_url' => fake()->imageUrl(200, 200, 'brewery'),
            'website' => fake()->url(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'instagram' => '@' . fake()->userName(),
            'facebook' => fake()->userName(),
            'twitter' => '@' . fake()->userName(),
            'cover_photo' => fake()->imageUrl(800, 400, 'brewery'),
            'founded' => fake()->year(),
        ];
    }
}
