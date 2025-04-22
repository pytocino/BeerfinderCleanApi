<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['bar', 'restaurant', 'store']);

        $countries = ['España', 'Alemania', 'Bélgica', 'Estados Unidos', 'Reino Unido'];
        $country = $this->faker->randomElement($countries);

        // Ajustar ciudad según el país
        $city = match ($country) {
            'España' => $this->faker->randomElement(['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Bilbao']),
            'Alemania' => $this->faker->randomElement(['Berlín', 'Múnich', 'Hamburgo']),
            'Bélgica' => $this->faker->randomElement(['Bruselas', 'Brujas', 'Gante']),
            'Estados Unidos' => $this->faker->randomElement(['Nueva York', 'Chicago', 'Los Ángeles']),
            'Reino Unido' => $this->faker->randomElement(['Londres', 'Manchester', 'Liverpool']),
            default => $this->faker->city(),
        };

        $name = match ($type) {
            'bar' => $this->faker->randomElement(['Bar ', 'Pub ', 'La Taberna ', 'Cervecería ']) . $this->faker->company(),
            'restaurant' => $this->faker->randomElement(['Restaurante ', 'Mesón ', 'Gastrobar ']) . $this->faker->company(),
            'store' => $this->faker->randomElement(['Tienda ', 'Beer Shop ', 'La Botillería ']) . $this->faker->company(),
            default => $this->faker->company(),
        };

        return [
            'name' => $name,
            'type' => $type,
            'country' => $country,
            'city' => $city,
            'address' => $this->faker->streetAddress(),
            'latitude' => $this->faker->latitude(36, 43),
            'longitude' => $this->faker->longitude(-9, 3),
            'description' => $this->faker->paragraph(),
            'image_url' => $this->faker->boolean(70) ? $this->faker->imageUrl(400, 300, 'business') : null,
        ];
    }
}
