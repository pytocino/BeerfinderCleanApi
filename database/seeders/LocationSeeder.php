<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear algunos bares y tiendas reales
        $locations = [
            [
                'name' => 'Cervecería Internacional',
                'type' => 'bar',
                'country' => 'España',
                'city' => 'Madrid',
                'address' => 'Calle de la Montera, 15',
                'latitude' => 40.4198,
                'longitude' => -3.7022,
                'description' => 'Bar con más de 100 variedades de cerveza internacional.',
                'image_url' => 'https://example.com/locations/cerveceria_internacional.jpg',
            ],
            [
                'name' => 'The Beer Store',
                'type' => 'store',
                'country' => 'España',
                'city' => 'Barcelona',
                'address' => 'Carrer de València, 193',
                'latitude' => 41.3892,
                'longitude' => 2.1586,
                'description' => 'Tienda especializada en cervezas artesanales de todo el mundo.',
                'image_url' => 'https://example.com/locations/the_beer_store.jpg',
            ],
            [
                'name' => 'Restaurante Lupulo',
                'type' => 'restaurant',
                'country' => 'España',
                'city' => 'Valencia',
                'address' => 'Carrer de Sueca, 27',
                'latitude' => 39.4634,
                'longitude' => -0.3763,
                'description' => 'Restaurante con gran selección de cervezas y maridajes.',
                'image_url' => 'https://example.com/locations/restaurante_lupulo.jpg',
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }

        // Crear ubicaciones adicionales aleatorias
        Location::factory()->count(20)->create();
    }
}
