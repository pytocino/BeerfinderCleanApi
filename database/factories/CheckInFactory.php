<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Beer;
use App\Models\Post;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CheckIn>
 */
class CheckInFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'post_id' => Post::factory(),  // Añadido relación con Post
            'beer_id' => Beer::factory(),
            'location_id' => fake()->boolean(80) ? Location::factory() : null, // 80% con ubicación
            'rating' => fake()->randomFloat(1, 1.0, 5.0), // Valoración de 1.0 a 5.0 con un decimal
        ];
    }

    /**
     * Indica que el check-in no tiene calificación.
     */
    public function withoutRating(): static
    {
        return $this->state(fn(array $attributes) => [
            'rating' => null,
        ]);
    }

    /**
     * Indica que el check-in no tiene ubicación.
     */
    public function withoutLocation(): static
    {
        return $this->state(fn(array $attributes) => [
            'location_id' => null,
        ]);
    }
}
