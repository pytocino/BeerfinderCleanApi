<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Beer;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Tipos de servicio comunes para cervezas
        $servingTypes = ['Bottle', 'Can', 'Draft', 'Taster', 'Flight'];

        // Monedas comunes
        $currencies = ['EUR', 'USD', 'GBP'];

        return [
            'user_id' => User::factory(),
            'beer_id' => Beer::factory(),
            'location_id' => fake()->boolean(80) ? Location::factory() : null, // 80% con ubicación
            'review' => $this->faker->paragraph(3),
            'rating' => fake()->randomFloat(1, 1.0, 5.0), // Valoración de 1.0 a 5.0 con un decimal
            'photo_url' => fake()->boolean(70) ? fake()->imageUrl(800, 600, 'beer') : null,
            'additional_photos' => fake()->boolean(40) ? json_encode([
                fake()->imageUrl(800, 600, 'beer'),
                fake()->imageUrl(800, 600, 'beer')
            ]) : null,
            'serving_type' => fake()->randomElement($servingTypes),
            'purchase_price' => fake()->randomFloat(2, 2, 15), // Precio razonable de cerveza
            'purchase_currency' => fake()->randomElement($currencies),
            'user_tags' => fake()->boolean(30) ? json_encode([
                fake()->numberBetween(1, 100),
                fake()->numberBetween(1, 100)
            ]) : null,
            'likes_count' => fake()->numberBetween(0, 500),
            'comments_count' => fake()->numberBetween(0, 100),
            'edited' => fake()->boolean(20), // 20% editados
            'edited_at' => function (array $attributes) {
                return $attributes['edited'] ? now()->subDays(rand(1, 10)) : null;
            },
        ];
    }

    /**
     * Configura el post sin valoración (solo comentario).
     */
    public function withoutRating(): static
    {
        return $this->state(fn(array $attributes) => [
            'rating' => null,
        ]);
    }

    /**
     * Configura el post sin ubicación.
     */
    public function withoutLocation(): static
    {
        return $this->state(fn(array $attributes) => [
            'location_id' => null,
        ]);
    }

    /**
     * Configura el post para un usuario específico.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Configura el post para una cerveza específica.
     */
    public function forBeer(Beer $beer): static
    {
        return $this->state(fn(array $attributes) => [
            'beer_id' => $beer->id,
        ]);
    }
}
