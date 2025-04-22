<?php

namespace Database\Factories;

use App\Models\Brewery;
use Illuminate\Database\Eloquent\Factories\Factory;

class BreweryFactory extends Factory
{
    protected $model = Brewery::class;

    public function definition(): array
    {
        $countries = ['España', 'Alemania', 'Bélgica', 'Estados Unidos', 'Reino Unido', 'República Checa', 'Irlanda'];
        $country = $this->faker->randomElement($countries);

        // Ajustar ciudad según el país
        $city = match ($country) {
            'España' => $this->faker->randomElement(['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Zaragoza']),
            'Alemania' => $this->faker->randomElement(['Berlín', 'Múnich', 'Colonia']),
            'Bélgica' => $this->faker->randomElement(['Bruselas', 'Brujas', 'Amberes']),
            'Estados Unidos' => $this->faker->randomElement(['Portland', 'Denver', 'San Diego']),
            'Reino Unido' => $this->faker->randomElement(['Londres', 'Manchester', 'Edimburgo']),
            'República Checa' => $this->faker->randomElement(['Praga', 'Pilsen']),
            'Irlanda' => $this->faker->randomElement(['Dublín', 'Cork']),
            default => $this->faker->city(),
        };

        return [
            'name' => $this->faker->company() . ' ' . $this->faker->randomElement(['Brewing Co.', 'Craft Beer', 'Cervecería', 'Brewery', 'Bier']),
            'country' => $country,
            'city' => $city,
            'address' => $this->faker->streetAddress(),
            'latitude' => $this->faker->latitude(36, 43),
            'longitude' => $this->faker->longitude(-9, 3),
            'description' => $this->faker->paragraph(),
            'logo_url' => $this->faker->boolean(70) ? $this->faker->imageUrl(200, 200, 'business') : null,
            'website' => $this->faker->boolean(80) ? $this->faker->url() : null,
        ];
    }
}
