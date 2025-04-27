<?php

namespace Database\Factories;

use App\Models\BeerStyle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Beer>
 */
class BeerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(3),
            'brewery' => $this->faker->company(),
            'style_id' => BeerStyle::query()->inRandomOrder()->value('id') ?? 1,
            'abv' => $this->faker->randomFloat(2, 3.0, 12.0),
            'ibu' => $this->faker->numberBetween(5, 120),
            'image_url' => $this->faker->imageUrl(640, 480, 'beer'),
        ];
    }
}
