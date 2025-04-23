<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Beer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favorite>
 */
class FavoriteFactory extends Factory
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
            'beer_id' => Beer::factory(),
        ];
    }

    /**
     * Configura el favorito para un usuario específico.
     */
    public function forUser(User $user): static
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }

    /**
     * Configura el favorito para una cerveza específica.
     */
    public function forBeer(Beer $beer): static
    {
        return $this->state(function (array $attributes) use ($beer) {
            return [
                'beer_id' => $beer->id,
            ];
        });
    }
}
