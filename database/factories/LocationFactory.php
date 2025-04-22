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
            'id' => fake()->text(),
            'name' => fake()->name(),
            'type' => fake()->text(),
            'country' => fake()->text(),
            'city' => fake()->text(),
            'address' => fake()->address(),
            'latitude' => fake()->text(),
            'longitude' => fake()->text(),
            'description' => $this->faker->paragraph(),
            'image_url' => fake()->text(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
