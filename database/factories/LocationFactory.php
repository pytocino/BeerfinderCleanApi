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
        $locationTypes = [
            'bar',
            'brewery',
            'restaurant',
            'store',
            'taproom',
            'pub',
            'club',
            'festival',
            'hotel'
        ];

        return [
            'name' => fake()->company() . ' ' . fake()->randomElement(['Bar', 'Pub', 'Brewery', 'Taproom']),
            'type' => fake()->randomElement($locationTypes),
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
            'instagram' => '@' . fake()->userName(),
            'facebook' => fake()->userName(),
            'twitter' => '@' . fake()->userName(),
            'verified' => fake()->boolean(20), // 20% de probabilidad de estar verificado
            'check_ins_count' => fake()->numberBetween(0, 500),
        ];
    }

    /**
     * Configurar la ubicación como un bar.
     */
    public function bar(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'bar',
            'name' => fake()->company() . ' Bar'
        ]);
    }

    /**
     * Configurar la ubicación como una cervecería.
     */
    public function brewery(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'brewery',
            'name' => fake()->company() . ' Brewery'
        ]);
    }

    /**
     * Configurar la ubicación como un restaurante.
     */
    public function restaurant(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'restaurant',
            'name' => fake()->company() . ' Restaurant'
        ]);
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
