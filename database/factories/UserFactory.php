<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
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
            'email' => fake()->safeEmail(),
            'password' => fake()->text(),
            'profile_picture' => fake()->text(),
            'bio' => $this->faker->paragraph(),
            'location' => fake()->text(),
            'email_verified_at' => fake()->safeEmail(),
            'remember_token' => fake()->text(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
