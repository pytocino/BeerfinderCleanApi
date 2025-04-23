<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(), // Campo requerido
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(), // Timestamp, no email
            'password' => Hash::make('password'), // Password hasheada
            'remember_token' => Str::random(10),
            'profile_picture' => fake()->imageUrl(),
            'bio' => $this->faker->paragraph(),
            'location' => fake()->city(),
            'birthdate' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'last_active_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
