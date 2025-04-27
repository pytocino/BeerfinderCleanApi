<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' ' . fake()->randomElement(['Bar', 'Pub', 'Brewery', 'Taproom']),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'address' => fake()->streetAddress(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'description' => $this->faker->paragraph(3),
            'image_url' => fake()->imageUrl(640, 480, 'beer'),
            'cover_photo' => fake()->imageUrl(1280, 720, 'bar'),
            'website' => fake()->url(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'verified' => fake()->boolean(20), // 20% de probabilidad de estar verificado
        ];
    }

    /**
     * Configurar la ubicación como verificada.
     */
    public function verified(): static
    {
        return $this->state(fn(array $attributes) => [
            'verified' => true
        ]);
    }
}
