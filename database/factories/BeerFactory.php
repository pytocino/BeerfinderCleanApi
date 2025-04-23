<?php

namespace Database\Factories;

use App\Models\Brewery;
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
            'name' => fake()->words(3, true),
            // Asegúrate de tener cervecerías y estilos ya creados, o crea relaciones
            'brewery_id' => Brewery::factory(),
            'style_id' => BeerStyle::factory(),
            'abv' => fake()->randomFloat(2, 3.0, 12.0),
            'ibu' => fake()->numberBetween(5, 120),
            'color' => fake()->randomElement(['Golden', 'Amber', 'Red', 'Brown', 'Black']),
            'label_image_url' => fake()->imageUrl(300, 300, 'beer'),
            'package_type' => fake()->randomElement(['Botella', 'Lata', 'Barril']),
            'availability' => fake()->randomElement(['Todo el año', 'Estacional', 'Limitada']),
            'origin_country' => fake()->country(),
            'collaboration' => fake()->boolean(20) ? fake()->company() : null,
            'description' => $this->faker->paragraph(3),
            'image_url' => fake()->imageUrl(640, 480, 'beer'),
            'first_brewed' => fake()->year(),
        ];
    }
}
