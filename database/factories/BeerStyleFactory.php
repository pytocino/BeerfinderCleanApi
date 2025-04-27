<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BeerStyle>
 */
class BeerStyleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $origins = ['Alemania', 'Bélgica', 'Reino Unido', 'Estados Unidos', 'República Checa', 'Irlanda'];

        return [
            'name' => $this->faker->unique()->words(2, true),
            'description' => $this->faker->paragraph(2),
            'origin_country' => $this->faker->randomElement($origins),
        ];
    }
}
