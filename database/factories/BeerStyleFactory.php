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
        // Valores mínimos y máximos para ABV
        $abvMin = fake()->randomFloat(2, 3.0, 8.0);
        $abvMax = fake()->randomFloat(2, $abvMin, 12.0);

        // Valores mínimos y máximos para IBU
        $ibuMin = fake()->numberBetween(5, 50);
        $ibuMax = fake()->numberBetween($ibuMin, 120);

        // Colores típicos de cerveza
        $colors = ['Pale', 'Golden', 'Amber', 'Copper', 'Brown', 'Ruby', 'Black'];

        // Orígenes comunes de estilos de cerveza
        $origins = ['Alemania', 'Bélgica', 'Reino Unido', 'Estados Unidos', 'República Checa', 'Irlanda'];

        return [
            'name' => fake()->unique()->words(2, true), // Nombres de estilo más realistas
            'description' => $this->faker->paragraph(2),
            'origin_country' => fake()->randomElement($origins),
            'color' => fake()->randomElement($colors),
            'abv_min' => $abvMin,
            'abv_max' => $abvMax,
            'ibu_min' => $ibuMin,
            'ibu_max' => $ibuMax,
        ];
    }
}
