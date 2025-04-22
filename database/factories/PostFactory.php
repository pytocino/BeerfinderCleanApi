<?php

namespace Database\Factories;

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
        return [
            'id' => fake()->text(),
            'user_id' => fake()->text(),
            'beer_id' => fake()->text(),
            'location_id' => fake()->text(),
            'review' => $this->faker->paragraph(),
            'rating' => fake()->text(),
            'photo_url' => fake()->text(),
            'additional_photos' => $this->faker->json(),
            'serving_type' => fake()->text(),
            'purchase_price' => fake()->text(),
            'purchase_currency' => fake()->text(),
            'user_tags' => $this->faker->json(),
            'likes_count' => fake()->text(),
            'comments_count' => fake()->text(),
            'edited' => fake()->text(),
            'edited_at' => $this->faker->dateTime(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
