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
        $servingTypes = ['Bottle', 'Can', 'Draft', 'Taster', 'Flight'];
        $currencies = ['EUR', 'USD', 'GBP'];

        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'beer_id' => Beer::query()->inRandomOrder()->value('id') ?? Beer::factory(),
            'location_id' => Location::query()->inRandomOrder()->value('id'),
            'review' => $this->faker->paragraph(3),
            'rating' => $this->faker->randomFloat(1, 1.0, 5.0),
            'photo_url' => $this->faker->boolean(70) ? $this->faker->imageUrl(800, 600, 'beer') : null,
            'additional_photos' => $this->faker->boolean(40) ? [
                $this->faker->imageUrl(800, 600, 'beer'),
                $this->faker->imageUrl(800, 600, 'beer')
            ] : [],
            'serving_type' => $this->faker->randomElement($servingTypes),
            'purchase_price' => $this->faker->randomFloat(2, 2, 15),
            'purchase_currency' => $this->faker->randomElement($currencies),
            'user_tags' => $this->faker->boolean(50) ? User::inRandomOrder()->limit(2)->pluck('id')->toArray() : [],
            'edited' => $this->faker->boolean(20),
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
